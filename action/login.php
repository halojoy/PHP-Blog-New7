<?php

if (UNAME) {
    header('location:index.php');
    exit();;
}

session_start();

$error = '';
if (isset($req_post['submit'])) {
    extract($req_post);
    $valid->csrf_check();
    $user = $db->getUserByName($username);
    if ($user && password_verify($password, $user->upass)) {
        $data = json_encode([$user->uid, $user->uname, $user->ulevel]);
        setcookie('userdata', $data, time() + 14*24*3600);
        header('location:index.php');
        exit();
    } else {
        $error = 'Username and Password were not correct';
    }
}

$view->header();

$view->loginForm($error, $valid);

$view->footer();
