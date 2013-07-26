﻿/*ejecuta el rerate de todo lo que este en la tabla balance_temp*/
CREATE OR REPLACE function ejecutar_rerate() RETURNS void
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
END;
$$ language 'plpgsql';

/*Funcion que compara dos balances*/
CREATE OR REPLACE function statuscero(ide integer) RETURNS boolean
AS $$
DECLARE
	b RECORD;
	t RECORD;
BEGIN
	/*Busco el registro en la tabla balance_temp*/
	SELECT * INTO b FROM balance WHERE id=ide;
	/*Busco el registro mas parecido en la tabla balance*/
	IF b.id_destination IS NOT NULL THEN
		SELECT * INTO t FROM balance_temp WHERE date_balance=b.date_balance AND id_carrier=b.id_carrier AND id_destination=b.id_destination AND type=b.type;
	ELSE
		SELECT * INTO t FROM balance_temp WHERE date_balance=b.date_balance AND id_carrier=b.id_carrier AND id_destination_int=b.id_destination_int AND type=b.type;
	END IF;
	/*si es nulo retorno falso*/
	IF t.id IS NULL THEN
		UPDATE balance SET status=0 WHERE id=b.id;
		RETURN false;
	ELSE
		RETURN true;
	END IF;
END;
$$ language 'plpgsql';


/*Funcion que compara dos balances*/
CREATE OR REPLACE function compara_balances(ide integer) RETURNS boolean
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
		SELECT * INTO b FROM balance WHERE date_balance=t.date_balance AND id_carrier=t.id_carrier AND id_destination=t.id_destination AND type=t.type AND status=1;
	ELSE
		SELECT * INTO b FROM balance WHERE date_balance=t.date_balance AND id_carrier=t.id_carrier AND id_destination_int=t.id_destination_int AND type=t.type AND status=1;
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
$$ language 'plpgsql';

/*Permite actualizar registros de la tabla balances con datos de la tabla balance_temp a traves del id*/
CREATE OR REPLACE function actualizar_balance(tid integer, bid integer) RETURNS boolean
AS $$
DECLARE
	t RECORD;
BEGIN
	SELECT * INTO t FROM balance_temp WHERE id=tid;
	IF t.id IS NOT NULL THEN
		UPDATE balance SET minutes=t.minutes, acd=t.acd, asr=t.asr, margin_percentage=t.margin_percentage, margin_per_minute=t.margin_per_minute, cost_per_minute=t.cost_per_minute, revenue_per_min=t.revenue_per_minute, pdd=t.pdd, incomplete_calls=t.incomplete_calls, incomplete_calls_ner=t.incomplete_calls_ner, complete_calls=t.complete_calls, complete_calls_ner=t.complete_calls_ner, calls_attempts=t.calls_attempts, duration_real=t.duration_real, duration_cost=t.duration_cost, ner02_efficient=t.ner02_efficient, ner02_seizure=t.ner02_seizure, pdd_calls=t.pdd_calls, revenue=t.revenue, cost=t.cost, margin=t.margin, date_change=t.date_change WHERE id=bid;
		RETURN true;
	ELSE
		RETURN false;
	END IF;
	
END;
$$ language 'plpgsql';

/*Permite copiar registros de la tabla balance_temp a la tabla balance a traves del id*/
CREATE OR REPLACE function pasar_a_balance(ide integer) RETURNS boolean
AS $$
DECLARE
	ids RECORD;
BEGIN
	SELECT * INTO ids FROM balance_temp WHERE id=ide;
	IF ids.id IS NOT NULL THEN
		INSERT INTO balance(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_carrier, id_destination, id_destination_int, status) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_minute, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.type, ids.id_carrier, ids.id_destination, ids.id_destination_int, 1);
		RETURN true;
	ELSE
		RETURN false;
	END IF;
END;
$$ language 'plpgsql';

/*Permite copiar registros de la tabla balances a la tabla balance_temp a traves del id*/
CREATE OR REPLACE function pasar_a_rrhistory(ide integer) RETURNS boolean
AS $$
DECLARE
	ids RECORD;
BEGIN
	SELECT * INTO ids FROM balance WHERE id=ide;
	IF ids.id IS NOT NULL THEN
		INSERT INTO rrhistory(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_min, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_balance, id_carrier, id_destination, id_destination_int) VALUES(ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_min, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.type, ids.id, ids.id_carrier, ids.id_destination, ids.id_destination_int);
		RETURN true;
	ELSE
		RETURN false;
	END IF;
END;
$$ language 'plpgsql';

/*Permite copiar registros de la tabla balances a la tabla balance_temp a traves de la fecha*/
CREATE OR REPLACE function pasar_a_balance_temp(fecha date) RETURNS boolean
AS $$
DECLARE
	ids RECORD;
BEGIN
	FOR ids IN SELECT * FROM balance WHERE date_balance=fecha LOOP
		INSERT INTO balance_temp(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_carrier, id_destination, id_destination_int) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_min, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.type, ids.id_carrier, ids.id_destination, ids.id_destination_int);
	END LOOP;
	RETURN true;
END;
$$ language 'plpgsql';

/*Permite copiar registros de la tabla balances a la tabla balance_temp a traves de la fecha*/
CREATE OR REPLACE function rrhistory_a_balance_temp(fecha date) RETURNS boolean
AS $$
DECLARE
	ids RECORD;
BEGIN
	FOR ids IN SELECT * FROM rrhistory WHERE date_balance=fecha LOOP
		INSERT INTO balance_temp(date_balance, minutes, acd, asr, margin_percentage, margin_per_minute, cost_per_minute, revenue_per_minute, pdd, incomplete_calls, incomplete_calls_ner, complete_calls, complete_calls_ner, calls_attempts, duration_real, duration_cost, ner02_efficient, ner02_seizure, pdd_calls, revenue, cost, margin, date_change, type, id_carrier, id_destination, id_destination_int) VALUES (ids.date_balance, ids.minutes, ids.acd, ids.asr, ids.margin_percentage, ids.margin_per_minute, ids.cost_per_minute, ids.revenue_per_min, ids.pdd, ids.incomplete_calls, ids.incomplete_calls_ner, ids.complete_calls, ids.complete_calls_ner, ids.calls_attempts, ids.duration_real, ids.duration_cost, ids.ner02_efficient, ids.ner02_seizure, ids.pdd_calls, ids.revenue, ids.cost, ids.margin, ids.date_change, ids.type, ids.id_carrier, ids.id_destination, ids.id_destination_int);
	END LOOP;
	RETURN true;
END;
$$ language 'plpgsql';