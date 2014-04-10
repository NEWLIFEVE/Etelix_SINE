<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
?>
<div class='subtitulos'>
    <h1>Provisiones </h1>
</div>
<div>
    <form id="formProvisions">
        <div name="formProvisions"  class="formProvisions">
            <div class="formInputs group">
                <h3>Grupo</h3>
                <input type="text" name="grupo" id="grupo"value=""/>
            </div>
            <div class="formInputs date">
                <h3>Fecha de Inicio</h3>
                <input type="text" name="datepicker" id="datepicker" value=""/>
            </div>
        </div>
    </form>
    <div id="genProvision" class="botones">
            <a class="provision">
               <img src="/images/provision.png" title='Generar provisiones'>
            </a>
    </div>
</div>

