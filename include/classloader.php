<?php

spl_autoload_register(function ($classname) {
    require 'class/'.$classname.'.php'; });
require 'class/markdown/autoload.php';
$db   = new Database($dsn, $db_user, $db_pass);
$view = new View();
$valid= new Valid();
$pagin= new Pagin();
$cebe = new \cebe\markdown\GithubMarkdown();
