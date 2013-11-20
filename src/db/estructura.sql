--
-- PostgreSQL database dump
--

-- Dumped from database version 9.1.9
-- Dumped by pg_dump version 9.1.10
-- Started on 2013-11-19 14:32:16 VET

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 228 (class 3079 OID 11677)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2241 (class 0 OID 0)
-- Dependencies: 228
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- TOC entry 558 (class 1247 OID 220353)
-- Dependencies: 6 161
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
-- TOC entry 244 (class 1255 OID 2360381)
-- Dependencies: 6 696
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
-- TOC entry 245 (class 1255 OID 2360382)
-- Dependencies: 696 6
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
		IF b.minutes=t.minutes AND b.revenue=t.revenue AND b.cost=t.cost AND b.margin=t.margin THEN
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
-- TOC entry 240 (class 1255 OID 2360383)
-- Dependencies: 6 696
-- Name: condicion(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION condicion() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
DECLARE 
	valor integer;
	result RECORD;
	registro RECORD;
	es RECORD;
BEGIN
	SELECT * INTO es FROM log_action WHERE name='Rerate';
	SELECT * INTO registro FROM log order by id desc limit 1;
	IF registro.id_log_action=es.id THEN
		SELECT ejecutar_rerate() INTO result;
		RETURN result;
	ELSE
		RETURN NULL;
	END IF;
END;
$$;


ALTER FUNCTION public.condicion() OWNER TO postgres;

--
-- TOC entry 241 (class 1255 OID 2360384)
-- Dependencies: 6 696
-- Name: ejecutar_rerate(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION ejecutar_rerate() RETURNS record
    LANGUAGE plpgsql
    AS $$
DECLARE
	b RECORD;
	t RECORD;
	result boolean;
	min date;
	max date;
	idAction RECORD;
BEGIN
	SELECT * INTO idAction FROM log_action WHERE name = 'Rerate Completado';
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
	INSERT INTO log(date, hour, id_log_action, id_users, description_date) VALUES (current_date, current_time, idAction.id, 1, current_date);
	RETURN idAction;
END;
$$;


ALTER FUNCTION public.ejecutar_rerate() OWNER TO postgres;

--
-- TOC entry 242 (class 1255 OID 2360385)
-- Dependencies: 6 696
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
-- TOC entry 243 (class 1255 OID 2360386)
-- Dependencies: 6 696
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
-- TOC entry 246 (class 1255 OID 2360387)
-- Dependencies: 6 696
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
-- TOC entry 247 (class 1255 OID 2360388)
-- Dependencies: 6 696
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
-- TOC entry 248 (class 1255 OID 2360389)
-- Dependencies: 696 6
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
-- TOC entry 221 (class 1259 OID 1876574)
-- Dependencies: 6
-- Name: accounting_document; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE accounting_document (
    id integer NOT NULL,
    issue_date date,
    from_date date,
    to_date date,
    valid_received_date date,
    sent_date date,
    doc_number character varying(50),
    minutes double precision,
    amount double precision,
    note character varying(250),
    id_type_accounting_document integer NOT NULL,
    id_carrier integer,
    email_received_date date,
    valid_received_hour time without time zone,
    email_received_hour time without time zone,
    id_currency integer,
    confirm integer,
    min_etx double precision,
    min_carrier double precision,
    rate_etx double precision,
    rate_carrier double precision,
    id_accounting_document integer,
    id_destination integer,
    id_destination_supplier integer
);


ALTER TABLE public.accounting_document OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 1876572)
-- Dependencies: 221 6
-- Name: accounting_document_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE accounting_document_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.accounting_document_id_seq OWNER TO postgres;

--
-- TOC entry 2242 (class 0 OID 0)
-- Dependencies: 220
-- Name: accounting_document_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE accounting_document_id_seq OWNED BY accounting_document.id;


--
-- TOC entry 223 (class 1259 OID 1876597)
-- Dependencies: 6
-- Name: accounting_document_temp; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE accounting_document_temp (
    id integer NOT NULL,
    issue_date date,
    from_date date,
    to_date date,
    valid_received_date date,
    sent_date date,
    doc_number character varying(50),
    minutes double precision,
    amount double precision,
    note character varying(250),
    id_type_accounting_document integer NOT NULL,
    id_carrier integer,
    email_received_date date,
    valid_received_hour time without time zone,
    email_received_hour time without time zone,
    id_currency integer,
    confirm integer,
    min_etx double precision,
    min_carrier double precision,
    rate_etx double precision,
    rate_carrier double precision,
    id_accounting_document integer,
    id_destination integer,
    id_destination_supplier integer
);


ALTER TABLE public.accounting_document_temp OWNER TO postgres;

--
-- TOC entry 2243 (class 0 OID 0)
-- Dependencies: 223
-- Name: COLUMN accounting_document_temp.issue_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN accounting_document_temp.issue_date IS '
';


--
-- TOC entry 222 (class 1259 OID 1876595)
-- Dependencies: 223 6
-- Name: accounting_document_temp_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE accounting_document_temp_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.accounting_document_temp_id_seq OWNER TO postgres;

--
-- TOC entry 2244 (class 0 OID 0)
-- Dependencies: 222
-- Name: accounting_document_temp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE accounting_document_temp_id_seq OWNED BY accounting_document_temp.id;


--
-- TOC entry 162 (class 1259 OID 220362)
-- Dependencies: 6
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
-- TOC entry 2245 (class 0 OID 0)
-- Dependencies: 162
-- Name: COLUMN balance.status; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN balance.status IS '0 deshabilitado 1 habilitado';


--
-- TOC entry 163 (class 1259 OID 220365)
-- Dependencies: 6 162
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
-- TOC entry 2246 (class 0 OID 0)
-- Dependencies: 163
-- Name: balance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE balance_id_seq OWNED BY balance.id;


SET default_with_oids = false;

--
-- TOC entry 164 (class 1259 OID 220367)
-- Dependencies: 6
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
-- TOC entry 165 (class 1259 OID 220370)
-- Dependencies: 6
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
-- TOC entry 166 (class 1259 OID 220376)
-- Dependencies: 165 6
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
-- TOC entry 2247 (class 0 OID 0)
-- Dependencies: 166
-- Name: balance_time_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE balance_time_id_seq OWNED BY balance_time.id;


--
-- TOC entry 167 (class 1259 OID 220378)
-- Dependencies: 6
-- Name: carrier; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE carrier (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    address text,
    record_date date NOT NULL,
    id_carrier_groups integer,
    group_leader integer,
    status integer
);


ALTER TABLE public.carrier OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 1876080)
-- Dependencies: 6
-- Name: carrier_groups; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE carrier_groups (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.carrier_groups OWNER TO postgres;

--
-- TOC entry 214 (class 1259 OID 1876078)
-- Dependencies: 215 6
-- Name: carrier_groups_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE carrier_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.carrier_groups_id_seq OWNER TO postgres;

--
-- TOC entry 2248 (class 0 OID 0)
-- Dependencies: 214
-- Name: carrier_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_groups_id_seq OWNED BY carrier_groups.id;


--
-- TOC entry 168 (class 1259 OID 220384)
-- Dependencies: 6 167
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
-- TOC entry 2249 (class 0 OID 0)
-- Dependencies: 168
-- Name: carrier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_id_seq OWNED BY carrier.id;


--
-- TOC entry 169 (class 1259 OID 220386)
-- Dependencies: 6
-- Name: carrier_managers; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE carrier_managers (
    start_date date,
    end_date date,
    id_carrier integer,
    id_managers integer,
    id integer NOT NULL
);


ALTER TABLE public.carrier_managers OWNER TO postgres;

--
-- TOC entry 193 (class 1259 OID 595636)
-- Dependencies: 6 169
-- Name: carrier_managers_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE carrier_managers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.carrier_managers_id_seq OWNER TO postgres;

--
-- TOC entry 2250 (class 0 OID 0)
-- Dependencies: 193
-- Name: carrier_managers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_managers_id_seq OWNED BY carrier_managers.id;


--
-- TOC entry 195 (class 1259 OID 978986)
-- Dependencies: 6
-- Name: company; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE company (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.company OWNER TO postgres;

--
-- TOC entry 194 (class 1259 OID 978984)
-- Dependencies: 195 6
-- Name: company_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE company_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.company_id_seq OWNER TO postgres;

--
-- TOC entry 2251 (class 0 OID 0)
-- Dependencies: 194
-- Name: company_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE company_id_seq OWNED BY company.id;


--
-- TOC entry 197 (class 1259 OID 978994)
-- Dependencies: 6
-- Name: contrato; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE contrato (
    id integer NOT NULL,
    sign_date date,
    production_date date,
    end_date date,
    id_carrier integer NOT NULL,
    id_company integer NOT NULL,
    up integer
);


ALTER TABLE public.contrato OWNER TO postgres;

--
-- TOC entry 196 (class 1259 OID 978992)
-- Dependencies: 197 6
-- Name: contrato_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE contrato_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contrato_id_seq OWNER TO postgres;

--
-- TOC entry 2252 (class 0 OID 0)
-- Dependencies: 196
-- Name: contrato_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE contrato_id_seq OWNED BY contrato.id;


--
-- TOC entry 207 (class 1259 OID 979092)
-- Dependencies: 6
-- Name: contrato_monetizable; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE contrato_monetizable (
    id integer NOT NULL,
    start_date date NOT NULL,
    end_date date,
    id_contrato integer NOT NULL,
    id_monetizable integer NOT NULL
);


ALTER TABLE public.contrato_monetizable OWNER TO postgres;

--
-- TOC entry 2253 (class 0 OID 0)
-- Dependencies: 207
-- Name: COLUMN contrato_monetizable.end_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN contrato_monetizable.end_date IS '
';


--
-- TOC entry 206 (class 1259 OID 979090)
-- Dependencies: 207 6
-- Name: contrato_monetizable_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE contrato_monetizable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contrato_monetizable_id_seq OWNER TO postgres;

--
-- TOC entry 2254 (class 0 OID 0)
-- Dependencies: 206
-- Name: contrato_monetizable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE contrato_monetizable_id_seq OWNED BY contrato_monetizable.id;


--
-- TOC entry 205 (class 1259 OID 979074)
-- Dependencies: 6
-- Name: contrato_termino_pago; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE contrato_termino_pago (
    id integer NOT NULL,
    start_date date NOT NULL,
    end_date date,
    id_contrato integer NOT NULL,
    id_termino_pago integer NOT NULL
);


ALTER TABLE public.contrato_termino_pago OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 979072)
-- Dependencies: 6 205
-- Name: contrato_termino_pago_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE contrato_termino_pago_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contrato_termino_pago_id_seq OWNER TO postgres;

--
-- TOC entry 2255 (class 0 OID 0)
-- Dependencies: 204
-- Name: contrato_termino_pago_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE contrato_termino_pago_id_seq OWNED BY contrato_termino_pago.id;


--
-- TOC entry 209 (class 1259 OID 1460960)
-- Dependencies: 6
-- Name: credit_limit; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE credit_limit (
    id integer NOT NULL,
    start_date date NOT NULL,
    end_date date,
    id_contrato integer NOT NULL,
    amount double precision NOT NULL
);


ALTER TABLE public.credit_limit OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 1460958)
-- Dependencies: 6 209
-- Name: credit_limit_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE credit_limit_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.credit_limit_id_seq OWNER TO postgres;

