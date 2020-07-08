<?php

if (ULEVEL < 2) {
    header('location:index.php');
    exit();;
}
$view->header();

if (isset($req_post['submit'])) {
    extract($req_post);
    if ($submit == 'Edit') {
        $title = trim($title);
        $body = trim($body);
        if (mb_strlen($title) >= 3 && mb_strlen($body) >= 3) {
            $db->updatePost($postid, $title, $body);
            header('location:?action=readpost&id='.$postid);
            exit();
        } else {
            echo '<div class="lefty">';
            echo 'At least 3 characters';
            echo '</div>';
        }
    }
    if ($submit == 'Delete') {
        $post = $db->getPostById($postid);
        $db->deletePost($post->author, $post->id);
        $db->deletePostComments($postid);
        header('location:index.php');
        exit();
    }
}

$id = $req_get['id'];
$post = $db->getPostById($id);
$view->editPostForm($post);

$view->footer();
