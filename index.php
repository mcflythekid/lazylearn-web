<?php
require_once 'core.php';
$TITLE = 'Lazylearn';
top_public();
?>

<script>
    FlashMessage.success("sss");
</script>

<style>
    @media(max-width: 768px) {
        .container .jumbotron, .container-fluid .jumbotron {
            padding-right: 20px;
            padding-left: 20px;
    }
</style>

<div class="jumbotron u-mt-20" >
    <h1>Forget about forgeting!</h1>
    <br>
    <p>
        <a class="btn btn-primary btn-lg" href="#" role="button">
            <i class="fa fa-facebook-official u-mr-5" aria-hidden="true"></i>
            Continue with Facebook
        </a>
    </p>
</div>

<div class="u-mt-30">
    <h4>
        <i class="fa fa-retweet u-mr-5" aria-hidden="true"></i>
        Spaced repetition
    </h4>
    <p>The learning technique that incorporates increasing intervals of time between
        subsequent review of previously learned material in order to exploit the psychological
        <a href="https://en.wikipedia.org/wiki/Spacing_effect" target="_blank">spacing effect</a>
    </p>
</div>

<div class="u-mt-30">
    <h4>
        <i class="fa fa-clock-o u-mr-5" aria-hidden="true"></i>
        Auto-reminder
    </h4>
    <p>The machine will pick upcoming flashcards for you.</p>
</div>

<div class="u-mt-30">
    <h4>
        <i class="fa fa-money u-mr-5" aria-hidden="true"></i>
        Totally free
    </h4>
    <p>In this time, Lazylearn is free, for all</p>
</div>

<div class="u-text-center u-mt-50 u-mb-50">
    <a href="/post/privacy.php">Privacy policy</a> -
    <a href="mailto:rapperkiban@gmail.com">Contact us</a> -
    <a href="https://github.com/mcflythekid">Github page</a>
</div>

<?=bottom_public()?>