--
-- TOC entry 2256 (class 0 OID 0)
-- Dependencies: 208
-- Name: credit_limit_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE credit_limit_id_seq OWNED BY credit_limit.id;


--
-- TOC entry 217 (class 1259 OID 1876540)
-- Dependencies: 6
-- Name: currency; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE currency (
    id integer NOT NULL,
    name character varying(10) NOT NULL
);


ALTER TABLE public.currency OWNER TO postgres;

--
-- TOC entry 216 (class 1259 OID 1876538)
-- Dependencies: 6 217
-- Name: currency_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE currency_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.currency_id_seq OWNER TO postgres;

--
-- TOC entry 2257 (class 0 OID 0)
-- Dependencies: 216
-- Name: currency_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE currency_id_seq OWNED BY currency.id;


--
-- TOC entry 203 (class 1259 OID 979046)
-- Dependencies: 6
-- Name: days_dispute_history; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE days_dispute_history (
    id integer NOT NULL,
    start_date date NOT NULL,
    end_date date,
    id_contrato integer NOT NULL,
    days integer NOT NULL
);


ALTER TABLE public.days_dispute_history OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 979044)
-- Dependencies: 203 6
-- Name: days_dispute_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE days_dispute_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.days_dispute_history_id_seq OWNER TO postgres;

