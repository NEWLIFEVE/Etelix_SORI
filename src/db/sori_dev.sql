--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.4
-- Dumped by pg_dump version 9.2.4
-- Started on 2014-03-17 16:00:35

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 243 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2360 (class 0 OID 0)
-- Dependencies: 243
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- TOC entry 244 (class 3079 OID 21611991)
-- Name: dblink; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS dblink WITH SCHEMA public;


--
-- TOC entry 2361 (class 0 OID 0)
-- Dependencies: 244
-- Name: EXTENSION dblink; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION dblink IS 'connect to other PostgreSQL databases from within a database';


SET search_path = public, pg_catalog;

--
-- TOC entry 791 (class 1247 OID 21612037)
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
-- TOC entry 297 (class 1255 OID 21612038)
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
-- TOC entry 298 (class 1255 OID 21612039)
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
-- TOC entry 299 (class 1255 OID 21612040)
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
-- TOC entry 300 (class 1255 OID 21612041)
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
-- TOC entry 301 (class 1255 OID 21612042)
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
-- TOC entry 302 (class 1255 OID 21612043)
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
-- TOC entry 303 (class 1255 OID 21612044)
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
-- TOC entry 304 (class 1255 OID 21612045)
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
-- TOC entry 305 (class 1255 OID 21612046)
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
-- TOC entry 170 (class 1259 OID 21612047)
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
-- TOC entry 171 (class 1259 OID 21612050)
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
-- TOC entry 2363 (class 0 OID 0)
-- Dependencies: 171
-- Name: accounting_document_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE accounting_document_id_seq OWNED BY accounting_document.id;


--
-- TOC entry 172 (class 1259 OID 21612052)
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
    id_destination_supplier integer,
    id_accounting_document_temp integer
);


ALTER TABLE public.accounting_document_temp OWNER TO postgres;

--
-- TOC entry 2364 (class 0 OID 0)
-- Dependencies: 172
-- Name: COLUMN accounting_document_temp.issue_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN accounting_document_temp.issue_date IS '
';


--
-- TOC entry 173 (class 1259 OID 21612055)
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
-- TOC entry 2365 (class 0 OID 0)
-- Dependencies: 173
-- Name: accounting_document_temp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE accounting_document_temp_id_seq OWNED BY accounting_document_temp.id;


--
-- TOC entry 174 (class 1259 OID 21612057)
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
-- TOC entry 2366 (class 0 OID 0)
-- Dependencies: 174
-- Name: COLUMN balance.status; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN balance.status IS '0 deshabilitado 1 habilitado';


--
-- TOC entry 175 (class 1259 OID 21612060)
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
-- TOC entry 2367 (class 0 OID 0)
-- Dependencies: 175
-- Name: balance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE balance_id_seq OWNED BY balance.id;


SET default_with_oids = false;

--
-- TOC entry 176 (class 1259 OID 21612062)
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
-- TOC entry 177 (class 1259 OID 21612065)
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
-- TOC entry 178 (class 1259 OID 21612071)
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
-- TOC entry 2368 (class 0 OID 0)
-- Dependencies: 178
-- Name: balance_time_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE balance_time_id_seq OWNED BY balance_time.id;


--
-- TOC entry 179 (class 1259 OID 21612073)
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
-- TOC entry 180 (class 1259 OID 21612079)
-- Name: carrier_groups; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE carrier_groups (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.carrier_groups OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 21612082)
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
-- TOC entry 2369 (class 0 OID 0)
-- Dependencies: 181
-- Name: carrier_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_groups_id_seq OWNED BY carrier_groups.id;


--
-- TOC entry 182 (class 1259 OID 21612084)
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
-- TOC entry 2370 (class 0 OID 0)
-- Dependencies: 182
-- Name: carrier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_id_seq OWNED BY carrier.id;


--
-- TOC entry 183 (class 1259 OID 21612086)
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
-- TOC entry 184 (class 1259 OID 21612089)
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
-- TOC entry 2371 (class 0 OID 0)
-- Dependencies: 184
-- Name: carrier_managers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_managers_id_seq OWNED BY carrier_managers.id;


--
-- TOC entry 185 (class 1259 OID 21612091)
-- Name: company; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE company (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.company OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 21612094)
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
-- TOC entry 2372 (class 0 OID 0)
-- Dependencies: 186
-- Name: company_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE company_id_seq OWNED BY company.id;


--
-- TOC entry 187 (class 1259 OID 21612096)
-- Name: contrato; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE contrato (
    id integer NOT NULL,
    sign_date date,
    production_date date,
    end_date date,
    id_carrier integer NOT NULL,
    id_company integer NOT NULL,
    up integer,
    bank_fee integer
);


