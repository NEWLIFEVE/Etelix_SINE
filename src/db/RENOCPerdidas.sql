
SELECT c.name AS cliente, d.name AS destino, s.name AS proveedor, b.minutes AS minutes, b.cost AS cost, b.revenue AS revenue, b.margin AS margin   
FROM(SELECT id_carrier_customer, id_destination_int, id_carrier_supplier, SUM(minutes) AS minutes, SUM(cost) AS cost, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_customer, id_destination_int, id_carrier_supplier
     ORDER BY margin ASC) b,
     carrier c,
     carrier s,
     destination_int d
WHERE b.id_carrier_customer=c.id AND d.id=b.id_destination_int AND b.id_carrier_supplier=s.id AND b.margin<0
ORDER BY margin ASC;

SELECT SUM(b.margin) AS margin, SUM(b.cost) AS cost, SUM(b.revenue) AS revenue
FROM(SELECT CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin, SUM(cost) AS cost, SUM(revenue) AS revenue
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_customer, id_destination_int, id_carrier_supplier
     ORDER BY margin ASC) b
WHERE b.margin<0;