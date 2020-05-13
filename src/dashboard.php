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
    <a style="display: none;" id="one-time-learn" class="btn btn-flat btn-lg btn-warning" href="/deck/learn.php?type=learn&id=all-deck">
        <?= $lang["page.dashboard.btn.one_time_learn"] ?>
    </a>
    <a style="display: none;" id="today-one-time-learn" class="btn btn-flat btn-lg btn-warning u-ml-10" href="/deck/learn.php?type=learn&id=all-deck-today">
        <?= $lang["page.dashboard.btn.today_one_time_learn"] ?>
    </a>
    <a style="display: none;" id="today-one-time-learn-done" class="btn btn-flat btn-lg btn-success u-ml-10"">
        <?= $lang["page.dashboard.btn.today_one_time_learn.done"] ?>
    </a>    
    <a style="display: none;" id="go-to-deck" class="btn btn-flat btn-lg btn-success u-ml-10" href="/deck.php">
        <?= $lang["page.dashboard.btn.explore_deck"] ?>
    </a>
</div>

<script>
    AppChart.drawCurrentUserDecks('lazychart__user');

    AppApi.async.get("/learn/count-onetime-learn-card").then(response=>{
        const count = response.data.count;
        console.log(" onetime " + count);

        if (count > 0){
            $("#one-time-learn").show();
        } else {
            $("#go-to-deck").show();
        }
    }).catch(()=>{
        $("#go-to-deck").show();
    });

    AppApi.async.get("/learn/count-today-onetime-learn-card").then(response=>{
        const count = response.data.count;
        console.log(" today onetime " + count);

        if (count > 0){
            $("#today-one-time-learn").show();
            $("#today-one-time-learn-done").hide();
        } else {
            $("#today-one-time-learn").hide();
            $("#today-one-time-learn-done").show();
        }
    });
</script>

<?=bottom_private()?>
