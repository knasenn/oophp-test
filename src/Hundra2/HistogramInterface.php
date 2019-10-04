<?php

namespace Aiur\Hundra2;

/**
 * A interface for a classes supporting histogram reports.
 */
interface HistogramInterface
{


    /**
     * Get the serie as text
     *
     * @return string with the text.
     */
    public function getAsText($serie);


    /**
     * Get the serie as text
     *
     * @return string with the text.
     */
    public function roll();


    /**
     * Get the serie as text
     *
     * @return string with the text.
     */
    public function check($hand, $current);
}
