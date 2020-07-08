<?php

if (!UNAME) {
    header('location:index.php');
    exit();
}
$view->header();

$users = $db->getUsers();
$view->listUsers($users);

$view->footer();
