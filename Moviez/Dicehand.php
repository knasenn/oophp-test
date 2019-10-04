<?php

namespace Aiur\Moviez;

/**
 * Guess my number, a class supporting the game through GET, POST and SESSION.
 */
 /**
  * Guess my number, a class supporting the game through GET, POST and SESSION.
  */
class Dicehand implements HistogramInterface
{

      use Histogram;


     /**
      * Constructor
      *
      */
    public function __construct()
    {
        $diceRandom = new Dice();
        $this->roll1 = $diceRandom->rollDice();
        $this->roll2 = $diceRandom->rollDice();
    }


     /**
      * Randomize roll hand
      *
      */
    public function roll()
    {
        $thisHand = array($this->roll1, $this->roll2);
        return $thisHand;
    }


     /**
      * Check hand for 1
      *
      */
    public function check($hand, $current)
    {
        if (in_array(1, $hand, true)) {
            $playerClass = new Player();
            $newCurrent = $playerClass->changePlayer($current);
            return array(0, $newCurrent);
        } else {
            $diceAdd = new Dice();
            $totalHand = $diceAdd->addDice($hand[0], $hand[1]);
            return array($totalHand, $current);
        }
    }
}
