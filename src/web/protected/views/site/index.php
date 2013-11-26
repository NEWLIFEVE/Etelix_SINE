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
    
<div class='subtitulos'><h1>Seleccione el reporte</h1></div>

<div class="listadeReportes">
    <div class="Reportes SOA" id="soa">
        <H1 class='h1_report h1SOA'>S O A</H1> 
    </div><br>
    
    <div class="Reportes BALANCE" id="balance">
        <h1 class='h1_report h1BALANCE'>Balance</h1>
    </div><br>
    
    <div class="Reportes REFAC" id="refac">
        <h1 class='h1_report h1REFAC'>REFAC</h1>
    </div><br>
    
    <div class="Reportes WAIVER" id="waiver">
        <h1 class='h1_report h1WAIVER'>WAIVER</h1>
    </div><br>
    
    <div class="Reportes RECREDI" id="recredi">
        <h1 class='h1_report h1RECREDI'>RECREDI</h1>
    </div><br>
    
    <div class="Reportes REFI_PROV" id="refi_prov">
        <h1 class='h1_report h1REFI_PROV'>REFI PROV</h1>
    </div><br>
</div>
<form id="form_report_sine">
    <div  class="formulario">
        <div class="formInputs operador">
            <h3>Operador</h3>
            <input type="text" id="operador"value=""/>
        </div>
        <div class="formInputs grupo">
            <h3>Grupo</h3>
            <input type="text" id="grupo"value=""/>
        </div>
        <div class='formInputs chang_Oper_Grup Oper_grupo'>
            <img src="/images/operador.png" class='ver'>
            <img src="/images/operador_hover.png" title='oculta el input de grupo y muestra el de operador' class='oculta'>
        </div>
        <div class='formInputs chang_Grup_Oper Oper_grupo'>
            <img src="/images/grupo.png" class='ver'>
            <img src="/images/grupo_hover.png" title='oculta el input de operador y muestra el de grupo' class='oculta'>
        </div>
        <div class="formInputs fecha">
            <h3>Fecha</h3>
            <input type="text" id="datepicker" />
        </div>
        <div class='provisiones'>
            <h3>Provisiones</h3>
            <div class="btn-group" data-toggle="buttons-radio">
                <button name="Si_prov" id="Si_prov" type="button" value=""class="btn btn-primary">Si</button>
                <button name="No_prov" id="No_prov" type="button" value=""class="btn btn-primary">No</button>
            </div>  
        </div>
    </div>
</form>
<div class="barra_tools_click">
    <!--<h7>Opciones</h7>-->
<!--    <div id="next_tool">
        <img src="/images/next_tool.png" >
    </div>-->
    <footer id="botones_exportar">
        <div id="excel" class="botones">
            <img src="/images/excel.png" class='ver'>
            <img src="/images/excel_hover.png" title='Exportar Reportes en Excel' class='oculta'>
        </div>

        <div id="mail" class="botones">
            <img src="/images/mail.png" class='ver'>
            <img src="/images/mail_hover.png" title='Enviar Reportes a su Correo Electronico' class='oculta'>
        </div>
    </footer>
</div>
