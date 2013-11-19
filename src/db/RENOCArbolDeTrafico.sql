/*
* Consulta que trae todos los destinos external
*/
SELECT x.id_destination AS id, d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
     GROUP BY id_destination
     ORDER BY margin DESC) x, destination d
WHERE x.margin > 10 AND x.id_destination = d.id
ORDER BY x.minutes DESC;

/*
* Consulta que trae los clientes por destino externo
*/
SELECT c.name, b.total_calls, b.complete_calls, b.minutes, (b.complete_calls*100/b.total_calls) AS asr, (b.minutes/b.complete_calls) AS acd, (b.pdd/b.total_calls) AS pdd, b.cost, b.revenue, b.margin, (b.cost/b.minutes)*100 AS costmin, (b.revenue/b.minutes)*100 AS ratemin, ((b.revenue/b.minutes)*100)-((b.cost/b.minutes)*100) AS marginmin
FROM(SELECT id_carrier_customer AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(pdd) AS pdd
     FROM balance
     WHERE date_balance='2013-09-08' AND id_destination=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
     GROUP BY id_carrier_customer
     ORDER BY minutes DESC
     LIMIT 5)b, carrier c
WHERE c.id=b.id;

/*
* Consulta de la suma de totales de clientes por destino externo
*/
SELECT SUM(b.total_calls) AS total_calls, SUM(b.complete_calls) AS complete_calls, SUM(b.minutes) as minutes, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin, (SUM(b.cost)/SUM(b.minutes))*100 AS costmin, (SUM(b.revenue)/SUM(b.minutes))*100 AS ratemin, ((SUM(b.revenue)/SUM(b.minutes))*100)-((SUM(b.cost)/SUM(b.minutes))*100) AS marginmin
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
FROM balance
WHERE date_balance='2013-09-08' AND id_destination=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
GROUP BY id_carrier_customer
ORDER BY minutes DESC
LIMIT 5)b

/*
* Consulta que trae los proveedores por destino externo
*/
SELECT c.name, b.total_calls, b.complete_calls, b.minutes, b.cost, b.revenue, b.margin, (b.cost/b.minutes)*100 AS costmin, (b.revenue/b.minutes)*100 AS ratemin, ((b.revenue/b.minutes)*100)-((b.cost/b.minutes)*100) AS marginmin
FROM(SELECT id_carrier_supplier AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08' AND id_destination=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
     GROUP BY id_carrier_customer
     ORDER BY minutes DESC
     LIMIT 5)b, carrier c
WHERE c.id=b.id















SELECT c.name AS cliente, x.id_carrier_customer AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08'
         AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
	 AND id_destination=701
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_customer = c.id
ORDER BY x.minutes DESC
LIMIT 5

/*
* Consulta con los totales de los primeros 5 clientes por destino externo
*/
SELECT SUM(incomplete_calls+complete_calls) AS total_calls,
       SUM(complete_calls) AS complete_calls,
       SUM(minutes) AS minutes,
       (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr,
       (SUM(minutes)/SUM(complete_calls)) AS acd,
       (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd,
       SUM(cost) AS cost,
       SUM(revenue) AS revenue,
       CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin,
       (SUM(cost)/SUM(minutes))*100 AS costmin,
       (SUM(revenue)/SUM(minutes))*100 AS ratemin,
       ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin

       SELECT 
       FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
       FROM balance
       WHERE date_balance='2013-09-08' AND id_destination=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
       GROUP BY id_carrier_customer
       ORDER BY minutes DESC
       LIMIT 5)


/*
* Consulta de la suma de totales de proveedores por destino externo
*/
SELECT SUM(b.total_calls) AS total_calls, SUM(b.complete_calls) AS complete_calls, SUM(b.minutes) as minutes, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin, (SUM(b.cost)/SUM(b.minutes))*100 AS costmin, (SUM(b.revenue)/SUM(b.minutes))*100 AS ratemin, ((SUM(b.revenue)/SUM(b.minutes))*100)-((SUM(b.cost)/SUM(b.minutes))*100) AS marginmin
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
FROM balance
WHERE date_balance='2013-09-08' AND id_destination=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
GROUP BY id_carrier_supplier
ORDER BY minutes DESC
LIMIT 5)b

/*
* Consulta de la suma de totales de clientes por destino externo
*/
SELECT SUM(b.total_calls) AS total_calls, SUM(b.complete_calls) AS complete_calls, SUM(b.minutes) as minutes, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin, (SUM(b.cost)/SUM(b.minutes))*100 AS costmin, (SUM(b.revenue)/SUM(b.minutes))*100 AS ratemin, ((SUM(b.revenue)/SUM(b.minutes))*100)-((SUM(b.cost)/SUM(b.minutes))*100) AS marginmin
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
FROM balance
WHERE date_balance='2013-09-08' AND id_destination_int=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
GROUP BY id_carrier_customer
ORDER BY minutes DESC
LIMIT 5)b

