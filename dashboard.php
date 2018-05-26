<?php
require_once 'core.php';
title('Deck');
top_private();
require 'component/chart.php';
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#collapseOne" href="#" class="a-no-underline display-block">
                        Statistics Chart
                    </a>
                </h4>
            </div>
            <div id="collapseOne" class="panel-collapse collapse in">
                <div class="panel-body">
                    <div class="lazychart" id="lazychart__user"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (()=>{
        var drawChart = ()=>{
            chart.drawUser($tool.getData('auth').userId, 'lazychart__user');
        };
        drawChart();
    })();
</script>
<?=bottom_private()?>
