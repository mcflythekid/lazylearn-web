<?php
require '../core.php';
$deckId = ''; if (isset($_GET['id'])) $deckId = escape($_GET['id']);
$learnType = ''; if (isset($_GET['type'])) $learnType = escape($_GET['type']);
$TITLE = 'loading...';
$HEADER = '<span id="appHeader">loading..</span>';
$PATHS = [
    ["/deck", "Deck"],
    ["/deck/view.php?id=" . $deckId, '<span id="appBreadcrumb1">loading..</span>'],
    ucfirst($learnType)
];
top_private();
Vocab();
Deck();
Card();
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
var $learn = ((e, AppApi, FlashMessage, Dialog, Card, Deck)=>{
    var deckId, learnType, arr, arrIndex, arrLength, isReverse, isEditing, isFlipped;
    var deckObject;

    e.str = ()=>{
        return{
            arr: arr,
            arrLength: arrLength,
            arrIndex: arrIndex,
            isReverse: isReverse
        };
    };

    e.init = ()=>{
        deckId = '<?=$deckId?>';
        learnType = '<?=$learnType?>';
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

        Deck.get(deckId, (deck)=>{
            deckObject = deck;
            if (!deck.archived) {
                $('#appHeader').text(deck.name);
            } else {
                $('#appHeader').html(deck.name + ' <span class="archived u-pb-5">Archived</span>');
            }
            document.title = deck.name;
            $('#appBreadcrumb1').text(deck.name);
        });

        AppApi.sync.get("/learn/get-deck?deckId=" + deckId + "&learnType=" + learnType).then((response)=>{

            document.title = response.data.deck.name;
            if (!response.data.cards.length){
                FlashMessage.warning("No card to learn");
                setTimeout(returnToDeck, 2000);
            }
            $.each(response.data.cards, (index, obj)=>{
                arr[index] = {
                    id: obj.id,
                    step: obj.step,
                    front: obj.front,
                    back: obj.back,
                    answered: false,
                    correct: false,
                    vocabId: obj.vocabId
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
        if (deckObject.vocabdeckId){
            editAsVocab();
        } else {
            editAsCard();
        }
    };

    var editAsCard = ()=>{
        alert('Not implemented yet');
    };

    var editAsVocab = ()=>{
        alert('Not implemented yet');
        isEditing = true;
        Vocab.openEdit(arr[arrIndex].vocabId, ()=>{
            arr[arrIndex].front = 'test';
            arr[arrIndex].back = 'test';
            ask(arrIndex);
        }, ()=>{
            isEditing = false;
        });
    };

    var delete_ = ()=>{
        if (deckObject.vocabdeckId){
            deleteAsVocab();
        } else {
            deleteAsCard();
        }
    };

    var deleteAsCard = ()=>{
        isEditing = true;
        Dialog.confirm(Card.deleteMsg, function(){
            AppApi.sync.post("/card/delete/" + arr[arrIndex].id).then(()=>{
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

    var deleteAsVocab = ()=>{
        isEditing = true;
        Vocab.delete(arr[arrIndex].vocabId, ()=>{
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
        }, ()=>{
            isEditing = false;
        });
    };

    var shuffleArray = (a)=>{
        for (let i = a.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    };

    var shuffle = ()=>{
        if (arrLength == 1) return;
        arr = shuffleArray(arr);
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
            AppApi.async.post("/learn/correct/" + arr[arrIndex].id);
        } else if (learnType === 'review'){
            if (arr[arrIndex].step == 0){
                AppApi.async.post("/learn/correct/" + arr[arrIndex].id);
            }
        }
        arr[arrIndex].answered = true;
        arr[arrIndex].correct = true;
        goNextUnanswered();
    };

    var wrong = ()=>{
        AppApi.async.post("/learn/incorrect/" + arr[arrIndex].id);
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

    var returnToDeck = ()=>{window.location = "/deck/view.php?id=" + deckId;}

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
})({}, AppApi, FlashMessage, Dialog, Card, Deck);
$learn.init();
</script>

<?=bottom_private()?>