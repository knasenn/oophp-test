<?php
/**
 * Create routes using $app programming style.
 */
//var_dump(array_keys(get_defined_vars()));



/**
 * Init the games and redirect
 */
$app->router->get("guess/init", function () use ($app) {
    // init session
    $game = new Aiur\Guess\Guess();
    $_SESSION["number"] = $game->number();
    $_SESSION["tries"] = $game->tries();
    $_SESSION["doCheat"] = $game->number();

    return $app->response->redirect("guess/play");
});



/**
 * Show game status
 */
$app->router->get("guess/play", function () use ($app) {
    $tries = $_SESSION["tries"] ?? null;
    $res = $_SESSION["res"] ?? null;
    $guess = $_SESSION["guess"] ?? null;

    //resettar
    $_SESSION["res"] = null;
    $_SESSION["guess"] = null;

    $data = [
        "guess" => $guess ?? null,
        "tries" => $tries,
        "number" => $number ?? null,
        "res" => $res,
        "doGuess" => $doGuess ?? null,
        "doCheat" => $doCheat ?? null,
    ];

    $app->page->add("guess/play", $data);
    $app->page->add("guess/debug");

    return $app->page->render([]);
});


/**
**
 * Make a guess
 */
$app->router->post("guess/play", function () use ($app) {
    $guess = $_POST["guess"] ?? null;

    //Fixa?
    $doInit = $_POST["doInit"] ?? null;
    $doGuess = $_POST["doGuess"] ?? null;
    //Fixa?
    $doCheat = $_POST["doCheat"] ?? null;


    $number = $_SESSION["number"] ?? null;
    $tries = $_SESSION["tries"] ?? null;
    $res = null;
    // $game = null;

    //EVENTUELLT IFSATS OCH EN REDIRECT OM TIMEOUT
    if ($doGuess) {
        $game = new Aiur\Guess\Guess($number, $tries);
        // $res = $game->makeGuess($guess, $tries);
        $res = $game->makeGuess($guess);
        $_SESSION["tries"] = $game->tries();
        $_SESSION["res"] = $res;
        $_SESSION["guess"] = $guess;
    }

    if ($doInit) {
        $game = new Aiur\Guess\Guess();
        $_SESSION["number"] = $game->number();
        $_SESSION["tries"] = $game->tries();
        $_SESSION["doCheat"] = $game->number();
        return $app->response->redirect("guess/play");
    }

    if ($doCheat) {
        $data = [
            "guess" => $guess ?? null,
            "tries" => $tries,
            "number" => $number ?? null,
            "res" => $res,
            "doGuess" => $doGuess ?? null,
            "doCheat" => $doCheat ?? null,
        ];
        $app->page->add("guess/play", $data);
        $app->page->add("guess/debug");
        return $app->page->render([]);
    }
    if ($guess == $number) {
        $app->page->add("guess/win");
        return $app->page->render([]);
    }

    if ($tries == 1) {
        if ($tries == $number) {
            $app->page->add("guess/win");
            return $app->page->render([]);
        } else {
            $app->page->add("guess/fail");
            return $app->page->render([]);
        }
    }

    return $app->response->redirect("guess/play");
});
