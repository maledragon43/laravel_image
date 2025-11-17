<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;

class ImageController extends Controller
{
    /**
     * Display the upload page
     */
    public function index()
    {
        // Retrieve all uploaded images from session
        $uploadedImages = [];
        $allSessionData = Session::all();
        
        foreach ($allSessionData as $key => $value) {
            if (strpos($key, 'image_') === 0 && is_array($value)) {
                $imageId = str_replace('image_', '', $key);
                $imageData = $value;
                
                // Check if image file still exists
                if (isset($imageData['working_path']) && Storage::disk('public')->exists($imageData['working_path'])) {
                    $uploadedImages[] = [
                        'id' => $imageId,
                        'filename' => $imageData['filename'] ?? 'unknown',
                        'url' => Storage::disk('public')->url($imageData['working_path']),
                    ];
                }
            }
        }
        
        return view('images.index', ['uploadedImages' => $uploadedImages]);
    }

    /**
     * Handle image upload (up to 5 images)
     * 
     * This method uses Intervention Image to process uploaded images.
     * It validates that only images are uploaded and limits to 5 files.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240', // 10MB max per image
        ]);

        $uploadedImages = [];

        foreach ($request->file('images') as $file) {
            // Generate unique filename
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            
            // Use Intervention Image to process and store the image
            // This ensures the image is valid and can be manipulated later
            $image = Image::make($file);
            
            // Store original image
            $path = 'images/originals/' . $filename;
            Storage::disk('public')->put($path, $image->encode());
            
            // Store working copy for editing (we'll maintain history for undo)
            $workingPath = 'images/working/' . $filename;
            Storage::disk('public')->put($workingPath, $image->encode());
            
            $imageId = Str::uuid()->toString();
            
            // Store image metadata in session for undo history
            Session::put("image_{$imageId}", [
                'filename' => $filename,
                'original_path' => $path,
                'working_path' => $workingPath,
                'history' => [], // Store operation history for undo
            ]);
            
            $uploadedImages[] = [
                'id' => $imageId,
                'filename' => $filename,
                'url' => Storage::disk('public')->url($workingPath),
            ];
        }

        return response()->json([
            'success' => true,
            'images' => $uploadedImages,
        ]);
    }

    /**
     * Display the edit page for a specific image
     */
    public function edit($id)
    {
        $imageData = Session::get("image_{$id}");
        
        if (!$imageData) {
            return redirect()->route('images.index')
                ->with('error', 'Image not found. Please upload again.');
        }

        $imageUrl = Storage::disk('public')->url($imageData['working_path']);
        
        return view('images.edit', [
            'imageId' => $id,
            'imageUrl' => $imageUrl,
        ]);
    }

