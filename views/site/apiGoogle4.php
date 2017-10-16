<?php
use app\models\EntTweets;

$twittEnLinea = " ";

ini_set('max_execution_time', 300);
foreach($tweets as $tweet){
    $twittEnLinea = $twittEnLinea .  $tweet->txt_tweet . " ";
    $tweet->b_usado = 1;
    $tweet->save();
}


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


/*$google = $language->annotateText($twittEnLinea);
$entidades = $google->entities();*/

$annotation = $language->analyzeSentiment($twittEnLinea);
$sentiment = $annotation->sentiment();

$personas = 0;
$organizaciones = 0;
$localidades = 0;
$otros = 0;
foreach($respuestas as $respuesta){
    if($respuesta->type == "PERSON"){
        $personas = $personas + 1;
    }else if($respuesta->type == "LOCATION"){
        $localidades = $localidades + 1;
    }else if($respuesta->type == "ORGANIZATION"){
        $organizaciones = $organizaciones + 1;
    }else{
        $otros = $otros + 1;
    }
        
}
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

<div class="col-md-9">
    <div>
        <h3>Sentimiento general</h3>
        <div id="chartContainerGeneral">FusionCharts XT will load here!</div>    
        <p>Magnitud <?= $sentiment['magnitude'] ?> </p>
        <p>Score <?= $sentiment['score'] ?> </p>
        <br>
        <button><?= count($respuestas)?> Entidades</button>            
        <button id="btn_personas"><?= $personas ?> Personas</button>
        <button id="btn_organizaciones"><?= $organizaciones ?> Organizaciones</button>
        <button id="btn_localidades"><?= $localidades ?> Localidades</button>
        <button id="btn_otros"><?= $otros ?> Otros</button>    
    </div>
</div>

<div id="div_personas" class="col-md-9 entidad" style="display: none">
    <script>
        FusionCharts.ready(function () {
            var wVstrsChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container',
                id: 'myChart1',
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
                            if(/*$respuesta->sentiment->score != 0 &&*/ $respuesta->type == "PERSON"){
                                //$result1 = $traductor->translate('en', 'es', $respuesta->name);                        
                        ?>
                                {
                                    "label": "<?= $respuesta->name ?>",
                                    "value": "<?= $respuesta->sentiment->score ?>",
                                    "link": "JavaScript:showAlert(<?= $respuesta->name ?>,<?= $respuesta->sentiment->score ?>)"
                                },
                        <?php 
                            } 
                        } 
                        ?>
                    ]
                },
                events: {
                    'dataplotClick': function(evt, args) {
                        window.showAlert = function(str, num){ 
                            console.log(str+num);
                            //var arr = str.split(",");
                            $('h4.modal-title').html(str);
                            $('div.modal-dialog').data('sentimiento', num);                            
                            //alert("[Example for the 'j-' prefix] \n" + arr[0] + " juice sales for the last year: " + arr[1]);
                            $("#myModal").modal();
                            /*$("#myModal").on('show.bs.modal', function () {
                                alert(arr[1]);
                            });*/
                        };
                    }
                }
            }).render();
        });
    </script>
    <div id="chart-container">FusionCharts will render here</div>
    <?php 
    foreach($respuestas as $respuesta){
        //echo "Texto: " . $respuesta->mentions->text->content . " tipo: " . $respuesta->mentions->type . " sentimiento: " . $respuesta->mentions->sentiment->score . "<br>";
        if($respuesta->type == "PERSON"){            
            echo "Texto: " . $respuesta->name . "<br>";
            echo "Tipo: " . $respuesta->type . "<br>";
            echo "Score: " . $respuesta->sentiment->score . "<br>";
            echo "Magnitude: " . $respuesta->sentiment->magnitude . "<br>";
            echo "<hr>";
        }
    }
    ?>
</div>

