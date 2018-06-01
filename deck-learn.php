<?php
require 'core.php';
title('loading...');
top_private();
require 'modal/card-edit.php';
?>

<div class="row" id="learn">
    <div class="col-lg-6 col-lg-offset-3 col-xs-12">

        <div class="row">
            <div id="learnstatus">
                <span id="learnstatus__position" class="pull-left">loading...</span>
                <span class="pull-right">
                    unanswered: <span class="learnstatus__count" id="learnstatus__count--unanswered">loading...</span>
                    correct: <span class="learnstatus__count" id="learnstatus__count--correct">loading...</span>
                    incorrect: <span class="learnstatus__count" id="learnstatus__count--incorrect">loading...</span>
                </span>
            </div>
        </div>

        <div class="row">
            <div id="learncmd" class="btn-group btn-group-justified" role="group" aria-label="Command">
                <a class="btn btn-default btn-sm" role="button" id="learncmd__back" title="Previous">
                    <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learncmd__next" title="Next">
                    <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learncmd__shuffle" title="Shuffle">
                    <span class="glyphicon glyphicon-random" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learncmd__reverse" title="Reverse sides">
                    <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learncmd__edit" title="Edit card">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learncmd__delete" title="Delete card">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm" role="button" id="learncmd__end" title="End session">
                    <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>
                </a>
            </div>
        </div>

        <div class="row">
            <div id="learndata">
                <div id="learndata__front">
                    <div id="learndata__front--result-correct" class="learndata__front--result" style="display: none;">CORRECT</div>
                    <div id="learndata__front--result-incorrect" class="learndata__front--result" style="display: none;">INCORRECT</div>
                    <div id="learndata__front--data"></div>
                </div>
                <div id="learndata__back">
                    <div id="learndata__back--data"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div id="learnanswer">
                <button id="learnanswer__flip" class="learnanswer btn btn-primary" style="display: none;">
                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                    Show answer
                </button>
                <button id="learnanswer__right" class="learnanswer btn btn-success" style="display: none;">
                    <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    I was right
                </button>
                <button id="learnanswer__wrong" class="learnanswer btn btn-danger" style="display: none;">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    I was wrong
                </button>
            </div>
        </div>

    </div>
</div>

