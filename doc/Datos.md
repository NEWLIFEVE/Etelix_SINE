Documentación plataforma RENOC
Términos: 
Margin(Margen):  es la resta de cost(costo) menos el revenue(venta).
Cost(Costo): es el costo de compra de minutos a los carriers proveedores.
Revenue: es la venta de minutos a los carriers clientes.
ACD: Average Call Duration

Problemas conocidos:
En la plataforma SORI, hay cierto problema en la lectura de los archivo, generando un error de margen con una diferencia de millones. Presentando como solución parcial la creación de una condicional de si el margen calculado es mayor a la resta de cost menos el revenue se colocara como valor del margen la resta del costo menos el revenue.

Datos:

Contrato:
Nombre: contrato.
Columnas:
 - id serial NOT NULL
 - sign_date date
 - production_date date
 - end_date date
 - id_carrier integer NOT NULL
 - id_company integer NOT NULL
 - up integer

Descripcion:
- id: identificador del contrato
- sign_date: fecha de firmado el contrato
- production_date: fecha de pusta en produccion del carrier
- end_date: fecha en que finalizó el contrato
- id_carrier: relacion con la tabla de carriers
- id_company: relacion con la tabla company
- up: es la unidad de produccion a la que pertenece el contrato