    /**
     * Rotate image 90 degrees clockwise
     * 
     * Uses Intervention Image's rotate() method to rotate the image.
     * Maintains history for undo functionality.
     */
    public function rotate($id, Request $request)
    {
        $imageData = Session::get("image_{$id}");
        
        if (!$imageData) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        // Load the current working image using Intervention Image
        $workingPath = storage_path('app/public/' . $imageData['working_path']);
        $image = Image::make($workingPath);
        
        // Save current state to history for undo
        $historyPath = 'images/history/' . Str::uuid() . '.' . pathinfo($imageData['filename'], PATHINFO_EXTENSION);
        Storage::disk('public')->put($historyPath, $image->encode());
        
        // Rotate 90 degrees clockwise using Intervention Image
        $image->rotate(-90); // Negative rotates clockwise
        
        // Save rotated image
        Storage::disk('public')->put($imageData['working_path'], $image->encode());
        
        // Update session with new history (store the history file path)
        $imageData['history'][] = $historyPath;
        Session::put("image_{$id}", $imageData);
        
        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($imageData['working_path']) . '?t=' . time(),
            'canUndo' => count($imageData['history']) > 0,
        ]);
    }

    /**
     * Crop image based on coordinates
     * 
     * Uses Intervention Image's crop() method to crop the image.
     * Receives x, y, width, height from the frontend crop tool.
     */
    public function crop($id, Request $request)
    {
        $request->validate([
            'x' => 'required|numeric|min:0',
            'y' => 'required|numeric|min:0',
            'width' => 'required|numeric|min:1',
            'height' => 'required|numeric|min:1',
        ]);

        $imageData = Session::get("image_{$id}");
        
        if (!$imageData) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        // Load the current working image using Intervention Image
        $workingPath = storage_path('app/public/' . $imageData['working_path']);
        $image = Image::make($workingPath);
        
        // Save current state to history for undo
        $historyPath = 'images/history/' . Str::uuid() . '.' . pathinfo($imageData['filename'], PATHINFO_EXTENSION);
        Storage::disk('public')->put($historyPath, $image->encode());
        
        // Crop using Intervention Image's crop method
        // Parameters: width, height, x, y
        $image->crop(
            (int) $request->width,
            (int) $request->height,
            (int) $request->x,
            (int) $request->y
        );
        
        // Save cropped image
        Storage::disk('public')->put($imageData['working_path'], $image->encode());
        
        // Update session with new history (store the history file path)
        $imageData['history'][] = $historyPath;
        Session::put("image_{$id}", $imageData);
        
        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($imageData['working_path']) . '?t=' . time(),
            'canUndo' => count($imageData['history']) > 0,
        ]);
    }

    /**
     * Undo last operation
     * 
     * Restores the previous state from history.
     */
    public function undo($id)
    {
        $imageData = Session::get("image_{$id}");
        
        if (!$imageData || empty($imageData['history'])) {
            return response()->json([
                'success' => false,
                'message' => 'No history to undo',
            ]);
        }

        // Get the last history entry
        $lastHistory = array_pop($imageData['history']);
        
        // Restore from history
        if (Storage::disk('public')->exists($lastHistory)) {
            $historyContent = Storage::disk('public')->get($lastHistory);
            Storage::disk('public')->put($imageData['working_path'], $historyContent);
        }
        
        // Update session
        Session::put("image_{$id}", $imageData);
        
        return response()->json([
            'success' => true,
            'url' => Storage::disk('public')->url($imageData['working_path']) . '?t=' . time(),
            'canUndo' => count($imageData['history']) > 0,
        ]);
    }

    /**
     * Save the final edited image
     * 
     * Moves the working image to the final storage location.
     */
    public function save($id)
    {
        $imageData = Session::get("image_{$id}");
        
        if (!$imageData) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        // Copy working image to final location
        $finalPath = 'images/final/' . $imageData['filename'];
        $workingContent = Storage::disk('public')->get($imageData['working_path']);
        Storage::disk('public')->put($finalPath, $workingContent);
        
        // Keep image in session so it remains visible on upload page
        // User can still delete it later if needed
        
        return response()->json([
            'success' => true,
            'message' => 'Image saved successfully',
            'url' => Storage::disk('public')->url($finalPath),
        ]);
    }

    /**
     * Delete an uploaded image
     * 
     * Removes the image files and session data.
     */
    public function destroy($id)
    {
        $imageData = Session::get("image_{$id}");
        
        if (!$imageData) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        // Delete all image files
        if (isset($imageData['original_path']) && Storage::disk('public')->exists($imageData['original_path'])) {
            Storage::disk('public')->delete($imageData['original_path']);
        }
        
        if (isset($imageData['working_path']) && Storage::disk('public')->exists($imageData['working_path'])) {
            Storage::disk('public')->delete($imageData['working_path']);
        }
        
        // Delete history files
        if (isset($imageData['history']) && is_array($imageData['history'])) {
            foreach ($imageData['history'] as $historyPath) {
                if (Storage::disk('public')->exists($historyPath)) {
                    Storage::disk('public')->delete($historyPath);
                }
            }
        }
        
        // Delete final image if it exists
        $finalPath = 'images/final/' . ($imageData['filename'] ?? '');
        if (Storage::disk('public')->exists($finalPath)) {
            Storage::disk('public')->delete($finalPath);
        }
        
        // Remove from session
        Session::forget("image_{$id}");
        
        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully',
        ]);
    }

    /**
     * Verify stored images on the server
     * 
     * Lists all images in storage directories with their metadata.
     */
    public function verify()
    {
        $storageInfo = [
            'working' => [],
            'originals' => [],
            'history' => [],
            'final' => [],
        ];

        // Check working images
        $workingFiles = Storage::disk('public')->files('images/working');
        foreach ($workingFiles as $file) {
            $storageInfo['working'][] = [
                'path' => $file,
                'url' => Storage::disk('public')->url($file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
                'last_modified_formatted' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
            ];
        }

        // Check original images
        $originalFiles = Storage::disk('public')->files('images/originals');
        foreach ($originalFiles as $file) {
            $storageInfo['originals'][] = [
                'path' => $file,
                'url' => Storage::disk('public')->url($file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
                'last_modified_formatted' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
            ];
        }

        // Check history images
        $historyFiles = Storage::disk('public')->files('images/history');
        foreach ($historyFiles as $file) {
            $storageInfo['history'][] = [
                'path' => $file,
                'url' => Storage::disk('public')->url($file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
                'last_modified_formatted' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
            ];
        }

        // Check final images
        $finalFiles = Storage::disk('public')->files('images/final');
        foreach ($finalFiles as $file) {
            $storageInfo['final'][] = [
                'path' => $file,
                'url' => Storage::disk('public')->url($file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
                'last_modified_formatted' => date('Y-m-d H:i:s', Storage::disk('public')->lastModified($file)),
            ];
        }

        // Count totals
        $totals = [
            'working' => count($storageInfo['working']),
            'originals' => count($storageInfo['originals']),
            'history' => count($storageInfo['history']),
            'final' => count($storageInfo['final']),
        ];

        return view('images.verify', [
            'storageInfo' => $storageInfo,
            'totals' => $totals,
        ]);
    }
}

