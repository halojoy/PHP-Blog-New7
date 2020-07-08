<?php

if (!UNAME) {
    header('location:index.php');
    exit();;
}

session_start();

$view->header();

if (isset($req_post['submit'])) {
    extract($req_post);
    $valid->csrf_check();
    if ($newpass1 == $newpass2) {
        $user = $db->getUserByName(UNAME);
        if ($user && password_verify($oldpass, $user->upass)) {
            $newpasshash = password_hash($newpass1, PASSWORD_BCRYPT);
            $db->updatePassword($user->uid, $newpasshash);
            header('location:?action=logout');
            exit();
        }
    }
}
$view->changePassForm($valid);

$view->footer();
