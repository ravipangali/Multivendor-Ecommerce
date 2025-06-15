<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class TinyMCEController extends Controller
{
    /**
     * Test method to verify controller is working
     */
    public function test()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'TinyMCE controller is working',
            'timestamp' => now(),
            'upload_url' => route('admin.tinymce.upload')
        ]);
    }

    /**
     * Handle TinyMCE image upload
     */
    public function upload(Request $request)
    {
        try {
            // Log the incoming request
            Log::info('TinyMCE upload request received', [
                'has_file' => $request->hasFile('file'),
                'files' => $request->allFiles(),
                'headers' => $request->headers->all()
            ]);

            // Validate the request
            $request->validate([
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120' // 5MB max
            ]);

            $file = $request->file('file');

            // Create directory if it doesn't exist
            $directory = 'tinymce_images';
            if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }

            // Generate unique filename
            $filename = $directory . '/' . Str::random(10) . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Store the file
            $path = $file->storeAs('public', $filename);

            if (!$path) {
                throw new \Exception('Failed to store file');
            }

            // Generate the URL
            $url = asset('storage/' . $filename);

            // Log successful upload
            Log::info('TinyMCE image uploaded successfully', [
                'filename' => $filename,
                'url' => $url,
                'size' => $file->getSize()
            ]);

            // Return the URL for TinyMCE
            return response()->json([
                'location' => $url
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('TinyMCE upload validation failed', [
                'errors' => $e->errors(),
                'file_info' => $request->hasFile('file') ? [
                    'name' => $request->file('file')->getClientOriginalName(),
                    'size' => $request->file('file')->getSize(),
                    'mime' => $request->file('file')->getMimeType()
                ] : 'No file'
            ]);

            return response()->json([
                'error' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);

        } catch (\Exception $e) {
            Log::error('TinyMCE upload failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
