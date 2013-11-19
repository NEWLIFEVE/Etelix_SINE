<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
$this->pageTitle = Yii::app()->name;
?>
<div class="row">
    <div id="capa">
        <div id="barrablanca">
        </div>
        <div class="reportes">
            <h1>RUTINARIOS
                <a id="flecha-forward" href="/site/rutinarios"  rel="tooltip" title="esta es la consulta basica por hora y fecha" class="tooltip-test ">></a>
            </h1>
        </div>
        <div class="reportes">
            <h1> ESPECIFICOS
                <a id="flecha-forward" href="/site/especificos" rel="tooltip" title="aqui se muestra informacion de data por fecha y hora especifica" class="tooltip-test">></a>
            </h1>
        </div>
        <div class="reportes">
            <h1> PERSONALIZADOS
                <a id="flecha-forward" href="/site/personalizados" rel="tooltip" title="puede realizar una busqueda filtrada de data, por fecha, operadora, entre otras" class="tooltip-test">></a>
            </h1>
        </div>
    </div>
    <div class="vistas">
    </div>
</div>
