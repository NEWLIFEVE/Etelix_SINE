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
   * funcion encargada de mostrar y esconder los botones excel, mail, etc en la barra de herramientas
   */
//  $('.barra_tools,.barra_tools_click').on('click',function()
//  {
//        $SINE.UI._show_hide_tools($(this));
//  });
  /**
  * 
  * @returns {undefined}
  */
  $(function() {
        $( "#datepicker" ).datepicker();
  });
  /**
   * cambia operador por grupo  grupo por operador
   */
  $('.chang_Oper_Grup').on('click',function()
  {
        $('.chang_Grup_Oper,.operador').show('fast');
        $('.chang_Oper_Grup,.grupo').hide('fast');
  });
  $('.chang_Grup_Oper').on('click',function()
  {
        $('.chang_Oper_Grup,.grupo').show('fast');
        $('.chang_Grup_Oper,.operador').hide('fast');
  });
    
    
    
    
    