/*
* Consulta de la suma de totales de proveedores por destino externo
*/
SELECT SUM(b.total_calls) AS total_calls, SUM(b.complete_calls) AS complete_calls, SUM(b.minutes) as minutes, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin, (SUM(b.cost)/SUM(b.minutes))*100 AS costmin, (SUM(b.revenue)/SUM(b.minutes))*100 AS ratemin, ((SUM(b.revenue)/SUM(b.minutes))*100)-((SUM(b.cost)/SUM(b.minutes))*100) AS marginmin
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
FROM balance
WHERE date_balance='2013-09-08' AND id_destination_int=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
GROUP BY id_carrier_supplier
ORDER BY minutes DESC
LIMIT 5)b


       SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
       FROM balance
       WHERE date_balance='2013-09-08' AND id_destination=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
       GROUP BY id_carrier_supplier
       ORDER BY minutes DESC
       LIMIT 5
/*SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls)  AS complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08'
         AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
	 AND id_destination=701
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_customer = c.id
ORDER BY x.minutes DESC
LIMIT 5*/

/*
* Consulta que trae los proveedores por destino externo
*/
SELECT c.name AS proveedor, x.id_carrier_supplier AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08'
         AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
	 AND id_destination=701
	 GROUP BY id_carrier_supplier
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_supplier = c.id
ORDER BY x.minutes DESC
LIMIT 5


/*
* Consulta que trae todos los destinos interno
*/
SELECT x.id_destination_int AS id, d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_destination_int, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_destination_int
     ORDER BY margin DESC) x, destination_int d
WHERE x.margin > 10 AND x.id_destination_int = d.id
ORDER BY x.minutes DESC;


/*
* Consulta que trae los clientes por destino interno
*/
SELECT c.name AS cliente, x.id_carrier_customer AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08'
         AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
	 AND id_destination_int=856
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_customer = c.id
ORDER BY x.minutes DESC
LIMIT 5


/*
* Consulta que trae los proveedores por destino interno
*/
SELECT c.name AS proveedor, x.id_carrier_supplier AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='2013-09-08'
         AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
	 AND id_destination_int=856
	 GROUP BY id_carrier_supplier
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_supplier = c.id
ORDER BY x.minutes DESC
LIMIT 5






SELECT id_carrier_customer AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr/*, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin*/
     FROM balance
     WHERE date_balance='2013-09-08' AND id_destination=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
     GROUP BY id_carrier_customer
     ORDER BY minutes DESC
     LIMIT 5



SELECT c.name AS cliente, b.total_calls, b.complete_calls, b.minutes, CASE WHEN b.complete_calls=0 THEN 0 WHEN b.total_calls=0 THEN 0 ELSE (b.complete_calls*100/b.total_calls) END AS asr, CASE WHEN b.minutes=0 THEN 0 WHEN b.complete_calls=0 THEN 0 ELSE (b.minutes/b.complete_calls) END AS acd, CASE WHEN b.pdd=0 THEN 0 WHEN b.total_calls=0 THEN 0 ELSE (b.pdd/b.total_calls) END AS pdd, b.cost, b.revenue, b.margin, CASE WHEN b.minutes=0 THEN 0 WHEN b.cost=0 THEN 0 ELSE (b.cost/b.minutes)*100 END AS costmin, CASE WHEN b.minutes=0 THEN 0 WHEN b.revenue=0 THEN 0 ELSE (b.revenue/b.minutes)*100 END AS ratemin, CASE WHEN b.minutes=0 THEN 0 ELSE(CASE WHEN b.revenue=0 THEN 0 ELSE (b.revenue/b.minutes)*100 END)-(CASE WHEN b.cost=0 THEN 0 ELSE (b.cost/b.minutes)*100 END) END AS marginmin
FROM(SELECT id_carrier_customer AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(pdd) AS pdd
FROM balance
WHERE date_balance='2013-08-29' AND id_destination_int=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier')
GROUP BY id_carrier_customer
ORDER BY minutes DESC
LIMIT 5)b, carrier c
WHERE c.id=b.id

SELECT SUM(b.total_calls) AS total_calls,
       SUM(b.complete_calls) AS complete_calls,
       SUM(b.minutes) AS minutes,
       SUM(b.cost) AS cost,
       SUM(b.revenue) AS revenue,
       SUM(b.margin) AS margin,
       CASE WHEN SUM(b.minutes)=0 THEN 0 WHEN SUM(b.cost)=0 THEN 0 ELSE (SUM(b.cost)/SUM(b.minutes))*100 END AS costmin,
       CASE WHEN SUM(b.minutes)=0 THEN 0 WHEN SUM(b.revenue)=0 THEN 0 ELSE (SUM(b.revenue)/SUM(b.minutes))*100 END AS ratemin,
       CASE WHEN SUM(b.minutes)=0 THEN 0 ELSE (CASE WHEN SUM(b.revenue)=0 THEN 0 ELSE (SUM(b.revenue)/SUM(b.minutes))*100 END)-(CASE WHEN SUM(b.cost)=0 THEN 0 ELSE (SUM(b.cost)/SUM(b.minutes))*100 END) END AS marginmin
FROM(SELECT id_carrier_customer AS id, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(pdd) AS pdd
     FROM balance
     WHERE date_balance='2013-09-08' AND id_destination_int=701 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer
     ORDER BY minutes DESC
     LIMIT 5)b