--
-- TOC entry 2258 (class 0 OID 0)
-- Dependencies: 202
-- Name: days_dispute_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE days_dispute_history_id_seq OWNED BY days_dispute_history.id;


--
-- TOC entry 170 (class 1259 OID 220389)
-- Dependencies: 6
-- Name: destination; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    id_geographic_zone integer
);


ALTER TABLE public.destination OWNER TO postgres;

--
-- TOC entry 171 (class 1259 OID 220392)
-- Dependencies: 170 6
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
-- TOC entry 2259 (class 0 OID 0)
-- Dependencies: 171
-- Name: destination_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_id_seq OWNED BY destination.id;


--
-- TOC entry 172 (class 1259 OID 220394)
-- Dependencies: 6
-- Name: destination_int; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination_int (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    id_geographic_zone integer
);


ALTER TABLE public.destination_int OWNER TO postgres;

--
-- TOC entry 173 (class 1259 OID 220397)
-- Dependencies: 172 6
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
-- TOC entry 2260 (class 0 OID 0)
-- Dependencies: 173
-- Name: destination_int_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_int_id_seq OWNED BY destination_int.id;


--
-- TOC entry 227 (class 1259 OID 2223407)
-- Dependencies: 6
-- Name: destination_supplier; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination_supplier (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    id_carrier integer NOT NULL
);


ALTER TABLE public.destination_supplier OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 2223405)
-- Dependencies: 227 6
-- Name: destination_supplier_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE destination_supplier_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.destination_supplier_id_seq OWNER TO postgres;

--
-- TOC entry 2261 (class 0 OID 0)
-- Dependencies: 226
-- Name: destination_supplier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_supplier_id_seq OWNED BY destination_supplier.id;


--
-- TOC entry 212 (class 1259 OID 1570212)
-- Dependencies: 6
-- Name: geographic_zone; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE geographic_zone (
    id integer NOT NULL,
    name_zona character varying(50) NOT NULL,
    color_zona character varying(50) NOT NULL
);


ALTER TABLE public.geographic_zone OWNER TO postgres;

--
-- TOC entry 213 (class 1259 OID 1570220)
-- Dependencies: 6 212
-- Name: geographic_zone_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE geographic_zone_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.geographic_zone_id_seq OWNER TO postgres;

--
-- TOC entry 2262 (class 0 OID 0)
-- Dependencies: 213
-- Name: geographic_zone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE geographic_zone_id_seq OWNED BY geographic_zone.id;


--
-- TOC entry 174 (class 1259 OID 220399)
-- Dependencies: 6
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
-- TOC entry 175 (class 1259 OID 220402)
-- Dependencies: 174 6
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
-- TOC entry 2263 (class 0 OID 0)
-- Dependencies: 175
-- Name: history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE history_id_seq OWNED BY rrhistory.id;


--
-- TOC entry 176 (class 1259 OID 220404)
-- Dependencies: 6
-- Name: log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log (
    id integer NOT NULL,
    date date NOT NULL,
    hour time without time zone NOT NULL,
    id_log_action integer,
    id_users integer,
    description_date date,
    id_esp integer
);


ALTER TABLE public.log OWNER TO postgres;

--
-- TOC entry 2264 (class 0 OID 0)
-- Dependencies: 176
-- Name: COLUMN log.description_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN log.description_date IS 'En el caso de los rerate, almacena la fecha del archivo rerate guardado';


--
-- TOC entry 177 (class 1259 OID 220407)
-- Dependencies: 6
-- Name: log_action; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log_action (
    id integer NOT NULL,
    name character varying(50)
);


ALTER TABLE public.log_action OWNER TO postgres;

