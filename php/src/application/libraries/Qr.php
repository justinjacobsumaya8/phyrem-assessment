<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Qr
{
    public function generateQR($employeeId)
    {
        $data = "employee-qr-{$employeeId}";
        $size = '200x200';
        $qr = imagecreatefrompng('https://chart.googleapis.com/chart?cht=qr&chld=H|1&chs=' . $size . '&chl=' . urlencode($data));

        $rootPath = $_SERVER['DOCUMENT_ROOT'] . "/uploads/employees";
        chmod($rootPath, 0777);

        $fileName = "qr-{$employeeId}.png";
        $path =  $rootPath . "/" . $fileName;
        imagepng($qr, $path);
        imagedestroy($qr);

        return $fileName;
    }
}
