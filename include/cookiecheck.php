<?php

if (isset($_COOKIE['userdata'])) {
    list($uid, $uname, $ulevel) = json_decode($_COOKIE['userdata']);
    define('UID', $uid);
    define('UNAME', $uname);
    define('ULEVEL', $ulevel);
    setcookie('userdata', $_COOKIE['userdata'], time() + 14*24*3600);
} else {
    define('UID', 0);
    define('UNAME',  false);
    define('ULEVEL', 0);
}

$theip = require 'include/getip.php';
if ($theip != 'unknown') {
    $url = 'http://ip-api.com/json/'.$theip;
    $data = json_decode(file_get_contents($url));
    date_default_timezone_set($data->timezone);
} elseif (!ini_get('date.timezone')) {
    date_default_timezone_set('UTC');
}
