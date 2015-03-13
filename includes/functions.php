<?php

require_once 'general_settings.php';

function cleanUrl($url) {
    if ($url) {
        return explode('/', $url);
    } else {
        return false;
    }
}

function translate($key) {
    return $key;
}

function set404() {
    echo 'ERROR 404';
    die();
}

?>