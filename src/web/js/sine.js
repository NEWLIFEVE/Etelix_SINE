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
 		//Crea los inputs usados para la fecha en especificos
 		var checkFecha=document.getElementsByName('lista[Fecha]');
 		if(checkFecha!="undefined")
 		{
 			optionsDate={
 				elemento:'input',
 				idInputStart:'startDate',
 				idInputEnd:'endingDate',
 				idCheck:'checkDate',
 				nameClassPicker:'start date',
 				nameClassCheck:'middle date',
 				spot:'div.choice_parametros.fecha'
 			};
 			checkFecha[0].onclick=function()
 			{
 				_changeClass($('.fecha label h4'),'stretchRight','offStretchRight',optionsDate);
 				document.getElementById(optionsDate.idCheck).onclick=function()
 				{
 					if (this.checked) _showElement($(_createElement(optionsDate.elemento,optionsDate.idInputEnd,optionsDate.idInputEnd,'end date',undefined,'Fin')).datepicker({dateFormat: 'yy-mm-dd'}),optionsDate.spot);
 					else _hideElement('#'+optionsDate.idInputEnd);
 				}
 			};
 		}
 		//crea el input usado para carrier en la interfaz especificos
 		var checkCarrier=document.getElementsByName('lista[Carrier]');
 		if(checkCarrier!="undefined")
 		{
 			optionsCarrier={
 				elemento:'input',
 				idInputStart:'carrier',
 				idInputEnd:'',
 				idCheck:'',
 				nameClassPicker:'middle_carrier carrier',
 				nameClassCheck:'middle carrier',
 				spot:'div.choice_parametros.carrier'
 			};
 			checkCarrier[0].onclick=function()
 			{
 				_changeClass($('.carrier label h4'),'stretchRight','offStretchRight',optionsCarrier);
 			};
 		}
 		//crea el input usado para grupos en la interfaz especificos
 		var checkGroup=document.getElementsByName('lista[Group]');
 		if(checkGroup!="undefined")
 		{
 			optionsGroup={
 				elemento:'input',
 				idInputStart:'group',
 				idInputEnd:'',
 				checks:{
 					primero:{
 						id:'asr',
 						name:'alarma',
 						className:'',
 						type:'radio',
 						text:'ASR'
 					},
 					segundo:{
 						id:'pdd',
 						name:'alarma',
 						className:'',
 						type:'radio',
 						text:'PDD'
 					},
 					tercero:{
 						id:'uno',
 						name:'porcentaje',
 						className:'',
 						type:'radio',
 						text:'+1%'
 					},
 					cuarto:{
 						id:'dos',
 						name:'porcentaje',
 						className:'',
 						type:'radio',
 						text:'+5%'
 					}
 				},
 				nameClassPicker:'middle_group group',
 				nameClassCheck:'middle group',
 				spot:'div.choice_parametros.group'
 			};
 			checkGroup[0].onclick=function()
 			{
 				_changeClass($('.group label h4'),'stretchRight','offStretchRight',optionsGroup);
 			};
 		}
 	}

 	/**
	 * Encargado de asignar/quitar una clase.
	 * @access private
	 * @param jQuery obj es el objeto de la fila que se quiere manipular
	 */
	function _changeClass(obj,activeClass,desactiveClass,options)
	{
		if(obj.attr('class')==activeClass)
		{
			var todos="";
			obj.removeClass(activeClass).addClass(desactiveClass);
			if (options.idInputStart!="") todos+='#'+options.idInputStart;
			if (options.idCheck!="") todos+=',#'+options.idCheck;
			if (options.idInputEnd!="") todos+=',#'+options.idInputEnd;
			_hideElement(todos);
		}
		else
		{
			obj.removeClass(desactiveClass).addClass(activeClass);
			if(options.idInputStart=='carrier')
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Carrier')).autocomplete({source:$RENOC.DATA.nombresCarriers}),options.spot);
			}
			else if(options.idInputStart=='group')
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);
				//radios
				var radios=options.checks;
				for(var key in radios)
				{
					console.dir(radios[key]);
					_showElement($("<input class='"+radios[key].className+"' id='"+radios[key].id+"' type='"+radios[key].type+"' name='"+radios[key].name+"'>"+radios[key].text+"</input>"),options.spot);
				}
				/*_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Grupo')).autocomplete({source:$RENOC.DATA.nombresGroups}),options.spot);*/
			}
			else
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Inicio')).datepicker({dateFormat: 'yy-mm-dd'}),options.spot);
				_showElement(_createElement(options.elemento,options.idCheck,options.idCheck,options.nameClassCheck,'checkbox'),options.spot);
			}
		}
		obj=null;
	}
        /**
	 * Crea un elemento html con todas caracteristicas
	 * @access private
	 * @param string element es el nombre del elemento a crear
	 * @param string id es el id que se le asigna al elemento
	 * @param string name es el nombre del elemento
	 * @param string className son la/las clases que llevara el elemento
	 * @param string type tipo de elemento
	 * @return dom newElement
	 */
	function _createElement(element,id,name,className,type,placeholder)
	{
		if (element!=undefined)
		{
			newElement=document.createElement(element);
			if (id!=undefined) newElement.id=id;
			if (name!=undefined) newElement.name=name;
			if (className!=undefined) newElement.className=className;
			if (type!=undefined) newElement.type=type;
			if (placeholder!=undefined) newElement.placeholder=placeholder;
			return newElement;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Recibe un objeto html y una ubicacion jQuery este mostrara el elemento
	 * @access private
	 * @param dom object es el elemento html a agregar y mostrar
	 * @param string spot es la ubicacion tipo jQuery donde agregar el elemento
	 */
	function _showElement(object,spot)
	{
		$element=$(object).css('display','none');
		$(spot).append($element);
		$element.fadeIn('slow');
		$element=null;
	}

	/**
	 * Recibe un string de ubicacion tipo jQuery y esta oculta y luego elimina el elemento
	 * @access private
	 * @param string spot es la ubicacion tipo jQuery
	 */
	function _hideElement(spot)
	{
		$(spot).fadeOut('slow');
		$(spot).remove();
	}

	/**
	 * Metodo encargado de ejecutar las repectivas llamadas
	 * @access public
	 */
	function accion()
	{
		
	}
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
        function formChangeAccDoc(ocultar, mostrar){
            
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
	 * Retorna los mestodos publicos
	 */
	return{
		init:init,
                formChangeAccDoc:formChangeAccDoc,
                changeCss:changeCss,
                elijeOpciones:elijeOpciones,
                resolve_reports_menu:resolve_reports_menu,
                _hideElement:_hideElement,
                _showElement:_showElement,
                _createElement:_createElement,
                _changeClass:_changeClass
               
	};
})();

/**
 * Submodulo de llamadas AJAX
 */
$SINE.AJAX=(function()
{
	/**
	 * Obtiene los datos del formulario 
	 * @access private
	 * @param string id es el id tipo jQuery para llamar el formulario
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
		$.ajax({url:"carrier/nombres",success:function(datos)
		{
			$SINE.DATA.carriers=JSON.parse(datos);
			$SINE.DATA.nombresCarriers=Array();
			for(var i=0, j=$SINE.DATA.carriers.length-1; i<=j; i++)
			{
				$SINE.DATA.nombresCarriers[i]=$SINE.DATA.carriers[i].name;
			};
		}
		});
		$.ajax({url:"grupos/nombres",success:function(datos)
		{
			$SINE.DATA.groups=JSON.parse(datos);
			$SINE.DATA.nombresGroups=Array();
			for(var i=0, j=$SINE.DATA.groups.length-1; i<=j; i++)
			{
				$SINE.DATA.nombresGroups[i]=$SINE.DATA.groups[i].name;
			};
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

	return {init:init}
})();

$SINE.DATA={};

$SINE.constructor=(function()
 {
    $SINE.UI.init();
 })();
