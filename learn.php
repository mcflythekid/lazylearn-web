<?php
require 'core.php';
title('loading...');
top();
require 'modal/card-edit.php';
?>

<div class="row">
    <div class="col-lg-6 col-lg-offset-3 col-xs-12">

        <div class="row">
            <div id="learn__status">
                <span id="learn__status--position" class="pull-left">loading...</span>
                <span id="learn__status--count" class="pull-right">
                    unanswered: <span id="learn__status--unanswered">loading...</span>
                    correct: <span id="learn__status--correct">loading...</span>
                    incorrect: <span id="learn__status--incorrect">loading...</span>
                </span>
            </div>
        </div>

        <div class="row">
            <div id="learn__cmd" class="btn-group btn-group-justified" role="group" aria-label="Command">
                <a class="btn btn-default btn-sm" role="button" id="learn__cmd--back" title="Previous">
                    <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learn__cmd--next" title="Next">
                    <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learn__cmd--shuffle" title="Shuffle">
                    <span class="glyphicon glyphicon-random" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learn__cmd--reverse" title="Reverse sides">
                    <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learn__cmd--edit" title="Edit card">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learn__cmd--delete" title="Delete card">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learn__cmd--end" title="End session">
                    <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        <div class="row">
            <div id="learn__data">
                <div id="learn__data--front">
                    <div id="learn__data--result">CORRECT</div>
                </div>
                <div id="learn__data--back"></div>
            </div>
        </div>

        <div class="row">
            <div id="learn__answer--mark">
                <button class="btn btn-primary">
                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                    Show answer
                </button>
                <button class="btn btn-success">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    I was right
                </button>
                <button class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    I was wrong
                </button>
            </div>
        </div>

    </div>
</div>










    <script>

    var em = false;
    var li = false;
    //var sa = $tool.param('type') === 'review' ? true : false;
    var ucs = false;
    var cs = 291738;
    var theCardset = [];

    var $learn = ((e)=>{
        var data;
        var deckId = $tool.param('id');
        var learnType = $tool.param('type');

        console.log(deckId);
        console.log(learnType);

        e.setPosition = (position)=>{
            $('#learn__status--position').text(position + "/" + data.cards.length);
        };
        e.setUnanswered = (unanswered)=>{
            $('#learn__status--unanswered').text(unanswered);
        };
        e.setCorect = (correct)=>{
            $('#learn__status--correct').text(correct);
        };
        e.setIncorrect = (incorrect)=>{
            $('#learn__status--incorrect').text(incorrect);
        };

        $app.apisync.get("/learn/" + deckId + "/by-" + learnType).then((r)=>{
            data = r.data;
            document.title = data.deck.name;
            if (!data.cards.length){
                $tool.info('This deck has no card.', ()=>{
                    window.location.href = "./deck.php?id=" + deckId;
                });
                return;
            }
            $.each(data.cards, (index, obj)=>{
                theCardset[index + 1] = {
                    card_id: obj.id,
                    card_front: obj.front,
                    card_back: obj.back,
                    answered: false,
                    correct: false
                };
            });
            e.setPosition(1);
            e.setUnanswered(data.cards.length);
            e.setIncorrect(0);
            e.setCorect(0);
            console.log(theCardset);
        });
    })({});




</script>
<script src="<?=$ASSET?>/learn.js"></script>
<?php
bottom();