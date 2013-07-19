CREATE OR REPLACE function busca_balance(fecha date,tipo integer, carrier integer, destination integer, destination) returns boolean
AS $$
DECLARE
#variables para tabla balance
fecha_balance date;
tipo_balance integer;
carrier_balance integer;
destino_balance integer;
destino_interno_balance integer;
#variables para tabla balance_temp
fecha_balance_temp date;
tipo_balance_temp integer;
carrier_balance_temp integer;
destino_balance_temp integer;
destino_interno_balance_temp integer;
BEGIN 
	SELECT date_balance, type, id_carrier, id_destination, id_destination_int INTO fecha_balance_temp, tipo_balance_temp, carrier_balance_temp, destino_balance_interno_temp FROM balance_temp WHERE id=ide;
	SELECT date_balance, type, id_carrier, id_destination, id_destination_int INTO fecha_balance, tipo_balance, carrier_balance, destino_balance, destino_interno_balance FROM balance WHERE 
	IF fecha_balance<>fecha THEN
		RETURN true;
	ELSE
		RETURN false;
	END IF;
END;

$$ language plpgsql;

/*Creo una tipo de variables*/
CREATE TYPE demo AS (id integer, date_balance date, minutes double precision, acd double precision, asr double precision, margin_percentage double precision, margin_per_minute double precision, cost_per_minute double precision, revenue_per_minute double precision, pdd double precision, incomplete_calls double precision, incomplete_calls_ner double precision, complete_calls double precision, complete_calls_ner double precision, calls_attempts double precision, duration_real double precision, duration_cost double precision, ner02_efficient double precision, ner02_seizure double precision, pdd_calls double precision, revenue double precision, cost double precision, margin double precision, date_change date, type integer, id_carrier integer, id_destination integer, id_destination_int integer);

/*funcion que verifica si existe un registro*/
CREATE OR REPLACE function buscar_balance(fecha date, tipo integer, carrier integer, destination integer, destination_int integer) RETURNS boolean
AS $$
DECLARE
--variables de tablas--
bid integer;
BEGIN
	IF destination IS NOT NULL THEN
		SELECT id INTO bid FROM balance WHERE date_balance=fecha AND type=tipo AND id_carrier=carrier AND id_destination=destination;
	ELSE
		SELECT id INTO bid FROM balance WHERE date_balance=fecha AND type=tipo AND id_carrier=carrier AND id_destination_int=destination_int;
	END IF;
	IF bid IS NOT NULL THEN
		RETURN true;
	ELSE
		RETURN false;
	END IF;
END;
$$ language plpgsql;

/*funcion que se encarga de traer los valores de la tabla balance_temp*/
CREATE OR REPLACE function buscar_balance_temp(uid integer) RETURNS demo
AS $$ 
DECLARE
balance demo;
bid integer;
bdate_balance date;
bminutes double precision;
bacd double precision;
basr double precision;
bmargin_percentage double precision;
bmargin_per_minute double precision; 
bcost_per_minute double precision;
brevenue_per_minute double precision;
bpdd double precision;
bincomplete_calls double precision;
bincomplete_calls_ner double precision;
bcomplete_calls double precision;
bcomplete_calls_ner double precision;
bcalls_attempts double precision;
bduration_real double precision;
bduration_cost double precision;
bner02_efficient double precision;
bner02_seizure double precision;
bpdd_calls double precision;
brevenue double precision;
bcost double precision;
bmargin double precision;
bdate_change date;
btype integer;
bid_carrier integer;
bid_destination integer;
bid_destination_int integer;
BEGIN
	SELECT id, date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_carrier, id_destination, id_destination_int INTO bid, bdate_balance, bminutes, bacd, basr, bmargin_percentage, bmargin_per_minute, brevenue_per_minute, bpdd, bincomplete_calls, bincomplete_calls_ner, bcomplete_calls, bcomplete_calls_ner, bcalls_attempts, bduration_real, bduration_cost, bner02_efficient, bner02_seizure, bpdd_calls, brevenue, bcost, bmargin, bdate_change, btype, bid_carrier, bid_destination, bid_destination_int WHERE id=uid;
	balance := demo(bid, bdate_balance, bminutes, bacd, basr, bmargin_percentage, bmargin_per_minute, brevenue_per_minute, bpdd, bincomplete_calls, bincomplete_calls_ner, bcomplete_calls, bcomplete_calls_ner, bcalls_attempts, bduration_real, bduration_cost, bner02_efficient, bner02_seizure, bpdd_calls, brevenue, bcost, bmargin, bdate_change, btype, bid_carrier, bid_destination, bid_destination_int);
	RETURN balance;
END;
$$ language plpgsql;