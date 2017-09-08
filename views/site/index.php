<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>

<form action="<?= Url::base() . '/site/mostrar-twitts'; ?>" method="POST">
  <div class="form-group">
    <input type="text" class="form-control" name="hashtag" placeholder="Introducir hashtag ej.(ejemplo)">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="numero" placeholder="Numero de twits a buscar">
  </div>

  <button type="submit" class="btn btn-primary">Buscar</button>
</form>
