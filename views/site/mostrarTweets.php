<?php
use yii\web\View;
use yii\helpers\Url;
?>
<form id="form-twitter" action="<?= Url::base() . '/site/analizar-tweet'; ?>" method="POST">
    <?php
    $twittEnLinea = " ";
    foreach($tweets as $tweet){
        $twittEnLinea = $twittEnLinea .  $tweet->txt_tweet . " ";
    }
    ?>
    <input type"text" name="twitt" value="<?= $twittEnLinea ?>"><?= $twittEnLinea ?>
    <button id="js_analizar" type="submit" class="btn btn-primary">Analizar</button>        
</form>

<?php

$this->registerJs ( "
    $('#js_analizar').on('click', function(e){
        e.preventDefault();
        var data = $('#form-twitter').serialize();
            $.ajax({
                url: basePath + 'site/analizar-tweet',
                data: data,
                type: 'post',
                success: function(resp){
                    window.location.href = basePath + 'site/resultados-api-google';
                        
                }
            }   
        });
    });
", View::POS_END );
?>
