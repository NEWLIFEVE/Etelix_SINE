  
  $(document).on('ready',function()
  {
        $SINE.AJAX.init();
  });
  /**
   * funcion encargada de cambiar estilos y comportamiento de menu principal de reportes
   * @access public
   */
  $('#soa,#balance,#refac,#waiver,#recredi,#refi_prov,#redis').on('click',function()
  {
        $SINE.UI.resolve_reports_menu($(this));
        $SINE.UI.elijeOpciones($(this));
  });
  /**
   * responde al click de los botones para exportar reportes
   */
  $('#previa,#mail, #excel').on('click',function()
  {
        $SINE.UI.export_report($(this));
  });
  /**
  * 
  * @returns {undefined}
  */
  $(function() 
  {
        $( "#datepicker" ).datepicker({ dateFormat: "yy-mm-dd", maxDate: "-0D"});
  }); 
  /**
   * cambia operador por grupo  grupo por operador
   */
  $('#chang_Oper_Grup,#chang_Grup_Oper').on('click',function()
  {   
        $SINE.UI.resolvedButton($(this));
  });
  /**
   * da valor a los radios de provisiones en reportes
   */
  $('#No_prov, #Si_prov,#No_disp, #Si_disp').on('click',function()
  {   
      switch ($(this).attr("id")){
            case "No_prov": case "Si_prov": 
                $SINE.UI.agrega_Val_radio($(this),$('#No_prov, #Si_prov'));
                break;
            case "No_disp": case "Si_disp": 
                $SINE.UI.agrega_Val_radio($(this),$('#No_disp, #Si_disp'));
                break;
        }   
  });
  /**
   * da valor a los radios de disputas en reportes
   */
  $('.views_not').on('click',function()
  {
       $(this).remove();
  });
