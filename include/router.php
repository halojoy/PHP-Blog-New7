<?php

$req_post = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
if ($req_get = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING)) {
    if (isset($req_get['action'])) {
        if (is_file('action/'.$req_get['action'].'.php')) {
            require 'action/'.$req_get['action'].'.php';
        }
    }
}
require 'action/home.php';
