<?php

namespace Aiur\Hundra2;

/**
 * Generating histogram data.
 */
trait Histogram
{

    /**
     * Return a string with a textual representation of the histogram.
     *
     * @return string representing the histogram.
     */
    public function getAsText($serie)
    {
        // $serie = $this->serie;
        sort($serie);
        $dictRolls = array_count_values($serie);
        $stringRollsHisto = "";
        foreach ($dictRolls as $key => $value) {
            $stringRollsHisto = $stringRollsHisto . $key . ": ";
            $stringRollsHisto = $stringRollsHisto . str_repeat("*", $value);
            $stringRollsHisto = $stringRollsHisto . "<br>";
        }
        return $stringRollsHisto;
    }
}
