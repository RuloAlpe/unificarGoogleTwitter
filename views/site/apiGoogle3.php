<?php
use app\models\EntTweets;

$twittEnLinea = " ";
?>

<div class="col-md-9">
    <button id="sentimiento_general">Sentimiento general</button>    
    <button id="sentimiento_entidades">Sentimiento por entidad</button>
</div>
<div id="div1" class="col-md-9">
    <div class="col-sm-8">
        <?php
        ini_set('max_execution_time', 300);
        foreach($tweets as $tweet){
            $annotation2 = $language->analyzeSentiment($tweet->txt_tweet);   
            $sentimiento = $annotation2->sentiment();
        ?>
        <script type="text/javascript">
            FusionCharts.ready(function () {
                var cSatScoreChart = new FusionCharts({
                    type: 'angulargauge',
                    renderAt: 'chartContainer<?= $tweet->id_tweet ?>',
                    width: '250',
                    height: '150',
                    dataFormat: 'json',
                    dataSource: {
                        "chart": {
                            //"caption": "Customer Satisfaction Score",
                            //"subcaption": "Last week",
                            "lowerLimit": "-1",
                            "upperLimit": "1",
                            "showGaugeBorder": "1",
                            "gaugeBorderColor": "#111111",
                            "gaugeBorderThickness": "1",
                            "gaugeBorderAlpha": "1",
                            "showValue": "1",
                            "valueBelowPivot": "1",
                            "theme": "fint"
                        },
                        "colorRange": {
                            "color": [
                                {
                                    "minValue": "-1",
                                    "maxValue": "-0.25",
                                    "code": "#e44a00"
                                },
                                {
                                    "minValue": "-0.25",
                                    "maxValue": "0.25",
                                    "code": "#f8bd19"
                                },
                                {
                                    "minValue": "0.25",
                                    "maxValue": "1",
                                    "code": "#6baa01"
                                }
                            ]
                        },
                        "dials": {
                            "dial": [{
                                "value": "<?= $sentimiento['score'] ?>"
                            }]
                        }
                    }
                }).render();
            });
        </script>
            <div style="background-color:grey;">
                <b><?= $tweet->txt_tweet ?></b>
                <div id="chartContainer<?= $tweet->id_tweet ?>">FusionCharts XT will load here!</div>
                <p>Magnitud: <?= $sentimiento['magnitude'] ?> </p>
                <p>Score: <?= $sentimiento['score'] ?> </p>       
            </div>
            <br>
        <?php
            $twittEnLinea = $twittEnLinea .  $tweet->txt_tweet . " ";
            $tweet->b_usado = 1;
            $tweet->save();
        }
        ?>
    </div>
    <?php
    $google = $language->annotateText($twittEnLinea);
    $entidades = $google->entities();
    /*$personas = $google->entitiesByType('PERSON');
    $organizaciones = $google->entitiesByType('ORGANIZATION');
    $localidades = $google->entitiesByType('LOCATION');
    $otros = $google->entitiesByType('OTHER');*/

    $annotation = $language->analyzeSentiment($twittEnLinea);
    $sentiment = $annotation->sentiment();
    ?>
    <script type="text/javascript">
        FusionCharts.ready(function () {
            var cSatScoreChart = new FusionCharts({
                type: 'angulargauge',
                renderAt: 'chartContainerGeneral',
                width: '250',
                height: '150',
                dataFormat: 'json',
                dataSource: {
                    "chart": {
                        //"caption": "Customer Satisfaction Score",
                        //"subcaption": "Last week",
                        "lowerLimit": "-1",
                        "upperLimit": "1",
                        "showGaugeBorder": "1",
                        "gaugeBorderColor": "#111111",
                        "gaugeBorderThickness": "1",
                        "gaugeBorderAlpha": "1",
                        "showValue": "1",
                        "valueBelowPivot": "1",
                        "theme": "fint"
                    },
                    "colorRange": {
                        "color": [
                            {
                                "minValue": "-1",
                                "maxValue": "-0.25",
                                "code": "#e44a00"
                            },
                            {
                                "minValue": "-0.25",
                                "maxValue": "0.25",
                                "code": "#f8bd19"
                            },
                            {
                                "minValue": "0.25",
                                "maxValue": "1",
                                "code": "#6baa01"
                            }
                        ]
                    },
                    "dials": {
                        "dial": [{
                            "value": "<?= $sentiment['score'] ?>"
                        }]
                    }
                }
            }).render();
        });
    </script>
    <div class="col-sm-4">
        <h3>Sentimiento general</h3>
        <div id="chartContainerGeneral">FusionCharts XT will load here!</div>    
        <p>Magnitud <?= $sentiment['magnitude'] ?> </p>
        <p>Score <?= $sentiment['score'] ?> </p>
        <br>
        <p><?= count($entidades)?> Entidades</p>            
        <p><?php // count($personas)?> Personas</p>
        <p><?php //count($organizaciones)?> Organizaciones</p>
        <p><?php // count($localidades)?> Localidades</p>
        <p><?php // count($otros)?> Otros</p>    
    </div>
