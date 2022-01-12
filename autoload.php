<?php

spl_autoload_register(function ($class_name) {
    include realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR. $class_name . '.php';
});