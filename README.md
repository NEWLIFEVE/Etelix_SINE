Etelix_SINE
============

Sistema de Reportes para Billing

- Creada aplicacion de consola con el comando "provisions" que generará las provisiones del dia anterior y el comando "loop" que generará las provisiones en un periodo de tiempo seteado

Relase 1.2.2
-Cambios en SOA y BALANCE:
se muestra en los reportes el bank fee derivado de los cobros
(se acordo que los cobros se guardan como salen reflejados en el banco, y los bank fee se guardan como resultado del valor mostrado en el banco - el monto de la factura o el deposito, asi que en el soa y balance, el bank fee actuara tal cual como los cobros).
- Mejoras en refac y repov, cuando las diferencias son 0, ya no se muestra el espacio en blanco, se arreglo para que en caso de ser exactamente iguales, muestre 0,00.

Relase 1.2.1
-Cambios en SOA y BALANCE:
       -consultas modificadas para traer provisiones y disputas de forma correctas
       -las provisiones no tienen due date.

-Cambios generales:
       -nuevo boton para imprimir la vista previa de los reportes.

Realese 1.0.1
-Cambios en SOA:
       -Color para las filas en gris cuando son Pagos o Cobros
       -Color para letras en Disputas y NC
       -Totales de Columnas
       -Cuadro indicativo Saldo a Favor
       -Formateo de cifras
       -Hora en el archivo y nombre de archivo
       -Eliminado el (-) en valores negativos, solo en columna due-balance
       -Eliminado due date para pagos y cobros y saldo inicial
       -Ordenado por Issue_date y el periodo de facturación.

Release 1.0
- Reporte SOA funcionando sin provisiones

Release 0.0.1
- Reporte SOA





