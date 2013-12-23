/**
 * Objeto Global
 */
 var $SINE={};
 
/**
 * Sobmodulo UI
 */
$SINE.UI=(function()
{
 	function init()
 	{
 	}
	/**
	 * Metodo encargado de ejecutar las repectivas llamadas
	 * @access public
	 */
	function accion()
	{	
	}
        /**
         * 
         * @param {type} selec
         * @returns {undefined}
         */
        function resolve_reports_menu(selec)
	{
            var params = $('#soa,#balance,#refac,#waiver,#recredi,#refi_prov');
            params.children().removeClass('h1_reportClick').addClass('h1_report');
            params.css('background', 'white').css('border-bottom', '1px solid silver').css('width', '92%');
            params.removeAttr('style');

            selec.children().removeClass('h1_report').addClass('h1_reportClick');
            selec.css('background', '#2E62B4').css('border-bottom', '1px solid white').css('width', '96%');
        }
        /**
         * 
         * @param {type} click
         * @param {type} no_Click
         * @returns {undefined}
         */
        function agrega_Val_radio(click,no_Click)
	{
            var dio_click=click[0].id;
            $(no_Click).val(''); 
            if (dio_click=='Si_prov'||dio_click=='Si_disp'){$(click).val('Si');$(click).blur();}
            else {$(click).val('No');$(click).blur();}
        }
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        function elijeOpciones(obj)
	{
            var ocultar =['.operador, .grupo, .provisiones,.disputas,.chang_Oper_Grup,.chang_Grup_Oper,.fecha,.termino_pago,.fecha_to'],
            nombre=obj[0].id;
            switch (nombre) 
            {
                case "soa":
                  var mostrar =['.grupo, .provisiones,.disputas,.fecha']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                  //POR AHORA SOLO FUNCIONA SOA...
                case "balance":
                  var mostrar =['']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                case "refac":
                    var mostrar =['#datepicker_to,.termino_pago,.fecha_to']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                case "waiver":
                    var mostrar =['']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                case "recredi":
                    var mostrar =['']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                case "refi_prov": 
                    var mostrar =['']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break;
            }
            $("#tipo_report").val(nombre);
            $('.formulario').css('display','block').css('width','81%').css('margin-left','39%');
//            $('.barra_tools_click').show();
            
            //ESTO HAY QUE QUITARLO CUANDO YA TODOS LOS TIPOS DE REPORTES FUNCIONEN
            if(nombre=="soa"||nombre=="refac")
                {
                    $('.trabajando').hide('slow');
                    $('.barra_tools_click').show('fast');
                }else{
                    $('.barra_tools_click').hide('fast');
                    $('.trabajando').show('slow');
                }
                //.....
        }
        /**
         * se encarga de indicar que boton e input ocultar o mostrar en el cambio entre grupos y operador
         * @param {type} obj
         * @returns {undefined}
         */
        function resolvedButton(obj)
	{
            var ocultar =['.chang_Grup_Oper,.operador,.chang_Oper_Grup,.grupo'],
            nombre=obj[0].id;
            switch (nombre) 
            {
                case "chang_Grup_Oper":
                  var mostrar =['.chang_Oper_Grup,.grupo']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break;
                case "chang_Oper_Grup":
                  var mostrar =['.chang_Grup_Oper,.operador']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break;
            }
        }
        /**
	 * Metodo encargado de la actualizacion de las facturas en disputas y notas de credito
	 * @access public
	 * @param ocultar array es el arreglo que contiene los elementos a ocultarse
	 * @param mostrar array es el arreglo que contiene los elementos a mostrarse
	 */  
        function formChangeAccDoc(ocultar, mostrar)
        {
            for (var i=0, j=ocultar.length - 1; i <= j; i++){
                $(ocultar[i]).hide('fast');              
            }
            for (var x=0, z=mostrar.length - 1; x <= z; x++){
                $(mostrar[x]).show('slow');              
            }  
        }
        /**
         * 
         * @param {type} clase
         * @param {type} attr
         * @param {type} value
         * @returns {undefined}
         */
        function changeCss(clase,attr,value)
        {
            $(clase).css(attr,value);
        }
       /**
        * responde al click del boton de email y excel para pasar la data a la funcion send de ajax...
        * @param {type} click
        * @returns {undefined}      * 
        */
        function export_report(click)
        {
              var valid_input=$SINE.UI.seleccionaCampos($('#tipo_report').val()); 
              console.log(valid_input);
              if(valid_input==0)
                {
                    $SINE.UI.msj_cargando("","");$SINE.UI.msj_change("<h2>Faltan campos por llenar </h2>","stop.png","1000","60px");  
                }else{
                    id=$(click).attr('id');
                    if(id=="mail")
                     {    
                        $SINE.AJAX.send("POST","/site/mail",$("#formulario").serialize());
                        $SINE.UI.msj_cargando("<h2>Enviando Email</h2>","cargando.gif");
                     }else{  
                             $SINE.UI.genExcel("/Site/Excel",$("#formulario").serialize());
                          } 
                }
        }
         /**
         * 
         * @param {type} tipo
         * @returns {unresolved}
         */
	function seleccionaCampos(tipo)
	{  
           switch (tipo){
            case 'soa':
                var respuesta=$SINE.UI.validaCampos($('#grupo').serializeArray());
                break               
            case 'refac':
                var respuesta=$SINE.UI.validaCampos($('#datepicker_from,#datepicker_to').serializeArray());
                break               
           }
           console.log(respuesta);
           return respuesta;
        }
        /**
         * 
         * @param {type} campos
         * @returns {Number}
         */
        function validaCampos(campos)
	{  
            for (var i=0, j=campos.length - 1; i <= j; i++)
                {
                    if(campos[i].value==""){
                        console.dir(campos[i]);
                        console.log(campos[i]);
                         var respuesta=0;
                        break;
                     }else{respuesta=1;}
                };
                return respuesta;
        }
        
        /**
         * 
         * @param {type} action
         * @param {type} formulario
         * @returns {undefined}
         */
        function genExcel(action,formulario)
        {
            window.open(action+"?"+formulario , "gen_excel_SINE" , "width=450,height=150,left=450,top=200");  
        }
        /**
         * 
         * @param {type} cuerpo_msj
         * @param {type} imagen
         * @returns {undefined}
         */
        function msj_cargando(cuerpo_msj,imagen)
        {
            $(".fondo_negro, .mensaje").remove();
            var msj=$("<div class='fondo_negro'></div><div class='mensaje'>"+cuerpo_msj+"<p><br><img src='/images/"+imagen+"' ></div>").hide(); 
            $("body").append(msj); 
            msj.fadeIn('slow');
        }
        /**
         * 
         * @param {type} cuerpo_msj
         * @param {type} imagen
         * @param {type} tiempo
         * @param {type} img_width
         * @returns {undefined}
         */
        function msj_change(cuerpo_msj,imagen,tiempo,img_width)
        {
            $(".mensaje").html(cuerpo_msj+"<p><img style='width: "+img_width+";' src='/images/"+imagen+"'>");
            setTimeout(function() { $(".fondo_negro, .mensaje").fadeOut('slow'); }, tiempo);
        }
	/**
	 * Retorna los mestodos publicos
	 */
	return{
		init:init,
                formChangeAccDoc:formChangeAccDoc,
                changeCss:changeCss,
                elijeOpciones:elijeOpciones,
                resolve_reports_menu:resolve_reports_menu,
                agrega_Val_radio:agrega_Val_radio,
                export_report:export_report,
                resolvedButton:resolvedButton,
                msj_cargando:msj_cargando,
                msj_change:msj_change,
                genExcel:genExcel,
                validaCampos:validaCampos,
                seleccionaCampos:seleccionaCampos
	};
})();

