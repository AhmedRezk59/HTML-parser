<?php
require('./autoload.php');

use Backend\ConverterFactory;

if (isset($_POST['submit'])) {
    if (!isset($_FILES['file']) || !isset($_POST['fileType'])) {
        throw new \Exception('Please choose the file and the file type');
    } else {
        $converter = new ConverterFactory();
        if ($converter) $converter->decide($_FILES['file'] ?? null, $_POST['fileType'] ?? null);
    }
}
