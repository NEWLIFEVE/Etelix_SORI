DROP function ejecutar_rerate()
/*
 * ejecuta el rerate de todo lo que este en la tabla balance_temp
 */
CREATE OR REPLACE FUNCTION ejecutar_rerate()
  RETURNS record AS
$BODY$
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
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION ejecutar_rerate()
  OWNER TO postgres;

/*
 * Funcion que compara dos balances
 */
CREATE OR REPLACE FUNCTION statuscero(ide integer)
  RETURNS boolean AS
$BODY$
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
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION statuscero(integer)
  OWNER TO postgres;

/*
 * Funcion que compara dos balances
 */
CREATE OR REPLACE FUNCTION compara_balances(ide integer)
  RETURNS boolean AS
$BODY$
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
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION compara_balances(integer)
  OWNER TO postgres;

/*
 * Permite actualizar registros de la tabla balances con datos de la tabla balance_temp a traves del id
 */
CREATE OR REPLACE FUNCTION actualizar_balance(tid integer, bid integer)
  RETURNS boolean AS
$BODY$
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
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION actualizar_balance(integer, integer)
  OWNER TO postgres;


/*
 * Permite copiar registros de la tabla balance_temp a la tabla balance a traves del id
 */
CREATE OR REPLACE FUNCTION pasar_a_balance(ide integer)
  RETURNS boolean AS
$BODY$
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
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION pasar_a_balance(integer)
  OWNER TO postgres;

/*
 * Permite copiar registros de la tabla balances a la tabla balance_temp a traves del id
 */
CREATE OR REPLACE FUNCTION pasar_a_rrhistory(ide integer)
  RETURNS boolean AS
$BODY$
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
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION pasar_a_rrhistory(integer)
  OWNER TO postgres;

/*
 * Permite copiar registros de la tabla balances a la tabla balance_temp a traves de la fecha
 */
CREATE OR REPLACE FUNCTION pasar_a_balance_temp(fecha date)
  RETURNS boolean AS
$BODY$
DECLARE
	ids RECORD;
BEGIN
	FOR ids IN SELECT * FROM balance WHERE date_balance=fecha LOOP
		INSERT INTO balance_temp(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_destination, id_destination_int, id_carrier_supplier, id_carrier_customer) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_minute, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.id_destination, ids.id_destination_int, ids.id_carrier_supplier, ids.id_carrier_customer);
	END LOOP;
	RETURN true;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION pasar_a_balance_temp(date)
  OWNER TO postgres;

/*
 * Permite copiar registros de la tabla balances a la tabla balance_temp a traves de la fecha
 */
CREATE OR REPLACE FUNCTION rrhistory_a_balance_temp(fecha date)
  RETURNS boolean AS
$BODY$
DECLARE
	ids RECORD;
BEGIN
	FOR ids IN SELECT * FROM rrhistory WHERE date_balance=fecha LOOP
		INSERT INTO balance_temp(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, id_destination, id_destination_int, id_carrier_supplier, id_carrier_customer) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_minute, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.id_destination, ids.id_destination_int, ids.id_carrier_supplier, ids.id_carrier_customer);
	END LOOP;
	RETURN true;
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION rrhistory_a_balance_temp(date)
  OWNER TO postgres;

/*
 * Funcion que llama el trigger
 */
CREATE OR REPLACE FUNCTION condicion()
  RETURNS trigger AS
$BODY$
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
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION condicion()
  OWNER TO postgres;

DROP TRIGGER rerate ON log;

CREATE TRIGGER rerate 
AFTER INSERT ON log
FOR EACH STATEMENT
EXECUTE PROCEDURE condicion();