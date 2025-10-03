<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadService
{
    private array $allowedTypes = [
        'images' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'documents' => ['pdf', 'doc', 'docx', 'txt'],
        'videos' => ['mp4', 'avi', 'mov', 'wmv']
    ];

    private array $maxSizes = [
        'images' => 5120, // 5MB in KB
        'documents' => 10240, // 10MB in KB
        'videos' => 51200 // 50MB in KB
    ];

    /**
     * Upload a file and return the stored path
     */
    public function uploadFile(UploadedFile $file, string $directory = 'uploads', string $type = 'images'): array
    {
        try {
            // Validate file type
            $this->validateFileType($file, $type);
            
            // Validate file size
            $this->validateFileSize($file, $type);
            
            // Generate unique filename
            $filename = $this->generateUniqueFilename($file);
            
            // Create full path
            $path = $directory . '/' . $filename;
            
            // Store file
            $storedPath = Storage::disk('public')->put($path, file_get_contents($file));
            
            if (!$storedPath) {
                throw new \Exception('Failed to store file');
            }
            
            Log::info('File uploaded successfully', [
                'original_name' => $file->getClientOriginalName(),
                'stored_path' => $storedPath,
                'size' => $file->getSize(),
                'type' => $file->getMimeType()
            ]);
            
            return [
                'success' => true,
                'path' => $storedPath,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'url' => Storage::disk('public')->url($storedPath)
            ];
            
        } catch (\Exception $e) {
            Log::error('File upload failed', [
                'error' => $e->getMessage(),
                'file' => $file->getClientOriginalName()
            ]);
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload multiple files
     */
    public function uploadMultipleFiles(array $files, string $directory = 'uploads', string $type = 'images'): array
    {
        $results = [];
        $successful = 0;
        $failed = 0;

        foreach ($files as $file) {
            $result = $this->uploadFile($file, $directory, $type);
            $results[] = $result;
            
            if ($result['success']) {
                $successful++;
            } else {
                $failed++;
            }
        }

        return [
            'results' => $results,
            'summary' => [
                'total' => count($files),
                'successful' => $successful,
                'failed' => $failed
            ]
        ];
    }

    /**
     * Delete a file
     */
    public function deleteFile(string $path): bool
    {
        try {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
                Log::info('File deleted successfully', ['path' => $path]);
                return true;
            }
            return false;
        } catch (\Exception $e) {
            Log::error('File deletion failed', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Validate file type
     */
    private function validateFileType(UploadedFile $file, string $type): void
    {
        $extension = strtolower($file->getClientOriginalExtension());
        
        if (!isset($this->allowedTypes[$type])) {
            throw new \Exception("Invalid file type category: {$type}");
        }
        
        if (!in_array($extension, $this->allowedTypes[$type])) {
            $allowed = implode(', ', $this->allowedTypes[$type]);
            throw new \Exception("File type {$extension} not allowed. Allowed types: {$allowed}");
        }
    }

    /**
     * Validate file size
     */
    private function validateFileSize(UploadedFile $file, string $type): void
    {
        $sizeInKb = $file->getSize() / 1024;
        $maxSize = $this->maxSizes[$type] ?? 5120;
        
        if ($sizeInKb > $maxSize) {
            $maxSizeMb = $maxSize / 1024;
            throw new \Exception("File size exceeds maximum allowed size of {$maxSizeMb}MB");
        }
    }

    /**
     * Generate unique filename
     */
    private function generateUniqueFilename(UploadedFile $file): string
    {
        $extension = $file->getClientOriginalExtension();
        $basename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $basename = Str::slug($basename);
        
        return $basename . '_' . time() . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Get file info
     */
    public function getFileInfo(string $path): array
    {
        if (!Storage::disk('public')->exists($path)) {
            return ['exists' => false];
        }

        return [
            'exists' => true,
            'size' => Storage::disk('public')->size($path),
            'last_modified' => Storage::disk('public')->lastModified($path),
            'url' => Storage::disk('public')->url($path)
        ];
    }

    /**
     * Get allowed file types for a category
     */
    public function getAllowedTypes(string $type): array
    {
        return $this->allowedTypes[$type] ?? [];
    }

    /**
     * Get max file size for a category
     */
    public function getMaxSize(string $type): int
    {
        return $this->maxSizes[$type] ?? 5120;
    }
}