/**
 * Submodulo de llamadas AJAX
 */
$SINE.AJAX=(function()
{
        /**
         * 
         * @param {type} id
         * @returns {@exp;@call;$@call;serializeArray}
         */
	function _getFormPost(id)
	{
	    return $(id).serializeArray();
	}
	/**
	 * Crea un array con los nombres de carrier y los grupos de carriers
	 * @access private
	 */
	function _getNamesCarriers()
	{
            $.ajax({url:"../Carrier/Nombres",success:function(datos)
            {
                    $SINE.DATA.carriers=JSON.parse(datos);
                    $SINE.DATA.nombresCarriers=Array();
                    for(var i=0, j=$SINE.DATA.carriers.length-1; i<=j; i++)
                    {
                            $SINE.DATA.nombresCarriers[i]=$SINE.DATA.carriers[i].name;
                    };$('input#operador').autocomplete({source:$SINE.DATA.nombresCarriers});
            }
            });
            $.ajax({url:"../Grupos/Nombres",success:function(datos)
            {
                    $SINE.DATA.groups=JSON.parse(datos);
                    $SINE.DATA.nombresGroups=Array();
                    for(var i=0, j=$SINE.DATA.groups.length-1; i<=j; i++)
                    {
                            $SINE.DATA.nombresGroups[i]=$SINE.DATA.groups[i].name;
                    };$('input#grupo').autocomplete({source:$SINE.DATA.nombresGroups});
            }
            });
	}
	/**
	 * Inicializa las funciones del submodulo
	 * @access public
	 */
	function init()
	{
		_getNamesCarriers();
	}
        /**
        * funcion encargada de pasar datos del formulario al componente para enviarse por correo o exportarse a excel
        * @param {type} type
        * @param {type} action
        * @param {type} formulario
        * @returns {undefined}
        */
        function send(type,action,formulario)
        {
            $.ajax({
                 type: type,
                 url: action,
                 data: formulario,
                 success: function(data)
                 {
                         $SINE.UI.msj_change("<h2>"+data+" con exito</h2>","si.png","1000","33%");  
                         console.log(data);
                 }
            });
        }
	return {init:init,
                _getFormPost:_getFormPost,
                send:send
               }
})();

$SINE.DATA={};

$SINE.constructor=(function()
 {
    $SINE.UI.init();
 })();
