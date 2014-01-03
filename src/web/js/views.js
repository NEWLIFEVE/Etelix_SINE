  
  $(document).on('ready',function()
  {
        $SINE.AJAX.init();
  });
  /**
   * funcion encargada de cambiar estilos y comportamiento de menu principal de reportes
   * @access public
   */
  $('#soa,#balance,#refac,#waiver,#recredi,#refi_prov').on('click',function()
  {
        $SINE.UI.resolve_reports_menu($(this));
        $SINE.UI.elijeOpciones($(this));
  });
  /**
   * responde al click de los botones para exportar reportes
   */
  $('#mail, #excel').on('click',function()
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
  $('#No_prov, #Si_prov').on('click',function()
  {   
        $SINE.UI.agrega_Val_radio($(this),$('#No_prov, #Si_prov'));
  });
  /**
   * da valor a los radios de disputas en reportes
   */
  $('#No_disp, #Si_disp').on('click',function()
  {   
        $SINE.UI.agrega_Val_radio($(this),$('#No_disp, #Si_disp'));
  });