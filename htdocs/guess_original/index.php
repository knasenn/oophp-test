<?php
/**
 * GISSA GET
*/

require __DIR__ . "/autoload.php";
require __DIR__ . "/config.php";

session_name("test");
session_start();


$guess = $_POST["guess"] ?? null;
$doInit = $_POST["doInit"] ?? null;
$doGuess = $_POST["doGuess"] ?? null;
$doCheat = $_POST["doCheat"] ?? null;

$number = $_SESSION["number"] ?? null;
$tries = $_SESSION["tries"] ?? null;
$game = null;

//Int
if ($doInit || $number === null) {
    // $number = rand(1, 100);
    // $tries = 6;
    $game = new Guess();
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();
    // header
} elseif ($doGuess) {
    // $tries -= 1;
    // if ($guess == $number) {
    //     $res = "CORRECT";
    // } elseif ($guess > $number) {
    //     $res = "TOO HIGH";
    // } else {
    //     $res = "TO LOW";
    // }
    $game = new Guess($number, $tries);
    $res = $game->makeGuess($guess);
    $_SESSION["tries"] = $game->tries();
}

// $_SESSION["number"] = $number;
// $_SESSION["tries"] = $tries;

require __DIR__ . "/view/guess_my_nr_post_session_class.php"
// require __DIR__ . "/view/debugg_session.php"
?>
<p></p>
