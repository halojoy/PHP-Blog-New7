<?php

if (ULEVEL < 2) {
    header('location:index.php');
    exit();
}
$view->header();

if (isset($req_post['submit'])) {
    extract($req_post);
    $db->updateLevel($uid, $ulevel);
}

$users = $db->getUsers();
$view->userLevel($users);

$view->footer();
