<?php

chdir('../');

if (is_file('include/config.php')) {
    header('location:../index.php');
    exit();
}

if (!isset($_POST['step'])) {
    include('install/html/step1.php');
    exit();
}

if ($_POST['step'] == '1') {
    $driver = $_POST['driver'];
    include('install/html/step2head.php');
    include('install/html/'.$driver.'.php');
    include('install/html/step2foot.php');
    exit();
}

if ($_POST['step'] == '2') {
    $blog_title = $_POST['blog_title'];
    $perpage    = $_POST['perpage'];
    $driver = $_POST['driver'];
    $name   = $_POST['dbname'];
    if ($driver == 'sqlite') {
        $host = $user = $pass = '';
    }
    if ($driver == 'mysql') {
        $host = $_POST['dbhost'];
        $user = $_POST['dbuser'];
        $pass = $_POST['dbpass'];
    }
    $admin    = $_POST['admin'];
    $password = $_POST['password'];

    include('install/class/DSNCreator.php');
    $pdo_dsn = new DSNCreator($blog_title, $perpage, $admin, $password);
    $pdo_dsn->createDSN($driver, $host, $name, $user, $pass);
    $pdo_dsn->configWrite();
    $pdo_dsn->createTables();

    include('install/html/finish.php');
}
exit();
