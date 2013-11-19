/**
* Reporte que muestra los 100 operadores por posicion neta
*/
/*Muestra los cien primeros*/
SELECT o.name AS operador, cs.id, cs.vminutes, cs.vrevenue, cs.vmargin, cs.cminutes, cs.ccost, cs.cmargin, cs.posicion_neta, cs.margen_total
FROM(SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
     FROM(SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS vmargin, CAST(0 AS double precision) AS cminutes, CAST(0 AS double precision) AS ccost, CAST(0 AS double precision) AS cmargin
          FROM balance
          WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
          GROUP BY id_carrier_customer
          UNION
          SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, CAST(0 AS double precision) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS cmargin
          FROM balance
          WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
          GROUP BY id_carrier_supplier)t
     GROUP BY id
     ORDER BY posicion_neta DESC)cs,
      carrier o
WHERE o.id=cs.id
ORDER BY cs.posicion_neta DESC;


/*Totales*/
SELECT SUM(cs.vminutes) AS vminutes, SUM(cs.vrevenue) AS vrevenue, SUM(cs.vmargin) AS vmargin, SUM(cs.cminutes) AS cminutes, SUM(cs.ccost) AS ccost, SUM(cs.cmargin) AS cmargin, SUM(cs.posicion_neta) AS posicion_neta, SUM(cs.margen_total) AS margin_total
FROM
(SELECT id, SUM(vminutes) AS vminutes, SUM(vrevenue) AS vrevenue, SUM(vmargin) AS vmargin, SUM(cminutes) AS cminutes, SUM(ccost) AS ccost, SUM(cmargin) AS cmargin, SUM(vrevenue-ccost) AS posicion_neta, SUM(vmargin+cmargin) AS margen_total
FROM(SELECT id_carrier_customer AS id, SUM(minutes) AS vminutes, SUM(revenue) AS vrevenue, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS vmargin, CAST(0 AS double precision) AS cminutes, CAST(0 AS double precision) AS ccost, CAST(0 AS double precision) AS cmargin
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_customer
     UNION
     SELECT id_carrier_supplier AS id, CAST(0 AS double precision) AS vminutes, CAST(0 AS double precision) AS vrevenue, CAST(0 AS double precision) AS vmargin, SUM(minutes) AS cminutes, SUM(cost) AS ccost, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS cmargin
     FROM balance
     WHERE date_balance='$fecha' AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination_int<>(SELECT id FROM destination_int WHERE name='Unknown_Destination') AND id_destination_int IS NOT NULL
     GROUP BY id_carrier_supplier)t
GROUP BY id
ORDER BY posicion_neta DESC)cs;