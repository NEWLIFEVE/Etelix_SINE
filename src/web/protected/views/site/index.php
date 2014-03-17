<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
$this->pageTitle = Yii::app()->name;
?>

<article class='titulo90'>
            SINE 
</article>
    
<div class='subtitulos'><h2>Seleccione reporte</h2></div>

<div class="listadeReportesscroll">
    <div class="listadeReportes">
        <div class="Reportes SOA" id="soa">
            <H1 class='h1_report h1SOA'>S O A</H1> 
        </div><br>
        <div class="Reportes summary" id="summary">
            <H1 class='h1_report h1Summary'>SUMMARY</H1> 
        </div><br>
        <div class="Reportes BALANCE" id="balance">
            <h1 class='h1_report h1BALANCE'>BALANCE</h1>
        </div><br>
        <div class="Reportes reteco" id="reteco">
            <h1 class='h1_report h1reteco'>RETECO</h1>
        </div><br>
        <div class="Reportes REFAC" id="refac">
            <h1 class='h1_report h1REFAC'>REFAC</h1>
        </div><br>
        <div class="Reportes REFI_PROV" id="refi_prov">
            <h1 class='h1_report h1REFI_PROV'>REPROV</h1>
        </div><br>

        <div class="Reportes RECREDI" id="recredi">
            <h1 class='h1_report h1RECREDI'>RECREDI</h1>
        </div><br>
<!--        <div class="Reportes RECOPA" id="recopa">
            <h1 class='h1_report h1RECOPA'>RECOPA</h1>
        </div><br> -->

<!--        <div class="Reportes WAIVER" id="waiver">
            <h1 class='h1_report h1WAIVER'>WAIVER</h1>
        </div><br>
        <div class="Reportes REDIS" id="redis">
            <h1 class='h1_report h1REDIS'>REDIS</h1>
        </div><br>-->
    </div>
</div>
<div>
<form id="formulario">
    <div name="formulario"  class="formulario">
        <input name="tipo_report" id="tipo_report"type="hidden"value=""/>
        <div class="formInputs operador">
            <h3>Operador</h3>
            <input type="text" name="operador" id="operador"value=""/>
        </div>
        <div class="formInputs grupo">
            <h3>Grupo</h3>
            <input type="text" name="grupo" id="grupo"value=""/>
        </div>
        <div id="chang_Oper_Grup" class='formInputs chang_Oper_Grup Oper_grupo'>
            <img src="/images/operador.png" class='ver'>
            <img src="/images/operador_hover.png" title='oculta el input de grupo y muestra el de operador' class='oculta'>
        </div>
        <div id="chang_Grup_Oper" class='formInputs chang_Grup_Oper Oper_grupo'>
            <img src="/images/grupo.png" class='ver'>
            <img src="/images/grupo_hover.png" title='oculta el input de operador y muestra el de grupo' class='oculta'>
        </div>
        <div class="formInputs fecha">
            <h3>Fecha</h3>
            <input type="text" name="datepicker" id="datepicker" value="<?php echo date('Y-m-d');?>"/>
        </div>
        <div class="formInputs filter_oper">
            <h3>Mostrar Operadores</h3>
            <select name="id_filter_oper" id="id_filter_oper">
            <option value="">Seleccione</option>
            <option value="0">TODOS</option>
            <option value="1">+2000$</option>
            <option value="2">-2000$</option>
            </select> 
        </div>
        <div class="formInputs order_recopa">
            <h3>Ordenar</h3>
            <select name="order_recopa" id="order_recopa">
            <option value="">Seleccione</option>
            <option value="0">Alfabéticamente</option>
            <option value="1">Mayor a menor</option>
            </select> 
        </div>
        <div class="formInputs periodo">
            <h3>Período</h3>
            <select name="id_periodo" id="id_periodo">
            <option value="">Seleccione</option>
            <option value="7">SEMANAL</option>
            <option value="15">QUINCENAL</option>
            <option value="30">MENSUAL</option>
            </select> 
        </div>
        <div class="formInputs termino_pago">
            <h3>Termino Pago</h3>
            <select name="id_termino_pago" id="id_termino_pago">
            <option value="">Seleccione</option>
            </select> 
        </div>
        
        <div class='formInputs provisiones'>
            <h3 class="h_prov">Provision Fact</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <input name="Si_prov" id="Si_prov" type="text" placeholder="Si" value=""class="btn btn-primary">Si</input>
                <input name="No_prov" id="No_prov" placeholder="No" type="text" value=""class="btn btn-primary">No</input>
            </div>  
        </div> 
        <div class='formInputs disputas'>
            <h3>Disputas</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <input name="Si_disp" id="Si_disp" type="text" placeholder="Si" value=""class="btn btn-primary">Si</input>
                <input name="No_disp" id="No_disp" placeholder="No" type="text" value=""class="btn btn-primary">No</input>
            </div>  
        </div>
        <div class='formInputs vencidas'>
            <h3>Venc +2sem</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <input name="Si_venc" id="Si_venc" type="text" placeholder="Si" value=""class="btn btn-primary">Si</input>
                <input name="No_venc" id="No_venc" placeholder="No" type="text" value=""class="btn btn-primary">No</input>
            </div>  
        </div>
        <div class='formInputs no_activity'>
            <h3>Sin actividad</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <input name="Si_act" id="Si_act" type="text" placeholder="Si" value=""class="btn btn-primary">Si</input>
                <input name="No_act" id="No_act" placeholder="No" type="text" value=""class="btn btn-primary">No</input>
            </div>  
        </div>
        <div class='formInputs intercompany'>
            <h3>Intercompañia</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <input name="Si_inter" id="Si_inter" type="text" placeholder="Si" value=""class="btn btn-primary">Si</input>
                <input name="No_inter" id="No_inter" placeholder="No" type="text" value=""class="btn btn-primary">No</input>
            </div>  
        </div>
        
<!--        <div class='formInputs pronostico'>
            <h3>Pronostico</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <input name="Si_pron" id="Si_disp" type="text" placeholder="Si" value=""class="btn btn-primary">Si</input>
                <input name="No_pron" id="No_disp" placeholder="No" type="text" value=""class="btn btn-primary">No</input>
            </div>  
        </div>-->
        <!--ESTO HAY QUE QUITARLO CUANDO YA TODOS LOS TIPOS DE REPORTES FUNCIONEN-->
        <div class="trabajando"><img src="/images/trabajando.png" class='ver'><h2>Estamos trabajando...</h2></div><!--este div es para indicar que la interfaz no esta lista-->
        <!--.................-->
    </div>
</form>
</div>
<div class="barra_tools_click">
    <footer id="botones_exportar">
        <div id="previa" class="botones">
                <img src="/images/previa.png" title='Vista previa del reporte'>
        </div>
        <div id="excel" class="botones">
            <a class="excel_a">
               <img src="/images/excel.png" title='Exportar Reportes en Excel'>
            </a>
        </div>
        <div id="mail" class="botones">
            <img src="/images/mail.png" title='Enviar Reportes a su Correo Electronico'>
        </div>
    </footer>
</div>
<div class="views_not" id="views_not"><h4 class="h1_views_not">la vista previa no esta disponible en esta resolucion...</h4></div>