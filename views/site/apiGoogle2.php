<?php
use app\models\EntTweets;

$annotation = $language->annotateText($twittsEnUnaLinea);
$personas = $annotation->entitiesByType('PERSON');
$organizaciones = $annotation->entitiesByType('ORGANIZATION');
$localidades = $annotation->entitiesByType('LOCATION');
$otros = $annotation->entitiesByType('OTHER');

$annotation2 = $language->analyzeSentiment($twittsEnUnaLinea);
$sentimiento = $annotation2->sentiment();
$sentencias = $annotation2->sentences();
?>
<div class="div_scroll col-sm-12">
    <ul class="nav navbar-nav">
        <li><a href="#section1"><button class="btm btn-primary">Sentimiento</button></a></li>
    </ul>
</div>

<div class="col-sm-3" style="background-color:yellow;">
    <h3>PERSON</h3>
    <?php
    if($personas){
        foreach($personas as $persona){
            echo "<b>" . $persona['name'] . "</b>";
            echo "<br>";        
            echo "Menciones: " . count($persona['mentions']);
            echo "<br>";        
            echo "Salience: " . $persona['salience'];
            echo "<hr>";
        }
    }
    ?>
</div>
<div class="col-sm-3" style="background-color:pink;">
    <h3>ORGANIZATION</h3>
    <?php
    if($organizaciones){
        foreach($organizaciones as $organizacion){
            echo "<b>" . $organizacion['name'] . "</b>";
            echo "<br>";                
            echo "Menciones: " . count($organizacion['mentions']);
            echo "<br>";        
            echo "Salience: " . $organizacion['salience'];
            echo "<hr>";
        }
    }
    ?>
</div>
<div class="col-sm-3" style="background-color:green;">
    <h3>LOCATION</h3>
    <?php
    if($localidades){
        foreach($localidades as $localidad){
            echo "<b>" . $localidad['name'] . "</b>";
            echo "<br>";                
            echo "Menciones: " . count($localidad['mentions']);
            echo "<br>";        
            echo "Salience: " . $localidad['salience'];
            echo "<hr>";
        }
    }
    ?>
</div>
<div class="col-sm-3" style="background-color:grey;">
    <h3>OTHER</h3>
    <?php
    if($otros){
        foreach($otros as $otro){
            echo "<b>" . $otro['name'] . "</b>";
            echo "<br>";                
            echo "Menciones: " . count($otro['mentions']);
            echo "<br>";        
            echo "Salience: " . $otro['salience'];
            echo "<hr>";
        }
    }
    ?>
</div>
<div id="section1" class="col-sm-12">
    <h3>Sentimiento general</h3>
    <p>Magnitud <?= $sentimiento['magnitude'] ?> </p>
    <p>Score <?= $sentimiento['score'] ?> </p>

    <div>
        <?php
            if($sentencias){
                echo "<h3>Sentimiento por sentencia</h3>";
                foreach($sentencias as $sentencia){
        ?>
                    <div class="col-sm-12">   
                        <div class="col-sm-8" style="background-color:grey;">
                            <?= $sentencia["text"]['content'] ?>
                        </div>
                        <div class="col-sm-4" style="background-color:orange;">
                            <?= "Magnitud: " . $sentencia["sentiment"]['magnitude'] ?>
                            <?= "Score: " . $sentencia["sentiment"]['score'] ?>
                        </div>
                    </div>
                    <hr>
                    <br>
                    <br>
        <?php
                }
            }
        ?>
    </div>
</div>

<?php
$tweets = EntTweets::find()->where(['b_usado'=>0])->all();
foreach($tweets as $tweet){
    $tweet->b_usado = 1;
    $tweet->save();   
}
?>
