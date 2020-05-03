<?php
require_once 'core.php';
require_once 'lang/core.php';
$TITLE = ('Lazy');
// $HEADER = "Lazy";

top_private();

Deck();
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a data-toggle="collapse" data-target="#collapseOne" href="#" class="a-no-underline display-block">
                        <?= $lang["page.dashboard.chart01.name"] ?>
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

<div class="u-center">
    <a style="display: none;" id="one-time-learn" class="btn btn-flat btn-lg btn-info" href="/deck/learn.php?type=learn&id=all-deck">
        <?= $lang["page.dashboard.btn.one_time_learn"] ?>
    </a>
    <a style="display: none;" id="go-to-deck" class="btn btn-flat btn-lg btn-info" href="/deck.php">
        <?= $lang["page.dashboard.btn.explore_deck"] ?>
    </a>
</div>

<script>
    AppChart.drawCurrentUserDecks('lazychart__user');
    AppApi.async.get("/learn/count-onetime-learn-card").then(response=>{
        if (response.data.count > 0){
            $("#one-time-learn").show();
        } else {
            $("#go-to-deck").show();
        }
    }).catch(()=>{
        $("#go-to-deck").show();
    });
</script>

<?=bottom_private()?>
