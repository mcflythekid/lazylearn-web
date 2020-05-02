<?php
require '../core.php';
$minpairId = ''; if (isset($_GET['id'])) $minpairId = escape($_GET['id']);
$cardId = ''; if (isset($_GET['cardid'])) $cardId = escape($_GET['cardid']);
$learnType = ''; if (isset($_GET['type'])) $learnType = escape($_GET['type']);
$TITLE = 'loading...';
$HEADER = '<span id="appHeader">loading..</span>';
$PATHS = [
    ["/minpair", "Minpair"],
    '<span id="appBreadcrumb1">loading..</span>'
];
top_private();
Minpair();
?>

    <div class="row" id="learn">
        <div class="col-lg-6 col-lg-offset-3 col-xs-12">

            <div class="row">
                <div id="learnstatus">
                    <span class="pull-right">
                    unanswered: <span class="learnstatus__count" id="learnstatus__count--unanswered">loading...</span>
                    correct: <span class="learnstatus__count" id="learnstatus__count--correct">loading...</span>
                    incorrect: <span class="learnstatus__count" id="learnstatus__count--incorrect">loading...</span>
                </span>
                </div>
            </div>

            <div class="row">
                <div id="learncmd" class="btn-group btn-group-justified" role="group" aria-label="Command">
                    <a class="btn btn-default btn-sm" role="button" id="learncmd__end" title="STOP">
                        <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>
                    </a>
                    <a class="btn btn-default btn-sm admin_component_todo" style="display: none;" role="button" id="learncmd__drop" title="Delete">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
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
                </div>
            </div>

            <div class="row">
                <div id="learnanswer">
                    <button id="learnanswer__play" class="learnanswer btn btn-primary">
                        <span class="glyphicon glyphicon-play" aria-hidden="true"></span>
                        Start
                    </button>
                    <button id="learnanswer__1" class="learnanswer__1_2 learnanswer btn btn-success" style="display: none;"></button>
                    <button id="learnanswer__2" class="learnanswer__1_2 learnanswer btn btn-success" style="display: none;"></button>
                </div>
            </div>

        </div>
    </div>

    <style>
        #learndata__front--data {
            height: 120px;
            line-height: 120px;
            text-align: center;
            font-size: 20px;
        }
    </style>

    <script>
        var minpairId = '<?=$minpairId?>';
        var cardId = '<?=$cardId?>';
        var learnType = '<?=$learnType?>';
        var minpair;
        var selectedWord;
        var count, correct, incorrect;
        
        var audioObjs1 = [];
        var audioObjs2 = [];
        var audios = [];
        audios[1] = (audioObjs1);
        audios[2] = (audioObjs2);
		
		var cardObj;
		AppApi.sync.get('/card/get/' + cardId).then(res=>{
			cardObj = res.data;
        });
        
        const onPlay = ()=>{
            $('.learndata__front--result').hide();
            $('#learndata__front--data').text('');
            $('.learnanswer__1_2').hide();
        };

        const onPlayEnded = ()=>{
            $('.learnanswer__1_2').show();
        };

        var reload = ()=>{
            Minpair.getLearn(minpairId, (object)=>{

                object.left.forEach(item=>{
                    var audioObj = new Audio(apiServer + "/file" + item.audioPath);
                    audioObj.onplay = onPlay;
                    audioObj.onended = onPlayEnded;
                    audioObjs1.push(audioObj);
                });
                object.right.forEach(item=>{
                    var audioObj = new Audio(apiServer + "/file" + item.audioPath);
                    audioObj.onplay = onPlay;
                    audioObj.onended = onPlayEnded;
                    audioObjs2.push(audioObj);
                });

                count = correct = incorrect = 0;
                minpair = object.minpair;
                var name = minpair.word1 + ' - ' + minpair.word2;
                $('#appHeader').text(name);
                document.title = name;
                $('#appBreadcrumb1').text(name);
                $('#learnanswer__1').text(minpair.phonetic1);
                $('#learnanswer__2').text(minpair.phonetic2);
                $('#learnanswer__play').show();
                updateState();
            }, err=>{
				Dialog.fail("Cannot found minpair");
			});
        };

        var updateState = ()=>{
            $('#learnstatus__count--unanswered').text(Constant.minpairCount - count);
            $('#learnstatus__count--correct').text(correct);
            $('#learnstatus__count--incorrect').text(incorrect);
        };

        var choose1 = ()=>{
            if (selectedWord == 1){
                correct++;
                $('#learndata__front--result-correct').show();
                $('#learndata__front--result-incorrect').hide();
            } else {
                incorrect++;
                $('#learndata__front--result-correct').hide();
                $('#learndata__front--result-incorrect').show();
            }
            $('#learndata__front--data').text(minpair["word" + selectedWord]);
            count++;
            updateState();
            setTimeout(next, 200);
        };
        var choose2 = ()=>{
            if (selectedWord == 2){
                correct++;
                $('#learndata__front--result-correct').show();
                $('#learndata__front--result-incorrect').hide();
            } else {
                incorrect++;
                $('#learndata__front--result-correct').hide();
                $('#learndata__front--result-incorrect').show();
            }
            $('#learndata__front--data').text(minpair["word" + selectedWord]);
            count++;
            updateState();
            setTimeout(next, 200);
        };
        var play = ()=>{
            if (!selectedWord){
                return;
            }
            const selectedAudiosSide = audios[selectedWord];
            const selectedAudioObj = selectedAudiosSide[Math.floor(Math.random() * selectedAudiosSide.length)];
            selectedAudioObj.play();
        };
		
        var drop = ()=>{
            Minpair.delete(minpairId, ()=>{
                location.reload();
            });
        };
		
        var end = ()=>{ //interupt
            window.location = Constant.deckUrl;
        };
		
		var finishSession = ()=>{ //learned until the end
			if (incorrect <= Constant.minpairAllowedErrors){
				if (learnType === 'learn'){
					AppApi.async.post("/learn/quality",{
                        cardId: cardId,
                        quality:5
                    });
				} else if (learnType === 'review' && cardObj.step == 0){
					AppApi.async.post("/learn/quality",{
                        cardId: cardId,
                        quality:5
                    });
				}
				Dialog.success('Success!<br>' + correct + " correct / " + Constant.minpairCount, ()=>{
					window.location = Constant.deckUrl;
				});
			} else {
				AppApi.async.post("/learn/incorrect/" + cardId);
				Dialog.fail('Failed! You wrong answer must not be greater than ' + Constant.minpairAllowedErrors, ()=>{
					location.reload();
				});
			}
		};
		
        var next = ()=>{
            if (count == Constant.minpairCount){
				finishSession();
            } else {
				selectedWord = Math.floor(Math.random() * 2) + 1;
				play();
			}
        };

        $('audio.audio').on('play', ()=>{
            $('.learndata__front--result').hide();
            $('#learndata__front--data').text('');
            $('.learnanswer__1_2').hide();
        }).on('ended', ()=>{
            $('.learnanswer__1_2').show();
        });

        $(document).on('click', '#learncmd__end', end);
        $(document).on('click', '#learncmd__drop', drop);
        $(document).on('click', '#learnanswer__1', choose1);
        $(document).on('click', '#learnanswer__2', choose2);
        $(document).on('click', '#learnanswer__play', function(){
            $(this).hide();
            next();

        });
        $(document).on('keydown', function(event){
            if(event.keyCode == 37) {;event.preventDefault(); choose1();} // Left
            if(event.keyCode == 39) {;event.preventDefault(); choose2();} // Right
        });

        var force = ()=>{
            AppApi.async.post("/learn/quality",{
                cardId: cardId,
                quality:5
            });
        };

        reload();

    </script>

<?=bottom_private()?>