<?php

class Database extends PDO {

    function __construct($dsn, $db_user, $db_pass)
    {
        parent::__construct($dsn, $db_user, $db_pass);
        $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    }

    function countPosts()
    {
        $sql = "SELECT count(*) FROM posts";
        return $this->query($sql)->fetchColumn();
    }

    function getPosts($offset, $perpage)
    {
        $sql = "SELECT * FROM posts ORDER BY created_at DESC LIMIT $offset, $perpage";
        return $this->query($sql)->fetchAll();
    }

    function getPostById($id)
    {
        $sql = "SELECT * FROM posts WHERE id=$id";
        return $this->query($sql)->fetch();
    }

    function getUserByName($name)
    {
        $sql = "SELECT * FROM logins WHERE uname='$name'";
        return $this->query($sql)->fetch();
    }

    function getUsers()
    {
        $sql = "SELECT * FROM logins";
        return $this->query($sql)->fetchAll();        
    }

    function getComNum($postid)
    {
        $sql = "SELECT COUNT(*) FROM comments WHERE postid=$postid";
        return $this->query($sql)->fetchColumn();
    }

    function getCommentById($id)
    {
        $sql = "SELECT * FROM comments WHERE cid=$id";
        return $this->query($sql)->fetch();
    }

    function getComments($postid)
    {
        $sql = "SELECT * FROM comments WHERE postid=$postid";
        return $this->query($sql)->fetchAll();
    }

    function getViews()
    {
        $sql = "SELECT id, title, numviews FROM posts 
                ORDER BY numviews DESC, id DESC LIMIT 10";
        return $this->query($sql)->fetchAll();
    }

    function insertUser($name, $pass)
    {
        $passhash = password_hash($pass, PASSWORD_BCRYPT);
        $sql = "INSERT INTO logins (uname, upass) VALUES (?, ?)";
        $stmt = $this->prepare($sql);
        $stmt->execute([$name, $passhash]);
    }

    function insertPost($title, $body)
    {
        $author = UNAME;
        $time = time();
        $sql = "INSERT INTO posts (title, body, author, created_at) 
                            VALUES (?, ?, ?, ?)";
        $stmt = $this->prepare($sql);
        $stmt->execute([$title, $body, $author, $time]);
        return $this->lastInsertId();
    }

    function insertComment($postid, $cauthor, $comment)
    {
        $time = time();
        $sql = "INSERT INTO comments (postid, cauthor, comment, comment_at) 
                            VALUES (?, ?, ?, ?)";
        $stmt = $this->prepare($sql);
        $stmt->execute([$postid, $cauthor, $comment, $time]);
    }

    function updatePassword($uid, $newpass)
    {
        $sql = "UPDATE logins SET upass='$newpass' WHERE uid=$uid";
        $this->exec($sql);
    }

    function updatePost($postid, $title, $body)
    {
        $sql = "UPDATE posts SET title=?, body=? WHERE id=$postid";
        $stmt = $this->prepare($sql);
        $stmt->execute([$title, $body]);
    }

    function updateComment($comid, $comment)
    {
        $sql = "UPDATE comments SET comment=? WHERE cid=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$comment, $comid]);
    }

    function updateLevel($uid, $ulevel)
    {
        $sql = "UPDATE logins SET ulevel=? WHERE uid=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$ulevel, $uid]);
    }

    function increaseUPosts($uname)
    {
        $sql = "UPDATE logins SET uposts = uposts + 1 WHERE uname=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$uname]);
    }

    function decreaseUPosts($uname)
    {
        $sql = "UPDATE logins SET uposts = uposts - 1 WHERE uname=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$uname]);
    }

    function increaseViews($id)
    {
        $sql = "UPDATE posts SET numviews = numviews + 1 WHERE id=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$id]);
    }

    function storeImage($pid, $image)
    {
        $sql = "UPDATE posts SET image = ? WHERE id=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$image, $pid]);
    }

    function deletePost($uname, $postid)
    {
        $this->decreaseUPosts($uname);
        $sql = "DELETE FROM posts WHERE id=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$postid]);
    }

    function deletePostComments($postid)
    {
        $sql = "DELETE FROM comments WHERE postid=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$postid]);
    }

    function deleteComment($comid)
    {
        $sql = "DELETE FROM comments WHERE cid=?";
        $stmt = $this->prepare($sql);
        $stmt->execute([$comid]);
    }

}
