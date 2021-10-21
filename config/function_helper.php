<?php

if (!function_exists("dd")) {
    function dd($variable)
    {
        echo "<pre>";
        print_r($variable);
        die;
    }
}



if (!function_exists("response_api")) {
    function response_api($variable)
    {
        $default_response = [
            "success" => true,
            "message" => "Action Success",
            "data" => null,
        ];

        $variable = array_merge($default_response, $variable);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($variable);
        die;
    }
}

if (!function_exists("url")) {
    function url($variable)
    {
        $suffix = str_replace("index.php", '', $_SERVER['SCRIPT_NAME']);
        return $suffix . $variable;
    }
}
