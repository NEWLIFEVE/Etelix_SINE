/**
 * Objeto Global
 */
 var $RENOC={};

/**
 * Submodulo para manejo de interfaz grafica
 */
 $RENOC.UI=(function()
 {
 	/**
 	 * Metodo para inicialzar acciones de click en interfaz
 	 * @acces public
 	 */
 	function init()
 	{
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
 		/*var checkTime=document.getElementsByName('lista[Hora]');
 		if(checkTime!="undefined")
 		{
 			optionsTime={
 				elemento:'input',
 				idInputStart:'startTime',
 				idInputEnd:'endingTime',
 				idCheck:'checkTime',
 				nameClassPicker:'start time',
 				nameClassCheck:'middle time',
 				spot:'div.choice_parametros.hora'
 			};
 			checkTime[0].onclick=function()
 			{
 				_changeClass($('.hora label h4'),'stretchRight','offStretchRight',optionsTime);
 				document.getElementById(optionsTime.idCheck).onclick=function()
 				{
	 				if (this.checked) _showElement($(_createElement(optionsTime.elemento,optionsTime.idInputEnd,optionsTime.idInputEnd,'end time',undefined,'Fin')).datepicker({dateFormat: 'yy-mm-dd'}),optionsTime.spot);
	 				else _hideElement('#'+optionsTime.idInputEnd);
 				};
 			};
 			
 		}*/
 		var checkCarrier=document.getElementsByName('lista[Carrier]');
 		if(checkCarrier!="undefined")
 		{
 			optionsMonth={
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
 				_changeClass($('.carrier label h4'),'stretchRight','offStretchRight',optionsMonth);
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
			if(options.idInputStart!='carrier')
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Inicio')).datepicker({dateFormat: 'yy-mm-dd'}),options.spot);
				_showElement(_createElement(options.elemento,options.idCheck,options.idCheck,options.nameClassCheck,'checkbox'),options.spot);
			}
			else
			{
				_showElement($(_createElement(options.elemento,options.idInputStart,options.idInputStart,options.nameClassPicker,undefined,'Carrier')).autocomplete({source:$RENOC.DATA.nombresCarriers}),options.spot);
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
	 * retorna los metodos publicos
	 */
	return{
		init:init
	}
 })();

/**
 * Submodulo de manejo de peticiones AJAX
 */
$RENOC.AJAX=(function()
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
	 * Crea un array con los nombres y id de los carriers
	 * @access private
	 */
	function _getNamesCarriers()
	{
		$.ajax({url:"carrier/nombres",success:function(datos)
		{
			$RENOC.DATA.carriers=JSON.parse(datos);
			$RENOC.DATA.IDS=Array();
			$RENOC.DATA.nombresCarriers=Array();
			for (var i = 0, j=$RENOC.DATA.carriers.length-1; i <= j; i++)
			{
				$RENOC.DATA.nombresCarriers[i]=$RENOC.DATA.carriers[i].name;
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

$RENOC.DATA={};