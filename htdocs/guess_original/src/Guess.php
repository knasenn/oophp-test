<?php
/**
 * Guess my number, a class supporting the game through GET, POST and SESSION.
 */
class Guess
{
    /**
     * @var int $number   The current secret number.
     * @var int $tries    Number of tries a guess has been made.
     */
    private $number = null;
    private $tries = 6;



    /**
     * Constructor to initiate the object with current game settings,
     * if available. Randomize the current number if no value is sent in.
     *
     * @param int $number The current secret number, default -1 to initiate
     *                    the number from start.
     * @param int $tries  Number of tries a guess has been made,
     *                    default 6.
     */

    public function __construct(int $number = -1, int $tries = 6)
    {
        $this->tries = $tries;
        if ($number === -1) {
            $number = rand(1, 100);
        }
        $this->number = $number;
        return $tries;
    }




    /**
     * Randomize the secret number between 1 and 100 to initiate a new game.
     *
     * @return void
     */
    public function random() : void
    {
        $this->number = rand(1, 100);
    }




    /**
     * Get number of tries left.
     *
     * @return int as number of tries made.
     */
    public function tries() : int
    {
        return $this->tries;
    }




    /**
     * Get the secret number.
     *
     * @return int as the secret number.
     */

    public function number() : int
    {
        return $this->number;
    }


    /**
     * Get the secret number.
     *
     * @return int as the secret number.
     */

    public function fail()
    {
        // echo("<br>");
        // echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        // echo("<br>");
        // $pathFail = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        // echo($pathFail);
        // echo("<br>");
        // $pathFailSplit = explode("/", $pathFail);
        // echo("<br>");
        // echo($pathFailSplit);
        // echo("<br>");
        // print_r($pathFailSplit);
        header("Location: view/fail.php");

        // header("Location: '$test'");
        // header("Location: /fail.php");
    }

    public function win()
    {
        // echo("<br>");
        // echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        // echo("<br>");
        // $pathFail = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
        // echo($pathFail);
        // echo("<br>");
        // $pathFailSplit = explode("/", $pathFail);
        // echo("<br>");
        // echo($pathFailSplit);
        // echo("<br>");
        // print_r($pathFailSplit);
        header("Location: view/win.php");

        // header("Location: '$test'");
        // header("Location: /fail.php");
    }




    /**
     * Make a guess, decrease remaining guesses and return a string stating
     * if the guess was correct, too low or to high or if no guesses remains.
     *
     * @throws GuessException when guessed number is out of bounds.
     *
     * @return string to show the status of the guess made.
     */

    public function makeGuess(int $guess) : string
    {

        if ($guess < 1 || $guess > 100) {
            throw new GuessException("number is out of bounds");
        }

        $this->tries--;

        if ($this->tries < 1) {
            // echo("hej");
            // require __DIR__ . "/../view/test.php";
            // require_once __DIR__ . "/../view/test.php";
            // header("Location: /../view/test.php");
            // var_dump($_SERVER["REQUEST_METHOD"]);
            // echo("<br>");
            // var_dump($_SERVER["REQUEST_URI"]);
            // echo("<br>");
            // var_dump($_SERVER["HTTP_HOST"]);
            // echo("<br>");
            // var_dump($_SERVER["REQUEST_URI"]);
            // echo("<br>");
            // var_dump($_SERVER["DOCUMENT_ROOT"]);
            // echo("<br>");
            // echo $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
            // echo("<br>");
            // $test = $_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']);
            // echo($test);
            // echo("<br>");
            $this->fail();
        }

        if ($guess === $this->number) {
            $res = "CORRECTo";
            $this->win();
        } elseif ($guess > $this->number) {
            $res = "TO HIGHo";
        } else {
            $res = "TO LOWo";
        }

        return $res;
    }
}
