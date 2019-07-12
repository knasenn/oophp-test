<?php

namespace Aiur\Hundra;

/**
 * Guess my number, a class supporting the game through GET, POST and SESSION.
 */


class Player
{

    /**
     * Change player
     *
     */
    public function changePlayer($current)
    {
        if ($current == 1) {
            return 2;
        } else {
            return 1;
        }
    }

    /**
     * Check player score
     *
     */
    public function checkPlayer($score)
    {
        if ($score > 99) {
            return true;
        } else {
            return false;
        }
    }
}
