<?php

if ( ! function_exists('trimWhiteSpaces'))
{
    /**
     * Replace 2 or more whitespaces by a single space.
     *
     * @param  string  $content
     * @param  int  $min
     * @return string
     */
    function trimWhiteSpaces($content, $min = 2)
    {
        return preg_replace("/ {" . $min . ",}/", " ", $content);
    }
}

if ( ! function_exists('trimAllSpaces'))
{
    /**
     * Remove all spaces.
     *
     * @param  string  $content
     * @return string
     */
    function trimAllSpaces($content)
    {
        return preg_replace('/\s+/', '', $content);
    }
}
