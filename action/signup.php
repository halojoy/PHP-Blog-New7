<?php

if (UNAME) {
    header('location:index.php');
    exit();;
}

session_start();

$view->header();

if (isset($req_post['submit'])) {
    extract($req_post);
    $valid->csrf_check();
    if ($valid->captcha_check()) {
        $username = trim($username);
        $password = trim($password);
        if (mb_strlen($username) >= 3 && mb_strlen($password) >= 3) {
            $user = $db->getUserByName($username);
            if (!$user) {
                $db->insertUser($username, $password);
                echo '<div class="lefty">';
                echo 'Registered.<br>You can now Login.';
                echo '</div>';
                $view->footer();
            } else {
                echo '<div class="lefty">';
                echo 'Username is already taken';
                echo '</div>';
            }
        } else {
            echo '<div class="lefty">';
            echo 'At least 3 characters';
            echo '</div>';
        }
    }
}
$view->signupForm($valid);

$view->footer();
