<?php

if (!function_exists('shuffleArray')) {
    function shuffleArray(array $array, \Closure $random): void
    {
        for ($i = count($array) - 1; $i > 0; $i--) {
            $j = floor($random() * ($i + 1));
            $temp = $array[$i];
            $array[$i] = $array[$j];
            $array[$j] = $temp;
        }
    }
}