<div id="div_organizaciones" class="col-md-9 entidad" style="display: none">
    <script>
        FusionCharts.ready(function () {
            var wVstrsChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container2',
                id: 'myChart2',
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
                            if(/*$respuesta->sentiment->score != 0 &&*/ $respuesta->type == "ORGANIZATION"){
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
    <div id="chart-container2">FusionCharts will render here</div>
    <?php 
    foreach($respuestas as $respuesta){
        //echo "Texto: " . $respuesta->mentions->text->content . " tipo: " . $respuesta->mentions->type . " sentimiento: " . $respuesta->mentions->sentiment->score . "<br>";
        if($respuesta->type == "ORGANIZATION"){
            echo "Texto: " . $respuesta->name . "<br>";
            echo "Tipo: " . $respuesta->type . "<br>";
            echo "Score: " . $respuesta->sentiment->score . "<br>";
            echo "Magnitude: " . $respuesta->sentiment->magnitude . "<br>";
            echo "<hr>";
        }
    }
    ?>
</div>

<div id="div_localidades" class="col-md-9 entidad" style="display: none">
    <script>
        FusionCharts.ready(function () {
            var wVstrsChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container3',
                id: 'myChart3',
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
                            if(/*$respuesta->sentiment->score != 0 &&*/ $respuesta->type == "LOCATION"){
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
    <div id="chart-container3">FusionCharts will render here</div>
    <?php 
    foreach($respuestas as $respuesta){
        //echo "Texto: " . $respuesta->mentions->text->content . " tipo: " . $respuesta->mentions->type . " sentimiento: " . $respuesta->mentions->sentiment->score . "<br>";
        if($respuesta->type == "LOCATION"){
            echo "Texto: " . $respuesta->name . "<br>";
            echo "Tipo: " . $respuesta->type . "<br>";
            echo "Score: " . $respuesta->sentiment->score . "<br>";
            echo "Magnitude: " . $respuesta->sentiment->magnitude . "<br>";
            echo "<hr>";
        }
    }
    ?>
</div>

<div id="div_otros" class="col-md-9 entidad" style="display: none">
    <script>
        FusionCharts.ready(function () {
            var wVstrsChart = new FusionCharts({
                type: 'column2d',
                renderAt: 'chart-container4',
                id: 'myChart4',
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
                            if(/*$respuesta->sentiment->score != 0 &&*/ $respuesta->type != "LOCATION" && $respuesta->type != "PERSON" && $respuesta->type != "ORGANIZATION"){
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
    <div id="chart-container4">FusionCharts will render here</div>
    <?php 
    foreach($respuestas as $respuesta){
        //echo "Texto: " . $respuesta->mentions->text->content . " tipo: " . $respuesta->mentions->type . " sentimiento: " . $respuesta->mentions->sentiment->score . "<br>";
        if($respuesta->type != "LOCATION" && $respuesta->type != "PERSON" && $respuesta->type != "ORGANIZATION"){
            echo "Texto: " . $respuesta->name . "<br>";
            echo "Tipo: " . $respuesta->type . "<br>";
            echo "Score: " . $respuesta->sentiment->score . "<br>";
            echo "Magnitude: " . $respuesta->sentiment->magnitude . "<br>";
            echo "<hr>";
        }
    }
    ?>
</div>

<script>
    $(document).ready(function(){
        $('#btn_personas').on('click', function(){
            $('.entidad').css('display', 'none');
            $('#div_personas').css('display', '');
        });div_organizaciones
        $('#btn_organizaciones').on('click', function(){
            $('.entidad').css('display', 'none');
            $('#div_organizaciones').css('display', '');
        });
        $('#btn_localidades').on('click', function(){
            $('.entidad').css('display', 'none');
            $('#div_localidades').css('display', '');
        });
        $('#btn_otros').on('click', function(){
            $('.entidad').css('display', 'none');
            $('#div_otros').css('display', '');
        });
    });
</script>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" data-sentimiento="0">

    <script type="text/javascript">
        var sentimiento = $('div.modal-dialog').data('sentimiento');
        FusionCharts.ready(function () {
            var cSatScoreChart = new FusionCharts({
                type: 'angulargauge',
                renderAt: 'chartContainerEntidadl',
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
                            "value": ""+sentimiento
                        }]
                    }
                }
            }).render();
        });
    </script>
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Modal Header</h4>
        </div>
        <div class="modal-body">
          <!-- <p class="modal-body-text">Some text in the modal.</p> -->
            <div id="chartContainerEntidadl">FusionCharts XT will load here!</div>   
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>