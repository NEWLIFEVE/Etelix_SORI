--
-- PostgreSQL database dump
--

-- Dumped from database version 9.2.4
-- Dumped by pg_dump version 9.2.2
-- Started on 2013-06-27 17:56:15

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- TOC entry 191 (class 3079 OID 11727)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2033 (class 0 OID 0)
-- Dependencies: 191
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = true;

--
-- TOC entry 178 (class 1259 OID 131145)
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
    revenue_per_min double precision NOT NULL,
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
    type integer NOT NULL,
    id_carrier integer,
    id_destination integer,
    id_destination_int integer
);


ALTER TABLE public.balance OWNER TO postgres;

--
-- TOC entry 177 (class 1259 OID 131143)
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
-- TOC entry 2034 (class 0 OID 0)
-- Dependencies: 177
-- Name: balance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE balance_id_seq OWNED BY balance.id;


--
-- TOC entry 190 (class 1259 OID 151127)
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
    revenue_per_min double precision NOT NULL,
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
    type integer NOT NULL,
    time_change time without time zone NOT NULL,
    name_carrier character varying(50) NOT NULL,
    name_destination character varying(50) NOT NULL
);


ALTER TABLE public.balance_time OWNER TO postgres;

--
-- TOC entry 189 (class 1259 OID 151125)
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
-- TOC entry 2035 (class 0 OID 0)
-- Dependencies: 189
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
-- TOC entry 2036 (class 0 OID 0)
-- Dependencies: 174
-- Name: carrier_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE carrier_id_seq OWNED BY carrier.id;


--
-- TOC entry 176 (class 1259 OID 131130)
-- Name: carrier_managers; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE carrier_managers (
    start_date date,
    end_date date,
    id_carrier integer,
    id_users integer
);


ALTER TABLE public.carrier_managers OWNER TO postgres;

--
-- TOC entry 180 (class 1259 OID 131158)
-- Name: destination; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.destination OWNER TO postgres;

--
-- TOC entry 179 (class 1259 OID 131156)
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
-- TOC entry 2037 (class 0 OID 0)
-- Dependencies: 179
-- Name: destination_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_id_seq OWNED BY destination.id;


--
-- TOC entry 184 (class 1259 OID 131184)
-- Name: destination_int; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE destination_int (
    id integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.destination_int OWNER TO postgres;

--
-- TOC entry 183 (class 1259 OID 131182)
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
-- TOC entry 2038 (class 0 OID 0)
-- Dependencies: 183
-- Name: destination_int_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE destination_int_id_seq OWNED BY destination_int.id;


--
-- TOC entry 182 (class 1259 OID 131171)
-- Name: history; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE history (
    id integer NOT NULL,
    date_balance date NOT NULL,
    minutes double precision NOT NULL,
    acd double precision NOT NULL,
    asr double precision NOT NULL,
    margin_percentage double precision NOT NULL,
    margin_per_minute double precision NOT NULL,
    cost_per_minute double precision NOT NULL,
    revenue_per_min double precision NOT NULL,
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
    type integer NOT NULL,
    id_balance integer
);


ALTER TABLE public.history OWNER TO postgres;

--
-- TOC entry 181 (class 1259 OID 131169)
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
-- TOC entry 2039 (class 0 OID 0)
-- Dependencies: 181
-- Name: history_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE history_id_seq OWNED BY history.id;


--
-- TOC entry 188 (class 1259 OID 131205)
-- Name: log; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log (
    id integer NOT NULL,
    date date NOT NULL,
    hour time without time zone NOT NULL,
    id_log_action integer,
    id_users integer
);


ALTER TABLE public.log OWNER TO postgres;

--
-- TOC entry 186 (class 1259 OID 131197)
-- Name: log_action; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE log_action (
    id integer NOT NULL,
    name character varying(50)
);


ALTER TABLE public.log_action OWNER TO postgres;

--
-- TOC entry 185 (class 1259 OID 131195)
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
-- TOC entry 2040 (class 0 OID 0)
-- Dependencies: 185
-- Name: log_action_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_action_id_seq OWNED BY log_action.id;


--
-- TOC entry 187 (class 1259 OID 131203)
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
-- TOC entry 2041 (class 0 OID 0)
-- Dependencies: 187
-- Name: log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE log_id_seq OWNED BY log.id;


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
-- TOC entry 2042 (class 0 OID 0)
-- Dependencies: 172
-- Name: profiles_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE profiles_id_seq OWNED BY profiles.id;


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
-- TOC entry 2043 (class 0 OID 0)
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
-- TOC entry 2044 (class 0 OID 0)
-- Dependencies: 170
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE users_id_seq OWNED BY users.id;


--
-- TOC entry 1985 (class 2604 OID 131148)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance ALTER COLUMN id SET DEFAULT nextval('balance_id_seq'::regclass);


--
-- TOC entry 1991 (class 2604 OID 151130)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance_time ALTER COLUMN id SET DEFAULT nextval('balance_time_id_seq'::regclass);


--
-- TOC entry 1984 (class 2604 OID 131124)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier ALTER COLUMN id SET DEFAULT nextval('carrier_id_seq'::regclass);


--
-- TOC entry 1986 (class 2604 OID 131161)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination ALTER COLUMN id SET DEFAULT nextval('destination_id_seq'::regclass);


--
-- TOC entry 1988 (class 2604 OID 131187)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY destination_int ALTER COLUMN id SET DEFAULT nextval('destination_int_id_seq'::regclass);


--
-- TOC entry 1987 (class 2604 OID 131174)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY history ALTER COLUMN id SET DEFAULT nextval('history_id_seq'::regclass);


--
-- TOC entry 1990 (class 2604 OID 131208)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);


