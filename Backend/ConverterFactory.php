<?php

namespace Backend;

use Backend\Converters\CSVConverter;

class ConverterFactory
{
    public function decide($file, $fileType)
    {
        switch ($fileType) {
            case 'CSV':
                $converter = new CSVConverter();
                break;
            default:
                throw new \Exception('This file type is not supported');
                break;
        }
        if ($converter) $converter->convert($file);
    }
}
