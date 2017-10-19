
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

<script>
    $(document).ready({
        $("#myModal").modal();
    });
</script>