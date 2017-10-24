<?php
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div id="inicio" class="row">
  <div class="col-md-3">
    <form>
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

      <button id="submitAnalizar" class="btn btn-primary ladda-button" data-style="zoom-in"><span class="ladda-label">Buscar</span></button>
    </form>

    <script>
    $(document).ready(function(){
      $(':checkbox').on('change',function(){
      var th = $(this), name = th.prop('name'); 
      if(th.is(':checked')){
          $(':checkbox[name="'  + name + '"]').not($(this)).prop('checked',false);   
        }
      });
      var l = Ladda.create(document.getElementById("submitAnalizar"));
      $('#submitAnalizar').on('click', function(e){
        e.preventDefault();
        l.start();
        var datos = $("form").serialize();
        //console.log(datos);
        $.ajax({
          url: basePath + 'site/index',
          data: datos,
          dataType : 'html',
          type: "POST",
          success: function(resp){
            //console.log(resp);
            l.stop();
            $('#inicio').append(resp);
          }
        });
      });
    });
    </script>
  </div>
</div>