--
-- TOC entry 1989 (class 2604 OID 131200)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log_action ALTER COLUMN id SET DEFAULT nextval('log_action_id_seq'::regclass);


--
-- TOC entry 1983 (class 2604 OID 131109)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles ALTER COLUMN id SET DEFAULT nextval('profiles_id_seq'::regclass);


--
-- TOC entry 1981 (class 2604 OID 131088)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY type_of_user ALTER COLUMN id SET DEFAULT nextval('type_of_user_id_seq'::regclass);


--
-- TOC entry 1982 (class 2604 OID 131096)
-- Name: id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users ALTER COLUMN id SET DEFAULT nextval('users_id_seq'::regclass);


--
-- TOC entry 2003 (class 2606 OID 131150)
-- Name: id_balance; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT id_balance PRIMARY KEY (id);


--
-- TOC entry 2015 (class 2606 OID 151132)
-- Name: id_balance_time; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY balance_time
    ADD CONSTRAINT id_balance_time PRIMARY KEY (id);


--
-- TOC entry 2001 (class 2606 OID 131129)
-- Name: id_carrier; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY carrier
    ADD CONSTRAINT id_carrier PRIMARY KEY (id);


--
-- TOC entry 2005 (class 2606 OID 131163)
-- Name: id_destination; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination
    ADD CONSTRAINT id_destination PRIMARY KEY (id);


--
-- TOC entry 2009 (class 2606 OID 131189)
-- Name: id_destination_1; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY destination_int
    ADD CONSTRAINT id_destination_1 PRIMARY KEY (id);


--
-- TOC entry 2007 (class 2606 OID 131176)
-- Name: id_history; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY history
    ADD CONSTRAINT id_history PRIMARY KEY (id);


--
-- TOC entry 2013 (class 2606 OID 131210)
-- Name: id_log; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT id_log PRIMARY KEY (id);


--
-- TOC entry 2011 (class 2606 OID 131202)
-- Name: id_log_action; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY log_action
    ADD CONSTRAINT id_log_action PRIMARY KEY (id);


--
-- TOC entry 1997 (class 2606 OID 131111)
-- Name: id_profiles; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT id_profiles PRIMARY KEY (id);


--
-- TOC entry 1993 (class 2606 OID 131090)
-- Name: id_type_of_user; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY type_of_user
    ADD CONSTRAINT id_type_of_user PRIMARY KEY (id);


--
-- TOC entry 1995 (class 2606 OID 131098)
-- Name: id_users; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY users
    ADD CONSTRAINT id_users PRIMARY KEY (id);


--
-- TOC entry 1999 (class 2606 OID 131118)
-- Name: users_30102_uq; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_30102_uq UNIQUE (id_users);


--
-- TOC entry 2023 (class 2606 OID 131177)
-- Name: balance_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY history
    ADD CONSTRAINT balance_fk FOREIGN KEY (id_balance) REFERENCES balance(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2018 (class 2606 OID 131133)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2020 (class 2606 OID 131151)
-- Name: carrier_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier) REFERENCES carrier(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2021 (class 2606 OID 131164)
-- Name: destination_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination) REFERENCES destination(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2022 (class 2606 OID 131190)
-- Name: destination_int_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY balance
    ADD CONSTRAINT destination_int_fk FOREIGN KEY (id_destination_int) REFERENCES destination_int(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2024 (class 2606 OID 131211)
-- Name: log_action_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_action_fk FOREIGN KEY (id_log_action) REFERENCES log_action(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2016 (class 2606 OID 131099)
-- Name: type_of_user_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY users
    ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user) REFERENCES type_of_user(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2017 (class 2606 OID 131112)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY profiles
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2019 (class 2606 OID 131138)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY carrier_managers
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2025 (class 2606 OID 131216)
-- Name: users_fk; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY log
    ADD CONSTRAINT users_fk FOREIGN KEY (id_users) REFERENCES users(id) MATCH FULL ON UPDATE CASCADE ON DELETE SET NULL;


--
-- TOC entry 2032 (class 0 OID 0)
-- Dependencies: 6
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2013-06-27 17:56:15

--
-- PostgreSQL database dump complete
--

