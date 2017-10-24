<script>
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
                    "value": "<?= $sent ?>"
                }]
            }
        }
    }).render();
});
</script>
<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= $pal ?></h4>
            </div>
            <div class="modal-body">
                <!-- <p class="modal-body-text">Some text in the modal.</p> -->
                <div id="chartContainerEntidadl">FusionCharts XT will load here!</div> 
                <div>
                    <?php
                        foreach($encontrados as $encontrado){
                            echo "<p>" . $encontrado . "</p>";
                        }
                    ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>