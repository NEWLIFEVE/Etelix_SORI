/*Funcion para cambiar a status cero de la tabla balance*/
CREATE OR REPLACE function statuscero(fecha date) RETURNS boolean
AS $$
DECLARE
	dia date;
BEGIN
	dia := fecha;
	UPDATE balance SET status=0 WHERE id IN (SELECT DISTINCT(id) FROM balance WHERE date_balance=dia AND id_destination NOT IN (SELECT x.balance FROM (SELECT DISTINCT(id_destination) AS Balance FROM balance WHERE date_balance=dia ORDER BY id_destination ASC) x, (SELECT DISTINCT(id_destination) AS Temporal FROM balance_temp WHERE date_balance=dia ORDER BY id_destination ASC) y WHERE x.Balance = y.Temporal) ORDER BY id ASC);
	UPDATE balance SET status=0 WHERE id IN (SELECT DISTINCT(id) FROM balance WHERE date_balance=dia AND id_destination_int NOT IN (SELECT x.balance FROM (SELECT DISTINCT(id_destination_int) AS Balance FROM balance WHERE date_balance=dia ORDER BY id_destination_int ASC) x, (SELECT DISTINCT(id_destination_int) AS Temporal FROM balance_temp WHERE date_balance=dia ORDER BY id_destination_int ASC) y WHERE x.Balance = y.Temporal) ORDER BY id ASC);
	RETURN true;
END;
$$ language 'plpgsql';

/*Funcion que busca el id de un balance en la tabla balance*/
CREATE OR REPLACE function getId(fecha date, tipo integer, carrier integer, destination integer, destination_int integer) RETURNS integer
AS $$
DECLARE
	did integer;
BEGIN
	IF destination IS NOT NULL THEN
		SELECT id INTO did FROM balance WHERE date_balance=fecha AND type=tipo AND id_carrier=carrier AND id_destination=destination AND status=1;
	ELSE
		SELECT id INTO did FROM balance WHERE date_balance=fecha AND type=tipo AND id_carrier=carrier AND id_destination_int=destination_int AND status=1;
	END IF;
	IF did IS NOT NULL THEN
		RETURN did;
	ELSE
		RETURN -1;
	END IF;
END;
$$ language 'plpgsql';

/*Funcion que se encarga de pasar un registro de la tabla temporal a la tabla de balance*/
CREATE OR REPLACE function pasar_a_balance(iden integer) RETURNS boolean
AS $$
DECLARE
	tid integer;
	tdate_balance date;
	tminutes double precision;
	tacd double precision;
	tasr double precision;
	tmargin_percentage double precision;
	tmargin_per_minute double precision; 
	tcost_per_minute double precision;
	trevenue_per_minute double precision;
	tpdd double precision;
	tincomplete_calls double precision;
	tincomplete_calls_ner double precision;
	tcomplete_calls double precision;
	tcomplete_calls_ner double precision;
	tcalls_attempts double precision;
	tduration_real double precision;
	tduration_cost double precision;
	tner02_efficient double precision;
	tner02_seizure double precision;
	tpdd_calls double precision;
	trevenue double precision;
	tcost double precision;
	tmargin double precision;
	tdate_change date;
	ttype integer;
	tid_carrier integer;
	tid_destination integer;
	tid_destination_int integer;
	bid integer;
	result boolean;
BEGIN
	SELECT id, date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_carrier, id_destination, id_destination_int INTO tid, tdate_balance, tminutes, tacd, tasr, tmargin_percentage, tmargin_per_minute, tcost_per_minute, trevenue_per_minute, tpdd, tincomplete_calls, tincomplete_calls_ner, tcomplete_calls, tcomplete_calls_ner, tcalls_attempts, tduration_real, tduration_cost, tner02_efficient, tner02_seizure, tpdd_calls, trevenue, tcost, tmargin, tdate_change, ttype, tid_carrier, tid_destination, tid_destination_int FROM balance_temp WHERE id=iden;
	IF tid IS NOT NULL THEN
		SELECT getid INTO bid FROM getId(tdate_balance, ttype, tid_carrier, tid_destination, tid_destination_int);
		IF bid > 0 THEN
			SELECT pasar_a_rrhistory(bid) INTO result;
			UPDATE balance SET minutes=tminutes, acd=tacd, asr=tasr, margin_percentage=tmargin_percentage, margin_per_minute=tmargin_per_minute, cost_per_minute=tcost_per_minute, revenue_per_min=trevenue_per_minute, pdd=tpdd, incomplete_calls=tincomplete_calls, incomplete_calls_ner=tincomplete_calls_ner, complete_calls=tcomplete_calls, complete_calls_ner=tcomplete_calls_ner, calls_attempts=tcalls_attempts, duration_real=tduration_real, duration_cost=tduration_cost, ner02_efficient=tner02_efficient, ner02_seizure=tner02_seizure, pdd_calls=tpdd_calls, revenue=trevenue, cost=tcost, margin=tmargin, date_change=tdate_change WHERE id=bid;
		ELSE
			INSERT INTO balance(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_carrier, id_destination, id_destination_int, status) VALUES (tdate_balance, tminutes, tacd, tasr, tmargin_percentage, tmargin_per_minute, tcost_per_minute, trevenue_per_minute, tpdd, tincomplete_calls, tincomplete_calls_ner, tcomplete_calls, tcomplete_calls_ner, tcalls_attempts, tduration_real, tduration_cost, tner02_efficient, tner02_seizure, tpdd_calls, trevenue, tcost, tmargin, tdate_change, ttype, tid_carrier, tid_destination, tid_destination_int, 1);
		END IF;
	END IF;
	RETURN true;
