<?php

class View {

    function header()
    {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
        <title><?php echo BLOGTITLE ?></title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style/default.css">
        </head>
        <body>
        <div id="container">
            <div id="topbar">
        <?php
            echo '<span id="blogtitle"><a href="index.php">'.BLOGTITLE.'</a></span>';
            echo '<a href="index.php">Home</a>'."\n";
            echo '<a href="index.php?action=popular">Popular</a>';
            if (UNAME) {
                if (ULEVEL > 0)
                    echo '<a href="index.php?action=addpost">AddPost</a>';
                echo '<a href="index.php?action=members">Members</a>'."\n";
                if (ULEVEL == 2)
                    echo '<a href="index.php?action=userlevel">UserLevel</a>';
                echo '<a href="index.php?action=changepass">ChangePass</a>';
                echo '<a href="index.php?action=logout">Logout</a> '.UNAME;
            } else {
                echo '<a href="index.php?action=login">Login</a>';
                echo '<a href="index.php?action=signup">SignUp</a>';
            }
            echo "\n";
        ?>
            </div>
            <div id="content">

        <?php
    }

    function footer()
    {
        ?>

            </div>
            <div id="footer">
                Copyright &copy; 2020 halojoy
            </div>
        </div>
        </body>
        </html>
        <?php
        exit();
    }

    function homePost($post, $postbody, $comNum)
    {
        echo '<div class="homelefty">';
        echo '<span class="ptitle">'."\n";
        echo '<a href="?action=readpost&id='.$post->id.'">'
                .$post->title.'</a></span>'."\n";
        echo '<span class="pdata"> by '.$post->author.'</span>';
        echo '<span class="pdata">'.date('Y-m-d H:i', $post->created_at).'</span>'."\n";
        echo '<div class="pbody">'.$postbody.'</div>'."\n";
        echo '<a href="?action=readpost&id='.$post->id.'">';
        echo 'Comments('.$comNum.')</a>';
        if ($post->image)
            echo '&nbsp;&nbsp;<a href="?action=readpost&id='.$post->id.'">Image</a>';
        echo '</div>'."\n";
        echo '<div class="bottomline"></div>'."\n\n";
    }

    function readPost($post, $imageOutput, $cebe)
    {
        echo '<div class="lefty">';
        echo '<span class="ptitle">'."\n";
        echo '<a href="?action=readpost&id='.$post->id.'">'
                .$post->title.'</a></span>'."\n";
        echo '<span class="pdata"> by '.$post->author.'</span>';
        echo '<span class="pdata">'.date('Y-m-d H:i', $post->created_at).'</span>'."\n";
        if (ULEVEL == 2) {
            echo '<span class="pdata">';
            echo '<a href="?action=editpost&id='.$post->id.'">Edit</a>';
            echo '</span>'."\n";
        }
        echo '<div class="image">'.$imageOutput.'</div>'."\n";
        echo $cebe->parse($post->body)."\n";
        echo '<div class="clearboth"></div>';
        echo '</div>'."\n";
        echo '<div class="bottomline"></div>'."\n\n";
    }

    function showComments($comments, $cebe)
    {
        foreach($comments as $com) {   
            echo '<div class="comlefty">';
            echo '<span class="ptitle">'.$com->cauthor.':</span>';
            echo '<span class="pdata">'.date('Y-m-d H:i', $com->comment_at).'</span>'."\n";
            if (ULEVEL == 2) {
                echo '<span class="pdata">';
                echo '<a href="?action=editcomment&id='.$com->cid.'">Edit</a>';
                echo '</span>'."\n";
            }
            echo '<div class="pbody">'.$cebe->parse($com->comment).'</div>'."\n";
            echo '</div>'."\n";
            echo '<div class="bottomline"></div>'."\n";
        }
    }

    function listUsers($users)
    {
        echo '<div class="lefty">';
        echo '<table>';
        echo '<tr><th>ID</th><th>Name</th><th>Level</th><th>Posts</th></tr>';
        foreach($users as $row) {
            echo '<tr>';
            echo '<td>'.$row->uid.'</td>';
            echo '<td>'.$row->uname.'</td>';
            echo '<td>'.$row->ulevel.'</td>';
            echo '<td>'.$row->uposts.'</td>';            
            echo '</tr>'."\n";
        }
        echo '</table>';
        echo '</div>';
    }            

    function userLevel($users)
    {
        echo '<div class="lefty">';
        echo '<table>';
        echo '<tr><th>ID</th><th>Name</th><th>Level</th><th></th></tr>';
        foreach($users as $row) {
            echo '<tr>';
            echo '<td>'.$row->uid.'</td>';
            echo '<td>'.$row->uname.'</td>';
            echo '<td>';
            echo '<form method="post">';
            echo '<input name="ulevel" type="number" min="0" max="2"
                value="'.$row->ulevel.'">';
            echo '</td><td>';
            echo '<input name="uid" type="hidden" value="'.$row->uid.'">';
            echo '<input name="submit" type="submit">';
            echo '</form>';
            echo '</td>';
            echo '</tr>'."\n";
        }
        echo '</table>';
        echo '</div>';
    }            