--
-- TOC entry 178 (class 1259 OID 220410)
-- Dependencies: 6 177
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
-- TOC entry 2265 (class 0 OID 0)
-- Dependencies: 178
-- Name: log_action_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_action_id_seq OWNED BY log_action.id;


--
-- TOC entry 179 (class 1259 OID 220412)
-- Dependencies: 6 176
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
-- TOC entry 2266 (class 0 OID 0)
-- Dependencies: 179
-- Name: log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_id_seq OWNED BY log.id;


--
-- TOC entry 180 (class 1259 OID 220414)
-- Dependencies: 6
-- Name: managers; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE managers (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    address text,
    record_date date NOT NULL,
    "position" character varying(50),
    lastname character varying(50)
);


ALTER TABLE public.managers OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 220420)
-- Dependencies: 6 180
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
-- TOC entry 2267 (class 0 OID 0)
-- Dependencies: 181
-- Name: managers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE managers_id_seq OWNED BY managers.id;


--
-- TOC entry 199 (class 1259 OID 979030)
-- Dependencies: 6
-- Name: monetizable; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE monetizable (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.monetizable OWNER TO postgres;

--
-- TOC entry 198 (class 1259 OID 979028)
-- Dependencies: 6 199
-- Name: monetizable_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE monetizable_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.monetizable_id_seq OWNER TO postgres;

--
-- TOC entry 2268 (class 0 OID 0)
-- Dependencies: 198
-- Name: monetizable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE monetizable_id_seq OWNED BY monetizable.id;


--
-- TOC entry 182 (class 1259 OID 220422)
-- Dependencies: 6
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
-- TOC entry 183 (class 1259 OID 220425)
-- Dependencies: 6 182
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
-- TOC entry 2269 (class 0 OID 0)
-- Dependencies: 183
-- Name: profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_id_seq OWNED BY profiles.id;


--
-- TOC entry 184 (class 1259 OID 220427)
-- Dependencies: 6
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
-- TOC entry 185 (class 1259 OID 220430)
-- Dependencies: 184 6
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
-- TOC entry 2270 (class 0 OID 0)
-- Dependencies: 185
-- Name: profiles_renoc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_renoc_id_seq OWNED BY profiles_renoc.id;


--
-- TOC entry 211 (class 1259 OID 1460973)
-- Dependencies: 6
-- Name: purchase_limit; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE purchase_limit (
    id integer NOT NULL,
    start_date date NOT NULL,
    end_date date,
    id_contrato integer NOT NULL,
    amount double precision NOT NULL
);


ALTER TABLE public.purchase_limit OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 1460971)
-- Dependencies: 6 211
-- Name: purchase_limit_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE purchase_limit_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.purchase_limit_id_seq OWNER TO postgres;

--
-- TOC entry 2271 (class 0 OID 0)
-- Dependencies: 210
-- Name: purchase_limit_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE purchase_limit_id_seq OWNED BY purchase_limit.id;


--
-- TOC entry 225 (class 1259 OID 2223389)
-- Dependencies: 6
-- Name: solved_days_dispute_history; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE solved_days_dispute_history (
    id integer NOT NULL,
    start_date date NOT NULL,
    end_date date,
    id_contrato integer NOT NULL,
    days integer NOT NULL
);


ALTER TABLE public.solved_days_dispute_history OWNER TO postgres;

--
-- TOC entry 224 (class 1259 OID 2223387)
-- Dependencies: 6 225
-- Name: solved_days_dispute_history_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE solved_days_dispute_history_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.solved_days_dispute_history_id_seq OWNER TO postgres;

--
-- TOC entry 2272 (class 0 OID 0)
-- Dependencies: 224
-- Name: solved_days_dispute_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solved_days_dispute_history_id_seq OWNED BY solved_days_dispute_history.id;


--
-- TOC entry 186 (class 1259 OID 220432)
-- Dependencies: 6 164
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
-- TOC entry 2273 (class 0 OID 0)
-- Dependencies: 186
-- Name: temp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE temp_id_seq OWNED BY balance_temp.id;


--
-- TOC entry 201 (class 1259 OID 979038)
-- Dependencies: 6
-- Name: termino_pago; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE termino_pago (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.termino_pago OWNER TO postgres;

--
-- TOC entry 200 (class 1259 OID 979036)
-- Dependencies: 201 6
-- Name: termino_pago_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE termino_pago_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.termino_pago_id_seq OWNER TO postgres;

--
-- TOC entry 2274 (class 0 OID 0)
-- Dependencies: 200
-- Name: termino_pago_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE termino_pago_id_seq OWNED BY termino_pago.id;


--
-- TOC entry 219 (class 1259 OID 1876566)
-- Dependencies: 6
-- Name: type_accounting_document; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE type_accounting_document (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    description character varying(250)
);


ALTER TABLE public.type_accounting_document OWNER TO postgres;

--
-- TOC entry 218 (class 1259 OID 1876564)
-- Dependencies: 219 6
-- Name: type_accounting_document_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE type_accounting_document_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.type_accounting_document_id_seq OWNER TO postgres;

--
-- TOC entry 2275 (class 0 OID 0)
-- Dependencies: 218
-- Name: type_accounting_document_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE type_accounting_document_id_seq OWNED BY type_accounting_document.id;


--
-- TOC entry 187 (class 1259 OID 220434)
-- Dependencies: 6
-- Name: type_of_user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE type_of_user (
    id integer NOT NULL,
    nombre character varying(45) NOT NULL
);


ALTER TABLE public.type_of_user OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 220437)
-- Dependencies: 187 6
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
-- TOC entry 2276 (class 0 OID 0)
-- Dependencies: 188
-- Name: type_of_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE type_of_user_id_seq OWNED BY type_of_user.id;


