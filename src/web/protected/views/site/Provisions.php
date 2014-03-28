<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
?>
<article class='titulo90'>SINE</article>
<div class='subtitulos'>
    <h1>Provisiones </h1>
</div>
<div>
    <form id="formProvisions">
        <div name="formProvisions"  class="formProvisions">
            <div class="formInputs grupo">
                <h3>Grupo</h3>
                <input type="text" name="grupo" id="grupo"value=""/>
            </div>
            <div class="formInputs fromDate">
                <h3>From Date</h3>
                <input type="text" name="fromDate" id="fromDate" value=""/>
            </div>
            <div class="formInputs toDate">
                <h3>To Date</h3>
                <input type="text" name="toDate" id="toDate" value=""/>
            </div>
        </div>
    </form>
    <div id="genProvision" class="botones">
            <a class="provision">
               <img src="/images/provision.png" title='Generar provisiones'>
            </a>
    </div>
</div>

