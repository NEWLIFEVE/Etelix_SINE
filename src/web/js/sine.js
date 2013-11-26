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
                if (dio_click=='Si_prov')$(click).val('1');
                else $(click).val('0');
        }
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
        function elijeOpciones(obj)
	{
            $('.formulario').fadeOut('slide');
            var ocultar =['.operador, .grupo, .datepicker, .provisiones,.chang_Oper_Grup,.chang_Grup_Oper'],
            nombre=obj[0].id;
            switch (nombre) 
            {
                case "soa":
                  var mostrar =['.grupo,.chang_Oper_Grup, .datepicker, .provisiones']; 
                      $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  break; 
                case "balance":
                  var mostrar =['.operador,.chang_Grup_Oper, .datepicker, .provisiones']; 
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
            $('.formulario').css('width','81%').fadeIn('slide');  
            $('.barra_tools_click').fadeIn();
        }
        /**
         * 
         * @param {type} obj
         * @returns {undefined}
         */
//         function _show_hide_tools(obj)
//	 {
//              $('#botones_exportar,#next_tool,#back_tool').remove();
//             if(obj.attr('class')=='barra_tools')
//             {
//                $(".barra_tools").append("<div id='back_tool'><img src='/images/back_tool.png'></div><footer id='botones_exportar'><div id='excel' class='botones'><img src='/images/excel.png' class='ver'><img src='/images/excel_hover.png' title='Exportar Reportes en Excel' class='oculta'></div><div id='mail' class='botones'><img src='/images/mail.png' class='ver'><img src='/images/mail_hover.png' title='Enviar Reportes a su Correo Electronico' class='oculta'></div></footer>");
//                $(".barra_tools").removeClass('barra_tools').addClass('barra_tools_click');
//             }
//             else
//             {
//                $(".barra_tools_click").removeClass('barra_tools_click').addClass('barra_tools').append("<div id='next_tool'><img src='/images/next_tool.png'></div>");
//             }  
//         }

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
            {//                var formulario=$SINE.AJAX._getFormPost($("form_report_sine"));
                var formulario="grupo="+$('#grupo').val()+"&operador="+$('#operador').val()+"&fecha="+$('#datepicker').val()+"&Si_prov="+$('#Si_prov').val()+"&No_prov="+$('#No_prov').val();
                var id=$(this).attr('id');
                    if(id=="mail"){$SINE.AJAX.send("POST","/Site/Mail",formulario);}
                             else {$SINE.AJAX.send("GET","/Site/Excel",formulario);}  
            });
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
                export_report:export_report
               
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
		$.ajax({url:"Carrier/Nombres",success:function(datos)
		{
			$SINE.DATA.carriers=JSON.parse(datos);
			$SINE.DATA.nombresCarriers=Array();
			for(var i=0, j=$SINE.DATA.carriers.length-1; i<=j; i++)
			{
				$SINE.DATA.nombresCarriers[i]=$SINE.DATA.carriers[i].name;
			};$('input#operador').autocomplete({source:$SINE.DATA.nombresCarriers});
		}
		});
		$.ajax({url:"Grupos/Nombres",success:function(datos)
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
