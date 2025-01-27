<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;

class BarcodeController extends Controller
{
    // Method to generate and save barcode
    public function saveBarcode($kodePeminjaman)
    {
        // Generate the barcode image in PNG format
        $barcode = DNS1D::getBarcodePNG($kodePeminjaman, 'C128');

        // Define the file path where the barcode will be saved
        $filePath = public_path("barcodes/{$kodePeminjaman}.png");

        // Save the barcode as a PNG file
        file_put_contents($filePath, base64_decode($barcode));

        // Return the file path if needed, or just confirm success
        return response()->json([
            'message' => 'Barcode generated successfully!',
            'file_path' => $filePath
        ]);
    }
}
