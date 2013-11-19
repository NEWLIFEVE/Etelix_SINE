<?php
/**
 * @var $this SiteController
 */
$this->layout=$this->getLayoutFile('menuContent');
?>
<div class="rutinarios">
    <header>
        <h1>
            <a id="flecha-backward" href="/"><</a>
        </h1>
    </header>
    <section>
        <article class='titulo90'>
            RUTINARIOS
        </article>

    </section>
</div>       
<script src="/js/jquery-ui.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/views.js"/></script>
<script src="http://malsup.github.io/jquery.blockUI.js"></script>