<?php

if (!is_file('include/config.php')) {
    header('location:install/install.php');
    exit();
}

require 'include/config.php';
require 'include/classloader.php';
require 'include/cookiecheck.php';
require 'include/router.php';
