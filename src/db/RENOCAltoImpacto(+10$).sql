/**
* Consultas para RENOC Alto Impacto (+10$)
*/
/*Consultas Manuel*/
/*Total por Cliente con mas de 10 dolares de margen*/
SELECT c.name AS cliente, x.id_carrier_customer AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
	 FROM balance
	 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
	 GROUP BY id_carrier_customer
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_customer = c.id
ORDER BY x.margin DESC;

/*Total por Proveedor con mas de 10 dolares de margen*/
SELECT c.name AS proveedor, x.id_carrier_supplier AS id, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
FROM(SELECT id_carrier_supplier, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, (SUM(pdd)/SUM(incomplete_calls+complete_calls)) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
	 FROM balance
	 WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
	 GROUP BY id_carrier_supplier
	 ORDER BY margin DESC) x, carrier c
WHERE x.margin > 10 AND x.id_carrier_supplier = c.id
ORDER BY x.margin DESC;

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

/*Consultas Eduardo*/
/*Total por Cliente con mas de 10 dolares de margen*/
SELECT c.name AS Cliente, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos, x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
FROM(SELECT b.id_carrier_customer AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE  WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c, destination_int d
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
	 GROUP BY b.id_carrier_customer
	 ORDER BY MARGEN DESC) x, carrier c
WHERE x.MARGEN > 10 AND x.CLIENTE = c.id
ORDER BY x.MARGEN DESC;

/*Total por Proveedor con mas de 10 dolares de margen*/
SELECT c.name AS Proveedor, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos,x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
FROM(SELECT b.id_carrier_supplier AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c, destination_int d
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
	 GROUP BY b.id_carrier_supplier
	 ORDER BY MARGEN DESC) x, carrier c
WHERE x.MARGEN > 10 AND x.CLIENTE = c.id
ORDER BY x.MARGEN DESC;

/*Total por destino con mas de 10 dolares de margen*/
SELECT x.CLIENTE AS Destino, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos,x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
FROM(SELECT d.name AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, destination d, carrier c
	 WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_destination = d.id AND d.name NOT LIKE 'Unk%' AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unk%'
	 GROUP BY d.name
	 ORDER BY MARGEN DESC) x
WHERE x.MARGEN > 10
ORDER BY x.MARGEN DESC;

/*La suma de los totales de clientes con mas de 10 dolares de margen*/
SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
FROM(SELECT b.id_carrier_customer AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE  WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c, destination_int d
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
	 GROUP BY b.id_carrier_customer
	 ORDER BY MARGEN DESC) x, carrier c
WHERE x.MARGEN > 10 AND x.CLIENTE = c.id;

/*La suma de los totales de proveedores con mas de 10 dolares de margen*/
SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
FROM(SELECT b.id_carrier_supplier AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c, destination_int d
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%'
	 GROUP BY b.id_carrier_supplier
	 ORDER BY MARGEN DESC) x, carrier c
WHERE x.MARGEN > 10 AND x.CLIENTE = c.id;

/*La suma de los totales de destinos con mas de 10 dolares de margen*/
SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
                           FROM(
                            SELECT d.name AS CLIENTE, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.pdd_calls) AS PDD, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>10 THEN SUM(b.margin) ELSE 0 END AS MARGEN
                            FROM balance b, destination d, carrier c
                            WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_destination = d.id AND d.name NOT LIKE 'Unk%' AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unk%'
                            GROUP BY d.name
                            ORDER BY MARGEN DESC) x
                           WHERE x.MARGEN > 10;

/*La suma de los totales de clientes en general*/
SELECT 'TOTAL' AS etiqueta, SUM(complete_calls+incomplete_calls) AS TotalCalls, SUM(complete_calls) AS CompleteCalls, SUM(minutes) AS Minutos, SUM(PDD) AS Pdd, SUM(COST) AS Cost, SUM(REVENUE) AS Revenue, SUM(margin) AS Margin
FROM balance b, carrier c, destination_int d
WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%';


/*La suma de los totales de proveedores en general*/
SELECT 'TOTAL' AS etiqueta, SUM(complete_calls+incomplete_calls) AS TotalCalls, SUM(complete_calls) AS CompleteCalls, SUM(minutes) AS Minutos, SUM(PDD) AS Pdd, SUM(COST) AS Cost, SUM(REVENUE) AS Revenue, SUM(margin) AS Margin
FROM balance b, carrier c, destination_int d
WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unknow%' AND b.id_destination_int=d.id AND d.name NOT LIKE 'Unknow%';


/*La suma de los totales de destinos en general*/
SELECT 'TOTAL' AS etiqueta, SUM(b.complete_calls+b.incomplete_calls) AS TotalCalls, SUM(b.complete_calls) AS CompleteCalls, SUM(b.minutes) AS Minutos, SUM(b.PDD) AS Pdd, SUM(b.cost) AS Cost, SUM(b.revenue) AS Revenue, SUM(b.margin) AS Margin
                                   FROM balance b, destination d, carrier c
                                   WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_destination = d.id AND d.name NOT LIKE 'Unk%' AND b.id_carrier_supplier = c.id AND c.name NOT LIKE 'Unk%';
        



















