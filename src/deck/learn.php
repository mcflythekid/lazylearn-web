<?php
require '../core.php';
require '../lang/core.php';
$deckId = ''; if (isset($_GET['id'])) $deckId = escape($_GET['id']);
$learnType = ''; if (isset($_GET['type'])) $learnType = escape($_GET['type']);

$TITLE = $lang["common.loading"];
$HEADER = '<span id="appHeader">' . $lang["common.loading"] . '</span>';
$PATHS = [
    ["/deck/view.php?id=" . $deckId, '<span id="appBreadcrumb1">' . $lang["common.loading"] . '</span>'],
    ucfirst($lang["common." . $learnType])
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
                <span id="learnstatus__position" class="pull-left"><?= $lang["common.loading"] ?></span>
                <span class="pull-right">
                    <?= $lang["page.basiclearn.label.unanswered"] ?>:
                    <span class="learnstatus__count" id="learnstatus__count--unanswered">
                        <?= $lang["common.loading"] ?>
                    </span>
                    <?= $lang["page.basiclearn.label.correct"] ?>:
                    <span class="learnstatus__count" id="learnstatus__count--correct">
                        <?= $lang["common.loading"] ?>
                    </span>
                    <?= $lang["page.basiclearn.label.incorrect"] ?>:
                    <span class="learnstatus__count" id="learnstatus__count--incorrect">
                        <?= $lang["common.loading"] ?>
                    </span>
                </span>
            </div>
        </div>

        <div class="row">
            <div id="learncmd" class="btn-group btn-group-justified" role="group" aria-label="Command">

                <a class="btn btn-default btn-sm btn-flat" role="button" id="learncmd__back" title='<?= $lang["page.basiclearn.command.previous"] ?>'>
                    <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm btn-flat" role="button" id="learncmd__next" title='<?= $lang["page.basiclearn.command.next"] ?>'>
                    <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm btn-flat" role="button" id="learncmd__shuffle" title='<?= $lang["page.basiclearn.command.shuffle"] ?>'>
                    <span class="glyphicon glyphicon-random" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm btn-flat" role="button" id="learncmd__reverse" title='<?= $lang["page.basiclearn.command.reverse"] ?>'>
                    <span class="glyphicon glyphicon-transfer" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm btn-flat" role="button" id="learncmd__edit" title='<?= $lang["page.basiclearn.command.edit"] ?>'>
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm btn-flat" role="button" id="learncmd__delete" title='<?= $lang["page.basiclearn.command.delete"] ?>'>
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                </a>
                <a class="btn btn-default btn-sm btn-flat" role="button" id="learncmd__end" title='<?= $lang["page.basiclearn.command.end"] ?>'>
                    <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="row">
            <div id="learndata">
                <div id="learndata__front" class="learn_center">
                    <div id="learndata__front--result-correct-4" class="learndata__front--result" style="display: none;"><?= $lang["page.basiclearn.tmpresult.hesitate"] ?></div>
                    <div id="learndata__front--result-correct-5" class="learndata__front--result" style="display: none;"><?= $lang["page.basiclearn.tmpresult.perfect"] ?></div>
                    <div id="learndata__front--result-incorrect" class="learndata__front--result" style="display: none;"><?= $lang["page.basiclearn.tmpresult.incorrect"] ?></div>
                    <div id="learndata__front--data"></div>
                </div>
                <div id="learndata__back" class="learn_center">
                    <div id="learndata__back--data"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div id="learnanswer">
                <div>
                    <button id="learnanswer__flip" class="learnanswer btn btn-primary btn-flat" style="display: none;">
                        <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                        <?= $lang["page.basiclearn.response.show"] ?>
                    </button>
                    <button id="learnanswer__wrong" class="learnanswer btn btn-danger btn-flat" style="display: none;">
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                        <?= $lang["page.basiclearn.response.forget"] ?>
                    </button>
                    <button data-quality="3" class="learnanswer learnanswer__right btn btn-warning btn-flat" style="display: none;">
                        <?= $lang["page.basiclearn.response.difficult"] ?>
                    </button>
                    <button data-quality="4" class="learnanswer learnanswer__right btn btn-info btn-flat" style="display: none;">
                        <?= $lang["page.basiclearn.response.hesitate"] ?>
                    </button>
                    <button data-quality="5" class="learnanswer learnanswer__right btn btn-success btn-flat" style="display: none;">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                        <?= $lang["page.basiclearn.response.perfect"] ?>
                    </button>
                </div>

                <div class="col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2">
                    <form id="learnanswer__typing" style="display: none;">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" autocapitalize=off required placeholder="<?= $lang["page.basiclearn.typing.input.holder"] ?>">
                            <span class="input-group-btn">
                            <button type="submit" class="btn btn-info btn-flat">
                                <?= $lang["page.basiclearn.typing.submit"] ?>
                            </button>
                            </span>
                        </div>
                    </form>
                <div>     
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="row u-unselectable">
            <div class="checkbox pull-right u-mr-20">
                <label>
                    <input required type="checkbox" id="toggle-type">
                    <?= $lang["page.basiclearn.typing.checkbox"] ?>
                </label>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
</div>

<script>

const isTypeableString = html=>{
    if (!html){ // Blank
        return false;
    }
    if (html.includes("<img") || html.includes("<audio")){
        return false;
    }
    var text = $(html)[0] ? $(html)[0].textContent : "";

    if (!text){ // Blank
        return false;
    }
    text = text.trim();
    if(text.indexOf("\n")!=-1){// New line
        return false;
    }
    if (!/^[\x00-\x7F]*$/.test(text)){ // None ASCII
        return false;
    }

    return true;
}

//alert(isTypeableString("<a>cc á</a>"));

var AnswerList = ((e)=>{
    var promises = [];
    e.push = promise => {
        promises.push(promise);
    };
    e.waitAll = ()=>{
        return Promise.all(promises);
    }
    return e;
})({});

const defaultTypingStatus = false;

var $learn = ((e, AppApi, FlashMessage, Dialog, Card, Deck)=>{
    var deckId, learnType, arr, arrIndex, arrLength, isReverse, isEditing, isFlipped;
    var deckObject;
    
    e.isTyping = defaultTypingStatus;
    e.isTypeable = null;
    e.onChangeTyping = isTyping=>{
        e.isTyping = isTyping;
        ask(arrIndex);
    };
    
    const getCorrectAnswerHtml = ()=>{
        return isReverse ? arr[arrIndex].front : arr[arrIndex].back;
    };

    e.onSubmitTyping = answer=>{
        const correctAnswer = getCorrectAnswerHtml();
        const correctText = $(correctAnswer)[0].textContent.trim();
        debugger;
        if (correctText == answer){
            $('#learndata__front--result-correct-5').show();
            flip();
            setTimeout(()=>{
                right(5);
            }, 666);
        } else {
            flip();
            $('#learndata__front--result-incorrect').show();
            setTimeout(wrong, 666);
        }
    };

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
            if(event.keyCode == 38 && !isEditing ) {event.preventDefault(); right(5);} // Up
            if(event.keyCode == 37 && !isEditing ) { // Left
                event.preventDefault();
                if (e.isTyping && e.isTypeable){
                    FlashMessage.info("<?= $lang["page.basiclearn.hotkey.back.blocked"] ?>");
                    return;
                }
                back();
            }
            if(event.keyCode == 39 && !isEditing ) { // Right
                event.preventDefault();
                if (e.isTyping && e.isTypeable){
                    FlashMessage.info("<?= $lang["page.basiclearn.hotkey.next.blocked"] ?>");
                    return;
                }
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
        $(document).on('click', '.learnanswer__right', function(e){
            e.preventDefault();
            right($(this).attr('data-quality'));
        });
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
                    vocabId: obj.vocabId,
                    quality: -1
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
            $('.learnanswer__right').show();
            $('#learnanswer__wrong').show();
            $('#learndata__back--data').removeClass('hidden');
            if (arr[index].correct){
                if (arr[index].quality == 4){
                    $('#learndata__front--result-correct-4').show();
                } else if (arr[index].quality == 5){
                    $('#learndata__front--result-correct-5').show();
                }
            } else {
                $('#learndata__front--result-incorrect').show();
            }
        } else {
            const correctAnswer = getCorrectAnswerHtml();
            if (isTypeableString(correctAnswer)){
                e.isTypeable = true;
            } else {
                e.isTypeable = false;
            }

            if (e.isTyping && e.isTypeable){
                $('#learnanswer__typing').show();
                $('#learnanswer__typing input').focus();
            } else {
                $('#learnanswer__typing').hide();
                $('#learnanswer__flip').show();
            }
            
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
        HoldOn.open();
        AnswerList.waitAll().then(()=>{
            var latest = document.referrer;
            if (!latest){
                latest = Constant.deckUrl;
            }
            window.location = latest;
        });
    };

    var edit = ()=>{
        if (deckObject.vocabdeckId){
            editAsVocab();
        } else {
            editAsCard();
        }
    };

    var editAsCard = ()=>{
        isEditing = true;
        Card.openEdit(arr[arrIndex].id, editedCard=>{
            arr[arrIndex].front = editedCard.front;
            arr[arrIndex].back = editedCard.back;
            ask(arrIndex);
        }, ()=>{
            isEditing = false;
        });
    };

    var editAsVocab = ()=>{
        isEditing = true;
        Vocab.openEdit(arr[arrIndex].vocabId, (result)=>{
            var card = result.cards.find(x => x.id === arr[arrIndex].id);
            arr[arrIndex].front = card.front;
            arr[arrIndex].back = card.back;
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
        FlashMessage.info("<?= $lang["page.basiclearn.command.shuffled.dispatched"] ?>");
    };

    var flip = ()=>{
        isFlipped = true;
        $('#learndata__back--data').removeClass('hidden');
        $('#learnanswer__flip').hide();
        $('#learnanswer__typing').hide();
        
        if (!e.isTyping && !e.isTypeable){
            $('.learnanswer__right').show();
            $('#learnanswer__wrong').show();
        }
    };

    var right = (quality)=>{
        if (arr[arrIndex].correct){
            goNextUnanswered();
            return;
        }

        if (learnType == 'learn'){
            AnswerList.push(AppApi.async.post("/learn/quality",{
                cardId: arr[arrIndex].id,
                quality: quality
            }));
        }
        
        if (quality > 3){
            arr[arrIndex].answered = true;
            arr[arrIndex].correct = true;
        }
        arr[arrIndex].quality = quality;
        goNextUnanswered();
    };

    var wrong = ()=>{
        AnswerList.push(AppApi.async.post("/learn/incorrect/" + arr[arrIndex].id));
        arr[arrIndex].answered = true;
        arr[arrIndex].correct = false;
        arr[arrIndex].correct = 0;
        goNextUnanswered();
    };

    var reverse = ()=>{
        isReverse = !isReverse;
        $('#learndata').effect('highlight', {}, 60, ()=>{
            ask(arrIndex);
        });
        if (isReverse){
            FlashMessage.info("<?= $lang["page.basiclearn.command.reverse.reversed.dispatched"] ?>");
        } else {
            FlashMessage.info("<?= $lang["page.basiclearn.command.reverse.origin.dispatched"] ?>");
        }
        
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

$("#toggle-type").prop('checked', defaultTypingStatus);
$("#toggle-type")[0].addEventListener('change', (event) => {
    if (event.target.checked) {
        $learn.onChangeTyping(true);
    } else {
        $learn.onChangeTyping(false);
    }
});
$("#learnanswer__typing").submit(function(event){
    event.preventDefault();
    const answer = $("#learnanswer__typing input").val();
    $("#learnanswer__typing input").val("");
    $learn.onSubmitTyping(answer);
});
</script>

<?=bottom_private()?>
