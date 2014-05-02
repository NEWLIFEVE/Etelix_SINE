﻿Etelix_SINE
============

Sistema de Reportes para Billing
    - Recredi: ahora las consultas de recredi general viene desglosada por termino pago, de igual manera si se selecciona customer o supplier con todos los termino pago.
    - Pendiente mostrar el resumen general de totales al momento de generar un recredi general.
    - Se elimino columna "BALANCE" en summary.
    - Se agregaron los titulos superiores que segmentan el reporte en pagos/cobros y SOAs.
    - Se coloco un filtro que solo muestra los soas provisionado, si este es diferentes a los soas, a su vez, si se muestra el soa provisionado se pinta la fila "num de rows" de verde para indicarlo.
    - Se implemento el desglose pot totales de pagos y cobros, y ahora al final de cada columna de pagos y cobros se muestra totales cobros, totales pagos y la diferencia.
    - En refac se agrego la opcion "todos" por default en el select de periodos, si se mantiene esta opcion, el reporte traera de una vez los refac con periodos semanales, quincenales y mensuales.
    - En reprov se agrego la opcion "todos" por default en el select de termino pago, si se mantiene asi traera los reprov de cada uno de los termino pago.
    - Ahora el refac puede traer todos los periodos y el reprov todos los termino pago con sus respectivos summary si asi se decide.
    - Se modifico el segmento de totales para las tablas, ahora muestra un desglose de diferencias de mas de un dolar, provisiones sin facturas, la suma entre estas y el total neto que ya se conoce. 
    - Se agrego una pequeña tabla de leyenda de colores para especificar que significa cada uno de los colores variantes dentro del reporte.
    - Se agrego indicador de tiempo para el reprov que hasta los momentos tarda un poco mas que el refac.
    - Se coloco columna en blanco para diferenciar segmento pagos/cobros del segmento SOAs.
    - Se agrego nuevas columnas en summary donde se desgloza los ultimos pagos y cobros realizados en tres variantes, (PREVIOUS WEEK, LAST WEEK y THIS WEEK)
    - Se agrego columna en summary, (currency balance)la cual muestra el balancea la fecha.
    - Se agrego columna en summary, (soa provisioned) la cual muestra el soa next tomando en cuenta las provisiones, dando una idea mas segura de lo que seria el para la fecha.
    - se agrego valor (incremental) para los soa next y soa provisioned, esta es la operacion entre el soa next/soa provisioned menos el soa due.
    - Se agrego indicador de tiempo de espera para la generacion de reportes.
    - Se solvento problema en el envio de emails para los reportes recredi y summary.
    - Correcciones en recredi listas.
    - Mejoras en calculo de disputas y balance para recredi.
    - cambio en datepickers para que la fecha minima de seleccion sea 2013-10-01.
    - Correccion de scroll para la interfaz principal.
    - Nueva interfaz para generar provisiones.
    - Correccion de soa, ahora va a sacar los due days y next days apartir de no solo la ultima fecha del ciclo del reporte sino tambien verifica que sea la mas alta.
    - Modificado el metodo format decimal para que cuando el valor sea null,devuelva 0,00. 

    - Nueva version de Provisiones: 
        * Coloca el id de la provision de factura en el campo id_accounting_document de la provision de trafico.
        * Coloca el id de la factura en el campo id_accounting_document de la provision de factura

    - Pequeñas mejoras en SINE.js.
    - pequeñas mejoras en refac y reprov.
    - Modificado el metodo que define acumulado en soa y balance para que sume balance inicial de estar repetido.
    - Modificado refac y reprov para que genere summary por medio de un check adicional donde se define si se muestra o no.
    - Modificacion de  refac y reprov a nivel de estilos.
    - Modificacion de reprov para que la busqueda sea hecha por termino de pago y ahora tiene la oportunidad de indicar si quiere ver carriers periodo 7 que piquen el mes,
      esta opcion viene por defecto en "si", la misma influye mas que todo al momento de consultar en un periodo dividido entre dos meses, donde se encontraran facturas completas y divididas.


Realese 1.2.9.1
    - Se arreglo el SOA y balance para que incluya todos los documentos, incluso repetidos, de esta forma
      el valor del SOA y el SUMMARY (SOA) son iguales.
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
    - Default los inputs de soa, balance, sumary y RETECO.
    - Arreglado Due_date y Monto Due en SUMMARY.
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





