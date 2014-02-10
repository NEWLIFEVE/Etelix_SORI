Etelix_SORI
===========

Sistema de Origen

Relase 1.5.7
-Registro bank fee para pagos, mejoras en condiciones comerciales.
-mejoras minimas en interfaz de documentos contables, ahora al borrar todos los documentos de una de las tablas, ejm:pagos, cobros, etc, se eliminara no solo la fila, sino el head.

Realese 1.5.6
-Se arreglo la guardada del bank fee, eliminado id_charge y se agrego id_accounting_document_temp.

Realese 1.5.5
-Se arreglo comportamiento en condiciones comerciales con los inputs de termino de pago supplier
-Se agrego menu para tipo de usuario LEGAL

Relase 1.5.4
-modificacion de validacion en facturas enviadas, ahora valida por doc nomber, carrier y compañia.
-reparacion de tabla de disputas en interfaz de documentos contables, ahora al modificar los minunos y tarifas actualiza de inmediato los montos y el valor de las disputa.
-se reparo la administracion del monto banck fee, se agrego un nuevo atributo "id_charge" a accountingDocument y accountingDocumentTemp para lograrlo, 
-se agrego un nuevo metodo encargado de almacenar el bank fee definitivo, y se mejoro un poco la sintaxis. todas lass operaciones funcionando correctamente.
-Nuevos inputs en condiciones comerciales(termino pago supplier, Tipo de Ciclo de Fact, Divide Fact por Mes, Dia de Inicio de Ciclo). los dos ultimos  se mostraran u ocultaran dependiendo de las opciones elegidas en termino pago supplier y Tipo de Ciclo de Fact.
-Se agrego input bank fee, donde se declara si eteliz asume bank fee o no, esta opcion sera aplicada en todos los carrier pertenecientes a un mismo grupo, si modifica otro carrier del mismo grupo, la condicion se volvera a modificar y cambiara para los demas carrier.
-Se modificaron los msjs en condiciones comerciales para que muestre una tabla mas organizada de las condiciones elegidas.
-En documentos contables, al seleccionar cobros y un grupo, la aplicacion buscara si se asume el bank fee para el grupo elegido.
-Al guardar cobros, el sistema automaticamente guardara otro documento contable paralelo con el monto bank fee introducido por el usuario.
-Si se elimina el cobro, tambien se eliminara el bank fee asociado.

Realese 1.5.3
-Agregado Bank fee a COndiciones Ocmerciales
-Agregado Bank Fee a Documentos contables

Release 1.5.2
-Termino de pago para los carriers como suppliers
-Agregadas condiciones de tipo de ciclos de fact

Release 1.5.1
- Cambiar estatus de provisiones al momento de ingresar facturas
- Agregada aplicacion de consola para tranferencia de registros de balances

Release 1.5
-Corrección de tiempo de carga de archivos captura

Release 1.4.4
- Habilitada interfaz para carga de archivos din validaciones, donde solo un ususario tiene acceso

Realese 1.4.3
-Funcion de redondeo para disputas.
-Se elimino el Readonly en Monto cuando cambia de documento y viene de Notas de Credito.
Realese 1.4.2
-Eliminadas las validaciones para disputas.
-Agregada issue date en formulario de Notas de C.
-Vista de print arreglada, se agregaron las tablas de Notas de C que no estaban incluidas.

Realese 1.4.1
/***FALTA***/

Realese 1.4.0
-Status en modelo Carrier.
-Status en formulario de Contrato.
-Modelo Solved_Days_Dispute_History.
-Modelo Destination_Supplier.
-Solved_Days_Dispute en formulario Contrato.
-Cambios en Algoritmo de carga de archivos Captura Diarios.
-Patron de modulos JS en SORI.js.
-Refactorizacion de codigo Documentos Contables (MVC, views.js ,sori.js).
-Tablas Temporales separadas por Tipo de Documento
-Validaciones de Documentos Repetidos tanto en accounting_document_temp como en accounting_document
-Validaciones de campos en el formulario
-Agregados 4 tipos de Documentos contables (Disputas Enviadas, Disputas Recibidas, Notas de C enviadas, Notas de C Recibidas)


Realese 1.3.0
-Nuevo Módulo Documentos Contables 
-Nuevo Módulo Administrar Grupos de Carriers.
-Nuevo Módulo para Confirmar Fact. Enviadas.
-Modificaciones en Modelo de Contrato Agregado Unidad de Produccion (UP).
-Se agrego UP en la vista de create de contrato.


Realese 1.2.0
-Nuevo Modulo Condiciones Comerciales.
-Log del Sistema.
-Cambios en Carga diaria de archivos Captura, 1era carga PRELIMINARES/2da carga DEFINITIVOS.
-Mensajes personalizados en Dist.Comercial.
-Mensajes en Condiciones Comerciales.
-Modificar contrato y crear nuevo contrato.
-Al momento de cargar diarios, los carriers nuevos se le asigna el manager 'Sin Asignar'.


Realese 1.1.1
- Ordenado Alfabeticamente Managers y Carriers en DistComercial.
- Nueva validacion de nombre de archivo en carga de archivo diario.
- Nueva validacion de orden de columnas en carga de archivo diario.
- Nueva carga de archivos preliminar y definitiva.

Realese 1.1.0
- Nueva Pantalla de Bienvenida.
- Agregado Control de Acceso a Funciones y Pantallas por medio de Permisologia.
- Interfaz para Distribucion Comercial .
- Proceso de Re-Rate Automatico.

Realese 1.0.1
- Proceso de Re-Rate no Automatico, se Ejecuta Manual el Procedimiento en BD.

Realese 1.0
- Carga de Archivos por Dia.
- Carga de Archivos por Hora.
- Carga de Archivos por Re-Rate.
