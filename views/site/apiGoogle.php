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
      renderAt: 'chartContainer',
      width: '400',
      height: '300',
      dataFormat: 'json',
      dataSource: {
          "chart": {
              "caption": "Customer Satisfaction Score",
              "subcaption": "Last week",
              "lowerLimit": "0",
              "upperLimit": "100",
              "showGaugeBorder": "1",
              "gaugeBorderColor": "#111111",
              "gaugeBorderThickness": "1",
              "gaugeBorderAlpha": "100",
              "showValue": "1",
              "valueBelowPivot": "1",
              "theme": "fint"
          },
          "colorRange": {
              "color": [
                  {
                      "minValue": "0",
                      "maxValue": "50",
                      "code": "#e44a00"
                  },
                  {
                      "minValue": "50",
                      "maxValue": "75",
                      "code": "#f8bd19"
                  },
                  {
                      "minValue": "75",
                      "maxValue": "100",
                      "code": "#6baa01"
                  }
              ]
          },
          "dials": {
              "dial": [{
                  "value": "67"
              }]
          }
      }
  }).render();
});
</script>
    <div style="background-color:grey;">
        <b><?= $tweet->txt_tweet ?></b>
        <div id="chartContainer">FusionCharts XT will load here!</div>
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
?>
<div>
    <h3>Sentimiento general</h3>
    <p>Magnitud <?= $sentimiento['magnitude'] ?> </p>
    <p>Score <?= $sentimiento['score'] ?> </p>
</div