--
-- TOC entry 189 (class 1259 OID 220439)
-- Dependencies: 6
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
-- TOC entry 190 (class 1259 OID 220442)
-- Dependencies: 6 189
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
-- TOC entry 2277 (class 0 OID 0)
-- Dependencies: 190
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 191 (class 1259 OID 220444)
-- Dependencies: 6
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
-- TOC entry 192 (class 1259 OID 220447)
-- Dependencies: 191 6
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
-- TOC entry 2278 (class 0 OID 0)
-- Dependencies: 192
-- Name: users_re_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_re_id_seq OWNED BY users_renoc.id;


--
-- TOC entry 2020 (class 2604 OID 1876577)
-- Dependencies: 221 220 221
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document ALTER COLUMN id SET DEFAULT nextval('accounting_document_id_seq'::regclass);


--
-- TOC entry 2021 (class 2604 OID 1876600)
-- Dependencies: 223 222 223
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp ALTER COLUMN id SET DEFAULT nextval('accounting_document_temp_id_seq'::regclass);


--
-- TOC entry 1991 (class 2604 OID 220449)
-- Dependencies: 163 162
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance ALTER COLUMN id SET DEFAULT nextval('balance_id_seq'::regclass);


--
-- TOC entry 1992 (class 2604 OID 220450)
-- Dependencies: 186 164
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance_temp ALTER COLUMN id SET DEFAULT nextval('temp_id_seq'::regclass);


--
-- TOC entry 1993 (class 2604 OID 220451)
-- Dependencies: 166 165
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance_time ALTER COLUMN id SET DEFAULT nextval('balance_time_id_seq'::regclass);


--
-- TOC entry 1994 (class 2604 OID 220452)
-- Dependencies: 168 167
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier ALTER COLUMN id SET DEFAULT nextval('carrier_id_seq'::regclass);


--
-- TOC entry 2017 (class 2604 OID 1876083)
-- Dependencies: 215 214 215
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_groups ALTER COLUMN id SET DEFAULT nextval('carrier_groups_id_seq'::regclass);


--
-- TOC entry 1995 (class 2604 OID 595638)
-- Dependencies: 193 169
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers ALTER COLUMN id SET DEFAULT nextval('carrier_managers_id_seq'::regclass);


--
-- TOC entry 2007 (class 2604 OID 978989)
-- Dependencies: 195 194 195
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY company ALTER COLUMN id SET DEFAULT nextval('company_id_seq'::regclass);


--
-- TOC entry 2008 (class 2604 OID 978997)
-- Dependencies: 197 196 197
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato ALTER COLUMN id SET DEFAULT nextval('contrato_id_seq'::regclass);


--
-- TOC entry 2013 (class 2604 OID 979095)
-- Dependencies: 206 207 207
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_monetizable ALTER COLUMN id SET DEFAULT nextval('contrato_monetizable_id_seq'::regclass);


--
-- TOC entry 2012 (class 2604 OID 979077)
-- Dependencies: 205 204 205
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago ALTER COLUMN id SET DEFAULT nextval('contrato_termino_pago_id_seq'::regclass);


--
-- TOC entry 2014 (class 2604 OID 1460963)
-- Dependencies: 209 208 209
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY credit_limit ALTER COLUMN id SET DEFAULT nextval('credit_limit_id_seq'::regclass);


--
-- TOC entry 2018 (class 2604 OID 1876543)
-- Dependencies: 216 217 217
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY currency ALTER COLUMN id SET DEFAULT nextval('currency_id_seq'::regclass);


--
-- TOC entry 2011 (class 2604 OID 979049)
-- Dependencies: 202 203 203
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY days_dispute_history ALTER COLUMN id SET DEFAULT nextval('days_dispute_history_id_seq'::regclass);


--
-- TOC entry 1996 (class 2604 OID 220453)
-- Dependencies: 171 170
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination ALTER COLUMN id SET DEFAULT nextval('destination_id_seq'::regclass);


--
-- TOC entry 1997 (class 2604 OID 220454)
-- Dependencies: 173 172
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_int ALTER COLUMN id SET DEFAULT nextval('destination_int_id_seq'::regclass);


--
-- TOC entry 2023 (class 2604 OID 2223410)
-- Dependencies: 226 227 227
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_supplier ALTER COLUMN id SET DEFAULT nextval('destination_supplier_id_seq'::regclass);


--
-- TOC entry 2016 (class 2604 OID 1570222)
-- Dependencies: 213 212
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY geographic_zone ALTER COLUMN id SET DEFAULT nextval('geographic_zone_id_seq'::regclass);


--
-- TOC entry 1999 (class 2604 OID 220455)
-- Dependencies: 179 176
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);


--
-- TOC entry 2000 (class 2604 OID 220456)
-- Dependencies: 178 177
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log_action ALTER COLUMN id SET DEFAULT nextval('log_action_id_seq'::regclass);


--
-- TOC entry 2001 (class 2604 OID 220457)
-- Dependencies: 181 180
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY managers ALTER COLUMN id SET DEFAULT nextval('managers_id_seq'::regclass);


--
-- TOC entry 2009 (class 2604 OID 979033)
-- Dependencies: 198 199 199
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY monetizable ALTER COLUMN id SET DEFAULT nextval('monetizable_id_seq'::regclass);