    function listViews($numviews)
    {
        echo '<div class="lefty">';
        echo '<h4>Popular Articles</h4>';
        echo '<table>';
        echo '<tr><th align="left">Title</th><th>Views</th></tr>';
        foreach($numviews as $post) {
            echo '<tr>';
            echo '<td><a href="?action=readpost&id='.
                    $post->id.'">'.$post->title.'</a></td>';
            echo '<td align="right">'.$post->numviews.'</td>';
            echo '</tr>';
        }
        echo '</table><br>';
        echo '</div>';
    }

    function loginForm($error, $valid)
    {
        ?>
        <div class="lefty">
        <br>
        Login
        <br><br>
        <?php
        if ($error) echo $error.'<br><br>';
        ?>
        <form method="post">
            <?php $valid->csrf_create() ?>
            <input name="username" placeholder="Username" required>
            <br>
            <input name="password" type="password" 
                placeholder="Password" required>
            <br>
            <input name="submit" type="submit">
        </form>
        <br>
        </div>
        <?php   
    }

    function signupForm($valid)
    {
        ?>
        <div class="lefty">
        <br>
        SignUp
        <br><br>
        <form method="post">
            <?php $valid->csrf_create() ?>
            <input name="username" placeholder="Username" required>
            <br>
            <input name="password" type="password" placeholder="Password" required>
            <br>
            <img src="captcha/captchaimg.php">
            <br>
            <?php $valid->captcha_input() ?>
            <br>
            <input name="submit" type="submit">
        </form>
        <br>
        </div>
        <?php   
    }

    function changePassForm($valid)
    {
        ?>
        <div class="lefty">
        <br>
        Change Password
        <br><br>
        <form method="post">
            <?php $valid->csrf_create() ?>
            <input name="oldpass"  type="password"
                placeholder="Old Password" required>
            <br>
            <input name="newpass1" type="password" 
                placeholder="New Password" required>
            <br>
            <input name="newpass2" type="password" 
                placeholder="New Password Again" required>
            <br>
            <input name="submit" type="submit">
        </form>
        <br>
        </div>
        <?php   
    }

    function addPostForm($valid)
    {
        ?>
        <div class="lefty">
        <br>
        Add Post
        <br><br>
        <form method="post" enctype="multipart/form-data">
            <?php $valid->csrf_create() ?>
            <input name="title" size="58" placeholder="Title" required>
            <br>
            <textarea name="body" cols="60" rows="8" 
            placeholder="Body" required></textarea>
            <br>
            Image Upload <input name="image" type="file">
            <br>
            <input name="submit" type="submit">
        </form>
        <br>
        </div>
        <?php
    }

    function editPostForm($post) {
        ?>
        <div class="lefty">
        <br>
        Edit Post
        <br><br>
        <form method="post">
            <input name="title" size="58" 
                placeholder="Title" value="<?php echo $post->title ?>" required>
            <br>
            <textarea name="body" cols="60" rows="8" 
                placeholder="Body" required><?php echo $post->body ?></textarea>
            <br>
            <input name="postid" type="hidden" value="<?php echo $post->id ?>">
            <input name="submit" type="submit" value="Edit">
            <input name="submit" type="submit" value="Delete"
                onclick="return confirm('Delete. Are you sure?')">
            <input type="button" value="Cancel"
                onClick="window.location.href=
                '?action=readpost&id=<?php echo $post->id ?>'">
        </form>
        <br>
        </div>
        <?php
    }

    function editCommentForm($com) {
        ?>
        <div class="lefty">
        <br>
        Edit Comment
        <br><br>
        <form method="post">
            <input name="cauthor" size="58" 
                value="<?php echo $com->cauthor ?>" disabled>
            <br>
            <textarea name="comment" cols="60" rows="8"
                    required><?php echo $com->comment ?></textarea>
            <br>
            <input name="comid" type="hidden" value="<?php echo $com->cid ?>">
            <input name="postid" type="hidden" value="<?php echo $com->postid ?>">
            <input name="submit" type="submit" value="Edit">
            <input name="submit" type="submit" value="Delete"
                onclick="return confirm('Delete. Are you sure?')">
            <input type="button" value="Cancel"
                onClick="window.location.href=
                '?action=readpost&id=<?php echo $com->postid ?>'">
        </form>
        <br>
        </div>
        <?php
    }

    function addCommentForm($valid)
    {
        ?>
        <div class="lefty">
        <form method="post">
        <?php
            $valid->csrf_create();
            if (UNAME) {
                echo '<input name="cauthor" size="58" 
                value="'.UNAME.'" readonly>';
            } else {
                echo "\n".'<input name="cauthor" size="58" 
                        placeholder="Your Name" required>'."\n";
            }
        ?>
            <br>
            <textarea name="comment" cols="60" rows="4" 
            placeholder="Your Comment" required></textarea>
            <br>
            <img src="captcha/captchaimg.php">
            <br>
            <?php $valid->captcha_input() ?>
            <br>
            <input name="submit" type="submit">
            <input type="button" value="Cancel"
                onClick="window.location.href='index.php'">
        </form>
        </div>
        <?php
    }

}