END;
$$ language 'plpgsql';

/*funcion para pasar registros desde balance a rrhistory*/
CREATE OR REPLACE function pasar_a_rrhistory(iden integer) RETURNS boolean
AS $$
DECLARE
--variables de tablas--
bid integer;
bdate_balance date;
bminutes double precision;
bacd double precision;
basr double precision;
bmargin_percentage double precision;
bmargin_per_minute double precision;
bcost_per_minute double precision;
brevenue_per_min double precision;
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
bstatus integer;
BEGIN
	SELECT id, date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_carrier, id_destination, id_destination_int, status INTO bid, bdate_balance, bminutes, bacd, basr, bmargin_percentage, bmargin_per_minute, bcost_per_minute, brevenue_per_min, bpdd, bincomplete_calls, bincomplete_calls_ner, bcomplete_calls, bcomplete_calls_ner, bcalls_attempts, bduration_real, bduration_cost, bner02_efficient, bner02_seizure, bpdd_calls, brevenue, bcost, bmargin, bdate_change, btype, bid_carrier, bid_destination, bid_destination_int, bstatus FROM balance WHERE id=iden;
	INSERT INTO rrhistory(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_balance, id_carrier, id_destination, id_destination_int) VALUES (bdate_balance, bminutes, bacd, basr, bmargin_percentage, bmargin_per_minute, bcost_per_minute, brevenue_per_min, bpdd, bincomplete_calls, bincomplete_calls_ner, bcomplete_calls, bcomplete_calls_ner, bcalls_attempts, bduration_real, bduration_cost, bner02_efficient, bner02_seizure, bpdd_calls, brevenue, bcost, bmargin, bdate_change, btype, bid, bid_carrier, bid_destination, bid_destination_int);
	RETURN true;
END;
$$ language 'plpgsql';

/*Funcion encargada de actualizar registros*/
CREATE OR REPLACE function compara_balances(fecha date) RETURNS void
AS $$
DECLARE
	bid integer;
	done boolean default true;
	result boolean;
	externa RECORD;
BEGIN
	FOR externa IN SELECT id FROM balance_temp WHERE date_balance=fecha LOOP
		SELECT pasar_a_balance(externa.id) INTO result;
	END LOOP;
	SELECT statuscero(fecha) INTO result;
	IF result = true THEN
		DELETE FROM balance_temp;
		INSERT INTO log(date, hour, id_log_action, id_users, description_date) VALUES (current_date, current_time, 57, 1, current_date);
	END IF;
END;
$$ language 'plpgsql';
/*
PROBANDO FUNCIONES
*/
/*Funciona*/
SELECT statuscero('2013-07-14');
/*Funciona*/
SELECT getId('2013-07-14', 0, 125, 387, null);
/*Funciona*/
SELECT pasar_a_rrhistory(109929);
/*Funciona*/
SELECT pasar_a_balance(18052);
/*Funciona*/
SELECT compara_balances('2013-07-20');





SELECT date_balance, date_change FROM balance WHERE date_balance='2013-07-14' AND status=1;
SELECT date_balance, date_change FROM balance WHERE date_balance='2013-07-14' AND status=0;

SELECT * FROM rrhistory WHERE date_balance='2013-07-14' AND type=0 AND id_carrier=125 AND id_destination=387

18052


SELECT * FROM balance_temp WHERE id=18052

UPDATE balance SET status=1 WHERE date_balance='2013-07-14'


SELECT DISTINCT(balance.id) FROM(SELECT id, id_destination FROM balance WHERE date_balance='2013-07-14' AND status=0) balance, (SELECT id_destination FROM balance_temp WHERE date_balance='2013-07-14') temporal WHERE balance.id_destination != temporal.id_destination




SELECT DISTINCT(balance.id) FROM(SELECT id, id_destination_int FROM balance WHERE date_balance='2013-07-14') balance, (SELECT id_destination_int FROM balance_temp WHERE date_balance='2013-07-14') temporal WHERE balance.id_destination_int != temporal.id_destination_int