<?php

if (!function_exists('dd')) {
    function dd($string)
    {
        var_dump($string);
        die();
    }
}

if (!function_exists('dd_je')) {
    function dd_je($string)
    {
        var_dump(json_encode($string));
        die();
    }
}
