<?php


/**
 * Init the games and redirect
 */
$app->router->get("hundra/init", function () use ($app) {
    // init session
    session_destroy();
    session_name("aiur18");
    session_start();

    return $app->response->redirect("hundra/play");
});


/**
 * Show game status
 */
$app->router->get("hundraa/play", function () use ($app) {
    $game = new Aiur\Hundra\Dicehand();
    $value = 0;


    $data = [
        "current" => $_SESSION["player"] ?? 1,
        "player1" => $_SESSION["player1"] ?? 0,
        "player2" => $_SESSION["player2"] ?? 0,
        "hand" => $_SESSION["hand"] ?? 0,
        "fullHand" => $_SESSION["fullHand"] ?? 0,
        "roll" => $_SESSION["roll"] ?? null,
        "roll1" => $_SESSION["roll1"] ?? null,
        "roll2" => $_SESSION["roll2"] ?? null,
        "save" => $_SESSION["save"] ?? null,
        "histoText" => $_SESSION["histoText"] ?? "",
        "histoArray" => $_SESSION["histoArray"] ?? [],
        "computerScores" => $_SESSION["computerScores"] ?? array(),
        "playerScores" => $_SESSION["playerScores"] ?? array()
    ];

    $app->page->add("hundra/play", $data);

    return $app->page->render([]);
});


/**
**
 * Make a guess
 */
$app->router->post("hundra/play", function () use ($app) {

    $current = $_SESSION["player"] ?? 1;
    $histoArray = $_SESSION["histoArray"] ?? [];

    $roll = $_POST["roll"] ?? null;
    $save = $_POST["save"] ?? null;
    $reset = $_POST["reset"] ?? null;

    if ($reset) {
        return $app->response->redirect("hundra-game");
    }

    if ($roll) {
        $game = new Aiur\Hundra\Dicehand();
        $roll = new Aiur\Hundra\Dice();

        $rollHand = $game->roll();
        $_SESSION["roll1"] = $rollHand[0];
        $_SESSION["roll2"] = $rollHand[1];

        array_push($histoArray, $rollHand[0], $rollHand[1]);
        $_SESSION["histoArray"] = $histoArray;
        $_SESSION["histoText"] = $game->getAsText($histoArray);



        $check = $game->check($rollHand, $current);
        if ($check[0] == 0) {
            $_SESSION["fullHand"] = 0;

            if (!isset($_SESSION['playerScores'])) {
                $_SESSION['playerScores'] = array();
            }
            array_push($_SESSION['playerScores'], $_SESSION["fullHand"]);

            $_SESSION["player"] = 2;
            return $app->response->redirect("hundra/playComp");
        } else {
            $_SESSION["fullHand"] = $roll->addDice($_SESSION["fullHand"], $check[0]);
        }
    }

    if ($save) {
        $game = new Aiur\Hundra\Dicehand();
        $roll = new Aiur\Hundra\Dice();
        $play = new Aiur\Hundra\Player();

        $_SESSION["player1"] = $roll->addDice($_SESSION["player1"], $_SESSION["fullHand"]);

        $score = $play->checkPlayer($_SESSION["player1"]);
        if ($score == true) {
            $app->page->add("hundra/win");
            return $app->page->render([]);
        } else {
            if (!isset($_SESSION['playerScores'])) {
                $_SESSION['playerScores'] = array();
            }
            array_push($_SESSION['playerScores'], $_SESSION["fullHand"]);

            $_SESSION["fullHand"] = 0;
            $_SESSION["player"] = 2;
            return $app->response->redirect("hundra/playComp");
        }
    }
    return $app->response->redirect("hundra/play");
});

/**
**
 * Computer plays
 */
$app->router->get("hundra/playComp", function () use ($app) {
    $game = new Aiur\Hundra\Dicehand();
    $roll = new Aiur\Hundra\Dice();
    $play = new Aiur\Hundra\Player();

    $current = $_SESSION["player"] ?? 1;

    $rollComputer = $game->roll();
    $_SESSION["roll1"] = 0;
    $_SESSION["roll2"] = 0;

    $check = $game->check($rollComputer, $current);
    if (!isset($_SESSION['computerScores'])) {
        $_SESSION['computerScores'] = array();
    }


    if ($_SESSION['computerScores'] > $_SESSION["playerScores"]) {
        array_push($_SESSION['computerScores'], $check[0]);
        $_SESSION["player2"] = $roll->addDice($_SESSION["player2"], $check[0]);
    } else {
        array_push($_SESSION['computerScores'], $check[0]);
        $_SESSION["player2"] = $roll->addDice($_SESSION["player2"], $check[0]);
        $rollComputer = $game->roll();
        $check = $game->check($rollComputer, $current);
        array_push($_SESSION['computerScores'], $check[0]);
        $_SESSION["player2"] = $roll->addDice($_SESSION["player2"], $check[0]);
    }


    $_SESSION["player"] = 1;

    $score = $play->checkPlayer($_SESSION["player2"]);
    if ($score == true) {
        $app->page->add("hundra/fail");
        return $app->page->render([]);
    } else {
        return $app->response->redirect("hundra/play");
    }
});
