<?php
use app\models\EntTweets;

$twittEnLinea = " ";

ini_set('max_execution_time', 300);
$order   = array("\r\n", "\n\r", "\n", "\r", "\t", '"', ',');
foreach($tweets as $tweet){
    $tweet->txt_tweet = str_replace($order, ' ', $tweet->txt_tweet);
    $twittEnLinea = $twittEnLinea .  $tweet->txt_tweet . " ";
    //$tweet->b_usado = 1;
    //$tweet->save();
}

$annotation = $language->analyzeSentiment($twittEnLinea);
$sentiment = $annotation->sentiment();
?>
<script>
    var personas = 0;
    var organizaciones = 0;
    var localidades = 0;
    var otros = 0;
    $.ajax({
        url: 'http://104.198.1.4:3000/api-google',
        data: {texto: "<?= $twittEnLinea ?>"},
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            "Access-Control-Allow-Origin": "*"
        },
        type: 'post',
        success: function(resp){
            if(resp.length > 1){
                getEntidades(resp, personas, organizaciones, localidades, otros);
                graficaPersonas(resp);
                graficaOrganizaciones(resp);
                graficaLocalidades(resp);
                graficaOtros(resp);
            }else{
                console.log("Error de Api google");
            }
        }
    });

    function getEntidades(resp, personas, organizaciones, localidades, otros){
        var contador = 0;
        resp.forEach(function(element){
            contador++;
            if(element.type == 'PERSON'){
                personas = personas + 1;
            }else if(element.type == 'ORGANIZATION'){
                organizaciones = organizaciones + 1;
            }else if(element.type == 'LOCATION'){
                localidades = localidades + 1;
            }else{
                otros = otros + 1;
            }

            if(contador === resp.length){
                pintarBotones(resp, personas, organizaciones, localidades, otros);
            }
        });
    }

    function pintarBotones(resp, personas, organizaciones, localidades, otros){
        $("#js-sentimiento-general").append("<h4>"+resp.length+" Entidades</h4>"+            
        "<button id='btn_personas'>"+personas+" Personas</button>"+
        "<button id='btn_organizaciones'>"+organizaciones+" Organizaciones</button>"+
        "<button id='btn_localidades'>"+localidades+" Localidades</button>"+
        "<button id='btn_otros'>"+otros+" Otros</button>");
    }

    function graficaPersonas(resp){
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
                    "data": (function(){
                        var data = [];
                        resp.forEach(function(element) {
                            if(element.type == 'PERSON' /*&& element.sentiment.score != 0*/){                     
                                data.push({
                                    "label": element.name,
                                    "value": element.sentiment.score,
                                    "link": "j-showAlert-"+element.name+","+element.sentiment.score
                                }); 
                            } 
                        });
                        return data;
                    })()
                },
                events: {
                    'dataplotClick': function(evt, args) {
                        window.showAlert = function(str, num){ 
                            //console.log(str);
                            var arr = str.split(","); 

                            $.ajax({
                                url: "http://localhost/unificarGoogleTwitter/web/site/buscar-palabra?pal="+arr[0]+"&sent="+arr[1],
                                success: function(resp){
                                    $('#js-sentimientos').append(resp);
                                    $("#myModal").modal();
                                    $("#myModal").on('hidden.bs.modal', function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        };
                    }
                }
            }).render();
        });
    }

    function graficaOrganizaciones(resp){
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
                    "data": (function(){
                        var data = [];
                        resp.forEach(function(element) {
                            if(element.type == 'ORGANIZATION' /*&& element.sentiment.score != 0*/){                     
                                data.push({
                                    "label": element.name,
                                    "value": element.sentiment.score,
                                    "link": "j-showAlert-"+element.name+","+element.sentiment.score
                                }); 
                            } 
                        });
                        return data;
                    })()
                },
                events: {
                    'dataplotClick': function(evt, args) {
                        window.showAlert = function(str, num){ 
                            //console.log(str+num);
                            var arr = str.split(","); 

                            $.ajax({
                                url: "http://localhost/unificarGoogleTwitter/web/site/buscar-palabra?pal="+arr[0]+"&sent="+arr[1],
                                success: function(resp){
                                    $('#js-sentimientos').append(resp);
                                    $("#myModal").modal();
                                    $("#myModal").on('hidden.bs.modal', function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        };
                    }
                }
            }).render();
        });
    }

    function graficaLocalidades(resp){
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
                    "data": (function(){
                        var data = [];
                        resp.forEach(function(element) {
                            if(element.type == 'LOCATION' /*&& element.sentiment.score != 0*/){                     
                                data.push({
                                    "label": element.name,
                                    "value": element.sentiment.score,
                                    "link": "j-showAlert-"+element.name+","+element.sentiment.score
                                }); 
                            } 
                        });
                        return data;
                    })()
                },
                events: {
                    'dataplotClick': function(evt, args) {
                        window.showAlert = function(str, num){ 
                            //console.log(str+num);
                            var arr = str.split(","); 

                            $.ajax({
                                url: "http://localhost/unificarGoogleTwitter/web/site/buscar-palabra?pal="+arr[0]+"&sent="+arr[1],
                                success: function(resp){
                                    $('#js-sentimientos').append(resp);
                                    $("#myModal").modal();
                                    $("#myModal").on('hidden.bs.modal', function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        };
                    }
                }
            }).render();
        });
    }

    function graficaOtros(resp){
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
                    "data": (function(){
                        var data = [];
                        resp.forEach(function(element) {
                            if(element.type != 'PERSON' && element.type != 'ORGANIZATION' && element.type != 'LOCATION' /*&& element.sentiment.score != 0*/){                     
                                data.push({
                                    "label": element.name,
                                    "value": element.sentiment.score,
                                    "link": "j-showAlert-"+element.name+","+element.sentiment.score
                                }); 
                            } 
                        });
                        return data;
                    })()
                },
                events: {
                    'dataplotClick': function(evt, args) {
                        window.showAlert = function(str, num){ 
                            //console.log(str+num);
                            var arr = str.split(",");

                            $.ajax({
                                url: "http://localhost/unificarGoogleTwitter/web/site/buscar-palabra?pal="+arr[0]+"&sent="+arr[1],
                                success: function(resp){
                                    $('#js-sentimientos').append(resp);
                                    $("#myModal").modal();
                                    $("#myModal").on('hidden.bs.modal', function () {
                                        $(this).remove();
                                    });
                                }
                            });
                        };
                    }
                }
            }).render();
        });
    }

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