ALTER TABLE public.contrato OWNER TO postgres;

--
-- TOC entry 188 (class 1259 OID 21612099)
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
-- TOC entry 2373 (class 0 OID 0)
-- Dependencies: 188
-- Name: contrato_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE contrato_id_seq OWNED BY contrato.id;


--
-- TOC entry 189 (class 1259 OID 21612101)
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
-- TOC entry 2374 (class 0 OID 0)
-- Dependencies: 189
-- Name: COLUMN contrato_monetizable.end_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN contrato_monetizable.end_date IS '
';


--
-- TOC entry 190 (class 1259 OID 21612104)
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
-- TOC entry 2375 (class 0 OID 0)
-- Dependencies: 190
-- Name: contrato_monetizable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE contrato_monetizable_id_seq OWNED BY contrato_monetizable.id;


--
-- TOC entry 191 (class 1259 OID 21612106)
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
-- TOC entry 192 (class 1259 OID 21612109)
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
-- TOC entry 2376 (class 0 OID 0)
-- Dependencies: 192
-- Name: contrato_termino_pago_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE contrato_termino_pago_id_seq OWNED BY contrato_termino_pago.id;


--
-- TOC entry 193 (class 1259 OID 21612111)
-- Name: contrato_termino_pago_supplier; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE contrato_termino_pago_supplier (
    id integer NOT NULL,
    start_date date NOT NULL,
    end_date date,
    id_contrato integer NOT NULL,
    id_termino_pago_supplier integer NOT NULL,
    month_break integer,
    first_day integer,
    id_fact_period integer
);


ALTER TABLE public.contrato_termino_pago_supplier OWNER TO postgres;

--
-- TOC entry 194 (class 1259 OID 21612114)
-- Name: contrato_termino_pago_supplier_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE contrato_termino_pago_supplier_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.contrato_termino_pago_supplier_id_seq OWNER TO postgres;

--
-- TOC entry 2377 (class 0 OID 0)
-- Dependencies: 194
-- Name: contrato_termino_pago_supplier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE contrato_termino_pago_supplier_id_seq OWNED BY contrato_termino_pago_supplier.id;


--
-- TOC entry 195 (class 1259 OID 21612116)
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
-- TOC entry 196 (class 1259 OID 21612119)
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
-- TOC entry 2378 (class 0 OID 0)
-- Dependencies: 196
-- Name: credit_limit_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE credit_limit_id_seq OWNED BY credit_limit.id;


--
-- TOC entry 197 (class 1259 OID 21612121)
-- Name: currency; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE currency (
    id integer NOT NULL,
    name character varying(10) NOT NULL
);


ALTER TABLE public.currency OWNER TO postgres;

--
-- TOC entry 198 (class 1259 OID 21612124)
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
-- TOC entry 2379 (class 0 OID 0)
-- Dependencies: 198
-- Name: currency_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE currency_id_seq OWNED BY currency.id;


--
-- TOC entry 199 (class 1259 OID 21612126)
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
-- TOC entry 200 (class 1259 OID 21612129)
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
-- TOC entry 2380 (class 0 OID 0)
-- Dependencies: 200
-- Name: days_dispute_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE days_dispute_history_id_seq OWNED BY days_dispute_history.id;


--
-- TOC entry 201 (class 1259 OID 21612131)
-- Name: destination; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    id_geographic_zone integer
);


ALTER TABLE public.destination OWNER TO postgres;

--
-- TOC entry 202 (class 1259 OID 21612134)
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
-- TOC entry 2381 (class 0 OID 0)
-- Dependencies: 202
-- Name: destination_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_id_seq OWNED BY destination.id;


--
-- TOC entry 203 (class 1259 OID 21612136)
-- Name: destination_int; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination_int (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    id_geographic_zone integer
);


ALTER TABLE public.destination_int OWNER TO postgres;

--
-- TOC entry 204 (class 1259 OID 21612139)
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
-- TOC entry 2382 (class 0 OID 0)
-- Dependencies: 204
-- Name: destination_int_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_int_id_seq OWNED BY destination_int.id;


--
-- TOC entry 205 (class 1259 OID 21612141)
-- Name: destination_supplier; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination_supplier (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    id_carrier integer NOT NULL
);


ALTER TABLE public.destination_supplier OWNER TO postgres;

--
-- TOC entry 206 (class 1259 OID 21612144)
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
-- TOC entry 2383 (class 0 OID 0)
-- Dependencies: 206
-- Name: destination_supplier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_supplier_id_seq OWNED BY destination_supplier.id;


