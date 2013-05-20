-- Database generated with pgModeler (PostgreSQL Database Modeler).
-- PostgreSQL version: 9.2
-- Project Site: pgmodeler.com.br
-- Model Author: ---

SET check_function_bodies = false;
-- ddl-end --


-- Database creation must be done outside an multicommand file.
-- These commands were put in this file only for convenience.
-- -- object: sori | type: DATABASE -- 
-- CREATE DATABASE sori
-- ;
-- -- ddl-end --
-- 

-- object: public.carrier | type: TABLE -- 
CREATE TABLE public.carrier(
	id serial NOT NULL,
	name varchar(50) NOT NULL,
	address text,
	fecha_registro date NOT NULL,
	CONSTRAINT id_carrier PRIMARY KEY (id)
)
WITH (OIDS=TRUE);
-- ddl-end --

-- ddl-end --

-- object: public.balance | type: TABLE -- 
CREATE TABLE public.balance(
	id bigserial NOT NULL,
	date date NOT NULL,
	minutes double precision NOT NULL,
	acd double precision NOT NULL,
	asr double precision NOT NULL,
	margin_percentage double precision NOT NULL,
	margin_per_minute double precision NOT NULL,
	cost_per_minute double precision NOT NULL,
	revenue_per_min double precision NOT NULL,
	pdd double precision NOT NULL,
	incomplete_calls double precision NOT NULL,
	complete_calls_ner double precision NOT NULL,
	complete_calls double precision NOT NULL,
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
	id_carrier integer,
	id_destination integer,
	CONSTRAINT id_balance PRIMARY KEY (id)
)
WITH (OIDS=TRUE);
-- ddl-end --

-- ddl-end --

-- object: carrier_fk | type: CONSTRAINT -- 
ALTER TABLE public.balance ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier)
REFERENCES public.carrier (id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE NOT DEFERRABLE;
-- ddl-end --


-- object: public.destination | type: TABLE -- 
CREATE TABLE public.destination(
	id serial NOT NULL,
	nombre varchar(50) NOT NULL,
	CONSTRAINT id_destination PRIMARY KEY (id)
)
WITH (OIDS=TRUE);
-- ddl-end --

-- ddl-end --

-- object: destination_fk | type: CONSTRAINT -- 
ALTER TABLE public.balance ADD CONSTRAINT destination_fk FOREIGN KEY (id_destination)
REFERENCES public.destination (id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE NOT DEFERRABLE;
-- ddl-end --


-- object: public.type_of_user | type: TABLE -- 
CREATE TABLE public.type_of_user(
	id serial NOT NULL,
	nombre varchar(45) NOT NULL,
	CONSTRAINT id_type_of_user PRIMARY KEY (id)
)
WITH (OIDS=TRUE);
-- ddl-end --

-- ddl-end --

-- object: public.users | type: TABLE -- 
CREATE TABLE public.users(
	id serial NOT NULL,
	username varchar(20) NOT NULL,
	password varchar(128) NOT NULL,
	email varchar(128) NOT NULL,
	activkey varchar(128) NOT NULL,
	superuser boolean NOT NULL,
	status boolean NOT NULL,
	create_at timestamp NOT NULL,
	lastvisit_at timestamp NOT NULL,
	id_type_of_user integer,
	CONSTRAINT id_users PRIMARY KEY (id)
)
WITH (OIDS=TRUE);
-- ddl-end --

-- ddl-end --

-- object: type_of_user_fk | type: CONSTRAINT -- 
ALTER TABLE public.users ADD CONSTRAINT type_of_user_fk FOREIGN KEY (id_type_of_user)
REFERENCES public.type_of_user (id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE NOT DEFERRABLE;
-- ddl-end --


-- object: public.profiles | type: TABLE -- 
CREATE TABLE public.profiles(
	id serial NOT NULL,
	first_name varchar(128),
	last_name varchar(128),
	id_users integer,
	CONSTRAINT id_profiles PRIMARY KEY (id)
)
WITH (OIDS=TRUE);
-- ddl-end --

-- ddl-end --

-- object: users_fk | type: CONSTRAINT -- 
ALTER TABLE public.profiles ADD CONSTRAINT users_fk FOREIGN KEY (id_users)
REFERENCES public.users (id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE NOT DEFERRABLE;
-- ddl-end --


-- object: users_30264_uq | type: CONSTRAINT -- 
ALTER TABLE public.profiles ADD CONSTRAINT users_30264_uq UNIQUE (id_users);
-- ddl-end --


-- object: public.history | type: TABLE -- 
CREATE TABLE public.history(
	id bigserial NOT NULL,
	date date NOT NULL,
	minutes double precision NOT NULL,
	acd double precision NOT NULL,
	asr double precision NOT NULL,
	margin_percentage double precision NOT NULL,
	margin_per_minute double precision NOT NULL,
	cost_per_minute double precision NOT NULL,
	revenue_per_min double precision NOT NULL,
	pdd double precision NOT NULL,
	incomplete_calls double precision NOT NULL,
	complete_calls_ner double precision NOT NULL,
	complete_calls double precision NOT NULL,
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
	id_balance bigint,
	CONSTRAINT id_history PRIMARY KEY (id)
)
WITH (OIDS=TRUE);
-- ddl-end --

COMMENT ON COLUMN public.history.acd IS 'Average Call Duration';
COMMENT ON COLUMN public.history.asr IS 'Average Assure Rating';
-- ddl-end --

-- object: balance_fk | type: CONSTRAINT -- 
ALTER TABLE public.history ADD CONSTRAINT balance_fk FOREIGN KEY (id_balance)
REFERENCES public.balance (id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE NOT DEFERRABLE;
-- ddl-end --


-- object: public.carrier_managers | type: TABLE -- 
CREATE TABLE public.carrier_managers(
	start_date date,
	end_date date,
	id_carrier integer,
	id_users integer
)
WITH (OIDS=TRUE);
-- ddl-end --

-- ddl-end --

-- object: carrier_fk | type: CONSTRAINT -- 
ALTER TABLE public.carrier_managers ADD CONSTRAINT carrier_fk FOREIGN KEY (id_carrier)
REFERENCES public.carrier (id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE NOT DEFERRABLE;
-- ddl-end --


-- object: users_fk | type: CONSTRAINT -- 
ALTER TABLE public.carrier_managers ADD CONSTRAINT users_fk FOREIGN KEY (id_users)
REFERENCES public.users (id) MATCH FULL
ON DELETE SET NULL ON UPDATE CASCADE NOT DEFERRABLE;
-- ddl-end --



