/**
* RENOC Alto Impacto RETAIL(+1$)
*/
/*Version Manuel*/

/****************************** RP y R ETELIX ***********************************************/
/*Total por cliente con mas de 1 dolar de margen*/
SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer) x, carrier c
WHERE x.margin>1 AND x.id_carrier_customer=c.id
ORDER BY x.margin DESC;

/*Suma de totales por cliente con mas de 1 dolar de margen*/
SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer) x
WHERE x.margin>1;

/*Suma de totales por cliente en general*/
SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, (SUM(x.complete_calls)*100/SUM(x.total_calls)) AS asr, (SUM(x.minutes)/SUM(x.complete_calls)) AS acd, SUM(x.pdd)/SUM(x.total_calls) AS pdd, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer) x;

/*Total por destino con mas de 1 dolar de margen*/
SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
     GROUP BY id_destination
     ORDER BY margin DESC) x, destination d
WHERE x.margin > 1 AND x.id_destination = d.id
ORDER BY x.margin DESC;

/*Suma de totales por destino con mas de 1 dolar de margen*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
     GROUP BY id_destination
     ORDER BY margin DESC) balance
WHERE margin>1;

/*Suma de totales por destino en general*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RP %' UNION SELECT id FROM carrier WHERE name LIKE 'R-E%')
     GROUP BY id_destination
     ORDER BY margin DESC) balance;

/*Totales por cliente con mas de 1 dollar de margen RPRO*/
SELECT c.name AS cliente, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer) x, carrier c
WHERE x.margin>1 AND x.id_carrier_customer=c.id
ORDER BY x.margin DESC;

/* suma de Totales por cliente con mas de 1 dollar de margen RPRO*/
SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer) x
WHERE x.margin>1;

