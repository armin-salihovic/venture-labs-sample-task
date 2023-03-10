<?php

function is_logged_in()
{
    return isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true;
}

function is_read_only($readOnly)
{
    return $readOnly !== 'no';
}

function to_json($data, $code = 200)
{
    header('Content-Type: application/json; charset=utf-8');

    echo json_encode($data);

    http_response_code($code);
}

function bad_request($msg)
{
    to_json($msg, 400);
}