--
-- TOC entry 207 (class 1259 OID 21612146)
-- Name: fact_period; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE fact_period (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.fact_period OWNER TO postgres;

--
-- TOC entry 208 (class 1259 OID 21612149)
-- Name: fact_period_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE fact_period_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.fact_period_id_seq OWNER TO postgres;

--
-- TOC entry 2384 (class 0 OID 0)
-- Dependencies: 208
-- Name: fact_period_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE fact_period_id_seq OWNED BY fact_period.id;


--
-- TOC entry 209 (class 1259 OID 21612151)
-- Name: geographic_zone; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE geographic_zone (
    id integer NOT NULL,
    name_zona character varying(50) NOT NULL,
    color_zona character varying(50) NOT NULL
);


ALTER TABLE public.geographic_zone OWNER TO postgres;

--
-- TOC entry 210 (class 1259 OID 21612154)
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
-- TOC entry 2385 (class 0 OID 0)
-- Dependencies: 210
-- Name: geographic_zone_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE geographic_zone_id_seq OWNED BY geographic_zone.id;


--
-- TOC entry 211 (class 1259 OID 21612156)
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
-- TOC entry 212 (class 1259 OID 21612159)
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
-- TOC entry 2386 (class 0 OID 0)
-- Dependencies: 212
-- Name: history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE history_id_seq OWNED BY rrhistory.id;


--
-- TOC entry 213 (class 1259 OID 21612161)
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
-- TOC entry 2387 (class 0 OID 0)
-- Dependencies: 213
-- Name: COLUMN log.description_date; Type: COMMENT; Schema: public; Owner: postgres
--

COMMENT ON COLUMN log.description_date IS 'En el caso de los rerate, almacena la fecha del archivo rerate guardado';


--
-- TOC entry 214 (class 1259 OID 21612164)
-- Name: log_action; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log_action (
    id integer NOT NULL,
    name character varying(50)
);


ALTER TABLE public.log_action OWNER TO postgres;

--
-- TOC entry 215 (class 1259 OID 21612167)
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
-- TOC entry 2388 (class 0 OID 0)
-- Dependencies: 215
-- Name: log_action_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_action_id_seq OWNED BY log_action.id;


--
-- TOC entry 216 (class 1259 OID 21612169)
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
-- TOC entry 2389 (class 0 OID 0)
-- Dependencies: 216
-- Name: log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_id_seq OWNED BY log.id;


--
-- TOC entry 217 (class 1259 OID 21612171)
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
-- TOC entry 218 (class 1259 OID 21612177)
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
-- TOC entry 2390 (class 0 OID 0)
-- Dependencies: 218
-- Name: managers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE managers_id_seq OWNED BY managers.id;


--
-- TOC entry 219 (class 1259 OID 21612179)
-- Name: monetizable; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE monetizable (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.monetizable OWNER TO postgres;

--
-- TOC entry 220 (class 1259 OID 21612182)
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
-- TOC entry 2391 (class 0 OID 0)
-- Dependencies: 220
-- Name: monetizable_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE monetizable_id_seq OWNED BY monetizable.id;


--
-- TOC entry 221 (class 1259 OID 21612184)
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
-- TOC entry 222 (class 1259 OID 21612187)
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
-- TOC entry 2392 (class 0 OID 0)
-- Dependencies: 222
-- Name: profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_id_seq OWNED BY profiles.id;


--
-- TOC entry 223 (class 1259 OID 21612189)
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
-- TOC entry 224 (class 1259 OID 21612192)
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
-- TOC entry 2393 (class 0 OID 0)
-- Dependencies: 224
-- Name: profiles_renoc_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_renoc_id_seq OWNED BY profiles_renoc.id;


--
-- TOC entry 225 (class 1259 OID 21612194)
-- Name: profiles_sine; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE profiles_sine (
    id integer NOT NULL,
    first_name character varying(128),
    last_name character varying(128),
    id_users_sine integer
);


ALTER TABLE public.profiles_sine OWNER TO postgres;

--
-- TOC entry 226 (class 1259 OID 21612197)
-- Name: profiles_sine_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE profiles_sine_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.profiles_sine_id_seq OWNER TO postgres;

--
-- TOC entry 2394 (class 0 OID 0)
-- Dependencies: 226
-- Name: profiles_sine_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_sine_id_seq OWNED BY profiles_sine.id;


--
-- TOC entry 227 (class 1259 OID 21612199)
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
-- TOC entry 228 (class 1259 OID 21612202)
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
-- TOC entry 2395 (class 0 OID 0)
-- Dependencies: 228
-- Name: purchase_limit_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE purchase_limit_id_seq OWNED BY purchase_limit.id;


--
-- TOC entry 229 (class 1259 OID 21612204)
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
-- TOC entry 230 (class 1259 OID 21612207)
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
-- TOC entry 2396 (class 0 OID 0)
-- Dependencies: 230
-- Name: solved_days_dispute_history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE solved_days_dispute_history_id_seq OWNED BY solved_days_dispute_history.id;


--
-- TOC entry 231 (class 1259 OID 21612209)
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
-- TOC entry 2397 (class 0 OID 0)
-- Dependencies: 231
-- Name: temp_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE temp_id_seq OWNED BY balance_temp.id;


--
-- TOC entry 232 (class 1259 OID 21612211)
-- Name: termino_pago; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE termino_pago (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    period integer,
    expiration integer
);


ALTER TABLE public.termino_pago OWNER TO postgres;

--
-- TOC entry 233 (class 1259 OID 21612214)
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
-- TOC entry 2398 (class 0 OID 0)
-- Dependencies: 233
-- Name: termino_pago_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE termino_pago_id_seq OWNED BY termino_pago.id;


--
-- TOC entry 234 (class 1259 OID 21612216)
-- Name: type_accounting_document; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE type_accounting_document (
    id integer NOT NULL,
    name character varying(50) NOT NULL,
    description character varying(250)
);


ALTER TABLE public.type_accounting_document OWNER TO postgres;

--
-- TOC entry 235 (class 1259 OID 21612219)
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
-- TOC entry 2399 (class 0 OID 0)
-- Dependencies: 235
-- Name: type_accounting_document_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE type_accounting_document_id_seq OWNED BY type_accounting_document.id;


--
-- TOC entry 236 (class 1259 OID 21612221)
-- Name: type_of_user; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE type_of_user (
    id integer NOT NULL,
    nombre character varying(45) NOT NULL
);


ALTER TABLE public.type_of_user OWNER TO postgres;

--
-- TOC entry 237 (class 1259 OID 21612224)
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
-- TOC entry 2400 (class 0 OID 0)
-- Dependencies: 237
-- Name: type_of_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE type_of_user_id_seq OWNED BY type_of_user.id;


--
-- TOC entry 238 (class 1259 OID 21612226)
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
-- TOC entry 239 (class 1259 OID 21612229)
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
-- TOC entry 2401 (class 0 OID 0)
-- Dependencies: 239
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 240 (class 1259 OID 21612231)
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
-- TOC entry 241 (class 1259 OID 21612234)
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
-- TOC entry 2402 (class 0 OID 0)
-- Dependencies: 241
-- Name: users_re_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_re_id_seq OWNED BY users_renoc.id;


--
-- TOC entry 242 (class 1259 OID 21612236)
-- Name: users_sine; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE users_sine (
    id integer DEFAULT nextval('users_re_id_seq'::regclass) NOT NULL,
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


ALTER TABLE public.users_sine OWNER TO postgres;

--
-- TOC entry 2191 (class 2604 OID 21612240)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document ALTER COLUMN id SET DEFAULT nextval('accounting_document_id_seq'::regclass);


--
-- TOC entry 2192 (class 2604 OID 21612241)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp ALTER COLUMN id SET DEFAULT nextval('accounting_document_temp_id_seq'::regclass);


--
-- TOC entry 2193 (class 2604 OID 21612242)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance ALTER COLUMN id SET DEFAULT nextval('balance_id_seq'::regclass);


--
-- TOC entry 2194 (class 2604 OID 21612243)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance_temp ALTER COLUMN id SET DEFAULT nextval('temp_id_seq'::regclass);


--
-- TOC entry 2195 (class 2604 OID 21612244)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance_time ALTER COLUMN id SET DEFAULT nextval('balance_time_id_seq'::regclass);


--
-- TOC entry 2196 (class 2604 OID 21612245)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier ALTER COLUMN id SET DEFAULT nextval('carrier_id_seq'::regclass);


--
-- TOC entry 2197 (class 2604 OID 21612246)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_groups ALTER COLUMN id SET DEFAULT nextval('carrier_groups_id_seq'::regclass);


--
-- TOC entry 2198 (class 2604 OID 21612247)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers ALTER COLUMN id SET DEFAULT nextval('carrier_managers_id_seq'::regclass);


--
-- TOC entry 2199 (class 2604 OID 21612248)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY company ALTER COLUMN id SET DEFAULT nextval('company_id_seq'::regclass);


--
-- TOC entry 2200 (class 2604 OID 21612249)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato ALTER COLUMN id SET DEFAULT nextval('contrato_id_seq'::regclass);


--
-- TOC entry 2201 (class 2604 OID 21612250)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_monetizable ALTER COLUMN id SET DEFAULT nextval('contrato_monetizable_id_seq'::regclass);


--
-- TOC entry 2202 (class 2604 OID 21612251)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago ALTER COLUMN id SET DEFAULT nextval('contrato_termino_pago_id_seq'::regclass);


--
-- TOC entry 2203 (class 2604 OID 21612252)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago_supplier ALTER COLUMN id SET DEFAULT nextval('contrato_termino_pago_supplier_id_seq'::regclass);


--
-- TOC entry 2204 (class 2604 OID 21612253)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY credit_limit ALTER COLUMN id SET DEFAULT nextval('credit_limit_id_seq'::regclass);


--
-- TOC entry 2205 (class 2604 OID 21612254)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY currency ALTER COLUMN id SET DEFAULT nextval('currency_id_seq'::regclass);


--
-- TOC entry 2206 (class 2604 OID 21612255)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY days_dispute_history ALTER COLUMN id SET DEFAULT nextval('days_dispute_history_id_seq'::regclass);


--
-- TOC entry 2207 (class 2604 OID 21612256)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination ALTER COLUMN id SET DEFAULT nextval('destination_id_seq'::regclass);


--
-- TOC entry 2208 (class 2604 OID 21612257)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_int ALTER COLUMN id SET DEFAULT nextval('destination_int_id_seq'::regclass);


--
-- TOC entry 2209 (class 2604 OID 21612258)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_supplier ALTER COLUMN id SET DEFAULT nextval('destination_supplier_id_seq'::regclass);


--
-- TOC entry 2210 (class 2604 OID 21612259)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY fact_period ALTER COLUMN id SET DEFAULT nextval('fact_period_id_seq'::regclass);


--
-- TOC entry 2211 (class 2604 OID 21612260)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY geographic_zone ALTER COLUMN id SET DEFAULT nextval('geographic_zone_id_seq'::regclass);


--
-- TOC entry 2213 (class 2604 OID 21612261)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);


--
-- TOC entry 2214 (class 2604 OID 21612262)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log_action ALTER COLUMN id SET DEFAULT nextval('log_action_id_seq'::regclass);


--
-- TOC entry 2215 (class 2604 OID 21612263)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY managers ALTER COLUMN id SET DEFAULT nextval('managers_id_seq'::regclass);


--
-- TOC entry 2216 (class 2604 OID 21612264)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY monetizable ALTER COLUMN id SET DEFAULT nextval('monetizable_id_seq'::regclass);


--
-- TOC entry 2217 (class 2604 OID 21612265)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles ALTER COLUMN id SET DEFAULT nextval('profiles_id_seq'::regclass);


--
-- TOC entry 2218 (class 2604 OID 21612266)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_renoc ALTER COLUMN id SET DEFAULT nextval('profiles_renoc_id_seq'::regclass);


--
-- TOC entry 2219 (class 2604 OID 21612267)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_sine ALTER COLUMN id SET DEFAULT nextval('profiles_sine_id_seq'::regclass);


--
-- TOC entry 2220 (class 2604 OID 21612268)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY purchase_limit ALTER COLUMN id SET DEFAULT nextval('purchase_limit_id_seq'::regclass);


--
-- TOC entry 2212 (class 2604 OID 21612269)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rrhistory ALTER COLUMN id SET DEFAULT nextval('history_id_seq'::regclass);


--
-- TOC entry 2221 (class 2604 OID 21612270)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solved_days_dispute_history ALTER COLUMN id SET DEFAULT nextval('solved_days_dispute_history_id_seq'::regclass);


--
-- TOC entry 2222 (class 2604 OID 21612271)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY termino_pago ALTER COLUMN id SET DEFAULT nextval('termino_pago_id_seq'::regclass);


--
-- TOC entry 2223 (class 2604 OID 21612272)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY type_accounting_document ALTER COLUMN id SET DEFAULT nextval('type_accounting_document_id_seq'::regclass);


--
-- TOC entry 2224 (class 2604 OID 21612273)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY type_of_user ALTER COLUMN id SET DEFAULT nextval('type_of_user_id_seq'::regclass);


--
-- TOC entry 2225 (class 2604 OID 21612274)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2226 (class 2604 OID 21612275)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_renoc ALTER COLUMN id SET DEFAULT nextval('users_re_id_seq'::regclass);


--
-- TOC entry 2241 (class 2606 OID 24207686)
-- Name: carrier_groups_id; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier_groups
    ADD CONSTRAINT carrier_groups_id PRIMARY KEY (id);


--
-- TOC entry 2257 (class 2606 OID 24207688)
-- Name: currency_id; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY currency
    ADD CONSTRAINT currency_id PRIMARY KEY (id);


--
-- TOC entry 2243 (class 2606 OID 24207690)
-- Name: id_PK; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT "id_PK" PRIMARY KEY (id);


--
-- TOC entry 2229 (class 2606 OID 24207692)
-- Name: id_accounting_document; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT id_accounting_document PRIMARY KEY (id);


--
-- TOC entry 2231 (class 2606 OID 24207694)
-- Name: id_accounting_document_temp; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT id_accounting_document_temp PRIMARY KEY (id);


--
-- TOC entry 2233 (class 2606 OID 24207696)
-- Name: id_balance; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT id_balance PRIMARY KEY (id);


--
-- TOC entry 2237 (class 2606 OID 24207698)
-- Name: id_balance_time; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance_time
    ADD CONSTRAINT id_balance_time PRIMARY KEY (id);


--
-- TOC entry 2239 (class 2606 OID 24207700)
-- Name: id_carrier; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier
    ADD CONSTRAINT id_carrier PRIMARY KEY (id);


--
-- TOC entry 2245 (class 2606 OID 24207702)
-- Name: id_company; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY company
    ADD CONSTRAINT id_company PRIMARY KEY (id);


--
-- TOC entry 2247 (class 2606 OID 24207704)
-- Name: id_contrato; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY contrato
    ADD CONSTRAINT id_contrato PRIMARY KEY (id);


--
-- TOC entry 2249 (class 2606 OID 24207706)
-- Name: id_contrato_monetizable; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY contrato_monetizable
    ADD CONSTRAINT id_contrato_monetizable PRIMARY KEY (id);


--
-- TOC entry 2251 (class 2606 OID 24207708)
-- Name: id_contrato_termino_pago; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY contrato_termino_pago
    ADD CONSTRAINT id_contrato_termino_pago PRIMARY KEY (id);


--
-- TOC entry 2253 (class 2606 OID 24207710)
-- Name: id_contrato_termino_pago_supplier; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY contrato_termino_pago_supplier
    ADD CONSTRAINT id_contrato_termino_pago_supplier PRIMARY KEY (id);


--
-- TOC entry 2255 (class 2606 OID 24207712)
-- Name: id_credit_limit; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY credit_limit
    ADD CONSTRAINT id_credit_limit PRIMARY KEY (id);


--
-- TOC entry 2259 (class 2606 OID 24207714)
-- Name: id_days_dispute_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY days_dispute_history
    ADD CONSTRAINT id_days_dispute_history PRIMARY KEY (id);


--
-- TOC entry 2261 (class 2606 OID 24207716)
-- Name: id_destination; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination
    ADD CONSTRAINT id_destination PRIMARY KEY (id);


--
-- TOC entry 2263 (class 2606 OID 24207718)
-- Name: id_destination_1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination_int
    ADD CONSTRAINT id_destination_1 PRIMARY KEY (id);


--
-- TOC entry 2265 (class 2606 OID 24207720)
-- Name: id_destination_supplier; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination_supplier
    ADD CONSTRAINT id_destination_supplier PRIMARY KEY (id);


--
-- TOC entry 2267 (class 2606 OID 24207722)
-- Name: id_fact_period; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY fact_period
    ADD CONSTRAINT id_fact_period PRIMARY KEY (id);


--
-- TOC entry 2269 (class 2606 OID 24207724)
-- Name: id_geographic_zone; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY geographic_zone
    ADD CONSTRAINT id_geographic_zone PRIMARY KEY (id);


--
-- TOC entry 2271 (class 2606 OID 24207726)
-- Name: id_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY rrhistory
    ADD CONSTRAINT id_history PRIMARY KEY (id);


--
-- TOC entry 2273 (class 2606 OID 24207728)
-- Name: id_log; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT id_log PRIMARY KEY (id);


--
-- TOC entry 2275 (class 2606 OID 24207730)
-- Name: id_log_action; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log_action
    ADD CONSTRAINT id_log_action PRIMARY KEY (id);


--
-- TOC entry 2277 (class 2606 OID 24207732)
-- Name: id_managers; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY managers
    ADD CONSTRAINT id_managers PRIMARY KEY (id);


--
-- TOC entry 2279 (class 2606 OID 24207734)
-- Name: id_monetizable; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY monetizable
    ADD CONSTRAINT id_monetizable PRIMARY KEY (id);


--
-- TOC entry 2281 (class 2606 OID 24207736)
-- Name: id_profiles; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT id_profiles PRIMARY KEY (id);


--
-- TOC entry 2285 (class 2606 OID 24207738)
-- Name: id_profiles_renoc; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT id_profiles_renoc PRIMARY KEY (id);


--
-- TOC entry 2289 (class 2606 OID 24207740)
-- Name: id_profiles_sine; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_sine
    ADD CONSTRAINT id_profiles_sine PRIMARY KEY (id);


--
-- TOC entry 2293 (class 2606 OID 24207742)
-- Name: id_purchase_limit; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY purchase_limit
    ADD CONSTRAINT id_purchase_limit PRIMARY KEY (id);


--
-- TOC entry 2295 (class 2606 OID 24207744)
-- Name: id_solved_days_dispute_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY solved_days_dispute_history
    ADD CONSTRAINT id_solved_days_dispute_history PRIMARY KEY (id);


--
-- TOC entry 2235 (class 2606 OID 24207746)
-- Name: id_temp; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance_temp
    ADD CONSTRAINT id_temp PRIMARY KEY (id);


--
-- TOC entry 2297 (class 2606 OID 24207748)
-- Name: id_termino_pago; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY termino_pago
    ADD CONSTRAINT id_termino_pago PRIMARY KEY (id);


--
-- TOC entry 2299 (class 2606 OID 24207750)
-- Name: id_type_accounting_document; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY type_accounting_document
    ADD CONSTRAINT id_type_accounting_document PRIMARY KEY (id);


--
-- TOC entry 2301 (class 2606 OID 24207752)
-- Name: id_type_of_user; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY type_of_user
    ADD CONSTRAINT id_type_of_user PRIMARY KEY (id);


--
-- TOC entry 2303 (class 2606 OID 24207754)
-- Name: id_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT id_users PRIMARY KEY (id);


--
-- TOC entry 2305 (class 2606 OID 24207756)
-- Name: id_users1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users_renoc
    ADD CONSTRAINT id_users1 PRIMARY KEY (id);


--
-- TOC entry 2307 (class 2606 OID 24207758)
-- Name: id_users2; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users_sine
    ADD CONSTRAINT id_users2 PRIMARY KEY (id);


--
-- TOC entry 2283 (class 2606 OID 24207760)
-- Name: users_30102_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_30102_uq UNIQUE (id_users);


--
-- TOC entry 2287 (class 2606 OID 24207762)
-- Name: users_renoc_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT users_renoc_uq UNIQUE (id_users_renoc);


--
-- TOC entry 2291 (class 2606 OID 24207764)
-- Name: users_sine_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles_sine
    ADD CONSTRAINT users_sine_uq UNIQUE (id_users_sine);


--
-- TOC entry 2352 (class 2620 OID 24207765)
-- Name: rerate; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER rerate AFTER INSERT ON log FOR EACH STATEMENT EXECUTE PROCEDURE condicion();


--
-- TOC entry 2308 (class 2606 OID 24207766)
-- Name: accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT accounting_document_fk FOREIGN KEY (id_accounting_document) REFERENCES accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2314 (class 2606 OID 24207771)
-- Name: accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT accounting_document_fk FOREIGN KEY (id_accounting_document) REFERENCES accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2315 (class 2606 OID 24207776)
-- Name: accounting_document_temp_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT accounting_document_temp_fk FOREIGN KEY (id_accounting_document_temp) REFERENCES accounting_document_temp(id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2341 (class 2606 OID 24207781)
-- Name: balance_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rrhistory
    ADD CONSTRAINT balance_fk FOREIGN KEY (id_balance) REFERENCES balance(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2321 (class 2606 OID 24207786)
-- Name: carrier_customer_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT carrier_customer_fk FOREIGN KEY (id_carrier_customer) REFERENCES carrier(id);


--
-- TOC entry 2326 (class 2606 OID 24207791)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2340 (class 2606 OID 24207796)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_supplier
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2309 (class 2606 OID 24207801)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2316 (class 2606 OID 24207806)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2328 (class 2606 OID 24207811)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2325 (class 2606 OID 24207816)
-- Name: carrier_groups_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier
    ADD CONSTRAINT carrier_groups_fk FOREIGN KEY (id_carrier_groups) REFERENCES carrier_groups(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2322 (class 2606 OID 24207821)
-- Name: carrier_supplier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT carrier_supplier_fk FOREIGN KEY (id_carrier_supplier) REFERENCES carrier(id);


--
-- TOC entry 2329 (class 2606 OID 24207826)
-- Name: company_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato
    ADD CONSTRAINT company_fk FOREIGN KEY (id_company) REFERENCES company(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2330 (class 2606 OID 24207831)
-- Name: contrat6o_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_monetizable
    ADD CONSTRAINT contrat6o_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2338 (class 2606 OID 24207836)
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY days_dispute_history
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2337 (class 2606 OID 24207841)
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY credit_limit
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2347 (class 2606 OID 24207846)
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY purchase_limit
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2348 (class 2606 OID 24207851)
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY solved_days_dispute_history
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2332 (class 2606 OID 24207856)
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2334 (class 2606 OID 24207861)
-- Name: contrato_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago_supplier
    ADD CONSTRAINT contrato_fk FOREIGN KEY (id_contrato) REFERENCES contrato(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2310 (class 2606 OID 24207866)
-- Name: currency_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT currency_fk FOREIGN KEY (id_currency) REFERENCES currency(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2317 (class 2606 OID 24207871)
-- Name: currency_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT currency_fk FOREIGN KEY (id_currency) REFERENCES currency(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2323 (class 2606 OID 24207876)
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2311 (class 2606 OID 24207881)
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2318 (class 2606 OID 24207886)
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2324 (class 2606 OID 24207891)
-- Name: destination_int_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_int_fk FOREIGN KEY (id_destination_int) REFERENCES destination_int(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2312 (class 2606 OID 24207896)
-- Name: destination_supplier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT destination_supplier_fk FOREIGN KEY (id_destination_supplier) REFERENCES destination_supplier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2319 (class 2606 OID 24207901)
-- Name: destination_supplier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT destination_supplier_fk FOREIGN KEY (id_destination_supplier) REFERENCES destination_supplier(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2335 (class 2606 OID 24207906)
-- Name: fact_period_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago_supplier
    ADD CONSTRAINT fact_period_fk FOREIGN KEY (id_fact_period) REFERENCES fact_period(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2339 (class 2606 OID 24207911)
-- Name: geographic_zone_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_int
    ADD CONSTRAINT geographic_zone_fk FOREIGN KEY (id_geographic_zone) REFERENCES geographic_zone(id);


--
-- TOC entry 2342 (class 2606 OID 24207916)
-- Name: log_action_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_action_fk FOREIGN KEY (id_log_action) REFERENCES log_action(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2327 (class 2606 OID 24207921)
-- Name: managers_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT managers_fk FOREIGN KEY (id_managers) REFERENCES managers(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2331 (class 2606 OID 24207926)
-- Name: monetizable_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_monetizable
    ADD CONSTRAINT monetizable_fk FOREIGN KEY (id_monetizable) REFERENCES monetizable(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2333 (class 2606 OID 24207931)
-- Name: termino_pago_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago
    ADD CONSTRAINT termino_pago_fk FOREIGN KEY (id_termino_pago) REFERENCES termino_pago(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2336 (class 2606 OID 24207936)
-- Name: termino_pago_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY contrato_termino_pago_supplier
    ADD CONSTRAINT termino_pago_fk FOREIGN KEY (id_termino_pago_supplier) REFERENCES termino_pago(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2313 (class 2606 OID 24207941)
-- Name: type_accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document
    ADD CONSTRAINT type_accounting_document_fk FOREIGN KEY (id_type_accounting_document) REFERENCES type_accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2320 (class 2606 OID 24207946)
-- Name: type_accounting_document_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY accounting_document_temp
    ADD CONSTRAINT type_accounting_document_fk FOREIGN KEY (id_type_accounting_document) REFERENCES type_accounting_document(id) ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2349 (class 2606 OID 24207951)
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2350 (class 2606 OID 24207956)
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_renoc
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2351 (class 2606 OID 24207961)
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users_sine
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2344 (class 2606 OID 24207966)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2345 (class 2606 OID 24207971)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_renoc
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users_renoc) REFERENCES users_renoc(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2343 (class 2606 OID 24207976)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2346 (class 2606 OID 24207981)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles_sine
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users_sine) REFERENCES users_sine(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2359 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- TOC entry 2362 (class 0 OID 0)
-- Dependencies: 791
-- Name: demo; Type: ACL; Schema: public; Owner: postgres
--

REVOKE ALL ON TYPE demo FROM PUBLIC;
REVOKE ALL ON TYPE demo FROM postgres;
GRANT ALL ON TYPE demo TO PUBLIC;


-- Completed on 2014-03-17 16:00:35

--
-- PostgreSQL database dump complete
--

