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
            <div id="learn__answer">
                <button id="learn__answer--flip" class="btn btn-primary">
                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                    Show answer
                </button>
                <button id="learn__answer--right" class="btn btn-success">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    I was right
                </button>
                <button id="learn__answer--wrong" class="btn btn-danger">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    I was wrong
                </button>
            </div>
        </div>

    </div>
</div>

<script>
var $learn = ((e)=>{
    var deckId, learnType, arr, arrIndex, arrLength, isReverse;

    e.init = ()=>{
        deckId = $tool.param('id');
        learnType = $tool.param('type');
        arr = [];
        arrIndex = 0;
        isReverse = false;

        $(document).on('click', '#learn__cmd--end', returnToDeck);
        $(document).on('click', '#learn__cmd--next', next);
        $(document).on('click', '#learn__cmd--back', back);
        $(document).on('click', '#learn__cmd--reverse', reverse);
        $(document).on('click', '#learn__cmd--edit', ()=>{
            $card__modal__edit.edit(arr[arrIndex].id, ()=>{
                alert('cc');
            });
        });
        $(document).on('click', '#learn__cmd--delete', ()=>{
            $tool.confirm("This will remove this card and cannot be undone!!!", function(){
                $app.apisync.delete("/card/" + arr[arrIndex].id).then(()=>{
                    alert('cl');
                });
            });
        });
        $(document).on('click', '#learn__cmd--shuffle', ()=>{
            arr = $tool.shuffle(arr);
            ask(0);
            alert('shuffled');
        });

        $app.apisync.get("/learn/" + deckId + "/by-" + learnType).then((r)=>{
            document.title = r.data.deck.name;
            if (!r.data.cards.length){
                $tool.info('This deck has no card.', e.returnToDeck);
                return;
            }
            $.each(r.data.cards, (index, obj)=>{
                arr[index] = {
                    id: obj.id,
                    front: obj.front,
                    back: obj.back,
                    answered: false,
                    correct: false
                };
                arrLength = arr.length;
            });
            ask(0);
            refreshCount();
        });
    };
    var ask = (index)=>{
        $('#learn__status--position').text((index - 0 + 1) +  "/" + arrLength);

       $('#learn__answer--flip').show();
       $('#learn__answer--right').hide();
       $('#learn__answer--wrong').hide();

       if (!isReverse){
           $('#learn__data--front').html(arr[index].front);
           $('#learn__data--back').html(arr[index].back);
       } else {
           $('#learn__data--front').html(arr[index].back);
           $('#learn__data--back').html(arr[index].front);
       }

    };

    var reverse = ()=>{
        isReverse = !isReverse;
        ask(arrIndex);
    }

    var refreshCount =()=>{
        var unanswered = 0, correct = 0, incorrect = 0;
        $.each(arr, (index, obj)=>{
            if (!obj.answered) {
                unanswered++
            } else {
                if (!obj.correct) {
                    incorrect++;
                } else {
                    correct++;
                }
            }
        });
        $('#learn__status--unanswered').text(unanswered);
        $('#learn__status--incorrect').text(incorrect);
        $('#learn__status--correct').text(correct);
    };

    var returnToDeck = ()=>{window.location.href = "./deck.php?id=" + deckId;}

    var back = ()=>{
        if(arrIndex > 0){
            arrIndex--;
            ask(arrIndex);
        }
    };

    var next = ()=>{
        if(arrIndex < arrLength - 1){
            arrIndex++;
            ask(arrIndex);
        }
    };
    return e;
})({});
$learn.init();
</script>
<?php bottom();