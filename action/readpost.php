<?php

session_start();

$view->header();

$id = $req_get['id'];

if (isset($req_post['submit'])) {
    extract($req_post);
    $valid->csrf_check();
    if ($valid->captcha_check()) {
        $cauthor = trim($cauthor);
        $comment = trim($comment);
        if (mb_strlen($cauthor) >= 3 && mb_strlen($comment) >= 3) {
            $db->insertComment($id, $cauthor, $comment);
            header('location:?action=readpost&id='.$id);
            exit();
        } else {
            echo '<div class="lefty">';
            echo 'At least 3 characters';
            echo '</div>';
        }
    }
}

$db->increaseViews($id);
$post = $db->getPostById($id);
if ($post->image) {
    $image = 'images/'.$post->image;
    $thumb = 'images/tmb_'.pathinfo($post->image, PATHINFO_FILENAME).'.png';
    $imageOutput = '<a href="'.$image.'" target="_blank"><img src="'.$thumb.'"></a>';
} else {
    $imageOutput = '';
}
$view->readPost($post, $imageOutput, $cebe);

$comments = $db->getComments($id);
if ($comments)
    $view->showComments($comments, $cebe);
else
    echo '<div class="lefty">No Comments here</div>';    

$view->addCommentForm($valid);

$view->footer();
