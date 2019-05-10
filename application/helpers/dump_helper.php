<?php

if (!function_exists('dd')) {
    function dd($string)
    {
        var_dump($string);
        die();
    }
}

if (!function_exists('dd_pre')) {
    function dd_pre($string)
    {
        echo '<pre>';
        var_dump($string);
        die();
        echo '</pre>';
    }
}

if (!function_exists('dd_json')) {
    function dd_json($string)
    {
        header("Content-type: application/json; charset=utf-8");
        echo json_encode($string);
        die();
    }
}
