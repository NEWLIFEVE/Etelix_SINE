#Etelix_SINE
============

##Sistema de Reportes para Billing

##Release 1.2.16
- Nueva validacion para recredi, balance y difference (filtrado de disputas con issue_date mas antiguos de lo que especifica los dias para solventar disputas en el contrato).
- Para difference y recredi se agrego validacion para que traiga disputas que con issue date menor a la fecha de consulta pero que tengan notas de credito con issue date mayor a la fecha en cuestion.
- Agregada tabla resumen con los totales por termino pago.
- Agregada tabla resumen por termino pago en cada caso con las diferencias entre billing y sine(solo los valores con pares en billing).
- Agregada validacion para que traiga o no los casos en rosado(casos donde sine no tiene par en billing).
- Agregada nueva estructura de termino pago y nueva relacion, (bilateral(casos donde los termino pago como customer y supplier son iguales)).
- Cambiado el orden en que se muestran los termino pago tanto en los select como en los reportes, ahora se ordena por semanales, quincenales y mensuales, sin importar si es prepago.

##Release 1.2.15.3
- Agregada fecha donde hay data para ser comparada por reporte billing, al seleccionar otro reporte diferente de "Difference", la fecha vuelve a la actual.
- Correccion en Balance: ahora toma las provisiones de la fecha de la consulta.

###Realese 1.2.15.2
- Agregado msj preliminar que indica que se esta calculando el tiempo para generar provisiones.
- Agregada funcionalidad js para la interfaz de provisiones(al finalizar las de generar provisiones, el msj de confirmacion se queda visible y solo se eliminara una vez se le de click en la transparencia).
- Agregado porcentaje en resumen total por casos.
- Cambio a español en titulño de leyenda y resumen total por casos.

###Realese 1.2.15.1
- Agregada leyenda en la parte superior de reporte difference.
- Agregado indicador de cantidades de casos por categorias.
- Mejoras en rendimiento para reporte difference.

###Release 1.2.15
- Habilitado modulo de provisiones
- Agregado reporte de DIFFERENCE.

###Release 1.2.14
- Se corrigio el css encargado de colorear los botones para exportar.
- SUMMARY: De ahora en adelante se muestra summary dividido en dos grupos, operadores MONETIZABLES (100% y 50%) y operadores NO MONETIZABLES (0%)
- SUMMARY: Ahora los valores negativos excluyendo los incrementales seran negativos.
- SUMMARY: Eliminada la linea que indicaba la existencia de soa provisionado.
- SUMMARY: Se corrigio la operacion encargada de calcular el soa provisionado.
- SUMMARY: Se repite la columna rows y carrier despuesde la seccion de pagos para poder navegar el reporte de forma mas sencilla.

###Release 1.2.13
- Summary: Se mejoro el metodo encargado de definir el soa provisionado.
- Summary: Se agrego un margen a las celdas que tengan soa provisionado.
- Summary y Recredi: Se corrigio un pequeño error que sucedia en el calculo de tiempo habiendo seleccionado ver carrier sin actividad.
- Summary: fueron eliminados los totales para soas provisionado.
- Recredi: se invirtio el orden de las columnas, ahora los datos de captura"balances" se muestra al principio despues de carriers, seguidamente se muestra el balance financiero y posteriormente los soas, provisiones y disputas.
- Se modifico el estilo del texto en la leyenda.
- Se agrego validacion en siteControler para los metodos Email, excel y previa para que el valor de Group solo lo use al momento de generar soas y balances, para con esto evitar errores al momento de generar los demas reportes habiendo cualquier texto en este input.
- Refac y reprov, se corrigio el metodo defineToDatePeriod encargado de definir la fecha de fin de periodo segun la fecha enviada en el caso de periodo quincenal.
- Se modifico refac y reprov para que los totales que no tengan valor queden en (0.00).
- Recredi, se implemento el metodo totalsGeneral encargado de mostrar tabla de resumen de totales general para cada atributo del reporte, cabe destacar que dicha tabla solo sera mostrada en el caso de extraer un recredi de todos los termino pago y relacion comercial customer o supplier, no con ambas.
- Se agregaron "atributos en la clase recredi" para almacenar totales en cada llamada al reporte.
- Se agregaron "atributos en la clase recredi" para almacenar los estilos utilizados en los metodos report y totalsGeneral.
- Se agrego validacion al momento de mostrar contenido dependiendo el termino pago para que si el modelo es NULL, entonces no muestre nada (en el caso de consulta general) y muestre una nota indicando que no hay data perteneciente al termino pago y relacion seleccionado(en consulta individual).

###Release 1.2.12
- Recredi: ahora las consultas de recredi general viene desglosada por termino pago, de igual manera si se selecciona customer o supplier con todos los termino pago.
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

###Release 1.2.11
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

