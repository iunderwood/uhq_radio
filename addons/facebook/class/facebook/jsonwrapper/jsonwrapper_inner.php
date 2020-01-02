<?php

require_once __DIR__ . '/JSON/JSON.php';

/**
 * @param $arg
 * @return mixed
 */
function json_encode($arg)
{
    global $services_json;
    if (!isset($services_json)) {
        $services_json = new Services_JSON();
    }

    return $services_json->encode($arg);
}

/**
 * @param $arg
 * @return mixed
 */
function json_decode($arg)
{
    global $services_json;
    if (!isset($services_json)) {
        $services_json = new Services_JSON();
    }

    return $services_json->decode($arg);
}