<div id="js-sentimientos">
    <div class="col-md-9">
        <div id="js-sentimiento-general">
            <h3>Sentimiento general</h3>
            <div id="chartContainerGeneral">FusionCharts XT will load here!</div>    
            <p>Magnitud <?= $sentiment['magnitude'] ?> </p>
            <p>Score <?= $sentiment['score'] ?> </p>
            <br>
        </div>
    </div>

    <div id="div_personas" class="col-md-9 entidad" style="display: none">
        <h2>Personas</h2>
        <div id="chart-container">FusionCharts will render here</div>
        
    </div>

    <div id="div_organizaciones" class="col-md-9 entidad" style="display: none">
        <div id="chart-container2">FusionCharts will render here</div>
    </div>

    <div id="div_localidades" class="col-md-9 entidad" style="display: none">
        <div id="chart-container3">FusionCharts will render here</div>
    </div>

    <div id="div_otros" class="col-md-9 entidad" style="display: none">
        <div id="chart-container4">FusionCharts will render here</div>
        <input type="checkbox">Input
    </div>
</div>

<script>
    $(document).on({
        'click' : function(e) {
            e.preventDefault();
            $('.entidad').css('display', 'none');
            $('#div_personas').css('display', '');
        }
    }, '#btn_personas');

    $(document).on({
        'click' : function(e) {
            e.preventDefault();
            $('.entidad').css('display', 'none');
            $('#div_organizaciones').css('display', '');
        }
    }, '#btn_organizaciones');

    $(document).on({
        'click' : function(e) {
            e.preventDefault();
            $('.entidad').css('display', 'none');
            $('#div_localidades').css('display', '');
        }
    }, '#btn_localidades');

    $(document).on({
        'click' : function(e) {
            e.preventDefault();
            $('.entidad').css('display', 'none');
            $('#div_otros').css('display', '');
        }
    }, '#btn_otros');

    $(document).on({
        'click' : function(e) {
            e.preventDefault();
            $('#myChart4').setChartAttribute({
                'data': [
                    {
                        "label": "Mon",
                        "value": "4123"
                    }, {
                        "label": "Tue",
                            "value": "4633"
                    }, {
                        "label": "Wed",
                            "value": "5507"
                    }, {
                        "label": "Thu",
                            "value": "4910"
                    }, {
                        "label": "Fri",
                            "value": "5529"
                    }, {
                        "label": "Sat",
                            "value": "5803"
                    }, {
                        "label": "Sun",
                        "value": "6202",
                    }
                ]
            });
        }
    }, 'input');
</script>