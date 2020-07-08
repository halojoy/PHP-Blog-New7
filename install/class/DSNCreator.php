<?php

class DSNCreator
{
    public $blog_title;
    public $perpage;
    public $admin;
    public $password;
    public $driver;
    public $dsn;
    public $user;
    public $pass;

    public function __construct($blog_title, $perpage, $admin, $password)
    {
        // Blog Title
        $this->blog_title = $blog_title;
        $this->perpage = $perpage;
        // Admin account
        $this->admin = $admin;
        $this->password = $password;
    }

    public function createDSN($driver, $host, $name, $user, $pass)
    {
        // Create DSN
        $this->driver = $driver;
        if ($driver == 'sqlite') {
            $this->dsn = 'sqlite:' . $name;
        }
        if ($driver == 'mysql') {
            $this->dsn = 'mysql:host=' . $host . ';dbname=' . $name;
        }
        $this->user = $user;
        $this->pass = $pass;

        // Create MySQL Database if not exists
        if ($driver == 'mysql') {
            $dsn = 'mysql:host='.$host;
            $db = new PDO($dsn, $user, $pass);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "CREATE DATABASE IF NOT EXISTS $name";
            $db->exec($sql);
            $db = null;
        }
    }

    public function configWrite()
    {
        $write = <<<WRITE
<?php

define('BLOGTITLE', '$this->blog_title');
\$perpage = $this->perpage;

\$driver  = '$this->driver';
\$dsn     = '$this->dsn';
\$db_user = '$this->user';
\$db_pass = '$this->pass';\n
WRITE;
        file_put_contents('include/config.php', $write);
    }

    public function createTables()
    {
        require('include/config.php');
        $db = new PDO($dsn, $db_user, $db_pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Create SQLite Tables
        if ($driver == 'sqlite') {
            $sql = "CREATE TABLE posts (
                id INTEGER PRIMARY KEY,
                title TEXT,
                body TEXT,
                image TEXT DEFAULT '',
                author TEXT,
                numviews INTEGER DEFAULT 0,
                created_at INTEGER
            )";
            $db->exec($sql);
            $sql = "CREATE TABLE comments (
                cid INTEGER PRIMARY KEY,
                postid INTEGER,
                comment TEXT,
                cauthor TEXT,
                comment_at INTEGER
            )";
            $db->exec($sql);
            $sql = "CREATE TABLE logins (
                uid INTEGER PRIMARY KEY,
                uname TEXT UNIQUE COLLATE NOCASE,
                upass TEXT,
                ulevel INTEGER DEFAULT 1,
                uposts INTEGER DEFAULT 0
            )";        // ulevel: 0=disabled 1=can post 2=admin, everything
            $db->exec($sql);
        }

        // Create MySQL Tables
        if ($driver == 'mysql') {
            $sql = "CREATE TABLE posts (
                id INTEGER AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(100),
                body  TEXT,
                image VARCHAR(100) DEFAULT '',
                author VARCHAR(100),
                numviews INTEGER DEFAULT 0,
                created_at INTEGER UNSIGNED
            )";
            $db->exec($sql);
            $sql = "CREATE TABLE comments (
                cid INTEGER AUTO_INCREMENT PRIMARY KEY,
                postid INTEGER,
                comment TEXT,
                cauthor VARCHAR(100),
                comment_at INTEGER UNSIGNED
            )";
            $db->exec($sql);
            $sql = "CREATE TABLE logins (
                uid INTEGER AUTO_INCREMENT PRIMARY KEY,
                uname VARCHAR(100) UNIQUE,
                upass VARCHAR(100),
                ulevel INTEGER DEFAULT 1,
                uposts INTEGER DEFAULT 0
            )";        // ulevel: 0=disabled 1=can post 2=admin, everything
            $db->exec($sql);
        }

        $passhash = password_hash($this->password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO logins (uname, upass, ulevel)
                        VALUES ('{$this->admin}', '$passhash', 2)";
        $db->exec($sql);        

        $db = null;
    }

}
