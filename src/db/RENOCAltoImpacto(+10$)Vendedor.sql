/*Total por Cliente con mas de 10 dolares de margen*/
SELECT c.name AS cliente, m.name AS vendedor, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
	 FROM balance
	 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) x, carrier c,
	 carrier_managers cm,
	 managers m
WHERE x.margin > 10 AND x.id_carrier_customer = c.id AND x.id_carrier_customer=cm.id_carrier AND cm.id_managers=m.id
ORDER BY m.name ASC, x.margin DESC;

/*Total por Proveedor con mas de 10 dolares de margen*/
SELECT c.name AS proveedor, m.name AS vendedor, x.id_carrier_supplier AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
	 FROM balance
	 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
	 GROUP BY id_carrier_supplier
	 ORDER BY margin DESC) x, carrier c,
	 carrier_managers cm,
	 managers m
WHERE x.margin > 10 AND x.id_carrier_supplier=c.id AND x.id_carrier_supplier=cm.id_carrier AND cm.id_managers=m.id
ORDER BY m.name ASC, x.margin DESC;

/*Total por destino con mas de 10 dolares de margen*/
SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
     GROUP BY id_destination
     ORDER BY margin DESC) x, destination d
WHERE x.margin > 10 AND x.id_destination = d.id
ORDER BY x.margin DESC;

/*La suma de los totales de clientes con mas de 10 dolares de margen*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
   FROM balance
   WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
   GROUP BY id_carrier_customer
   ORDER BY margin DESC) balance
WHERE margin>10;

/*La suma de los totales de proveedores con mas de 10 dolares de margen*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
	FROM balance
	WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
	GROUP BY id_carrier_supplier
	ORDER BY margin DESC) balance
WHERE margin>10;

/*La suma de los totales de destinos con mas de 10 dolares de margen*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
     GROUP BY id_destination
     ORDER BY margin DESC) balance
WHERE margin>10;

/*La suma de los totales de clientes en general*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer
     ORDER BY margin DESC) balance;

/*La suma de los totales de proveedores en general*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
	 FROM balance
	 WHERE date_balance='$fecha'
	 AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
	 GROUP BY id_carrier_supplier
	 ORDER BY margin DESC) balance;

/*La suma de los totales de destinos en general*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL
     GROUP BY id_destination
     ORDER BY margin DESC) balance;
