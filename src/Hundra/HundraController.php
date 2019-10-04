<?php

namespace Aiur\Hundra;

use Anax\Commons\AppInjectableInterface;
use Anax\Commons\AppInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $this->app if implementing the interface
 * AppInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class HundraController implements AppInjectableInterface
{
    use AppInjectableTrait;



    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    // **********************************
    public function indexAction() : string
    {
        // Deal with the action and return a response.
        return "Index";
    }
    // **********************************
    public function debugActionGet() : string
    {
        // Deal with the action and return a response.
        return "Debug";
    }



    // **********************************
    // Om vill typhinta returneras OBJECT
    public function initActionGet()
    {
        $response = $this->app->response;

        // init session
        session_destroy();
        session_name("aiur18");
        session_start();

        return $response->redirect("hundra1/play");
    }



    // **********************************
    // Om vill typhinta returneras OBJECT
    public function playActionGet()
    {
        $session = $this->app->session;
        $page = $this->app->page;

        // $game = new Dicehand();
        // $value = 0;

        $data = [
            "current" => $session->get("player", 1),
            "player1" => $session->get("player1", 0),
            "player2" => $session->get("player2", 0),
            "hand" => $session->get("hand", 0),
            "fullHand" => $session->get("fullHand", 0),
            "roll" => $session->get("roll", null),
            "roll1" => $session->get("roll1", null),
            "roll2" => $session->get("roll2", null),
            "save" => $session->get("save", null),
            "histoText" => $session->get("histoText", ""),
            "histoArray" => $session->get("histoArray", []),
            "computerScores" => $session->get("computerScores", array()),
            "playerScores" => $session->get("playerScores", array())
        ];

        $page->add("hundra1/play", $data);

        return $page->render([]);
    }



    // **********************************
    // Om vill typhinta returneras OBJECT
    public function playActionPost()
    {
        $session = $this->app->session;
        $request = $this->app->request;
        $page = $this->app->page;
        $response = $this->app->response;


        $current = $session->get("player", 1);
        $histoArray = $session->get("histoArray", []);


        $roll = $request->getPost("roll", null);
        $save = $request->getPost("save", null);
        $reset = $request->getPost("reset", null);

        if ($session->has("playerScores")) {
        } else {
            $session->set("playerScores", array());
        }


        if ($reset) {
            return $response->redirect("hundra1/init");
        }

        if ($roll) {
            $game = new Dicehand();
            $roll = new Dice();

            $rollHand = $game->roll();
            $session->set("roll1", $rollHand[0]);
            $session->set("roll2", $rollHand[1]);

            array_push($histoArray, $rollHand[0], $rollHand[1]);
            $session->set("histoArray", $histoArray);
            $session->set("histoText", $game->getAsText($histoArray));


            $check = $game->check($rollHand, $current);
            if ($check[0] == 0) {
                $session->set("fullHand", 0);

                $playerScoreVar = $session->get('playerScores');
                $fullHandVar = $session->get("fullHand");
                array_push($playerScoreVar, $fullHandVar);
                $session->set('playerScores', $playerScoreVar);


                $session->set("player", 2);
                return $response->redirect("hundra1/playComp");
            } else {
                $session->set("fullHand", ($roll->addDice($session->get("fullHand"), $check[0])));
            }
        }

        if ($save) {
            $game = new Dicehand();
            $roll = new Dice();
            $play = new Player();

            $session->set("player1", ($roll->addDice($session->get("player1"), $session->get("fullHand"))));

            $score = $play->checkPlayer($_SESSION["player1"]);
            if ($score == true) {
                $page->add("hundra1/win");
                return $page->render([]);
            } else {
                $playerScoreVar = $session->get('playerScores');
                $fullHandVar = $session->get("fullHand");
                array_push($playerScoreVar, $fullHandVar);
                $session->set('playerScores', $playerScoreVar);

                $session->set("fullHand", 0);
                $session->set("player", 2);
                return $response->redirect("hundra1/playComp");
            }
        }
        return $response->redirect("hundra1/play");
    }



    // **********************************
    // Om vill typhinta returneras OBJECT
    public function playCompActionGet()
    {
        //Varriabler för session osv
        $session = $this->app->session;
        $page = $this->app->page;
        $response = $this->app->response;

        $game = new Dicehand();
        $roll = new Dice();
        // $play = new Player();

        // Hämtar vilken spelare(default player 1 vid start)
        $current = $session->get("player", 1);
        $session->set("roll1", 0);
        $session->set("roll2", 0);

        //Skapar en tom array om ej finns
        if ($session->has('computerScores')) {
        } else {
            $session->set('computerScores', array());
        }

        // Rullar en hand 1
        $rollComputer1 = $game->roll();

        // Kontrollerar om 1a i handen
        $rollChance1 = $game->check($rollComputer1, $current);
        if ($rollChance1[0] == 0) {
            // Lagrar och redirectar till player 1
            $session->set("player", 1);
            $computerScoreVar = $session->get('computerScores');
            array_push($computerScoreVar, 0);
            $session->set('computerScores', $computerScoreVar);

            return $response->redirect("hundra1/play");
        }

        // Redirectar till fail om computer vinner på första rull
        if (($session->get("player2")+$rollChance1[0]) > 99) {
            $page->add("hundra1/fail");
            return $page->render([]);
        }

        // Kontrollerar om 2a i handen
        $rollComputer = $game->roll();
        $rollChance2 = $game->check($rollComputer, $current);
        if ($rollChance2[0] == 0) {
            // Lagrar och redirectar till player 1
            $session->set("player", 1);
            $computerScoreVar = $session->get('computerScores');
            array_push($computerScoreVar, 0);
            $session->set('computerScores', $computerScoreVar);

            return $response->redirect("hundra1/play");
        } else {
            // Redirectar till fail om computer vinner på första rull
            if (($session->get("player2")+$rollChance1[0]+$rollChance2[0]) > 99) {
                $page->add("hundra1/fail");
                return $page->render([]);
            } else {
                // Lagrar hand 1 och 2 i listan med poäng
                $computerScoresArray = $session->get('computerScores');
                $bothHands = $rollChance1[0] + $rollChance2[0];
                array_push($computerScoresArray, $bothHands);
                $session->set('computerScores', $computerScoresArray);

                // Lagrar hand 1 och 2 i total score
                $session->set("player2", ($roll->addDice($session->get("player2"), $rollChance1[0])));
                $session->set("player2", ($roll->addDice($session->get("player2"), $rollChance2[0])));
                // Redirectar till player 1
                $session->set("player", 1);
                return $response->redirect("hundra1/play");
            }
        }
    }
}