<script>
var $learn = ((e)=>{
    var deckId, learnType, arr, arrIndex, arrLength, isReverse, isEditing, isFlipped;

    e.str = ()=>{
        return{
            arr: arr,
            arrLength: arrLength,
            arrIndex: arrIndex,
            isReverse: isReverse
        };
    };

    e.init = ()=>{
        deckId = $tool.param('id');
        learnType = $tool.param('type');
        arr = [];
        arrIndex = 0;
        isReverse = false;
        isEditing = false;
        isFlipped = false;

        $(document).on('keydown', function(event){
            if(event.keyCode == 40 && !isEditing ) {event.preventDefault(); wrong();} // Down
            if(event.keyCode == 38 && !isEditing ) {;event.preventDefault(); right()} // Up
            if(event.keyCode == 37 && !isEditing ) {;event.preventDefault(); back();} // Left
            if(event.keyCode == 39 && !isEditing ) { // Right
                event.preventDefault();
                if (arr[arrIndex].answered || isFlipped) {
                    next();
                } else {
                    flip();
                }
            }
        });

        $(document).on('click', '#learncmd__end', end);
        $(document).on('click', '#learncmd__next', next);
        $(document).on('click', '#learncmd__back', back);
        $(document).on('click', '#learncmd__reverse', reverse);
        $(document).on('click', '#learncmd__edit', edit);
        $(document).on('click', '#learncmd__delete', delete_);
        $(document).on('click', '#learncmd__shuffle', shuffle);
        $(document).on('click', '#learnanswer__flip', flip);
        $(document).on('click', '#learnanswer__right', right);
        $(document).on('click', '#learnanswer__wrong', wrong);

        $app.apisync.get("/deck/" + deckId + "/learn-data?type=" + learnType).then((r)=>{
            document.title = r.data.deck.name;
            if (!r.data.cards.length){
                $tool.info('This deck has no card.', returnToDeck);
                return;
            }
            $.each(r.data.cards, (index, obj)=>{
                arr[index] = {
                    id: obj.id,
                    step: obj.step,
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
        refreshCount();
        isFlipped = false;

        $('#learnstatus__position').text((index - 0 + 1) +  "/" + arrLength);

        $('.learndata__front--result').hide();
        $('.learnanswer').hide();
        $('#learndata__back--data').addClass('hidden');

        if(arr[index].answered){
            $('#learnanswer__right').show();
            $('#learnanswer__wrong').show();
            $('#learndata__back--data').removeClass('hidden');
            if (arr[index].correct){
                $('#learndata__front--result-correct').show();
            } else {
                $('#learndata__front--result-incorrect').show();
            }
        } else {
            $('#learnanswer__flip').show();
        }
        
        if (!isReverse){
            $('#learndata__front--data').html(arr[index].front);
            $('#learndata__back--data').html(arr[index].back);
        } else {
            $('#learndata__front--data').html(arr[index].back);
            $('#learndata__back--data').html(arr[index].front);
        }
    };

    var end = ()=>{
        returnToDeck();
    };

    var edit = ()=>{
        isEditing = true;
        $card__modal__edit.edit(arr[arrIndex].id, (front, back)=>{
            arr[arrIndex].front = front;
            arr[arrIndex].back = back;
            ask(arrIndex);
        }, ()=>{
            isEditing = false;
        });
    };

    var delete_ = ()=>{
        isEditing = true;
        $tool.confirm("This will remove this card and cannot be undone!!!", function(){
            $app.apisync.delete("/card/" + arr[arrIndex].id).then(()=>{
                if (arrLength == 1){
                    returnToDeck();
                    return;
                }
                arr.splice(arrIndex, 1);
                if (arrIndex == arrLength - 1){
                    arrIndex--;
                }
                arrLength--;
                ask(arrIndex);
            });
        }, ()=>{
            isEditing = false;
        });
    };

    var shuffle = ()=>{
        if (arrLength == 1) return;
        arr = $tool.shuffle(arr);
        arr.sort((a, b)=>{
            var valA = a.answered ? 1 : 0;
            var valB = b.answered ? 1 : 0;
            return valA - valB;
        });
        arrIndex = 0;
        $('#learndata').effect('pulsate', {}, 60, ()=>{
            ask(arrIndex);
        });
    };

    var flip = ()=>{
        isFlipped = true;
        $('#learndata__back--data').removeClass('hidden');
        $('#learnanswer__flip').hide();
        $('#learnanswer__right').show();
        $('#learnanswer__wrong').show();
    };

    var right = ()=>{
        if (learnType === 'learn'){
            $app.api.patch("/card/" + arr[arrIndex].id + "/correct");
        } else if (learnType === 'review'){
            if (arr[arrIndex].step == 0){
                $app.api.patch("/card/" + arr[arrIndex].id + "/correct");
            }
        }
        arr[arrIndex].answered = true;
        arr[arrIndex].correct = true;
        goNextUnanswered();
    };

    var wrong = ()=>{
        $app.api.patch("/card/" + arr[arrIndex].id + "/incorrect");
        arr[arrIndex].answered = true;
        arr[arrIndex].correct = false;
        goNextUnanswered();
    };

    var reverse = ()=>{
        isReverse = !isReverse;
        $('#learndata').effect('highlight', {}, 60, ()=>{
            ask(arrIndex);
        });
    };

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
        $('#learnstatus__count--unanswered').text(unanswered);
        $('#learnstatus__count--incorrect').text(incorrect);
        $('#learnstatus__count--correct').text(correct);
    };

    var goNextUnanswered = function(){
        var getNextUnansweredIndex = ()=>{
            for (var i = arrIndex + 1; i <= arrLength - 1; i++){
                if (!arr[i].answered) return i;
            }
            for (var i = 0; i < arrIndex; i++){
                if (!arr[i].answered) return i;
            }
            return -1;
        };
        var nextIndex = getNextUnansweredIndex();
        if (nextIndex == -1){
            end();
        } else {
            arrIndex = nextIndex;
            ask(arrIndex);
        }

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

<?=bottom_private()?>