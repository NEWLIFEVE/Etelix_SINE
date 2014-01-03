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
    
<div class='subtitulos'><h1>Seleccione reporte</h1></div>

<div class="listadeReportesscroll">
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
        <!--        <div class="formInputs termino_pago">
            <h3>Termino Pago</h3>
            <select name="id_termino_pago" id="id_termino_pago">
            <option value="">Seleccione</option>
            <option value="1">P-Semanales</option>
            <option value="2">P-Mensuales</option>
            <option value="3">7/3</option>
            <option value="4">7/5</option>
            <option value="5">7/7</option>
            <option value="6">15/5</option>
            <option value="7">15/7</option>
            <option value="8">15/15</option>
            <option value="9">30/7</option>
            <option value="10">30/30</option>
            </select> 
        </div>-->
        <div class="formInputs termino_pago">
            <h3>Periódo</h3>
            <select name="id_termino_pago" id="id_termino_pago">
            <option value="">Seleccione</option>
            <option value="3">SEMANAL</option>
            <option value="6">QUINCENAL</option>
            <option value="10">MENSUAL</option>
            </select> 
        </div>
        <div class='formInputs provisiones'>
            <h3>Provisiones</h3>
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
        <!--ESTO HAY QUE QUITARLO CUANDO YA TODOS LOS TIPOS DE REPORTES FUNCIONEN-->
        <div class="trabajando"><img src="/images/trabajando.png" class='ver'><h2>Estamos trabajando...</h2></div><!--este div es para indicar que la interfaz no esta lista-->
        <!--.................-->
    </div>
</form>
</div>
<div class="barra_tools_click">
    <footer id="botones_exportar">
        <div id="excel" class="botones">
            <a class="excel_a">
               <img src="/images/excel.png" class='ver'>
               <img src="/images/excel_hover.png" title='Exportar Reportes en Excel' class='oculta'>  
            </a>
        </div>

        <div id="mail" class="botones">
            <img src="/images/mail.png" class='ver'>
            <img src="/images/mail_hover.png" title='Enviar Reportes a su Correo Electronico' class='oculta'>
        </div>
    </footer>
</div>