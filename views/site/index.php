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
    <input type="text" class="form-control" name="numero" placeholder="Numero de twits a buscar">
  </div>

  <h3>Buscar por Usuario</h3>  
  <div class="form-group">
    <input type="text" class="form-control" name="user" placeholder="Introducir usuario ej. usuario">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="numeroUser" placeholder="Numero de twits a buscar">
  </div>

  <button type="submit" class="btn btn-primary">Buscar</button>
</form>
