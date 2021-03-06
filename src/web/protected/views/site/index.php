<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
$this->pageTitle = Yii::app()->name;
?>

<article class='titulo90'>SINE</article>
<div class='subtitulos'>
    <h2>Seleccione reporte</h2>
</div>
<div class="listadeReportesscroll">
    <div class="listadeReportes">
        <div class="Reportes SOA" id="soa">
            <h1 class='h1_report h1SOA'>S O A</h1>
        </div>
        <br>
        <div class="Reportes summary" id="summary">
            <h1 class='h1_report h1Summary'>SUMMARY</h1> 
        </div>
        <br>
        <div class="Reportes BALANCE" id="balance">
            <h1 class='h1_report h1BALANCE'>BALANCE</h1>
        </div>
        <br>
        <div class="Reportes reteco" id="reteco">
            <h1 class='h1_report h1reteco'>RETECO</h1>
        </div>
        <br>
        <div class="Reportes REFAC" id="refac">
            <h1 class='h1_report h1REFAC'>REFAC</h1>
        </div>
        <br>
        <div class="Reportes REFI_PROV" id="refi_prov">
            <h1 class='h1_report h1REFI_PROV'>REPROV</h1>
        </div>
        <br>
        <div class="Reportes RECREDI" id="recredi">
            <h1 class='h1_report h1RECREDI'>RECREDI</h1>
        </div>
        <br>
        <div class='Reportes DIFFERENCE' id='difference'>
            <h1 class='h1_report h1DIFFERENCE'>DIFFERENCE</h1>
        </div>
        <br>
        <div class='Reportes DESEG' id='dsReport'>
            <h1 class='h1_report h1DSREPORT'>REDS</h1>
        </div>
        <br>
        <?php 
//            echo SiteController::accessControl(Yii::app()->user->id,
//            "<br>
//            <div class='Reportes DIFFERENCE' id='difference'>
//                <h1 class='h1_report h1DIFFERENCE'>DIFFERENCE</h1>
//            </div>"); 
        ?>
<!--        <div class="Reportes RECOPA" id="recopa">
            <h1 class='h1_report h1RECOPA'>RECOPA</h1>
        </div>
        <br>-->
        <!--<div class="Reportes WAIVER" id="waiver">
            <h1 class='h1_report h1WAIVER'>WAIVER</h1>
        </div>
        <br>-->
        <!--<div class="Reportes REDIS" id="redis">
            <h1 class='h1_report h1REDIS'>REDIS</h1>
        </div>
        <br>-->
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
            <div class="note_ref_pro">
                <!--<h3 class="h3_note instructive">Seleccione el ultimo dia del ciclo</h3>-->
            </div>
            <div class="formInputs filter_oper">
                <h3>Mostrar Operadores</h3>
                <select name="id_filter_oper" id="id_filter_oper">
                    <option value="">Seleccione</option>
                    <option value="0">Todos</option>
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
                    <option value='todos'>Todos</option>
                    <option value="7">Semanal</option>
                    <option value="15">Quincenal</option>
                    <option value="30">Mensual</option>
                </select>
            </div>
            <div class="formInputs type_termino_pago">
                <h3>Relación Comercial</h3>
                <select name="type_termino_pago" id="type_termino_pago">
                    <option value=null>Ambos</option>
                    <option value="0">Customer</option>
                    <option value="1">Supplier</option>
                </select>
            </div>
            <div class="formInputs termino_pago">
                <h3 class="label_custom_supplier">Termino Pago</h3>
                <select name="id_termino_pago" id="id_termino_pago">
                    <option value="">Seleccione</option>
                </select>
            </div>
            <div class="formInputs summary_option">
                <h3>Mostrar Summary</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_sum" id="Si_sum" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_sum" id="No_sum" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>
            </div>
            <div class="formInputs divide_factura">
                <h3 class="label_divided">Mostrar carriers que dividen por mes</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_div" id="Si_div" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_div" id="No_div" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>
            </div>
            <div class='formInputs provisiones'>
                <h3 class="h_prov">Provision Fact</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_prov" id="Si_prov" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_prov" id="No_prov" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>
            </div>
            <div class='formInputs disputas'>
                <h3>Disputas</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_disp" id="Si_disp" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_disp" id="No_disp" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>
            </div>
            <div class='formInputs segRetainer'>
                <h3>DS en curso de reporte</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_segRet" id="Si_segRet" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_segRet" id="No_segRet" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>
            </div>
            <div class='formInputs vencidas'>
                <h3>Venc +2sem</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_venc" id="Si_venc" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_venc" id="No_venc" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>
            </div>
            <div class='formInputs no_activity'>
                <h3>Carrier Sin Mov 6M</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_act" id="Si_act" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_act" id="No_act" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>  
            </div>
            <div class='formInputs car_activity'>
                <h3>Carrier Inactivos</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_car_act" id="Si_car_act" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_car_act" id="No_car_act" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>  
            </div>
            <div class='formInputs intercompany'>
                <h3>Intercompañia</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_inter" id="Si_inter" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_inter" id="No_inter" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>  
            </div>
            <div class='formInputs matches'>
                <h3>Carrier sin coincidencias en billing</h3>
                <div class="btn-group" data-toggle="buttons-radio">
                    <input name="Si_matches" id="Si_matches" type="text" placeholder="Si" value=""class="btn btn-primary">
                    <input name="No_matches" id="No_matches" placeholder="No" type="text" value=""class="btn btn-primary">
                </div>  
            </div>
            <div class='formInputs note'>
                <h3 class="h3_note">Este reporte esta en etapa de prueba y algunos datos podrian no ser confiables...</h3>  
            </div>
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
<div class="views_not" id="views_not">
    <h1 class="h1_views_not">la vista previa no esta disponible en esta resolucion...</h1>
</div>
<input type="hidden" name="timeHide" id="timeHide" value="<?php echo date('Y-m-d');?>"/>