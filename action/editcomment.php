<?php

if (ULEVEL < 2) {
    header('location:index.php');
    exit();;
}
$view->header();

if (isset($req_post['submit'])) {
    extract($req_post);
    if ($submit == 'Edit') {
        $comment = trim($comment);
        if (mb_strlen($comment) >= 3) {
            $db->updateComment($comid, $comment);
            header('location:?action=readpost&id='.$postid);
            exit();
        } else {
            echo '<div class="lefty">';
            echo 'At least 3 characters';
            echo '</div>';
        }
    }
    if ($submit == 'Delete') {
        $db->deleteComment($comid);
        header('location:?action=readpost&id='.$postid);
        exit();
    }
}

$id = $req_get['id'];
$comment = $db->getCommentById($id);
$view->editCommentForm($comment);

$view->footer();
