Etelix_SINE
============

Sistema de Reportes para Billing
    - Modificacion de soa y balance para que muestre facturas y demas documentos que esten repetidos.
    - Agregados totales de minutos para refac y reprov.
    - Agregada nota que indica al usuario que debe seleccionar el ultimo dia del periodo de facturacion en el datepicker para refac y reprov.
    - Solucionados detalles en summary.
    - Mejora en generacion de reporte soa(en descripcion cuando el periodo esta conformado por meses diferentes, muestra ambos en formato "Oct-07-Sep-13", de lo contrario solo muestra el formato original "Oct-07-13")
    - Se reparo el codigo que trae los documentos contables para soa y balance para que al seleccionar 'CABINAS PERU', traiga 'FULLREDPERU' y 'R-ETELIX.COM PERU'. (anteriormente faltaba por considerar 'R-ETELIX.COM PERU').
    - Se filtro la consulta de grupos en el autocompletar para que no traiga Unknown_Carrier.
    - Modificacion en summary, ahora se muestra con una especie de pronostico por semana, ademas se diversifico la forma como se muestran los totales, segun lo requerido .
    - Cambios en la forma como se muestra el nombre de los reportes reteco, summary y recredi.
    - Modificacion de comportamiento de select termino pago en recredi, summary y reteco.
    - Cambio de indicadores para el termino pago. 
Realese 1.2.8
    - Default los inputs de soa, balance, sumary y reteco.
    - Arreglado Due_date y Monto Due en Summary.
    - Arreglado calculo de dias vencidos y por vencer.
    - Recredi mejorado.
    - Nuevos filtros para recredi y summary
    - Agregado dias vencidos y por vencer en Summary.


Realese 1.2.7
    - Nombre de Carrier en SOA y BALANCE
    - Orden de Pie de Tabla en SOA

Release 1.2.6
    - Summary por terminar pero igual se sube.
    - Modificacion de sql para reteco
    - Nuevo reporte reteco, el cual muestra todos los contratos al dia y especifica que datos les falta por completar para cada contrato.
    - Nuevo reporte "summary", el mismo se encarga de dar un listrado de grupos que a su vez contienen ultimos pagos o cobros, soas vencidos y por vencer y se filtra por "no actividad", termino pago e intercompañia
    - Se agrego titulos para las tablas que indican los balances en soa, soa(due) y soa(next)
    - Modificacion de recredi, soa(due), soa(next) con sus respectivos due date, tambien ahora el recredi se filtra por termino pago.
    - Nuevo soa terminado.
Release 1.2.5.1
    - Nuevo reporte de SOA con un total vencido y el proximo a vencer.
    - Algunas moficicaciones temporales en Reportes.php implementadas para hacer que soa y balance funcionen de diferentes maneras (recomendable modificar el balance para que se genere tal como el soa actual)
Release 1.2.5
    - Nueva version de provisiones
    - Agregados revenue, margin y cost de los ultimos tres dias por carrier
    - Mejora en REPROV y REFAC
Release 1.2.4.1
    - Cambio en formula donde se utiliza notas de credito para calcular balance acumulado
    - Corregido problema en comando de provisiones
Release 1.2.4
    - Corregida calculo de dias en REPROV
    - Nueva version de generacion de provisiones, con soporte para termino de pagos de carriers suppliers 
Relase 1.2.3
    - Agregado Bank Fee
    - Cambio en reportes para que genere bank fee de pagos.
    - Creado boton de impresion de la vista previa del reporte
    - Mejorado responsive, al abrir desde un telefono, la aplicacion notifica que no estará disponible el vista previa
    - Corregida resta en REFAC y REPROV.
    - Creada aplicacion de consola con el comando "provisions" que generará las provisiones del dia anterior y el comando "loop" que generará las provisiones en un periodo de tiempo seteado
Relase 1.2.2
    - Cambios en SOA y BALANCE:
se muestra en los reportes el bank fee derivado de los cobros
(se acordo que los cobros se guardan como salen reflejados en el banco, y los bank fee se guardan como resultado del valor mostrado en el banco - el monto de la factura o el deposito, asi que en el soa y balance, el bank fee actuara tal cual como los cobros).
    - Mejoras en refac y repov, cuando las diferencias son 0, ya no se muestra el espacio en blanco, se arreglo para que en caso de ser exactamente iguales, muestre 0,00.
Relase 1.2.1
    - Cambios en SOA y BALANCE:
    - Consultas modificadas para traer provisiones y disputas de forma correctas
    - Las provisiones no tienen due date.
    - Cambios generales:
        - Nuevo boton para imprimir la vista previa de los reportes.
Realese 1.0.1
    - Cambios en SOA:
       - Color para las filas en gris cuando son Pagos o Cobros
       - Color para letras en Disputas y NC
       - Totales de Columnas
       - Cuadro indicativo Saldo a Favor
       - Formateo de cifras
       - Hora en el archivo y nombre de archivo
       - Eliminado el (-) en valores negativos, solo en columna due-balance
       - Eliminado due date para pagos y cobros y saldo inicial
       - Ordenado por Issue_date y el periodo de facturación.
Release 1.0
       - Reporte SOA funcionando sin provisiones
Release 0.0.1
       - Reporte SOA





