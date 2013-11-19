<?php
/**
 * @var $this SiteController
 */
$this->layout=$this->getLayoutFile('menuContent');
?>
<div class="especificos">
    <header>
        <h1>
            <a id="flecha-backward" href="/"><</a>
        </h1>
    </header>
    <section>
        <article class="titulo90">
            ESPECIFICOS
        </article>
        <article class="parametros">
            <p>Seleccione los parametros</p>
            <form id="formulario">
                <div class="choice_parametros fecha">
                    <input type="checkbox" value="true" id="fecha" class="custom-checkbox" name="lista[Fecha]">
                    <label for="fecha">
                        <h4 id="td1">
                            Fecha
                        </h4>
                    </label>
                </div>
                <!--<div class="choice_parametros hora">
                    <input type="checkbox" value="true" id="Hora" class="custom-checkbox" name="lista[Hora]">
                    <label for="Hora">
                        <h4 id="td1">
                            Hora
                        </h4>
                    </label>
                </div>-->
                <div class="choice_parametros carrier">
                    <input type="checkbox" value="true" id="Carrier" class="custom-checkbox" name="lista[Carrier]">
                    <label for="Carrier">
                        <h4 id="td1">
                            Carrier
                        </h4>
                    </label>
                </div>
                <footer id="footer_especificos">
                    <div id="excel" class="botones">
                        <img src="/images/excel.png" class='ver'>
                        <img src="/images/excel_hover.png" title='Exportar Reportes en Excel' class='oculta' id='excel'>
                    </div>
                    <div id="lista" class="botones">
                        <img src="/images/mailRenoc.png" class='ver'>
                        <img src="/images/mailRenoc_hover.png" title='Enviar Reportes a Correo Electronico RENOC' class='oculta'>
                    </div>
                    <div id="mail" class="botones">
                        <img src="/images/mail.png" class='ver'>
                        <img src="/images/mail_hover.png" title='Enviar Reportes a su Correo Electronico' class='oculta'>
                    </div>
                </footer>
        </article>
        <article class='especificos_reportes'>
            <p>Seleccione los Reportes</p>
                <div class="choice primeras">
                    <input type="checkbox" value="true" id="compraventa" class="custom-checkbox" name="lista[compraventa]">
                    <label for="compraventa">
                        <h4 id="td1">
                            Ranking Compra Venta
                        </h4>
                    </label>
                </div>
                <div class="choice">
                    <input type="checkbox" value="true" id="AI10" class="custom-checkbox" name="lista[AI10]">
                    <label for="AI10">
                        <h4 id="td2">
                            Alto Impacto(+10$)
                        </h4>
                    </label>
                </div>
                <div class="choice">
                    <input type="checkbox" value="true" id="AI10R" class="custom-checkbox" name="lista[AI10R]">
                    <label for="AI10R">
                        <h4 id="td2">
                            Alto Impacto Resumen(+10$)
                        </h4>
                    </label>
                </div>
                <div class="choice">
                    <input type="checkbox" value="true" id="calidad" class="custom-checkbox" name="lista[calidad]">
                    <label for="calidad">
                        <h4 id="td1">
                            Calidad
                        </h4>
                    </label>
                </div>
            </form>
        </article>
    </section>
</div> 
<script src="/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>
