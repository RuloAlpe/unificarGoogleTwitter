<?php
use yii\web\View;
use yii\helpers\Url;
?>
<form id="form-twitter" action="<?= Url::base() . '/site/habilitar-tweet'; ?>" method="POST">
    <div class="jumbotron">
        <h2>Categorias</h2>
        <input type="checkbox" name="categoria[]" value="PERSON">Personas
        <input type="checkbox" name="categoria[]" value="LOCATION">Localidades
        <input type="checkbox" name="categoria[]" value="VERB">Verbos
        <input type="checkbox" name="categoria[]" value="ORGANIZATION">Organizaciones
        <input type="checkbox" name="categoria[]" value="EVENT">Eventos
        
    </div>
    <input type="checkbox" id="checkAll">Seleccionar todos.
    
    <?php
    foreach($tweets as $tweet){
    ?>
        <div class="jumbotron js_twitter">
            <input type="checkbox" name="twitter[]" value=<?= $tweet->id_tweet ?>><?= $tweet->txt_tweet ?>
        </div>
    <?php
        $tweet->b_usado = 1;
        $tweet->save();
    }
    ?>
    <button id="js_analizar" type="submit" class="btn btn-primary">Analizar</button>        
</form>

<?php
$this->registerJs("
    $('#checkAll').click(function(){
        $('.js_twitter input:checkbox').not(this).prop('checked', this.checked);
    });
", View::POS_END );
?>

<?php
/*
$this->registerJs ( "
    $('#js_analizar').on('click', function(e){
        e.preventDefault();
        var data = $('#form-twitter').serialize();
            $.ajax({
                url: basePath + 'site/habilitar-tweet',
                data: data,
                type: 'post',
                success: function(resp){
                    window.location.href = basePath + 'site/resultados-api-google';
                        
                }
            }   
        });
    });
", View::POS_END );*/
?>
