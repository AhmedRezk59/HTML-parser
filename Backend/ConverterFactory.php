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
                throw new \Exception('This file type doesn\'t exist');
                break;
        }
        if ($converter) return $converter->convert($file);
    }
}
