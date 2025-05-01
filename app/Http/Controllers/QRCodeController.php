<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Writer;

class QrCodeController extends Controller
{
    public function index()
    {
        // Cek apakah folder qrcodes ada
        $qrCodeDir = public_path('qrcodes');
        
        if (!File::exists($qrCodeDir)) {
            // Jika folder tidak ada, tampilkan pesan "QR Code belum ada"
            return view('pages.qrcode.index', ['message' => 'QR Code belum ada']);
        }

        // Ambil semua file QR code yang ada di folder public/qrcodes
        $qrCodes = File::files($qrCodeDir);

        // Mengirimkan file QR code ke view
        return view('pages.qrcode.index', compact('qrCodes'));
    }

    public function generateQrCode(Request $request)
    {
          // Validasi input dari user
          $validatedData = $request->validate([
            'lokasi' => 'required|string',
        ]);
    
        // Data yang akan dimasukkan ke QR
        // Hanya menggunakan nilai dari 'lokasi' saja, tanpa JSON
        $data = $validatedData['lokasi'];
    
        // Buat renderer SVG
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
    
        // Tulis QR code
        $writer = new Writer($renderer);
        $svgData = $writer->writeString($data);  // Hanya data lokasi
    
        // Simpan ke file
        $fileName = 'qr_' . $request->user()->id . '_' . now()->timestamp . '.svg';
        $filePath = public_path('qrcodes/' . $fileName);
        
        // Pastikan folder qrcodes ada
        File::ensureDirectoryExists(public_path('qrcodes'));
        file_put_contents($filePath, $svgData);
        
        // Response JSON
        return response()->json([
            'message' => 'QR Code berhasil dibuat',
            'file' => asset('qrcodes/' . $fileName),
        ]);
    }
    
}
