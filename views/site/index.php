<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<form action="<?= Url::base() . '/site/mostrar-twitts'; ?>" method="POST">
  <h3>Buscar por hashtag</h3>
  <div class="form-group">
    <input type="text" class="form-control" name="hashtag" placeholder="Introducir hashtag ej. hashtag">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="numero" placeholder="Numero de twits a buscar max 100">
  </div>

  <input type="checkbox" name="tiempo" value="1" checked>24 horas
  <input type="checkbox" name="tiempo" value="2">5 dias
  <input type="checkbox" name="tiempo" value="3">10 dias
  <br>
  <!-- <h3>Buscar por Usuario</h3>  
  <div class="form-group">
    <input type="text" class="form-control" name="user" placeholder="Introducir usuario ej. usuario">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="numeroUser" placeholder="Numero de twits a buscar max 100">
  </div> -->

  <button type="submit" class="btn btn-primary">Buscar</button>
</form>

<script>
$(':checkbox').on('change',function(){
 var th = $(this), name = th.prop('name'); 
 if(th.is(':checked')){
     $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);   
  }
});
</script>