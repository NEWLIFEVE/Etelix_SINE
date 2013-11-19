/**
* Consultas a base de datos para reporte Ranking Compra/Venta
*/
/*Ranking Vendedores*/
SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
FROM(SELECT id_carrier_customer, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_customer)b,
     managers m,
     carrier_managers cm
WHERE m.id = cm.id_managers AND b.id_carrier_customer = cm.id_carrier
GROUP BY m.name, m.lastname
ORDER BY margin DESC;

/*Total Ranking Vendedores*/
SELECT SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
FROM(SELECT id_carrier_customer, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_customer)b;

/*Ranking Compradores*/
SELECT m.name AS nombre, m.lastname AS apellido, SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
FROM(SELECT id_carrier_supplier, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_supplier)b,
     managers m,
     carrier_managers cm
WHERE m.id = cm.id_managers AND b.id_carrier_supplier = cm.id_carrier
GROUP BY m.name, m.lastname
ORDER BY margin DESC;

/*Total Ranking Compradores*/
SELECT SUM(b.minutes) AS minutes, SUM(b.revenue) AS revenue, SUM(b.margin) AS margin
FROM(SELECT id_carrier_supplier, SUM(minutes) AS minutes, SUM(revenue) AS revenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' 
       AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
       AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_supplier)b;

/*La suma de totales*/
SELECT m.name AS nombre, m.lastname AS apellido, SUM(cs.margin) AS margin
FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance='$fecha' 
       AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
       AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_customer
     UNION
     SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance 
     WHERE date_balance='$fecha' 
       AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') 
       AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
     GROUP BY id_carrier_supplier)cs,
     managers m,
     carrier_managers cm
WHERE m.id = cm.id_managers AND cs.id = cm.id_carrier
GROUP BY m.name, m.lastname
ORDER BY margin DESC;

/*Total consolidado*/
SELECT SUM(cs.margin) AS margin
 FROM(SELECT id_carrier_customer AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
      FROM balance
      WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
      GROUP BY id_carrier_customer
      UNION
      SELECT id_carrier_supplier AS id, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
      FROM balance 
      WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination')
      GROUP BY id_carrier_supplier)cs;