--
-- TOC entry 2002 (class 2604 OID 220458)
-- Dependencies: 183 182
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles ALTER COLUMN id SET DEFAULT nextval('profiles_id_seq'::regclass);


--
-- TOC entry 2003 (class 2604 OID 220459)
-- Dependencies: 185 184
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_renoc ALTER COLUMN id SET DEFAULT nextval('profiles_renoc_id_seq'::regclass);


--
-- TOC entry 2015 (class 2604 OID 1460976)
-- Dependencies: 211 210 211
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY purchase_limit ALTER COLUMN id SET DEFAULT nextval('purchase_limit_id_seq'::regclass);


--
-- TOC entry 1998 (class 2604 OID 220460)
-- Dependencies: 175 174
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rrhistory ALTER COLUMN id SET DEFAULT nextval('history_id_seq'::regclass);


--
-- TOC entry 2022 (class 2604 OID 2223392)
-- Dependencies: 225 224 225
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solved_days_dispute_history ALTER COLUMN id SET DEFAULT nextval('solved_days_dispute_history_id_seq'::regclass);


--
-- TOC entry 2010 (class 2604 OID 979041)
-- Dependencies: 200 201 201
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY termino_pago ALTER COLUMN id SET DEFAULT nextval('termino_pago_id_seq'::regclass);


--
-- TOC entry 2019 (class 2604 OID 1876569)
-- Dependencies: 218 219 219
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY type_accounting_document ALTER COLUMN id SET DEFAULT nextval('type_accounting_document_id_seq'::regclass);


--
-- TOC entry 2004 (class 2604 OID 220461)
-- Dependencies: 188 187
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY type_of_user ALTER COLUMN id SET DEFAULT nextval('type_of_user_id_seq'::regclass);


--
-- TOC entry 2005 (class 2604 OID 220462)
-- Dependencies: 190 189
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2006 (class 2604 OID 220463)
-- Dependencies: 192 191
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_renoc ALTER COLUMN id SET DEFAULT nextval('users_re_id_seq'::regclass);


--
-- TOC entry 2081 (class 2606 OID 1876085)
-- Dependencies: 215 215 2235
-- Name: carrier_groups_id; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier_groups
    ADD CONSTRAINT carrier_groups_id PRIMARY KEY (id);


--
-- TOC entry 2083 (class 2606 OID 1876545)
-- Dependencies: 217 217 2235
-- Name: currency_id; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY currency
    ADD CONSTRAINT currency_id PRIMARY KEY (id);


--
-- TOC entry 2033 (class 2606 OID 595663)
-- Dependencies: 169 169 2235
-- Name: id_PK; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT "id_PK" PRIMARY KEY (id);


--
-- TOC entry 2087 (class 2606 OID 1876579)
-- Dependencies: 221 221 2235
-- Name: id_accounting_document; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT id_accounting_document PRIMARY KEY (id);


--
-- TOC entry 2089 (class 2606 OID 1876602)
-- Dependencies: 223 223 2235
-- Name: id_accounting_document_temp; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT id_accounting_document_temp PRIMARY KEY (id);


--
-- TOC entry 2025 (class 2606 OID 281860)
-- Dependencies: 162 162 2235
-- Name: id_balance; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT id_balance PRIMARY KEY (id);


--
-- TOC entry 2029 (class 2606 OID 281862)
-- Dependencies: 165 165 2235
-- Name: id_balance_time; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance_time
    ADD CONSTRAINT id_balance_time PRIMARY KEY (id);


--
-- TOC entry 2031 (class 2606 OID 281864)
-- Dependencies: 167 167 2235
-- Name: id_carrier; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier
    ADD CONSTRAINT id_carrier PRIMARY KEY (id);


--
-- TOC entry 2061 (class 2606 OID 978991)
-- Dependencies: 195 195 2235
-- Name: id_company; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY company
    ADD CONSTRAINT id_company PRIMARY KEY (id);


--
-- TOC entry 2063 (class 2606 OID 978999)
-- Dependencies: 197 197 2235
-- Name: id_contrato; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY contrato
    ADD CONSTRAINT id_contrato PRIMARY KEY (id);


--
-- TOC entry 2073 (class 2606 OID 979097)
-- Dependencies: 207 207 2235
-- Name: id_contrato_monetizable; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY contrato_monetizable
    ADD CONSTRAINT id_contrato_monetizable PRIMARY KEY (id);


--
-- TOC entry 2071 (class 2606 OID 979079)
-- Dependencies: 205 205 2235
-- Name: id_contrato_termino_pago; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY contrato_termino_pago
    ADD CONSTRAINT id_contrato_termino_pago PRIMARY KEY (id);


--
-- TOC entry 2075 (class 2606 OID 1460965)
-- Dependencies: 209 209 2235
-- Name: id_credit_limit; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY credit_limit
    ADD CONSTRAINT id_credit_limit PRIMARY KEY (id);


--
-- TOC entry 2069 (class 2606 OID 979051)
-- Dependencies: 203 203 2235
-- Name: id_days_dispute_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY days_dispute_history
    ADD CONSTRAINT id_days_dispute_history PRIMARY KEY (id);


--
-- TOC entry 2035 (class 2606 OID 281866)
-- Dependencies: 170 170 2235
-- Name: id_destination; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination
    ADD CONSTRAINT id_destination PRIMARY KEY (id);


