<?php

namespace App\Services;

use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrService
{
    public static function extractText($imagePath)
    {
        return (new TesseractOCR($imagePath))
            ->lang('eng') 
            ->run();
    }
}
