<?php

namespace App\Business;

class ScreenBusiness
{
    public function generateQRCodeKey(int $length = 32): string
    {
        return bin2hex(random_bytes($length/2));
    }
}