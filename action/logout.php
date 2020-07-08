<?php

if (!UNAME) {
    header('location:index.php');
    exit();
}

setcookie('userdata', '', time()-3600);
header('location:index.php');
exit();
