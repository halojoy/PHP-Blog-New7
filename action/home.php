<?php

$view->header();

$total = $db->countPosts();
if ($total == 0)
    echo '<div class="lefty">No Posts</div>';
else {
    if (isset($req_get['p']))
        $pageno = $req_get['p'];
    else
        $pageno = 1;
    $pagin->setup($total, $perpage, $pageno);
    $posts = $db->getPosts($pagin->offset, $perpage);
    foreach($posts as $post) {
        if (mb_strlen($post->body) > 64) {
            $strarr = explode('xyx', wordwrap($post->body, 64, 'xyx'));
            $postbody = $strarr[0].' ....';
        }
        else {
            $postbody = $post->body;
        }
        $comNum = $db->getComNum($post->id);
        $view->homePost($post, $postbody, $comNum);
    }
    $pagin->showfoot();
}

$view->footer();
