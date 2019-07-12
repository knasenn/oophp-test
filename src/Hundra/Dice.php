<?php

namespace Aiur\Hundra;

class Dice
{

    /**
     * Dice randomizer
     *
     */
    public function rollDice()
    {
        $random = rand(1, 6);
        return $random;
    }

    /**
     * Dice randomizer
     *
     */
    public function addDice($dice1, $dice2)
    {
        $add = $dice1 + $dice2;
        return $add;
    }
}