</div>

<div id="div2" class="col-sm-9" style="display:none">
    <?php
    //TRADUCTOR
    /*$source = 'es';
    $target = 'en';
    $result = $traductor->translate($source, $target, $twittEnLinea);
    echo "TRADUCCION: " . $result . "<hr>";*/
    
    echo "<h2>Sentimiento por entidad</h2>";

    // Get cURL resource
    $curl = curl_init();
    // Set some options - we are passing in a useragent too here
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => 'http://localhost:3000/api-google',
        CURLOPT_USERAGENT => 'Codular Sample cURL Request',
        CURLOPT_POST => 1,
        CURLOPT_POSTFIELDS => 'texto='.$twittEnLinea,//$result,
        CURLOPT_HTTPHEADER => array('Content-Type: application/x-www-form-urlencoded')
    ));

    if(!curl_exec($curl)){
        die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
    }

    // Send the request & save response to $resp
    $resp = curl_exec($curl);
    // Close request to clear up some resources
    curl_close($curl);
    //echo "RESPUESTA CURL";
    //var_dump($resp);

    $respuestas = json_decode($resp);
    /*echo "<pre>";
    print_r($respuestas);
    echo "</pre>";
    exit();*/   
    ?>
    <script>
    FusionCharts.ready(function () {
        var wVstrsChart = new FusionCharts({
            type: 'column2d',
            renderAt: 'chart-container',
            id: 'myChart',
            width: '650',
            height: '500',
            dataFormat: 'json',
            dataSource: {
                "chart": {
                    "caption": "Sentimiento por entidad",
                    //"subcaption": "Last 10 weeks",
                    "xAxisName": "Entidad",
                    "yAxisName": "sentimiento",
                    "numberSuffix": "",
                    "theme": "fint",
                    "showValues": "0",
                    //Show Zero plane
                    "showZeroPlane": "1",                                
                    //Customize Zero Plane Properties 
                    "zeroPlaneColor":"#003366",
                    "zeroPlaneAlpha": "100",
                    "zeroPlaneThickness": "3",
                    "divLineIsDashed": "0",
                    "divLineAlpha": "40"
                },
                "data": [
                    <?php foreach($respuestas as $respuesta){ 
                        if($respuesta->sentiment->score != 0){
                            //$result1 = $traductor->translate('en', 'es', $respuesta->name);                        
                    ?>
                            {
                                "label": "<?= $respuesta->name ?>",
                                "value": "<?= $respuesta->sentiment->score ?>"
                            },
                    <?php 
                        } 
                    } 
                    ?>
                ]
            }
        }).render();
    });
    </script>
    <div id="chart-container">FusionCharts will render here</div>
    <?php

    foreach($respuestas as $respuesta){
        //echo "Texto: " . $respuesta->mentions->text->content . " tipo: " . $respuesta->mentions->type . " sentimiento: " . $respuesta->mentions->sentiment->score . "<br>";
        echo "Texto: " . $respuesta->name . "<br>";
        echo "Tipo: " . $respuesta->type . "<br>";
        echo "Score: " . $respuesta->sentiment->score . "<br>";
        echo "Magnitude: " . $respuesta->sentiment->magnitude . "<br>";
        echo "<hr>";
    }
    ?>
</div>

<script>
    $(document).ready(function(){
        $('#sentimiento_general').on('click', function(){
            $('#div2').css('display', 'none');
            $('#div1').css('display', '');
        });
        $('#sentimiento_entidades').on('click', function(){
            $('#div1').css('display', 'none');
            $('#div2').css('display', '');
        });
    });
</script>