###Realese 1.2.10
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

###Realese 1.2.9.1
- Se arreglo el SOA y balance para que incluya todos los documentos, incluso repetidos, de esta forma
  el valor del SOA y el SUMMARY (SOA) son iguales.
- Agregados totales de minutos para refac y reprov.
- Agregada nota que indica al usuario que debe seleccionar el ultimo dia del periodo de facturacion en el datepicker para refac y reprov.
- Solucionados detalles en summary.
- Mejora en generacion de reporte soa(en descripcion cuando el periodo esta conformado por meses diferentes, muestra ambos en formato "Oct-07-Sep-13", de lo contrario solo muestra el formato original "Oct-07-13")
- Se reparo el codigo que trae los documentos contables para soa y balance para que al seleccionar 'CABINAS PERU', traiga 'FULLREDPERU' y 'R-ETELIX.COM PERU'. (anteriormente faltaba por considerar 'R-ETELIX.COM PERU').
- Se filtro la consulta de grupos en el autocompletar para que no traiga Unknown_Carrier.
- Modificacion en SUMMARY, ahora se muestra con una especie de pronostico por semana, ademas se diversifico la forma como se muestran los totales, segun lo requerido .
- Cambios en la forma como se muestra el nombre de los reportes RETECO, SUMMARY y RECREDI.
- Modificacion de comportamiento de select termino pago en RECREDI, SUMMARY y RETECO.
- Cambio de indicadores para el termino pago. 
    
###Realese 1.2.8
- Default los inputs de soa, balance, sumary y RETECO.
- Arreglado Due_date y Monto Due en SUMMARY.
- Arreglado calculo de dias vencidos y por vencer.
- Recredi mejorado.
- Nuevos filtros para recredi y summary
- Agregado dias vencidos y por vencer en Summary.


###Realese 1.2.7
- Nombre de Carrier en SOA y BALANCE
- Orden de Pie de Tabla en SOA

###Release 1.2.6
- Summary por terminar pero igual se sube.
- Modificacion de sql para reteco
- Nuevo reporte reteco, el cual muestra todos los contratos al dia y especifica que datos les falta por completar para cada contrato.
- Nuevo reporte "summary", el mismo se encarga de dar un listrado de grupos que a su vez contienen ultimos pagos o cobros, soas vencidos y por vencer y se filtra por "no actividad", termino pago e intercompañia
- Se agrego titulos para las tablas que indican los balances en soa, soa(due) y soa(next)
- Modificacion de recredi, soa(due), soa(next) con sus respectivos due date, tambien ahora el recredi se filtra por termino pago.
- Nuevo soa terminado.

###Release 1.2.5.1
- Nuevo reporte de SOA con un total vencido y el proximo a vencer.
- Algunas moficicaciones temporales en Reportes.php implementadas para hacer que soa y balance funcionen de diferentes maneras (recomendable modificar el balance para que se genere tal como el soa actual)

###Release 1.2.5
- Nueva version de provisiones
- Agregados revenue, margin y cost de los ultimos tres dias por carrier
- Mejora en REPROV y REFAC

###Release 1.2.4.1
- Cambio en formula donde se utiliza notas de credito para calcular balance acumulado
- Corregido problema en comando de provisiones

###Release 1.2.4
- Corregida calculo de dias en REPROV
- Nueva version de generacion de provisiones, con soporte para termino de pagos de carriers suppliers 

###Relase 1.2.3
- Agregado Bank Fee
- Cambio en reportes para que genere bank fee de pagos.
- Creado boton de impresion de la vista previa del reporte
- Mejorado responsive, al abrir desde un telefono, la aplicacion notifica que no estará disponible el vista previa
- Corregida resta en REFAC y REPROV.
- Creada aplicacion de consola con el comando "provisions" que generará las provisiones del dia anterior y el comando "loop" que generará las provisiones en un periodo de tiempo seteado

###Relase 1.2.2
- Cambios en SOA y BALANCE:
se muestra en los reportes el bank fee derivado de los cobros
(se acordo que los cobros se guardan como salen reflejados en el banco, y los bank fee se guardan como resultado del valor mostrado en el banco - el monto de la factura o el deposito, asi que en el soa y balance, el bank fee actuara tal cual como los cobros).
- Mejoras en refac y repov, cuando las diferencias son 0, ya no se muestra el espacio en blanco, se arreglo para que en caso de ser exactamente iguales, muestre 0,00.

###Relase 1.2.1
- Cambios en SOA y BALANCE:
- Consultas modificadas para traer provisiones y disputas de forma correctas
- Las provisiones no tienen due date.
- Cambios generales:
- Nuevo boton para imprimir la vista previa de los reportes.

###Realese 1.0.1
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

###Release 1.0
- Reporte SOA funcionando sin provisiones

###Release 0.0.1
- Reporte SOA





