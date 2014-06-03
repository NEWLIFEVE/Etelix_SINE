$(document).on('ready',function()
{
     $SINE.AJAX.init();
     $SINE.AJAX.getNamesCarriers();
});
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
        _datepicker();
        _clickElement();
        _hoverLink();
        _predefined();
        _changeElement();
    }
    /**
     * inicia javascript requerido
     * carga de termino pago, radios seleccionados, etc
     * @returns {undefined}
     */
    function _predefined()
    {
        $('#No_inter,#No_act,#No_car_act,#No_prov, #No_sum').val('No').addClass("active");
        $('#Si_disp,#Si_div').val('Si').addClass("active");
    }
    /**
     * datepicker jquery ui
     * @returns {undefined}
     */
    function _datepicker() 
    {
        $( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D", minDate: "2013-10-01"});
    };
    /**
     * metodo encargado de escuchar changes desde la interfaz y redireccionar a la accion que se necesite
     * @returns {undefined}
     */
    function _changeElement() 
    {
        $('#type_termino_pago,#id_termino_pago').change(function()
        {
            switch ($(this).attr("id"))
            {
                case "type_termino_pago":
                    $SINE.UI.adminInput($(this));
                    break;
                case "id_termino_pago":
                    if($("#tipo_report").val()=="refi_prov"){
                        $SINE.UI.adminTp($(this));
                    }else{
                        if($("#type_termino_pago").val()=='null')
                            $SINE.UI.adminInput(("#type_termino_pago"));
                    }
                    break;
            }
        });
    } 
    /**
     * metodo encargado de escuchar click desde la interfaz y redireccionar a la accion que se necesite
     * @returns {undefined}
     */
    function _clickElement() 
    {
        $('#showProvisions,#genProvision,#soa,#balance,#summary,#reteco,#refac,#waiver,#recredi,#recopa,#refi_prov,#redis,#difference,#No_prov,#Si_prov,#No_div,#Si_div,#No_disp,#Si_disp,#No_venc,#Si_venc,#No_inter,#Si_inter,#No_act,#Si_act,#No_car_act,#Si_car_act,#No_sum,#Si_sum,#previa,#mail,#excel,#views_not').on('click',function()
        {   
            switch ($(this).attr("id")){
                case "soa":case"balance": case"reteco": case"refac":case "refi_prov":case "waiver":case"recredi":case"recopa": case"redis": case"summary": case"difference":
                    $SINE.UI.resolve_reports_menu($(this));
                    $SINE.UI.elijeOpciones($(this));
                    break;
                case "No_prov": case "Si_prov": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_prov, #Si_prov'));
                    break;
                case "No_disp": case "Si_disp": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_disp, #Si_disp'));
                    break;
                case "No_venc": case "Si_venc": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_venc, #Si_venc'));
                    break;
                case "No_inter": case "Si_inter": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_inter, #Si_inter'));
                    break;
                case "No_act": case "Si_act": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_act, #Si_act'));
                    break;
                case "No_car_act": case "Si_car_act": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_car_act,#Si_car_act'));
                    break;
                case "No_div": case "Si_div": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_div,#Si_div'));
                    break;
                case "No_sum": case "Si_sum": 
                    $SINE.UI.agrega_Val_radio($(this),$('#No_sum,#Si_sum'));
                    break;
                case "previa": case "mail": case "excel": 
                    $SINE.UI.export_report($(this));
                    break;
                case "showProvisions": /**revisar esta opcion, parte del js esta en la vista, hay que depurar eso, aqui solo esta funcionando ( $SINE.AJAX.provisions("GET","/site/Provisions") )*/
                    $SINE.AJAX.provisions("GET","/site/Provisions",null);
                    $SINE.UI.GenDatepicker("input#datepickerOne");
                    $SINE.AJAX.getNamesCarriers();
                    break;
                case "genProvision": 
                    $SINE.UI.genProvisions($(this));
                    break;
                case "views_not":
                    $(this).remove();
                    break;
              }   
        });
    };
    

    
    /**
     * metodo encargado de escuchar los hover
     * @returns {undefined}
     */
    function _hoverLink() 
    {
        $("#excel").hover(function()
        {
            switch ($(this).attr("id"))
            {
                case "excel":
                    var valid_input=$SINE.UI.seleccionaCampos($('#tipo_report').val()); 
                    if(valid_input==1) $(".excel_a").attr("href","Site/Excel?" + $('#formulario').serialize()+ "");    
                    break;
            } 
        });
    };
    /**
    * asigna datepicker a el input que se le pase, este metodo aplica para aquellos casos donde la 
    * llamada del datePicker desde el inicio no funcionan, por ejemplo en la llamada de vistas por ajax
    * @param {type} obj
    * @returns {undefined}
    */
    function GenDatepicker(obj) 
    {
        $( obj ).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D", minDate: "2013-10-01"});
    };
    /**
     * administra el menu vertical
     * @param {type} selec
     * @returns {undefined}
     */
    function resolve_reports_menu(selec)
    {
        var params = $('#soa,#balance,#summary,#reteco,#refac,#waiver,#recredi,#recopa,#refi_prov,#redis,#difference');
        params.children().removeClass('h1_reportClick').addClass('h1_report');
        params.css('background', 'white').css('border-bottom', '1px solid silver').css('width', '92%');
        params.removeAttr('style');

        selec.children().removeClass('h1_report').addClass('h1_reportClick');
        selec.css('background', '#2E62B4').css('border-bottom', '1px solid white').css('width', '96%').css('height', '77px');
    }
    /**
     * administra radios
     * @param {type} click
     * @param {type} no_Click
     * @returns {undefined}
     */
    function agrega_Val_radio(click,no_Click)
    {
        var dio_click=click[0].id.substring(0,2);
        $(no_Click).val(''); 
        if (dio_click=='Si'){$(click).val('Si');$(click).blur();}
        else {$(click).val('No');$(click).blur();}
    }
    /**
     * 
     * @param {type} obj
     * @returns {undefined}
     */
    function adminInput(obj)
    {
        if($(obj).val()=='null'){
            $("#id_termino_pago").val("todos");
            $(".label_custom_supplier").html("Termino Pago");
        }else{
            $(".label_custom_supplier").html("Termino Pago "+$("#type_termino_pago option:selected").text());
        } 
    }
    /**
     * Administra elementos para que se muestren u oculten pasandole como variable el .val del elemento, por ahora solo trabaja con termino pago
     * @param {type} obj
     * @returns {undefined}
     */
    function adminTp(obj)
    {
        if($(obj).val()=="1"||$(obj).val()=="3"||$(obj).val()=="4"||$(obj).val()=="5"||$(obj).val()=="todos")
            $(".divide_factura").show("fast");
        else
            $(".divide_factura").hide("fast");
    }
        /**
         * administra inputs de formulario para la pantalla principal tomando como variable el id del elemento seleccionado
         * @param {type} obj
         * @returns {undefined}
         */
    function elijeOpciones(obj)
    {
        var ocultar =[".operador,.grupo,.fecha,.provisiones,.disputas,.vencidas,.intercompany,.termino_pago,.type_termino_pago,.type_termino_pago_sum_re,.termino_pago_sum_re,.termino_pago_refac_reprov,.divide_factura,.no_activity,.car_activity,.chang_Oper_Grup,.chang_Grup_Oper,.periodo,.filter_oper,.order_recopa,.trabajando,.note,.note_ref_pro,.summary_option,#id_termino_pago option[value='todos'],#id_termino_pago option[value='']"],

        nombre=obj[0].id;
        switch (nombre){
            case "soa":
              var mostrar =['.fecha,.grupo,.provisiones,.disputas']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break; 
            case "summary":
              var mostrar =[".fecha,.type_termino_pago,.type_termino_pago_sum_re,.intercompany,.no_activity,.termino_pago,.termino_pago_sum_re,.termino_pago_refac_reprov,#id_termino_pago option[value='todos']"]; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar);
                  $SINE.UI.adminInput($("#type_termino_pago").val("null"));
                  $(".type_termino_pago").addClass("type_termino_pago_sum_re");$(".type_termino_pago_sum_re").removeClass("type_termino_pago");
                  $(".termino_pago,.termino_pago_refac_reprov").addClass("termino_pago_sum_re");$(".termino_pago_sum_re").removeClass("termino_pago termino_pago_refac_reprov");
              break; 
            case "balance":
              var mostrar =['.fecha,.grupo,.disputas,.note']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar);
              break; 
            case "reteco":
              var mostrar =[".type_termino_pago,.car_activity,.type_termino_pago_sum_re,.termino_pago_sum_re,.termino_pago,.termino_pago_refac_reprov,#id_termino_pago option[value='todos']"]; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar);
                  $SINE.UI.adminInput($("#type_termino_pago").val("null"));
                  $(".type_termino_pago_sum_re").addClass("type_termino_pago"),$(".type_termino_pago").removeClass("type_termino_pago_sum_re");
                  $(".termino_pago_sum_re,.termino_pago_refac_reprov").addClass("termino_pago");$(".termino_pago").removeClass("termino_pago_sum_re termino_pago_refac_reprov");
              break; 
            case "refac":
                var mostrar =['.fecha,.periodo,.note_ref_pro,.summary_option']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break;
            case "refi_prov":
                var mostrar =[".fecha,.termino_pago_sum_re,.termino_pago,.termino_pago_refac_reprov,.note_ref_pro,.summary_option,#id_termino_pago option[value='todos']"]; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  $("#id_termino_pago").val("todos");
                  $(".label_custom_supplier").html("Termino Pago");
                  $(".termino_pago_sum_re,.termino_pago").addClass("termino_pago_refac_reprov");$(".termino_pago_refac_reprov").removeClass("termino_pago_sum_re termino_pago");
              break;
            case "waiver":
                var mostrar =['.trabajando']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break; 
            case "recredi":case "difference":
                var mostrar =[".fecha,.intercompany,.no_activity,.termino_pago,.termino_pago_sum_re,.type_termino_pago,.type_termino_pago_sum_re,.termino_pago_refac_reprov,.note,#id_termino_pago option[value='todos']"]; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  $SINE.UI.adminInput($("#type_termino_pago").val("null"));
                  $(".type_termino_pago").addClass("type_termino_pago_sum_re");$(".type_termino_pago_sum_re").removeClass("type_termino_pago");
                  $(".termino_pago,.termino_pago_refac_reprov").addClass("termino_pago_sum_re");$(".termino_pago_sum_re").removeClass("termino_pago termino_pago_refac_reprov");
              break; 
            case "recopa":
                var mostrar =['.fecha,.filter_oper,.vencidas,.order_recopa']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break; 
            case "redis": 
                var mostrar =['.trabajando']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break;
        }
        $("#tipo_report").val(nombre);
        $('.formulario').css('display','block').css('width','64%').css('margin-left','39%');
            $('.barra_tools_click').show("fast");


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
        switch (nombre) {
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
    * metodo encargado de ocultar y mostrar input y demas elementos dependiendo de la opcion seleccionada
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
     * cambia html
     * @param {type} clase
     * @param {type} value
     * @returns {undefined}
     */
    function changeHtml(clase,value)
    {
         $(clase).html(value);
    }
   /**
    * responde al click del boton de email y excel para pasar la data a la funcion send de ajax...
    * @param {type} click
    * @returns {undefined}      * 
    */
    function export_report(click)
    {
        if($SINE.UI.seleccionaCampos($('#tipo_report').val()) == 0)
        {
            $SINE.UI.msjCargando("","");$SINE.UI.msjChange("<h2>Faltan campos por llenar </h2>","stop.png","1000","60px");  
        }else{
            var id=$(click).attr('id');
            if(id=="mail"){ 
                $SINE.UI.msjCargando("<h2>Enviando Email</h2>","cargando.gif");$SINE.AJAX.send("GET","/site/CalcTimeReport",$("#formulario").serialize(),"<h2>Enviando Email</h2>");
                $SINE.AJAX.send("POST","/site/mail",$("#formulario").serialize(),null);
            }
            else if(id=="previa"){    
                $SINE.UI.msjCargando("<h2>Cargando Vista Previa</h2>","cargando.gif");$SINE.AJAX.send("GET","/site/CalcTimeReport",$("#formulario").serialize(),"<h2>Cargando Vista Previa</h2>");
                $SINE.AJAX.send("GET","/site/previa",$("#formulario").serialize(), null);
            }else{   
//                 $SINE.UI.genExcel("/site/Excel",$("#formulario").serialize()); 
                $SINE.AJAX.send("GET","/site/Excel",$("#formulario").serialize(),null); 
                $( document ).ajaxError(function() {
                    $SINE.UI.msjCargando("<h2>Exportando Archivo Excel</h2>","cargando.gif");$SINE.AJAX.send("GET","/site/CalcTimeReport",$("#formulario").serialize(),"<h2>Exportando Archivo Excel </h2>");
                    $SINE.AJAX.send("GET","/site/Excel",$("#formulario").serialize(),null); 
                });
           } 
        }
    }
    /**
     * metodo encargado de guardar las provisiones desde interfaz
     * @param {type} click
     * @returns {undefined}
     */
    function genProvisions(click)
    {
        if($SINE.UI.seleccionaCampos($(click).attr('id')) == 0)
        {
            $SINE.UI.msjCargando("","");$SINE.UI.msjChange("<h2>Debe llenar al menos el periodo </h2>","stop.png","1000","60px");  
        }else{
            $SINE.UI.msjConfirm("<h3>Esta a punto de generar las provisiones para " + $SINE.UI.ifNull($("#group").val(), "<b>TODOS</b> los carriers", "el carrier <b>"+$("#group").val()+"</b>") + " desde la fecha <b>"+$("#datepickerOne").val()+"</b></h3>");
            $('#confirm,#cancel').on('click', function()
            {
                if($(this).attr('id')=="confirm")
                {
                    $SINE.AJAX.provisions("GET","/site/CalcTimeProvisions",$("#datepickerOne,#group").serialize(),"time");
                    $SINE.AJAX.provisions("GET","/site/GenProvisions",$("#datepickerOne,#group").serialize(),"gen");
                }else{
                    $(".fondo_negro, .mensaje").fadeOut();
                }
            });
        }
    }
    /**
    * metodo encargado de seleccionar los campos que se enviaran por formulario dependiendo el tipo de reporte a ccion a realizar
    * @param {type} tipo
    * @returns {unresolved}
    */
    function seleccionaCampos(tipo)
    {  
        switch (tipo){
         case 'soa':
             var respuesta=$SINE.UI.validaCampos($('#grupo').serializeArray());
             break 
         case 'summary':
             var respuesta=$SINE.UI.validaCampos($('#tipo_report,#id_termino_pago').serializeArray());
             break 
         case 'balance':
             var respuesta=$SINE.UI.validaCampos($('#grupo').serializeArray());
             break               
         case 'reteco':
             var respuesta=$SINE.UI.validaCampos($('#datepicker').serializeArray());
             break               
         case 'refac':
             var respuesta=$SINE.UI.validaCampos($('#id_periodo').serializeArray());
             break               
         case 'recredi':
             var respuesta=$SINE.UI.validaCampos($('#tipo_report,#id_termino_pago').serializeArray());
             break               
         case 'difference':
             var respuesta=$SINE.UI.validaCampos($('#tipo_report,#id_termino_pago').serializeArray());
             break               
         case 'recopa':
             var respuesta=$SINE.UI.validaCampos($('#id_filter_oper,#order_recopa').serializeArray());
             break               
         case 'refi_prov':
             var respuesta=$SINE.UI.validaCampos($('#id_termino_pago').serializeArray());
             break               
         case 'redis':
             var respuesta=$SINE.UI.validaCampos($('#id_periodo').serializeArray());
             break               
         case 'genProvision':
             var respuesta=$SINE.UI.validaCampos($('#datepickerOne').serializeArray());
             break               
        }
        console.log(respuesta);
        return respuesta;
    }
    /**
     * valida campos vacios
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
     * genera excel de la forma tradicional, (generando un popup)valido para generar multiples excel en una sola llamada
     * @param {type} action
     * @param {type} formulario
     * @returns {undefined}
     */
    function genExcel(action,formulario)
    {
        window.open(action+"?"+formulario , "gen_excel_SINE" , "width=450,height=150,left=450,top=200");  
    }
    function msjConfirm(body)
    {
        $(".fondo_negro, .mensaje").remove();
        var msj=$("<div class='fondo_negro'></div><div class='mensaje'>"+body+"Si esta de acuerdo, presione Aceptar, de lo contrario Cancelar<div class='confirmButtons'><div id='cancel'class='cancel'>Cancelar</div>&nbsp;<div id='confirm'class='confirm'>Aceptar</div></div></div>").hide(); 
        $("body").append(msj);  msj.fadeIn('slow');
    }
    /**
     * metodo encargado de generar el msj principal de cargando...
     * @param {type} bodyMsj
     * @param {type} img
     * @returns {undefined}
     */
    function msjCargando(bodyMsj,img)
    {
        $(".fondo_negro, .mensaje, .fancybox").remove();
        var msj=$("<div class='fondo_negro'></div><div class='mensaje'>"+bodyMsj+"<p><br><img src='/images/"+img+"'></div>").hide(); 
        $("body").append(msj); 
        msj.fadeIn('slow');
    }
    /**
     * metodo encargado de mostrar vistas traidas por ajax, no tiene opciones, solo muestra lo que se quiera
     * @param {type} body
     * @param {type} additional
     * @returns {undefined}
     */
    function emergingView(body,additional)
    {
        $(".emergingBackground,.emergingView, .fondo_negro, .mensaje, .fancybox").remove();$(".emergingView").html("");
        var msj=$("<div class='emergingBackground'></div><div class='emergingView'>"+body+"</div>").hide(); 
        $("body").append(msj); 
        msj.slideDown('slow');
        $(".emergingBackground").click(function(){  $(".emergingBackground,.emergingView").slideToggle("slow");});
    }
    /**
     * cambia el contenido del msj principal
     * @param {type} bodyMsj
     * @param {type} img
     * @param {type} time
     * @param {type} imgWidth
     * @returns {undefined}
     */
    function msjChange(bodyMsj,img,time,imgWidth)
    {
        $(".mensaje").html(""+bodyMsj+"<p><img style='width:"+imgWidth+";' src='/images/"+img+"'>");
        if(time!=null)
        setTimeout(function() { $(".fondo_negro, .mensaje").fadeOut('slow'); }, time);
    }
    /**
     * muestra los listado en una vista previa, por ahora solo tiene la opcion de imprimir
     * @param {type} body
     * @returns {undefined}
     */
    function fancyBox(body)
    {
        $(".mensaje").addClass("fancybox").removeClass("mensaje");
        $(".fondo_negro").addClass("emergingBackground");
        $(".fancybox").css("display", "none");
        $(".fancybox").fadeIn("slow").html("<div class='imprimir'><img src='/images/print.png'class='ver'></div><div class='a_imprimir'>"+body+"</div>");
        $('.imprimir').on('click',function (){ $SINE.UI.imprimir(".a_imprimir"); });
        $('.fondo_negro').on('click',function () { $(".fondo_negro, .fancybox").removeClass("emergingBackground").fadeOut('slow');});
    }
    /**
     * imprime el fancybox
     * @param {type} div
     * @returns {undefined}
     */
    function imprimir(div)
    {
        var imp,
        contenido=$(div).clone().html();                    //selecciona el objeto
        imp = window.open(" SINE ","Formato de Impresion"); // titulo
        imp.document.open();                                //abre la ventana
        //imp.document.write('style: ...');                 //css
        imp.document.write(contenido);                      //agrega el objeto
        imp.document.close();
        imp.print();                                        //Abre la opcion de imprimir
        imp.close();                                        //cierra la ventana nueva
    };
    /**
     * 
     * @param {type} value
     * @param {type} ifNull
     * @param {type} notNull
     * @returns {undefined}
     */
    function ifNull(value, ifNull, notNull)
    {
        if(value==null||value=="")
           return ifNull
        else
           return notNull
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
            msjCargando:msjCargando,
            msjChange:msjChange,
            genExcel:genExcel,
            validaCampos:validaCampos,
            seleccionaCampos:seleccionaCampos,
            changeHtml:changeHtml,
            fancyBox:fancyBox,
            imprimir:imprimir,
            adminInput:adminInput,
            genProvisions:genProvisions,
            adminTp:adminTp,
            emergingView:emergingView,
            GenDatepicker:GenDatepicker,
            msjConfirm:msjConfirm,
            ifNull:ifNull
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
    function getFormPost(id)
    {
        return $(id).serializeArray();
    }
    /**
     * Crea un array con los nombres de carrier y los grupos de carriers
     * @access private
     */
    function getNamesCarriers()
    {
        $.ajax({url:"../Grupos/Nombres",success:function(datos)
        {
                $SINE.DATA.groups=JSON.parse(datos);
                $SINE.DATA.nombresGroups=Array();
                for(var i=0, j=$SINE.DATA.groups.length-1; i<=j; i++)
                {
                        $SINE.DATA.nombresGroups[i]=$SINE.DATA.groups[i].name;
                };$('input#grupo, input#group').autocomplete({source:$SINE.DATA.nombresGroups});
        }
        });
    }
    /**
     * Inicializa las funciones del submodulo
     * @access public
     */
    function init()
    {
            _updateTerminoPago();
    }
    /**
    * funcion encargada de pasar datos del formulario al componente para enviarse por correo o exportarse a excel
    * @param {type} type
    * @param {type} action
    * @param {type} formulario
    * @param {type} msjTime
    * @returns {undefined}
    */
    function send(type,action,formulario,msjTime)
    {
        $.ajax({
             type: type,
             url: action,
             data: formulario,
             success: function(data)
             {  
                 if(msjTime != null){   /*consulta el tiempo estimado para generar los reportes y lo muestra cambiando el msj actual confirm, por ahora solo tiene impacto sobre recredi y summary*/
                    $SINE.UI.msjChange(msjTime + data,"cargando.gif",null,"80%"); 
                 }else{
                    if(action=="/site/Excel"){         
                        $SINE.UI.msjChange("<h2>Descarga completada con exito</h2>","si.png","1500","33%");  
                        $(".excel_a").removeAttr("href");
                        console.log("Descarga Exitosa");
                    }else if(action=="/site/previa"){ 
                        $SINE.UI.fancyBox(data);
                        console.log("Vista Previa Exitosa");
                    }else{                              
                        $SINE.UI.msjChange("<h2>"+data+" con exito</h2>","si.png","1000","33%"); 
                        console.log(data);
                    }  
                 }
             }
        });
    }
    /**
     * busca la vista de provisiones y guarda las provisiones desde interfaz
     * @param {type} type
     * @param {type} action
     * @param {type} formulario
     * @param {type} request
     * @returns {undefined}
     */
    function provisions(type,action,formulario, request)
    {
        $.ajax({
             type: type,
             url: action,
             data: formulario,
             success: function(data)
             {     
                 if(formulario==null){        /*Busca la vista de provisiones para mostrarla en el div emergingView*/
                     $SINE.UI.emergingView(data);
                 }else{              
                     if(request == "time"){   /*consulta el tiempo estimado para generar las consultas y lo muestra cambiando el msj actual confirm*/
                         $SINE.UI.msjChange("<h2>Se están generando las provisiones</h2><h3> este proceso puede tomar <b>"+data+"</b></h3> no cierre su navegador durante ese tiempo","cargando.gif",null,"70%"); 
                     }else{                   /*manda la los datos al metodo encargado de generar las provisiones, espera que el mismo termine para cerrar el msj indicando que las provisiones fueron generadas*/
                         console.log(data);
                         $SINE.UI.msjChange("<h2>Provisiones Generadas con exito</h2>","si.png","1000","33%");  
                     }
                 }
             }
        });
    }
    /**
     * carga el listado de termino pago 
     * @returns {undefined}
     */
    function _updateTerminoPago()
    {
        $.ajax({url:"/site/UpdateTerminoPago",success:function(data)
            {
                $("#id_termino_pago").append(data);
            }
        });
    }

    return {init:init,
            getNamesCarriers:getNamesCarriers,
            getFormPost:getFormPost,
            send:send,
            provisions:provisions
           }
})();

$SINE.DATA={};

$SINE.constructor=(function()
 {
    $SINE.UI.init();
 })();