<?php

namespace Backend\Converters;

use Backend\Interfaces\ConverterInterface;
use DOMDocument;

class CSVConverter implements ConverterInterface
{
    const fileType = '.cvs';

    public function convert(array $file): void
    {
        $html = file_get_contents($file['tmp_name']);
        $dom = new DOMDocument();
        @$dom->loadHTML($html);
        
        $data = [];
        $data['trackingNumber'] = $dom->getElementById('wo_number')->textContent;
        $data['PONumber'] = $dom->getElementById('po_number')->textContent;
        $scheduledDate = $dom->getElementById('scheduled_date')->textContent;
        $data['dateTime'] = $this->getTimeStamp($scheduledDate);
        $data['customer'] = trim($dom->getElementById('customer')->textContent);
        $data['trade'] = $dom->getElementById('trade')->textContent;
        $data['nte'] = $dom->getElementById('nte')->textContent;
        $data['storeID'] = $dom->getElementById('location_name')->textContent;
        $locationAddress = trim($dom->getElementById('location_address')->textContent);
        $address = $this->getAddress($locationAddress);
        $data['street'] = $address['street'];
        $data['state'] = $address['state'];
        $data['zip code'] = $address['zip_code'];
        $data['city'] = $address['city'];
        $data['phone'] = $dom->getElementById('location_phone')->textContent;
        // var_dump($data['customer']);
        $this->createCSV($data);
    }

    private function createCSV($data): void
    {
        $fileName = 'customer' . self::fileType;
        header('Content-Type: text/csv');
        header("Content-Disposition: attachment; filename=$fileName");
        $output = fopen("php://output", 'w');
        $headers = array_keys($data);
        $values = array_values($data);
        fputcsv($output, $headers);
        fputcsv($output, $values);
        fclose($output);
    }

    private function getTimeStamp($dateTime): string
    {
        $dateTime = trim($dateTime, " \n\r\t\0\x0b\xa0");
        $dateTime = date_parse($dateTime);
        $date = array_slice($dateTime, 0, 3);
        $time = array_slice($dateTime, 3, 2);
        return implode('-', $date) . ' ' . implode(':', $time);
    }

    private function getAddress($address): array
    {
        $addressArray = [];
        preg_match('/Main street [1-9]*/', $address, $match);
        $addressArray['street'] = $match[0];
        preg_match('/Chicago/', $address, $match);
        $addressArray['city'] = $match[0];
        preg_match('/IL/', $address, $match);
        $addressArray['state'] = $match[0];
        preg_match('/\d{5}/', $address, $match);
        $addressArray['zip_code'] = $match[0];
        return $addressArray;
    }
}
