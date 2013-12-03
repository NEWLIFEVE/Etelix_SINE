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
                if (dio_click=='Si_prov'||dio_click=='Si_disp')$(click).val('1');
                else $(click).val('0');
        }
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        function elijeOpciones(obj)
	{
            var ocultar =['.operador, .grupo, .datepicker, .provisiones,.disputas,.chang_Oper_Grup,.chang_Grup_Oper'],
            nombre=obj[0].id;
            switch (nombre) 
            {
                case "soa":
                  var mostrar =['.grupo, .datepicker, .provisiones,.disputas']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                case "balance":
                  var mostrar =['.grupo, .chang_Oper_Grup,.datepicker, .provisiones,.disputas']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                case "refac":
                  break; 
                case "waiver":
                  break; 
                case "recredi":
                  break; 
                case "refi_prov": 
                  break;
            }
            $("#tipo_report").val(nombre);
            $('.formulario').css('display','block').css('width','81%').css('margin-left','39%');
            $('.barra_tools_click').show();
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
                $(ocultar[i]).fadeOut('fast');              
            }
            for (var x=0, z=mostrar.length - 1; x <= z; x++){
                $(mostrar[x]).fadeIn('fast');              
            }  
        }
        /**
         * 
         * @param {type} clase
         * @param {type} attr
         * @param {type} value
         * @returns {undefined}
         */
        function changeCss(clase,attr,value){
            $(clase).css(attr,value);
        }
         /**
        * responde al click del boton de email y excel para pasar la data a la funcion send de ajax...
        */
        function export_report()
        {
            $('#mail, #excel').on('click',function()
            {   
                var formulario="tipo_report="+$("#tipo_report").val()+"&grupo="+$('#grupo').val()+"&operador="+$('#operador').val()+"&fecha="+$('#datepicker').val()+"&Si_prov="+$('#Si_prov').val()+"&No_prov="+$('#No_prov').val()+"&Si_disp="+$('#Si_disp').val()+"&No_disp="+$('#No_disp').val(),
                id=$(this).attr('id');
             if(id=="mail"){    
                  $SINE.AJAX.send("POST","/Site/Mail",formulario);
                  $SINE.UI.msj_cargando("<h2>Enviando Email</h2>","cargando.gif");
               }else{  
                       $SINE.AJAX.send("GET","/Site/Excel",formulario);
                    }  
            });
        }
        /**
         * 
         * @param {type} cuerpo_msj
         * @param {type} imagen
         * @returns {undefined}
         */
        function msj_cargando(cuerpo_msj,imagen)
        {
           $(".cargando, .mensaje").remove();
           var msj=$("<div class='cargando'></div><div class='mensaje'>"+cuerpo_msj+"<p><br><img src='/images/"+imagen+"' ></div>").hide(); 
               $("body").append(msj); 
               msj.fadeIn('slow');
        }
        /**
         * 
         * @param {type} cuerpo_msj
         * @param {type} imagen
         * @returns {undefined}
         */
        function msj_change(cuerpo_msj,imagen)
        {
             $(".mensaje").html(cuerpo_msj+"<p><img style='width: 33%;' src='/images/"+imagen+"'>");
             setTimeout(function() { $(".cargando, .mensaje").fadeOut('slow'); }, 1000);
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
                msj_change:msj_change
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
        * * @param {type} formulario
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
                     if(action=="/Site/Excel") window.open(action+"?"+formulario , "gen_excel_SINE" , "width=450,height=150,left=450,top=200"); 
                     else $SINE.UI.msj_change("<h2>"+data+" con exito</h2>","si.png");    
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