--
-- TOC entry 2037 (class 2606 OID 281868)
-- Dependencies: 172 172 2235
-- Name: id_destination_1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination_int
    ADD CONSTRAINT id_destination_1 PRIMARY KEY (id);


--
-- TOC entry 2093 (class 2606 OID 2223412)
-- Dependencies: 227 227 2235
-- Name: id_destination_supplier; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination_supplier
    ADD CONSTRAINT id_destination_supplier PRIMARY KEY (id);


--
-- TOC entry 2079 (class 2606 OID 1570227)
-- Dependencies: 212 212 2235
-- Name: id_geographic_zone; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY geographic_zone
    ADD CONSTRAINT id_geographic_zone PRIMARY KEY (id);


--
-- TOC entry 2039 (class 2606 OID 281870)
-- Dependencies: 174 174 2235
-- Name: id_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY rrhistory
    ADD CONSTRAINT id_history PRIMARY KEY (id);


--
-- TOC entry 2041 (class 2606 OID 281872)
-- Dependencies: 176 176 2235
-- Name: id_log; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT id_log PRIMARY KEY (id);


--
-- TOC entry 2043 (class 2606 OID 281874)
-- Dependencies: 177 177 2235
-- Name: id_log_action; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log_action
    ADD CONSTRAINT id_log_action PRIMARY KEY (id);


--
-- TOC entry 2045 (class 2606 OID 281876)
-- Dependencies: 180 180 2235
-- Name: id_managers; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY managers
    ADD CONSTRAINT id_managers PRIMARY KEY (id);


--
-- TOC entry 2065 (class 2606 OID 979035)
-- Dependencies: 199 199 2235
-- Name: id_monetizable; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY monetizable
    ADD CONSTRAINT id_monetizable PRIMARY KEY (id);


--
-- TOC entry 2047 (class 2606 OID 281878)
-- Dependencies: 182 182 2235
-- Name: id_profiles; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT id_profiles PRIMARY KEY (id);


--
-- TOC entry 2051 (class 2606 OID 281880)
-- Dependencies: 184 184 2235
-- Name: id_profiles_renoc; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT id_profiles_renoc PRIMARY KEY (id);


--
-- TOC entry 2077 (class 2606 OID 1460978)
-- Dependencies: 211 211 2235
-- Name: id_purchase_limit; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY purchase_limit
    ADD CONSTRAINT id_purchase_limit PRIMARY KEY (id);


--
-- TOC entry 2091 (class 2606 OID 2223394)
-- Dependencies: 225 225 2235
-- Name: id_solved_days_dispute_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solved_days_dispute_history
    ADD CONSTRAINT id_solved_days_dispute_history PRIMARY KEY (id);


--
-- TOC entry 2027 (class 2606 OID 281882)
-- Dependencies: 164 164 2235
-- Name: id_temp; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance_temp
    ADD CONSTRAINT id_temp PRIMARY KEY (id);


--
-- TOC entry 2067 (class 2606 OID 979043)
-- Dependencies: 201 201 2235
-- Name: id_termino_pago; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY termino_pago
    ADD CONSTRAINT id_termino_pago PRIMARY KEY (id);


--
-- TOC entry 2085 (class 2606 OID 1876571)
-- Dependencies: 219 219 2235
-- Name: id_type_accounting_document; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY type_accounting_document
    ADD CONSTRAINT id_type_accounting_document PRIMARY KEY (id);


--
-- TOC entry 2055 (class 2606 OID 281884)
-- Dependencies: 187 187 2235
-- Name: id_type_of_user; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY type_of_user
    ADD CONSTRAINT id_type_of_user PRIMARY KEY (id);


--
-- TOC entry 2057 (class 2606 OID 281886)
-- Dependencies: 189 189 2235
-- Name: id_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT id_users PRIMARY KEY (id);


--
-- TOC entry 2059 (class 2606 OID 281888)
-- Dependencies: 191 191 2235
-- Name: id_users1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users_renoc
    ADD CONSTRAINT id_users1 PRIMARY KEY (id);


--
-- TOC entry 2049 (class 2606 OID 281890)
-- Dependencies: 182 182 2235
-- Name: users_30102_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_30102_uq UNIQUE (id_users);


--
-- TOC entry 2053 (class 2606 OID 281892)
-- Dependencies: 184 184 2235
-- Name: users_renoc_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT users_renoc_uq UNIQUE (id_users_renoc);


--
-- TOC entry 2132 (class 2620 OID 2360390)
-- Dependencies: 176 240 2235
-- Name: rerate; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER rerate AFTER INSERT ON log FOR EACH STATEMENT EXECUTE PROCEDURE condicion();


