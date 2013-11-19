/*Min vs $ 15 dias*/
SELECT date_balance, SUM(minutes) AS minutes, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
FROM balance
WHERE date_balance>='$fecha'::date - '14 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs $ 60 dias*/
SELECT date_balance, SUM(minutes) AS minutes, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
FROM balance
WHERE date_balance>='$fecha'::date - '59 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs Perdidas 15 dias*/
SELECT b.date_balance, SUM(b.minutes) AS minutes, SUM(b.margin) AS margin
FROM(SELECT date_balance, SUM(minutes) AS minutes, CAST(0 AS double precision) AS margin
     FROM balance
     WHERE date_balance>='$fecha'::date - '14 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
     GROUP BY date_balance
     UNION
     SELECT date_balance, CAST(0 AS double precision) AS minutes, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance>='$fecha'::date - '14 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND margin<0
     GROUP BY date_balance)b
GROUP BY b.date_balance
ORDER BY b.date_balance DESC

/*Min vs Perdidas 60 dias*/
SELECT b.date_balance, SUM(b.minutes) AS minutes, SUM(b.margin) AS margin
FROM(SELECT date_balance, SUM(minutes) AS minutes, CAST(0 AS double precision) AS margin
     FROM balance
     WHERE date_balance>='$fecha'::date - '59 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
     GROUP BY date_balance
     UNION
     SELECT date_balance, CAST(0 AS double precision) AS minutes, CASE WHEN SUM(revenue-cost)<SUM(margin) THEN SUM(revenue-cost) ELSE SUM(margin) END AS margin
     FROM balance
     WHERE date_balance>='$fecha'::date - '59 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL AND margin<0
     GROUP BY date_balance)b
GROUP BY b.date_balance
ORDER BY b.date_balance DESC

/*Min vs Revenue 15 dias*/
SELECT date_balance, SUM(minutes) AS minutes, SUM(revenue) AS revenue
FROM balance
WHERE date_balance>='$fecha'::date - '14 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs Revenue 60 dias*/
SELECT date_balance, SUM(minutes) AS minutes, SUM(revenue) AS revenue
FROM balance
WHERE date_balance>='$fecha'::date - '59 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs ASR 15 dias*/
SELECT date_balance, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr
FROM balance
WHERE date_balance>='$fecha'::date - '14 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs ASR 60 dias*/
SELECT date_balance, SUM(minutes) AS minutes, (SUM(complete_calls)*100/SUM(incomplete_calls+complete_calls)) AS asr
FROM balance
WHERE date_balance>='$fecha'::date - '59 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs ALOC 15 dias*/
SELECT date_balance, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd
FROM balance
WHERE date_balance>='$fecha'::date - '14 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs ALOC 60 dias*/
SELECT date_balance, SUM(minutes) AS minutes, CASE WHEN SUM(complete_calls)=0 THEN 0 ELSE (SUM(minutes)/SUM(complete_calls)) END AS acd
FROM balance
WHERE date_balance>='$fecha'::date - '59 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs Llamadas 15 dias*/
SELECT date_balance, SUM(minutes) AS minutes, SUM(incomplete_calls+complete_calls) AS total_calls
FROM balance
WHERE date_balance>='$fecha'::date - '14 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC

/*Min vs Llamadas 60 dias*/
SELECT date_balance, SUM(minutes) AS minutes, SUM(incomplete_calls+complete_calls) AS total_calls
FROM balance
WHERE date_balance>='$fecha'::date - '59 days'::interval AND id_carrier_supplier<>(SELECT id FROM carrier WHERE name='Unknown_Carrier') AND id_destination<>(SELECT id FROM destination WHERE name='Unknown_Destination') AND id_destination IS NOT NULL
GROUP BY date_balance
ORDER BY date_balance DESC
