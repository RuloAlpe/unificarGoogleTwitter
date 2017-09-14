<?php
use app\models\EntTweets;

$twittEnLinea = " ";
foreach($tweets as $tweet){
    $annotation2 = $language->analyzeSentiment($tweet->txt_tweet);
    $sentimiento = $annotation2->sentiment();
?>
<script type="text/javascript">
    FusionCharts.ready(function () {
        var cSatScoreChart = new FusionCharts({
            type: 'angulargauge',
            renderAt: 'chartContainer<?= $tweet->id_tweet ?>',
            width: '400',
            height: '300',
            dataFormat: 'json',
            dataSource: {
                "chart": {
                    "caption": "Customer Satisfaction Score",
                    "subcaption": "Last week",
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
$annotation = $language->analyzeSentiment($twittEnLinea);
$sentiment = $annotation->sentiment();
?>
<script type="text/javascript">
    FusionCharts.ready(function () {
        var cSatScoreChart = new FusionCharts({
            type: 'angulargauge',
            renderAt: 'chartContainerGeneral',
            width: '400',
            height: '300',
            dataFormat: 'json',
            dataSource: {
                "chart": {
                    "caption": "Customer Satisfaction Score",
                    "subcaption": "Last week",
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
<div>
    <h3>Sentimiento general</h3>
    <div id="chartContainerGeneral">FusionCharts XT will load here!</div>    
    <p>Magnitud <?= $sentiment['magnitude'] ?> </p>
    <p>Score <?= $sentiment['score'] ?> </p>
</div


