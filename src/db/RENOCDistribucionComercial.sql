SELECT m.name AS vendedor, c.name AS operador FROM carrier c, managers m, carrier_managers cm
WHERE m.id = cm.id_managers AND c.id = cm.id_carrier AND cm.end_date IS NULL AND cm.start_date <= '$fecha' 
ORDER BY m.name ASC;