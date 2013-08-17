--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.4
-- Dumped by pg_dump version 9.2.2
-- Started on 2013-08-08 19:14:53

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 200 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2088 (class 0 OID 0)
-- Dependencies: 200
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 568 (class 1247 OID 320249)
-- Name: demo; Type: TYPE; Schema: public; Owner: postgres
--

CREATE TYPE demo AS (
	id integer,
	date_balance date,
	minutes double precision,
	acd double precision,
	asr double precision,
	margin_percentage double precision,
	margin_per_minute double precision,
	cost_per_minute double precision,
	revenue_per_minute double precision,
	pdd double precision,
	incomplete_calls double precision,
	incomplete_calls_ner double precision,
	complete_calls double precision,
	complete_calls_ner double precision,
	calls_attempts double precision,
	duration_real double precision,
	duration_cost double precision,
	ner02_efficient double precision,
	ner02_seizure double precision,
	pdd_calls double precision,
	revenue double precision,
	cost double precision,
	margin double precision,
	date_change date,
	type integer,
	id_carrier integer,
	id_destination integer,
	id_destination_int integer
);


ALTER TYPE public.demo OWNER TO postgres;

--
-- TOC entry 216 (class 1255 OID 442529)
-- Name: actualizar_balance(integer, integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION actualizar_balance(tid integer, bid integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	t RECORD;
BEGIN
	SELECT * INTO t FROM balance_temp WHERE id=tid;
	IF t.id IS NOT NULL THEN
		UPDATE balance SET minutes=t.minutes, acd=t.acd, asr=t.asr, margin_percentage=t.margin_percentage, margin_per_minute=t.margin_per_minute, cost_per_minute=t.cost_per_minute, revenue_per_minute=t.revenue_per_minute, pdd=t.pdd, incomplete_calls=t.incomplete_calls, incomplete_calls_ner=t.incomplete_calls_ner, complete_calls=t.complete_calls, complete_calls_ner=t.complete_calls_ner, calls_attempts=t.calls_attempts, duration_real=t.duration_real, duration_cost=t.duration_cost, ner02_efficient=t.ner02_efficient, ner02_seizure=t.ner02_seizure, pdd_calls=t.pdd_calls, revenue=t.revenue, cost=t.cost, margin=t.margin, date_change=t.date_change WHERE id=bid;
		RETURN true;
	ELSE
		RETURN false;
	END IF;
	
END;
$$;


ALTER FUNCTION public.actualizar_balance(tid integer, bid integer) OWNER TO postgres;

--
-- TOC entry 220 (class 1255 OID 442528)
-- Name: compara_balances(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION compara_balances(ide integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	b RECORD;
	t RECORD;
	rr boolean;
	bb boolean;
	tt boolean;
BEGIN
	/*Busco el registro en la tabla balance_temp*/
	SELECT * INTO t FROM balance_temp WHERE id=ide;
	/*Busco el registro mas parecido en la tabla balance*/
	IF t.id_destination IS NOT NULL THEN
							     
		SELECT * INTO b FROM balance WHERE date_balance=t.date_balance AND id_carrier_supplier=t.id_carrier_supplier AND id_destination=t.id_destination AND status=1 AND id_carrier_customer=t.id_carrier_customer;
	ELSE
		SELECT * INTO b FROM balance WHERE date_balance=t.date_balance AND id_carrier_supplier=t.id_carrier_supplier AND id_destination_int=t.id_destination_int AND status=1 AND id_carrier_customer=t.id_carrier_customer;
	END IF;
	/*Verifico que trajo algo*/
	IF b.id IS NOT NULL THEN
		IF b.minutes=t.minutes AND b.revenue=t.revenue AND b.cost=t.cost THEN
			/*Si son iguales lo dejo asi*/
			DELETE FROM balance_temp WHERE id=t.id;
			RETURN true;
		ELSE
			/*de lo contrario paso para rrhistory*/
			SELECT pasar_a_rrhistory(b.id) INTO rr;
			/*y actualizo el registro*/
			IF rr=true THEN
				SELECT actualizar_balance(t.id, b.id) INTO bb;
				IF bb=true THEN
					DELETE FROM balance_temp WHERE id=t.id;
					RETURN true;
				ELSE
					RETURN false;
				END IF;
			ELSE
				RETURN false;
			END IF;
		END IF;
	ELSE
		/*Si no existe alguno parecido lo guardo enseguida en la tabla balance*/
		SELECT pasar_a_balance(t.id) INTO tt;
		IF tt=true THEN
			DELETE FROM balance_temp WHERE id=t.id;
		END IF;
		RETURN true;
	END IF;
END;
$$;


ALTER FUNCTION public.compara_balances(ide integer) OWNER TO postgres;

--
-- TOC entry 217 (class 1255 OID 442526)
-- Name: ejecutar_rerate(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ejecutar_rerate() RETURNS void
    LANGUAGE plpgsql
    AS $$
DECLARE
	b RECORD;
	t RECORD;
	result boolean;
	min date;
	max date;
BEGIN
	SELECT MIN(date_balance), MAX(date_balance) INTO min, max FROM balance_temp;
	WHILE min <= max LOOP
		FOR b IN SELECT id FROM balance WHERE date_balance=min ORDER BY id ASC LOOP
			SELECT statuscero(b.id) INTO result;
		END LOOP;
		min:=min + '1 days'::interval;
	END LOOP;
	FOR t IN SELECT * FROM balance_temp ORDER BY id ASC LOOP
		SELECT compara_balances(t.id) INTO result;
	END LOOP;
	INSERT INTO log(date, hour, id_log_action, id_users, description_date) VALUES (current_date, current_time, 57, 1, current_date);
END;
$$;


ALTER FUNCTION public.ejecutar_rerate() OWNER TO postgres;

--
-- TOC entry 213 (class 1255 OID 442530)
-- Name: pasar_a_balance(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION pasar_a_balance(ide integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	ids RECORD;
BEGIN
	SELECT * INTO ids FROM balance_temp WHERE id=ide;
	IF ids.id IS NOT NULL THEN
		INSERT INTO balance(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_carrier_supplier, id_destination, id_destination_int, status, id_carrier_customer) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_minute, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.id_carrier_supplier, ids.id_destination, ids.id_destination_int, 1, ids.id_carrier_customer);
		RETURN true;
	ELSE
		RETURN false;
	END IF;
END;
$$;


ALTER FUNCTION public.pasar_a_balance(ide integer) OWNER TO postgres;

--
-- TOC entry 214 (class 1255 OID 442532)
-- Name: pasar_a_balance_temp(date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION pasar_a_balance_temp(fecha date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	ids RECORD;
BEGIN
	FOR ids IN SELECT * FROM balance WHERE date_balance=fecha LOOP
		INSERT INTO balance_temp(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_destination, id_destination_int, id_carrier_supplier, id_carrier_customer) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_minute, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.id_destination, ids.id_destination_int, ids.id_carrier_supplier, ids.id_carrier_customer);
	END LOOP;
	RETURN true;
END;
$$;


ALTER FUNCTION public.pasar_a_balance_temp(fecha date) OWNER TO postgres;

--
-- TOC entry 215 (class 1255 OID 442531)
-- Name: pasar_a_rrhistory(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION pasar_a_rrhistory(ide integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	ids RECORD;
BEGIN
	SELECT * INTO ids FROM balance WHERE id=ide;
	IF ids.id IS NOT NULL THEN
		INSERT INTO rrhistory(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_balance, id_destination, id_destination_int, id_carrier_supplier, id_carrier_customer) VALUES(ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_minute, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.id, ids.id_destination, ids.id_destination_int, ids.id_carrier_supplier, ids.id_carrier_customer);
		RETURN true;
	ELSE
		RETURN false;
	END IF;
END;
$$;


ALTER FUNCTION public.pasar_a_rrhistory(ide integer) OWNER TO postgres;

--
-- TOC entry 218 (class 1255 OID 442533)
-- Name: rrhistory_a_balance_temp(date); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION rrhistory_a_balance_temp(fecha date) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	ids RECORD;
BEGIN
	FOR ids IN SELECT * FROM rrhistory WHERE date_balance=fecha LOOP
		INSERT INTO balance_temp(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_destination, id_destination_int, id_carrier_supplier, id_carrier_customer) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_minute, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.id_destination, ids.id_destination_int, ids.id_carrier_supplier, ids.id_carrier_customer);
	END LOOP;
	RETURN true;
END;
$$;


ALTER FUNCTION public.rrhistory_a_balance_temp(fecha date) OWNER TO postgres;

--
-- TOC entry 219 (class 1255 OID 442527)
-- Name: statuscero(integer); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION statuscero(ide integer) RETURNS boolean
    LANGUAGE plpgsql
    AS $$
DECLARE
	b RECORD;
	t RECORD;
BEGIN
	/*Busco el registro en la tabla balance_temp*/
	SELECT * INTO b FROM balance WHERE id=ide;
	/*Busco el registro mas parecido en la tabla balance*/
	IF b.id_destination IS NOT NULL THEN                                        
		SELECT * INTO t FROM balance_temp WHERE date_balance=b.date_balance AND id_destination=b.id_destination AND id_carrier_supplier=b.id_carrier_supplier AND id_carrier_customer=b.id_carrier_customer;
	ELSE
		SELECT * INTO t FROM balance_temp WHERE date_balance=b.date_balance AND id_destination_int=b.id_destination_int AND id_carrier_supplier=b.id_carrier_supplier AND id_carrier_customer=b.id_carrier_customer;
	END IF;
	/*si es nulo retorno falso*/
	IF t.id IS NULL THEN
		UPDATE balance SET status=0 WHERE id=b.id;
		RETURN false;
	ELSE
		RETURN true;
	END IF;
END;
$$;


ALTER FUNCTION public.statuscero(ide integer) OWNER TO postgres;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- TOC entry 177 (class 1259 OID 131145)
-- Name: balance; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE balance (
    id integer NOT NULL,
    date_balance date NOT NULL,
    minutes double precision NOT NULL,
    acd double precision NOT NULL,
    asr double precision NOT NULL,
    margin_percentage double precision NOT NULL,
    margin_per_minute double precision NOT NULL,
    cost_per_minute double precision NOT NULL,
    revenue_per_minute double precision NOT NULL,
    pdd double precision NOT NULL,
    incomplete_calls double precision NOT NULL,
    incomplete_calls_ner double precision NOT NULL,
    complete_calls double precision NOT NULL,
    complete_calls_ner double precision NOT NULL,
    calls_attempts double precision NOT NULL,
    duration_real double precision NOT NULL,
    duration_cost double precision NOT NULL,
    ner02_efficient double precision NOT NULL,
    ner02_seizure double precision NOT NULL,
    pdd_calls double precision NOT NULL,
    revenue double precision NOT NULL,
    cost double precision NOT NULL,
    margin double precision NOT NULL,
    date_change date,
    id_carrier_supplier integer,
    id_destination integer,
    id_destination_int integer,
    status integer,
    id_carrier_customer integer
);


ALTER TABLE public.balance OWNER TO postgres;

--
-- TOC entry 2089 (class 0 OID 0)
-- Dependencies: 177
-- Name: COLUMN balance.status; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN balance.status IS '0 deshabilitado 1 habilitado';


--
-- TOC entry 176 (class 1259 OID 131143)
-- Name: balance_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE balance_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.balance_id_seq OWNER TO postgres;

--
-- TOC entry 2090 (class 0 OID 0)
-- Dependencies: 176
-- Name: balance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE balance_id_seq OWNED BY balance.id;


SET default_with_oids = false;

--
-- TOC entry 190 (class 1259 OID 297229)
-- Name: balance_temp; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE balance_temp (
    id integer NOT NULL,
    date_balance date NOT NULL,
    minutes double precision NOT NULL,
    acd double precision NOT NULL,
    asr double precision NOT NULL,
    margin_percentage double precision NOT NULL,
    margin_per_minute double precision NOT NULL,
    cost_per_minute double precision NOT NULL,
    revenue_per_minute double precision NOT NULL,
    pdd double precision NOT NULL,
    incomplete_calls double precision NOT NULL,
    incomplete_calls_ner double precision NOT NULL,
    complete_calls double precision NOT NULL,
    complete_calls_ner double precision NOT NULL,
    calls_attempts double precision NOT NULL,
    duration_real double precision NOT NULL,
    duration_cost double precision NOT NULL,
    ner02_efficient double precision NOT NULL,
    ner02_seizure double precision NOT NULL,
    pdd_calls double precision NOT NULL,
    revenue double precision NOT NULL,
    cost double precision NOT NULL,
    margin double precision NOT NULL,
    date_change date,
    id_destination integer,
    id_destination_int integer,
    id_carrier_supplier integer,
    id_carrier_customer integer
);


ALTER TABLE public.balance_temp OWNER TO postgres;

SET default_with_oids = true;

--
-- TOC entry 189 (class 1259 OID 151127)
-- Name: balance_time; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE balance_time (
    id integer NOT NULL,
    date_balance_time date NOT NULL,
    "time" integer NOT NULL,
    minutes double precision NOT NULL,
    acd double precision NOT NULL,
    asr double precision NOT NULL,
    margin_percentage double precision NOT NULL,
    margin_per_minute double precision NOT NULL,
    cost_per_minute double precision NOT NULL,
    revenue_per_minute double precision NOT NULL,
    pdd double precision NOT NULL,
    incomplete_calls double precision NOT NULL,
    incomplete_calls_ner double precision NOT NULL,
    complete_calls double precision NOT NULL,
    complete_calls_ner double precision NOT NULL,
    calls_attempts double precision NOT NULL,
    duration_real double precision NOT NULL,
    duration_cost double precision NOT NULL,
    ner02_efficient double precision NOT NULL,
    ner02_seizure double precision NOT NULL,
    pdd_calls double precision NOT NULL,
    revenue double precision NOT NULL,
    cost double precision NOT NULL,
    margin double precision NOT NULL,
    date_change date NOT NULL,
    time_change time without time zone NOT NULL,
    name_supplier character varying,
    name_customer character varying,
    name_destination character varying
);


ALTER TABLE public.balance_time OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 151125)
-- Name: balance_time_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE balance_time_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.balance_time_id_seq OWNER TO postgres;

--
-- TOC entry 2091 (class 0 OID 0)
-- Dependencies: 188
-- Name: balance_time_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE balance_time_id_seq OWNED BY balance_time.id;


--
-- TOC entry 175 (class 1259 OID 131121)
-- Name: carrier; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE carrier (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    address text,
    record_date date NOT NULL
);


ALTER TABLE public.carrier OWNER TO postgres;

--
-- TOC entry 174 (class 1259 OID 131119)
-- Name: carrier_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE carrier_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.carrier_id_seq OWNER TO postgres;

--
-- TOC entry 2092 (class 0 OID 0)
-- Dependencies: 174
-- Name: carrier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_id_seq OWNED BY carrier.id;


--
-- TOC entry 199 (class 1259 OID 497488)
-- Name: carrier_managers; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE carrier_managers (
    start_date date,
    end_date date,
    id_carrier integer,
    id_managers integer
);


ALTER TABLE public.carrier_managers OWNER TO postgres;

--
-- TOC entry 179 (class 1259 OID 131158)
-- Name: destination; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.destination OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 131156)
-- Name: destination_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE destination_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.destination_id_seq OWNER TO postgres;

--
-- TOC entry 2093 (class 0 OID 0)
-- Dependencies: 178
-- Name: destination_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_id_seq OWNED BY destination.id;


--
-- TOC entry 183 (class 1259 OID 131184)
-- Name: destination_int; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination_int (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.destination_int OWNER TO postgres;

--
-- TOC entry 182 (class 1259 OID 131182)
-- Name: destination_int_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE destination_int_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.destination_int_id_seq OWNER TO postgres;

--
-- TOC entry 2094 (class 0 OID 0)
-- Dependencies: 182
-- Name: destination_int_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_int_id_seq OWNED BY destination_int.id;


--
-- TOC entry 181 (class 1259 OID 131171)
-- Name: rrhistory; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE rrhistory (
    id integer NOT NULL,
    date_balance date NOT NULL,
    minutes double precision NOT NULL,
    acd double precision NOT NULL,
    asr double precision NOT NULL,
    margin_percentage double precision NOT NULL,
    margin_per_minute double precision NOT NULL,
    cost_per_minute double precision NOT NULL,
    revenue_per_minute double precision NOT NULL,
    pdd double precision NOT NULL,
    incomplete_calls double precision NOT NULL,
    incomplete_calls_ner double precision NOT NULL,
    complete_calls double precision NOT NULL,
    complete_calls_ner double precision NOT NULL,
    calls_attempts double precision NOT NULL,
    duration_real double precision NOT NULL,
    duration_cost double precision NOT NULL,
    ner02_efficient double precision NOT NULL,
    ner02_seizure double precision NOT NULL,
    pdd_calls double precision NOT NULL,
    revenue double precision NOT NULL,
    cost double precision NOT NULL,
    margin double precision NOT NULL,
    date_change date,
    id_balance integer,
    id_destination integer,
    id_destination_int integer,
    id_carrier_supplier integer,
    id_carrier_customer integer
);


ALTER TABLE public.rrhistory OWNER TO postgres;

--
-- TOC entry 180 (class 1259 OID 131169)
-- Name: history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.history_id_seq OWNER TO postgres;

--
-- TOC entry 2095 (class 0 OID 0)
-- Dependencies: 180
-- Name: history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE history_id_seq OWNED BY rrhistory.id;


--
-- TOC entry 187 (class 1259 OID 131205)
-- Name: log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log (
    id integer NOT NULL,
    date date NOT NULL,
    hour time without time zone NOT NULL,
    id_log_action integer,
    id_users integer,
    description_date date
);


ALTER TABLE public.log OWNER TO postgres;

--
-- TOC entry 2096 (class 0 OID 0)
-- Dependencies: 187
-- Name: COLUMN log.description_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN log.description_date IS 'En el caso de los rerate, almacena la fecha del archivo rerate guardado';


--
-- TOC entry 185 (class 1259 OID 131197)
-- Name: log_action; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log_action (
    id integer NOT NULL,
    name character varying(50)
);


ALTER TABLE public.log_action OWNER TO postgres;

--
-- TOC entry 184 (class 1259 OID 131195)
-- Name: log_action_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE log_action_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.log_action_id_seq OWNER TO postgres;

--
-- TOC entry 2097 (class 0 OID 0)
-- Dependencies: 184
-- Name: log_action_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_action_id_seq OWNED BY log_action.id;


--
-- TOC entry 186 (class 1259 OID 131203)
-- Name: log_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.log_id_seq OWNER TO postgres;

--
-- TOC entry 2098 (class 0 OID 0)
-- Dependencies: 186
-- Name: log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_id_seq OWNED BY log.id;


--
-- TOC entry 198 (class 1259 OID 497478)
-- Name: managers; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE managers (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    address text,
    record_date date NOT NULL,
    "position" character varying(50)
);


ALTER TABLE public.managers OWNER TO postgres;

--
-- TOC entry 197 (class 1259 OID 497476)
-- Name: managers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE managers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.managers_id_seq OWNER TO postgres;

--
-- TOC entry 2099 (class 0 OID 0)
-- Dependencies: 197
-- Name: managers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE managers_id_seq OWNED BY managers.id;


--
-- TOC entry 173 (class 1259 OID 131106)
-- Name: profiles; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE profiles (
    id integer NOT NULL,
    first_name character varying(128),
    last_name character varying(128),
    id_users integer
);


ALTER TABLE public.profiles OWNER TO postgres;

--
-- TOC entry 172 (class 1259 OID 131104)
-- Name: profiles_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE profiles_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.profiles_id_seq OWNER TO postgres;

--
-- TOC entry 2100 (class 0 OID 0)
-- Dependencies: 172
-- Name: profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_id_seq OWNED BY profiles.id;


--
-- TOC entry 196 (class 1259 OID 490628)
-- Name: profiles_renoc; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE profiles_renoc (
    id integer NOT NULL,
    first_name character varying(128),
    last_name character varying(128),
    id_users_renoc integer
);


ALTER TABLE public.profiles_renoc OWNER TO postgres;

--
-- TOC entry 195 (class 1259 OID 490626)
-- Name: profiles_renoc_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE profiles_renoc_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.profiles_renoc_id_seq OWNER TO postgres;

--
-- TOC entry 2101 (class 0 OID 0)
-- Dependencies: 195
-- Name: profiles_renoc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_renoc_id_seq OWNED BY profiles_renoc.id;


--
-- TOC entry 191 (class 1259 OID 297232)
-- Name: temp_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE temp_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.temp_id_seq OWNER TO postgres;

--
-- TOC entry 2102 (class 0 OID 0)
-- Dependencies: 191
-- Name: temp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE temp_id_seq OWNED BY balance_temp.id;


--
-- TOC entry 169 (class 1259 OID 131085)
-- Name: type_of_user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE type_of_user (
    id integer NOT NULL,
    nombre character varying(45) NOT NULL
);


ALTER TABLE public.type_of_user OWNER TO postgres;

--
-- TOC entry 168 (class 1259 OID 131083)
-- Name: type_of_user_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE type_of_user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.type_of_user_id_seq OWNER TO postgres;

--
-- TOC entry 2103 (class 0 OID 0)
-- Dependencies: 168
-- Name: type_of_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE type_of_user_id_seq OWNED BY type_of_user.id;


--
-- TOC entry 171 (class 1259 OID 131093)
-- Name: users; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users (
    id integer NOT NULL,
    username character varying(20) NOT NULL,
    password character varying(128) NOT NULL,
    email character varying(128) NOT NULL,
    activkey character varying(128) NOT NULL,
    superuser boolean NOT NULL,
    status boolean NOT NULL,
    create_at timestamp without time zone NOT NULL,
    lastvisit_at timestamp without time zone NOT NULL,
    id_type_of_user integer
);


ALTER TABLE public.users OWNER TO postgres;

--
-- TOC entry 170 (class 1259 OID 131091)
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- TOC entry 2104 (class 0 OID 0)
-- Dependencies: 170
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 194 (class 1259 OID 490571)
-- Name: users_renoc; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users_renoc (
    id integer NOT NULL,
    username character varying(20) NOT NULL,
    password character varying(128) NOT NULL,
    email character varying(128) NOT NULL,
    activkey character varying(128) NOT NULL,
    superuser boolean NOT NULL,
    status boolean NOT NULL,
    create_at timestamp without time zone NOT NULL,
    lastvisit_at timestamp without time zone NOT NULL,
    id_type_of_user integer
);


ALTER TABLE public.users_renoc OWNER TO postgres;

--
-- TOC entry 193 (class 1259 OID 490569)
-- Name: users_re_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE users_re_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_re_id_seq OWNER TO postgres;

--
-- TOC entry 2105 (class 0 OID 0)
-- Dependencies: 193
-- Name: users_re_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_re_id_seq OWNED BY users_renoc.id;


--
-- TOC entry 2023 (class 2604 OID 131148)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance ALTER COLUMN id SET DEFAULT nextval('balance_id_seq'::regclass);


--
-- TOC entry 2030 (class 2604 OID 297234)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance_temp ALTER COLUMN id SET DEFAULT nextval('temp_id_seq'::regclass);


--
-- TOC entry 2029 (class 2604 OID 151130)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance_time ALTER COLUMN id SET DEFAULT nextval('balance_time_id_seq'::regclass);


--
-- TOC entry 2022 (class 2604 OID 131124)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier ALTER COLUMN id SET DEFAULT nextval('carrier_id_seq'::regclass);


--
-- TOC entry 2024 (class 2604 OID 131161)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination ALTER COLUMN id SET DEFAULT nextval('destination_id_seq'::regclass);


--
-- TOC entry 2026 (class 2604 OID 131187)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_int ALTER COLUMN id SET DEFAULT nextval('destination_int_id_seq'::regclass);


--
-- TOC entry 2028 (class 2604 OID 131208)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);


--
-- TOC entry 2027 (class 2604 OID 131200)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log_action ALTER COLUMN id SET DEFAULT nextval('log_action_id_seq'::regclass);


--
-- TOC entry 2033 (class 2604 OID 497481)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY managers ALTER COLUMN id SET DEFAULT nextval('managers_id_seq'::regclass);


--
-- TOC entry 2021 (class 2604 OID 131109)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles ALTER COLUMN id SET DEFAULT nextval('profiles_id_seq'::regclass);


--
-- TOC entry 2032 (class 2604 OID 490631)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_renoc ALTER COLUMN id SET DEFAULT nextval('profiles_renoc_id_seq'::regclass);


--
-- TOC entry 2025 (class 2604 OID 131174)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rrhistory ALTER COLUMN id SET DEFAULT nextval('history_id_seq'::regclass);


--
-- TOC entry 2019 (class 2604 OID 131088)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY type_of_user ALTER COLUMN id SET DEFAULT nextval('type_of_user_id_seq'::regclass);


--
-- TOC entry 2020 (class 2604 OID 131096)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2031 (class 2604 OID 490574)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_renoc ALTER COLUMN id SET DEFAULT nextval('users_re_id_seq'::regclass);


--
-- TOC entry 2045 (class 2606 OID 131150)
-- Name: id_balance; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT id_balance PRIMARY KEY (id);


--
-- TOC entry 2057 (class 2606 OID 151132)
-- Name: id_balance_time; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance_time
    ADD CONSTRAINT id_balance_time PRIMARY KEY (id);


--
-- TOC entry 2043 (class 2606 OID 131129)
-- Name: id_carrier; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier
    ADD CONSTRAINT id_carrier PRIMARY KEY (id);


--
-- TOC entry 2047 (class 2606 OID 131163)
-- Name: id_destination; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination
    ADD CONSTRAINT id_destination PRIMARY KEY (id);


--
-- TOC entry 2051 (class 2606 OID 131189)
-- Name: id_destination_1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination_int
    ADD CONSTRAINT id_destination_1 PRIMARY KEY (id);


--
-- TOC entry 2049 (class 2606 OID 131176)
-- Name: id_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY rrhistory
    ADD CONSTRAINT id_history PRIMARY KEY (id);


--
-- TOC entry 2055 (class 2606 OID 131210)
-- Name: id_log; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT id_log PRIMARY KEY (id);


--
-- TOC entry 2053 (class 2606 OID 131202)
-- Name: id_log_action; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log_action
    ADD CONSTRAINT id_log_action PRIMARY KEY (id);


--
-- TOC entry 2067 (class 2606 OID 497486)
-- Name: id_managers; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY managers
    ADD CONSTRAINT id_managers PRIMARY KEY (id);


--
-- TOC entry 2039 (class 2606 OID 131111)
-- Name: id_profiles; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT id_profiles PRIMARY KEY (id);


--
-- TOC entry 2063 (class 2606 OID 490633)
-- Name: id_profiles_renoc; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT id_profiles_renoc PRIMARY KEY (id);


--
-- TOC entry 2059 (class 2606 OID 297239)
-- Name: id_temp; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance_temp
    ADD CONSTRAINT id_temp PRIMARY KEY (id);


--
-- TOC entry 2035 (class 2606 OID 131090)
-- Name: id_type_of_user; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY type_of_user
    ADD CONSTRAINT id_type_of_user PRIMARY KEY (id);


--
-- TOC entry 2037 (class 2606 OID 131098)
-- Name: id_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT id_users PRIMARY KEY (id);


--
-- TOC entry 2061 (class 2606 OID 490576)
-- Name: id_users1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users_renoc
    ADD CONSTRAINT id_users1 PRIMARY KEY (id);


--
-- TOC entry 2041 (class 2606 OID 131118)
-- Name: users_30102_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_30102_uq UNIQUE (id_users);


--
-- TOC entry 2065 (class 2606 OID 490635)
-- Name: users_renoc_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT users_renoc_uq UNIQUE (id_users_renoc);


--
-- TOC entry 2074 (class 2606 OID 514248)
-- Name: balance_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rrhistory
    ADD CONSTRAINT balance_fk FOREIGN KEY (id_balance) REFERENCES balance(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2073 (class 2606 OID 510595)
-- Name: carrier_customer_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT carrier_customer_fk FOREIGN KEY (id_carrier_customer) REFERENCES carrier(id);


--
-- TOC entry 2079 (class 2606 OID 497491)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2072 (class 2606 OID 510590)
-- Name: carrier_supplier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT carrier_supplier_fk FOREIGN KEY (id_carrier_supplier) REFERENCES carrier(id);


--
-- TOC entry 2070 (class 2606 OID 510580)
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2071 (class 2606 OID 510585)
-- Name: destination_int_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_int_fk FOREIGN KEY (id_destination_int) REFERENCES destination_int(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2075 (class 2606 OID 131211)
-- Name: log_action_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_action_fk FOREIGN KEY (id_log_action) REFERENCES log_action(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2080 (class 2606 OID 497496)
-- Name: managers_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT managers_fk FOREIGN KEY (id_managers) REFERENCES managers(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2068 (class 2606 OID 131099)
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2077 (class 2606 OID 490582)
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_renoc
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2069 (class 2606 OID 131112)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2076 (class 2606 OID 131216)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2078 (class 2606 OID 490636)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users_renoc) REFERENCES users_renoc(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2087 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-08-08 19:14:53

--
-- PostgreSQL database dump complete
--

