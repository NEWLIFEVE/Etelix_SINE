<?php
/**
 * @var $this SiteController
 * @var $model LoginForm
 * @var $form CActiveForm
 */
?>
<form id='formProvisions' name='formProvisions'>
        <h1 class='titleProv'>Provisiones </h1>
          <h3 class='provisionNote'>* Introduzca el grupo y la fecha desde donde quiere generar provisiones</h3>  
          <h3 class='provisionNote'>* Puede dejar el grupo vacio, de esta forma se generaran provisiones a todos los carriers</h3>  
     <div name='formProvisions'  class='formProvisions'>
         <div class='formInputs group'>
             <h3>Grupo</h3>
             <input type='text' name='grupo' id='grupo' value=''/>
         </div>
         <div class='formInputs date'>
             <h3>Fecha de Inicio</h3>
             <input type='text' name='datepicker' id='datepicker' value=''/>
         </div>
     </div>
</form>
 <div id='genProvision' class='botones'>
     <h2 class='H1provInput'>Generar provisiones</h2>
 </div>

