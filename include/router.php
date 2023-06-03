<?php

$req_post = $_POST;
$req_get  = $_GET;
if ($req_get) {
    if (isset($req_get['action'])) {
        if (is_file('action/'.$req_get['action'].'.php')) {
            require 'action/'.$req_get['action'].'.php';
        }
    }
}
require 'action/home.php';
