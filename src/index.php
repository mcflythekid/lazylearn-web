<?php
require_once 'core.php';
$TITLE = 'Lazylearn';
top_public();
?>

<style>
    @media(max-width: 768px) {
        .container .jumbotron, .container-fluid .jumbotron {
            padding-right: 20px;
            padding-left: 20px;
    }
</style>
<style>
    body{font-family: "Lucida Console", "Courier New", serif;}
    a{text-decoration: none; color:blue;}
</style>
<h1>a legendary flashcard machine</h1>
<ul>
    <li><a href="/auth/login.php"><h2>login</a></li>
    <li><a href="/auth/register.php"><h2>create an account</a></li>
</ul>
<pre>
by using this app, you agree that <a target="_blank" href="https://fb.com/mcflythekid">@mcflythekid</a>
and <a target="_blank" href="https://github.com/mcflythekid">@mcflythekid</a>
is the the most crazy developer ever in the earth
</pre>

<?=bottom_public()?>
