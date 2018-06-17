<?php
require '../core.php';

$minpairId = ''; if (isset($_GET['id'])) $minpairId = escape($_GET['id']);

$TITLE = 'loading...';
$HEADER = '<span id="appHeader">loading..</span>';
$PATHS = [
    ["/minpair", "Minimum Pair"],
    '<span id="appBreadcrumb1">loading..</span>'
];

top_private();
modal();
?>

    <audio class="audio" id="audio1"></audio>
    <audio class="audio" id="audio2"></audio>

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
                    <a class="btn btn-default btn-sm" role="button" id="learncmd__end" title="End session">
                        <span class="glyphicon glyphicon-stop" aria-hidden="true"></span>
                    </a>
                    <a class="btn btn-default btn-sm" role="button" id="learncmd__drop" title="Delete this pair">
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
        var minpair;
        var selectedWord;
        var count, correct, incorrect;

        var reload = ()=>{
            Minpair.get(minpairId, (object)=>{
                count = correct = incorrect = 0;
                minpair = object;
                var name = minpair.word1 + ' - ' + minpair.word2 + ' [' + minpair.learnedCount + ']';
                $('#appHeader').text(name);
                document.title = name;
                $('#appBreadcrumb1').text(name);
                $('#audio1').attr('src', apiServer + "/file/" + minpair.audioPath1);
                $('#audio2').attr('src', apiServer + "/file/" + minpair.audioPath2);
                $('#learnanswer__1').text(minpair.phonetic1);
                $('#learnanswer__2').text(minpair.phonetic2);
                $('#learnanswer__play').show();
                updateState();
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
            $('#audio' + selectedWord)[0].play();
        };
        var drop = ()=>{
            Minpair.delete(minpairId, ()=>{
                window.location = Constant.minpairUrl;
            });
        };
        var end = ()=>{
            window.location = Constant.minpairUrl;
        };
        var next = ()=>{
            if (count == Constant.minpairCount){
                Minpair.learned(minpairId, ()=>{
                    FlashMessage.success("Leaned");
                    reload();
                    return;
                });
            }
            selectedWord = Math.floor(Math.random() * 2) + 1;
            play();
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

        reload();

    </script>

<?=bottom_private()?>