--
-- TOC entry 2121 (class 2606 OID 2223433)
-- Dependencies: 2086 221 221 2235
-- Name: accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT accounting_document_fk FOREIGN KEY (id_accounting_document) REFERENCES accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2127 (class 2606 OID 2223463)
-- Dependencies: 221 223 2086 2235
-- Name: accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT accounting_document_fk FOREIGN KEY (id_accounting_document) REFERENCES accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2102 (class 2606 OID 281893)
-- Dependencies: 174 162 2024 2235
-- Name: balance_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rrhistory
    ADD CONSTRAINT balance_fk FOREIGN KEY (id_balance) REFERENCES balance(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2094 (class 2606 OID 281898)
-- Dependencies: 2030 167 162 2235
-- Name: carrier_customer_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT carrier_customer_fk FOREIGN KEY (id_carrier_customer) REFERENCES carrier(id);


--
-- TOC entry 2099 (class 2606 OID 595652)
-- Dependencies: 2030 167 169 2235
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2109 (class 2606 OID 1876652)
-- Dependencies: 167 197 2030 2235
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2131 (class 2606 OID 2223413)
-- Dependencies: 2030 227 167 2235
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_supplier
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2118 (class 2606 OID 2223418)
-- Dependencies: 2030 167 221 2235
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2124 (class 2606 OID 2223448)
-- Dependencies: 2030 223 167 2235
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2098 (class 2606 OID 2223382)
-- Dependencies: 167 215 2080 2235
-- Name: carrier_groups_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier
    ADD CONSTRAINT carrier_groups_fk FOREIGN KEY (id_carrier_groups) REFERENCES carrier_groups(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2095 (class 2606 OID 281908)
-- Dependencies: 2030 162 167 2235
-- Name: carrier_supplier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT carrier_supplier_fk FOREIGN KEY (id_carrier_supplier) REFERENCES carrier(id);


--
-- TOC entry 2110 (class 2606 OID 1876657)
-- Dependencies: 195 2060 197 2235
-- Name: company_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato
    ADD CONSTRAINT company_fk FOREIGN KEY (id_company) REFERENCES company(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2114 (class 2606 OID 979098)
-- Dependencies: 207 2062 197 2235
-- Name: contrat6o_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_monetizable
    ADD CONSTRAINT contrat6o_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2111 (class 2606 OID 979052)
-- Dependencies: 203 197 2062 2235
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY days_dispute_history
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2112 (class 2606 OID 979080)
-- Dependencies: 2062 205 197 2235
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2116 (class 2606 OID 1460966)
-- Dependencies: 197 2062 209 2235
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY credit_limit
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2117 (class 2606 OID 1460989)
-- Dependencies: 2062 197 211 2235
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY purchase_limit
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2130 (class 2606 OID 2223400)
-- Dependencies: 2062 197 225 2235
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solved_days_dispute_history
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2119 (class 2606 OID 2223423)
-- Dependencies: 221 217 2082 2235
-- Name: currency_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT currency_fk FOREIGN KEY (id_currency) REFERENCES currency(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2125 (class 2606 OID 2223453)
-- Dependencies: 2082 223 217 2235
-- Name: currency_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT currency_fk FOREIGN KEY (id_currency) REFERENCES currency(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2096 (class 2606 OID 281913)
-- Dependencies: 2034 170 162 2235
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2122 (class 2606 OID 2223438)
-- Dependencies: 170 221 2034 2235
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2128 (class 2606 OID 2223468)
-- Dependencies: 223 170 2034 2235
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2097 (class 2606 OID 281918)
-- Dependencies: 2036 172 162 2235
-- Name: destination_int_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_int_fk FOREIGN KEY (id_destination_int) REFERENCES destination_int(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2123 (class 2606 OID 2223443)
-- Dependencies: 2092 227 221 2235
-- Name: destination_supplier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT destination_supplier_fk FOREIGN KEY (id_destination_supplier) REFERENCES destination_supplier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2129 (class 2606 OID 2223473)
-- Dependencies: 223 227 2092 2235
-- Name: destination_supplier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT destination_supplier_fk FOREIGN KEY (id_destination_supplier) REFERENCES destination_supplier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2101 (class 2606 OID 1570228)
-- Dependencies: 2078 172 212 2235
-- Name: geographic_zone_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_int
    ADD CONSTRAINT geographic_zone_fk FOREIGN KEY (id_geographic_zone) REFERENCES geographic_zone(id);


--
-- TOC entry 2103 (class 2606 OID 989914)
-- Dependencies: 2042 177 176 2235
-- Name: log_action_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_action_fk FOREIGN KEY (id_log_action) REFERENCES log_action(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2100 (class 2606 OID 595657)
-- Dependencies: 180 169 2044 2235
-- Name: managers_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT managers_fk FOREIGN KEY (id_managers) REFERENCES managers(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2115 (class 2606 OID 979103)
-- Dependencies: 207 2064 199 2235
-- Name: monetizable_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_monetizable
    ADD CONSTRAINT monetizable_fk FOREIGN KEY (id_monetizable) REFERENCES monetizable(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2113 (class 2606 OID 979085)
-- Dependencies: 2066 205 201 2235
-- Name: termino_pago_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago
    ADD CONSTRAINT termino_pago_fk FOREIGN KEY (id_termino_pago) REFERENCES termino_pago(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2120 (class 2606 OID 2223428)
-- Dependencies: 221 219 2084 2235
-- Name: type_accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT type_accounting_document_fk FOREIGN KEY (id_type_accounting_document) REFERENCES type_accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2126 (class 2606 OID 2223458)
-- Dependencies: 2084 219 223 2235
-- Name: type_accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT type_accounting_document_fk FOREIGN KEY (id_type_accounting_document) REFERENCES type_accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2107 (class 2606 OID 281933)
-- Dependencies: 189 2054 187 2235
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2108 (class 2606 OID 281938)
-- Dependencies: 187 2054 191 2235
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_renoc
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2105 (class 2606 OID 281943)
-- Dependencies: 189 182 2056 2235
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2106 (class 2606 OID 281953)
-- Dependencies: 2058 184 191 2235
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users_renoc) REFERENCES users_renoc(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2104 (class 2606 OID 989919)
-- Dependencies: 2056 176 189 2235
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2240 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-11-19 14:32:35 VET

--
-- PostgreSQL database dump complete
--

