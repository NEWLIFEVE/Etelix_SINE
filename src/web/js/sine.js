$(document).on('ready',function()
{
     $SINE.AJAX.init();
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
        $('#No_inter,#No_act,#No_car_act,#No_prov').val('No').addClass("active");
        $('#Si_disp').val('Si').addClass("active");
    }
    /**
     * datepicker jquery ui
     * @returns {undefined}
     */
    function _datepicker() 
    {
        $( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D"});
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
                    if($("#type_termino_pago").val()=='null')
                        $SINE.UI.adminInput(("#type_termino_pago"));
                    
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
        $('#showProvisions,#soa,#balance,#summary,#reteco,#refac,#waiver,#recredi,#recopa,#refi_prov,#redis,#No_prov,#Si_prov,#No_disp,#Si_disp,#No_venc,#Si_venc,#No_inter,#Si_inter,#No_act,#Si_act,#No_car_act,#Si_car_act,#previa,#mail,#excel,#views_not,#genProvision').on('click',function()
        {   
            switch ($(this).attr("id")){
                case "soa":case"balance": case"reteco": case"refac":case "refi_prov":case "waiver":case"recredi":case"recopa": case"redis": case"summary":
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
                case "previa": case "mail": case "excel": 
                    $SINE.UI.export_report($(this));
                    break;
                case "genProvision": 
                    $SINE.UI.genProvisions($(this));
                    break;
                case "showProvisions": 
                    
                    $SINE.UI.msj_cargando("","");$SINE.UI.fancy_box($(".viewsProvisions").html());  
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
     * administra el menu vertical
     * @param {type} selec
     * @returns {undefined}
     */
    function resolve_reports_menu(selec)
    {
        var params = $('#soa,#balance,#summary,#reteco,#refac,#waiver,#recredi,#recopa,#refi_prov,#redis');
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
        var dio_click=click[0].id;
        $(no_Click).val(''); 
        if (dio_click=='Si_prov'||dio_click=='Si_disp'||dio_click=='Si_venc'||dio_click=='Si_act'||dio_click=='Si_car_act'||dio_click=='Si_inter'){$(click).val('Si');$(click).blur();}
        else {$(click).val('No');$(click).blur();}
    }
    /**
     * 
     * @param {type} obj
     * @returns {undefined}
     */
    /*********/
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
         * administra inputs de formulario para la pantalla principal
         * @param {type} obj
         * @returns {undefined}
         */
    function elijeOpciones(obj)
    {
        var ocultar =['.operador,.grupo,.fecha,.provisiones,.disputas,.vencidas,.intercompany,.termino_pago,.type_termino_pago,.type_termino_pago_sum_re,.termino_pago_sum_re,.no_activity,.car_activity,.chang_Oper_Grup,.chang_Grup_Oper,.periodo,.filter_oper,.order_recopa,.trabajando,.note'],
        nombre=obj[0].id;
        switch (nombre){
            case "soa":
              var mostrar =['.fecha,.grupo,.provisiones,.disputas']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break; 
            case "summary":
              var mostrar =['.fecha,.type_termino_pago,.type_termino_pago_sum_re,.intercompany,.no_activity,.termino_pago,.termino_pago_sum_re']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar);
                  $("#type_termino_pago").val("null");
                  $SINE.UI.adminInput($('#type_termino_pago'));
                  $(".type_termino_pago").addClass("type_termino_pago_sum_re");$(".type_termino_pago_sum_re").removeClass("type_termino_pago");
                  $(".termino_pago").addClass("termino_pago_sum_re");$(".termino_pago_sum_re").removeClass("termino_pago");
              break; 
            case "balance":
              var mostrar =['.fecha,.grupo,.disputas,.note']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar);
              break; 
            case "reteco":
              var mostrar =['.type_termino_pago,.car_activity,.type_termino_pago_sum_re,.termino_pago_sum_re,.termino_pago']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar);
                  $("#type_termino_pago").val("null");
                  $SINE.UI.adminInput($('#type_termino_pago'));
                  $(".type_termino_pago_sum_re").addClass("type_termino_pago"),$(".type_termino_pago").removeClass("type_termino_pago_sum_re");
                  $(".termino_pago_sum_re").addClass("termino_pago");$(".termino_pago").removeClass("termino_pago_sum_re");
              break; 
            case "refac":
                var mostrar =['.periodo,.fecha']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break; 
            case "waiver":
                var mostrar =['.trabajando']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break; 
            case "recredi":
                var mostrar =['.fecha,.intercompany,.no_activity,.termino_pago,.termino_pago_sum_re,.type_termino_pago,.type_termino_pago_sum_re,.note']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
                  $("#type_termino_pago").val("null");
                  $SINE.UI.adminInput($('#type_termino_pago'));
                  $(".type_termino_pago").addClass("type_termino_pago_sum_re");$(".type_termino_pago_sum_re").removeClass("type_termino_pago");
                  $(".termino_pago").addClass("termino_pago_sum_re");$(".termino_pago_sum_re").removeClass("termino_pago");
              break; 
            case "recopa":
                var mostrar =['.fecha,.filter_oper,.vencidas,.order_recopa']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break; 
            case "refi_prov": 
                var mostrar =['.periodo,.fecha']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break;
            case "redis": 
                var mostrar =['.trabajando']; 
                  $SINE.UI.formChangeAccDoc(ocultar, mostrar); 
              break;
        }
        $("#tipo_report").val(nombre);
        $('.formulario').css('display','block').css('width','81%').css('margin-left','39%');
//            $('.barra_tools_click').show();

        //ESTO HAY QUE QUITARLO CUANDO YA TODOS LOS TIPOS DE REPORTES FUNCIONEN
        if(nombre=="soa"||nombre=="balance"||nombre=="summary"||nombre=="reteco"||nombre=="refac"||nombre=="refi_prov"||nombre=="recredi"||nombre=="recopa")
        {
            $('.barra_tools_click').show('fast');
        }else{
            $('.barra_tools_click').hide('fast');
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
            $SINE.UI.msj_cargando("","");$SINE.UI.msj_change("<h2>Faltan campos por llenar </h2>","stop.png","1000","60px");  
        }else{
            var id=$(click).attr('id');
            if(id=="mail"){    
                $SINE.AJAX.send("POST","/site/mail",$("#formulario").serialize());
                $SINE.UI.msj_cargando("<h2>Enviando Email</h2>","cargando.gif");
             }
            else if(id=="previa"){    
                $SINE.AJAX.send("GET","/site/previa",$("#formulario").serialize());
                $SINE.UI.msj_cargando("<h2>Cargando Vista Previa</h2>","cargando.gif");
             }else{                                            
                  $SINE.AJAX.send("GET","/site/Excel",$("#formulario").serialize()); 
                  $( document ).ajaxError(function() {
                      $SINE.UI.msj_cargando("<h2>Exportando Archivo Excel </h2>","cargando.gif");
                      $SINE.AJAX.send("GET","/site/Excel",$("#formulario").serialize()); 
                  });
                  } 
        }
    }
    function genProvisions(click)
    {
        if($SINE.UI.seleccionaCampos($(click).attr('id')) == 0)
        {
            $SINE.UI.msj_cargando("","");$SINE.UI.msj_change("<h2>Debe llenar al menos el periodo </h2>","stop.png","1000","60px");  
        }else{
              console.log("ya esta listo para el ajax");
                $SINE.AJAX.provisions("GET","/site/GenProvisions",$("#formProvisions").serialize());
//                $SINE.UI.msj_cargando("<h2>Enviando Email</h2>","cargando.gif");

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
         case 'recopa':
             var respuesta=$SINE.UI.validaCampos($('#id_filter_oper,#order_recopa').serializeArray());
             break               
         case 'refi_prov':
             var respuesta=$SINE.UI.validaCampos($('#id_periodo').serializeArray());
             break               
         case 'redis':
             var respuesta=$SINE.UI.validaCampos($('#id_periodo').serializeArray());
             break               
         case 'genProvision':
             var respuesta=$SINE.UI.validaCampos($('#fromDate,#toDate').serializeArray());
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
        var msj=$("<div class='fondo_negro'></div><div class='mensaje'>"+cuerpo_msj+"<p><br><img src='/images/"+imagen+"'></div>").hide(); 
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
        $(".mensaje").html(""+cuerpo_msj+"<p><img style='width:"+img_width+";' src='/images/"+imagen+"'>");
        setTimeout(function() { $(".fondo_negro, .mensaje").fadeOut('slow'); }, tiempo);
    }
    /**
     * 
     * @param {type} cuerpo
     * @returns {undefined}
     */
    function fancy_box(cuerpo)
    {
        $(".mensaje").addClass("fancybox").removeClass("mensaje");
        $(".fancybox").css("display", "none");
        $(".fancybox").fadeIn("slow").html("<div class='imprimir'><img src='/images/print.png'class='ver'></div><div class='a_imprimir'>"+cuerpo+"</div>");
        $('.imprimir').on('click',function (){ $SINE.UI.imprimir(".a_imprimir"); });
        $('.fondo_negro').on('click',function () { $(".fondo_negro, .fancybox").fadeOut('slow');});
    }
    /**
     * 
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
            seleccionaCampos:seleccionaCampos,
            changeHtml:changeHtml,
            fancy_box:fancy_box,
            imprimir:imprimir,
            adminInput:adminInput,
            genProvisions:genProvisions
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
//            $.ajax({url:"../Carrier/Nombres",success:function(datos)
//            {
//                    $SINE.DATA.carriers=JSON.parse(datos);
//                    $SINE.DATA.nombresCarriers=Array();
//                    for(var i=0, j=$SINE.DATA.carriers.length-1; i<=j; i++)
//                    {
//                            $SINE.DATA.nombresCarriers[i]=$SINE.DATA.carriers[i].name;
//                    };$('input#operador').autocomplete({source:$SINE.DATA.nombresCarriers});
//            }
//            });
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
//                _updateFactPeriod();
            _UpdateTerminoPago();
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
//                     console.log(data);
                 if(action=="/site/Excel"){         
                     $SINE.UI.msj_change("<h2>Descarga completada con exito</h2>","si.png","1500","33%");  
                     $(".excel_a").removeAttr("href");
                     console.log("Descarga Exitosa");
                 }else if(action=="/site/previa"){ 
                     $SINE.UI.fancy_box(data);
                     console.log("Vista Previa Exitosa");
                 }else{                              
                     $SINE.UI.msj_change("<h2>"+data+" con exito</h2>","si.png","1000","33%"); 
                     console.log(data);
                 }    
             }
        });
    }
    function provisions(type,action,formulario)
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
//        function _updateFactPeriod()
//        {
//            $.ajax({url:"/site/updateFactPeriod",success:function(data)
//                {
//                    console.log(data);
//                    $("#id_periodo_Supp").append(data);
//                }
//            });
//        }
    function _UpdateTerminoPago()
    {
        $.ajax({url:"/site/UpdateTerminoPago",success:function(data)
            {
                $("#id_termino_pago").append(data);
            }
        });
    }

    return {init:init,
            _getFormPost:_getFormPost,
            send:send,
            provisions:provisions
           }
})();

$SINE.DATA={};

$SINE.constructor=(function()
 {
    $SINE.UI.init();
 })();
 
//            /** se usaria en caso de ser necesario cambiar carrier-groups
//            * cambia operador por grupo  grupo por operador
//            */
//           $('#chang_Oper_Grup,#chang_Grup_Oper').on('click',function()
//           {   
//                 $SINE.UI.resolvedButton($(this));
//           });