/* suma de totales Totales por cliente  RPRO*/
SELECT SUM(x.total_calls) AS total_calls, SUM(x.complete_calls) AS complete_calls, SUM(x.minutes) AS minutes, (SUM(x.complete_calls)*100/SUM(x.total_calls)) AS asr, (SUM(x.minutes)/SUM(x.complete_calls)) AS acd, SUM(x.pdd)/SUM(x.total_calls) AS pdd, SUM(x.cost) AS cost, SUM(x.revenue) AS revenue, SUM(x.margin) AS margin, (((SUM(x.revenue)*100)/SUM(x.cost))-100) AS margin_percentage
FROM(SELECT id_carrier_customer, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%') AND date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer) x;

/*Totales por destino con mas de 1 dollar de margen RPRO*/
SELECT d.name AS destino, x.total_calls, x.complete_calls, x.minutes, x.asr, x.acd, x.pdd/x.total_calls AS pdd, x.cost, x.revenue, x.margin, (((x.revenue*100)/x.cost)-100) AS margin_percentage, (x.cost/x.minutes)*100 AS costmin, (x.revenue/x.minutes)*100 AS ratemin, ((x.revenue/x.minutes)*100)-((x.cost/x.minutes)*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr, (SUM(minutes)/SUM(complete_calls)) AS acd, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
     GROUP BY id_destination
     ORDER BY margin DESC) x, destination d
WHERE x.margin > 1 AND x.id_destination = d.id
ORDER BY x.margin DESC;

/*Suma de totales por destino con mas de 1 dolar de margen RPRO*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
     GROUP BY id_destination
     ORDER BY margin DESC) balance
WHERE margin>1;

/*Suma de totales por destino en general RPRO*/
SELECT SUM(total_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, (SUM(complete_calls)*100)/SUM(total_calls) AS asr, SUM(minutes)/SUM(complete_calls) AS acd, SUM(pdd)/SUM(total_calls) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, SUM(margin) AS margin, ((SUM(revenue)*100)/SUM(cost))-100 AS margin_percentage, (SUM(cost)/SUM(minutes))*100 AS costmin, (SUM(revenue)/SUM(minutes))*100 AS ratemin, ((SUM(revenue)/SUM(minutes))*100)-((SUM(cost)/SUM(minutes))*100) AS marginmin
FROM(SELECT id_destination, SUM(incomplete_calls+complete_calls) AS total_calls, SUM(complete_calls) AS complete_calls, SUM(minutes) AS minutes, SUM(pdd) AS pdd, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name = 'Unknown_Destination') AND id_destination IS NOT NULL AND id_carrier_customer IN (SELECT id FROM carrier WHERE name LIKE 'RPRO%')
     GROUP BY id_destination
     ORDER BY margin DESC) balance;





/*Version Eduardo*/
/*Total por cliente con mas de 1 dolar de margen*/
SELECT x.CLIENTE AS Cliente, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos,x.PDD AS Pdd,x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
FROM(SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'RP %'
	 GROUP BY c.name
	 UNION
	 SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
	 FROM balance b, carrier c
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'R-E%'
	 GROUP BY c.name) x
WHERE x.MARGEN > 1
ORDER BY x.MARGEN DESC;

/*Suma de totales por cliente con mas de 1 dolar de margen*/
SELECT 'TOTAL' AS etiqueta, sum(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
FROM(SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
     FROM balance b, carrier c
     WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'RP %'
     GROUP BY c.name
     UNION
     SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
     FROM balance b, carrier c
     WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'R-E%'
     GROUP BY c.name) x
WHERE x.MARGEN > 1;

/*Suma de totales por cliente en general*/
SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
FROM(SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
	 FROM balance b, carrier c
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'RP %'
	 GROUP BY c.name
	 UNION
	 SELECT c.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
	 FROM balance b, carrier c
	 WHERE b.date_balance = '$fecha' AND b.id_destination_int IS NOT NULL AND b.id_carrier_customer = c.id AND c.name LIKE 'R-E%'
	 GROUP BY c.name) x;

/*Total por destino con mas de 1 dolar de margen*/
SELECT x.CLIENTE AS destino, x.TOTALCALLS AS TotalCalls, x.CALLS AS CompleteCalls, x.MINUTOS AS Minutos, x.PDD AS Pdd, x.COST AS Cost, x.REVENUE AS Revenue, x.MARGEN AS Margin
FROM(SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
     FROM balance b, destination d, carrier c
     WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'RP%'
     GROUP BY d.name
     UNION
     SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
     FROM balance b, destination d, carrier c
     WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'R-E%'
     GROUP BY d.name) x
     WHERE x.MARGEN > 1
     ORDER BY x.MARGEN DESC;

/*Suma de totales por destino con mas de 1 dolar de margen*/
SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos, SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
FROM(SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
     FROM balance b, destination d, carrier c
     WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'RP%'
     GROUP BY d.name
     UNION
     SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, CASE WHEN SUM(b.margin)>1 THEN SUM(b.margin) ELSE 0 END AS MARGEN
     FROM balance b, destination d, carrier c
     WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'R-E%'
     GROUP BY d.name) x
     WHERE x.MARGEN > 1;
/*Suma de totales por destino en general*/
SELECT 'TOTAL' AS etiqueta, SUM(x.TOTALCALLS) AS TotalCalls, SUM(x.CALLS) AS CompleteCalls, SUM(x.MINUTOS) AS Minutos,SUM(x.PDD) AS Pdd, SUM(x.COST) AS Cost, SUM(x.REVENUE) AS Revenue, SUM(x.MARGEN) AS Margin
FROM(SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
	 FROM balance b, destination d, carrier c
	 WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'RP%'
	 GROUP BY d.name
     UNION
     SELECT d.name AS CLIENTE, SUM(b.pdd_calls) AS PDD, SUM(b.complete_calls) AS CALLS, SUM(b.complete_calls+b.incomplete_calls) AS TOTALCALLS, SUM(b.minutes) AS MINUTOS, SUM(b.cost) AS COST, SUM(b.revenue) AS REVENUE, SUM(b.margin) AS MARGEN
     FROM balance b, destination d, carrier c
     WHERE b.date_balance = '$fecha' AND b.id_destination IS NOT NULL AND b.id_carrier_customer = c.id AND b.id_destination = d.id AND c.name LIKE 'R-E%'
     GROUP BY d.name) x;