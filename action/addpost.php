<?php

if (ULEVEL < 1) {
    header('location:index.php');
    exit();;
}

session_start();

$view->header();

if (isset($req_post['submit'])) {
    extract($req_post);
    $valid->csrf_check();
    $title = trim($title);
    $body = trim($body);
    if (mb_strlen($title) >= 3 && mb_strlen($body) >= 3) {
        $pid = $db->insertPost($title, $body);
        $db->increaseUPosts(UNAME);
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            require 'include/imageupload.php';
        }
        header('location:?action=readpost&id='.$pid);
        exit();
    } else {
        echo '<div class="lefty">';
        echo 'At least 3 characters';
        echo '</div>';
    }
}
$view->addPostForm($valid);

$view->footer();
