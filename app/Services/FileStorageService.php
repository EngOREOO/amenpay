<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

class FileStorageService
{
    protected $config;
    protected $disk;

    public function __construct()
    {
        $this->config = config('filesystems');
        $this->disk = config('filesystems.default');
    }

    /**
     * Upload and process user avatar
     */
    public function uploadAvatar(UploadedFile $file, int $userId): array
    {
        try {
            $validation = $this->validateImage($file, [
                'max_size' => 5 * 1024 * 1024, // 5MB
                'allowed_types' => ['jpg', 'jpeg', 'png', 'webp'],
                'min_dimensions' => [100, 100],
                'max_dimensions' => [2048, 2048]
            ]);

            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'message' => 'Image validation failed',
                    'errors' => $validation['errors']
                ];
            }

            $filename = $this->generateAvatarFilename($userId, $file->getClientOriginalExtension());
            $path = "avatars/{$filename}";

            // Process and optimize image
            $processedImage = $this->processAvatar($file);
            
            // Store original and optimized versions
            $originalPath = $this->storeFile($processedImage['original'], $path);
            $optimizedPath = $this->storeFile($processedImage['optimized'], $path . '_optimized');

            // Generate thumbnail
            $thumbnailPath = $this->generateThumbnail($processedImage['original'], $path . '_thumb');

            Log::info('Avatar uploaded successfully', [
                'user_id' => $userId,
                'original_path' => $originalPath,
                'optimized_path' => $optimizedPath,
                'thumbnail_path' => $thumbnailPath
            ]);

            return [
                'success' => true,
                'message' => 'Avatar uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'original_url' => $this->getPublicUrl($originalPath),
                    'optimized_url' => $this->getPublicUrl($optimizedPath),
                    'thumbnail_url' => $this->getPublicUrl($thumbnailPath),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to upload avatar', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to upload avatar',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload KYC documents
     */
    public function uploadKycDocument(UploadedFile $file, int $userId, string $documentType): array
    {
        try {
            $validation = $this->validateDocument($file, [
                'max_size' => 10 * 1024 * 1024, // 10MB
                'allowed_types' => ['pdf', 'jpg', 'jpeg', 'png'],
                'document_types' => ['national_id', 'passport', 'driving_license', 'utility_bill']
            ]);

            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'message' => 'Document validation failed',
                    'errors' => $validation['errors']
                ];
            }

            $filename = $this->generateKycFilename($userId, $documentType, $file->getClientOriginalExtension());
            $path = "kyc/{$userId}/{$documentType}/{$filename}";

            // Store document
            $storedPath = $this->storeFile($file, $path);

            // If it's an image, create a preview
            $previewPath = null;
            if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                $previewPath = $this->generateDocumentPreview($file, $path . '_preview');
            }

            Log::info('KYC document uploaded successfully', [
                'user_id' => $userId,
                'document_type' => $documentType,
                'path' => $storedPath,
                'preview_path' => $previewPath
            ]);

            return [
                'success' => true,
                'message' => 'KYC document uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'document_url' => $this->getPublicUrl($storedPath),
                    'preview_url' => $previewPath ? $this->getPublicUrl($previewPath) : null,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'document_type' => $documentType
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to upload KYC document', [
                'user_id' => $userId,
                'document_type' => $documentType,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to upload KYC document',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload transaction receipt
     */
    public function uploadReceipt(UploadedFile $file, int $transactionId): array
    {
        try {
            $validation = $this->validateImage($file, [
                'max_size' => 5 * 1024 * 1024, // 5MB
                'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf'],
                'min_dimensions' => [100, 100],
                'max_dimensions' => [4096, 4096]
            ]);

            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'message' => 'Receipt validation failed',
                    'errors' => $validation['errors']
                ];
            }

            $filename = $this->generateReceiptFilename($transactionId, $file->getClientOriginalExtension());
            $path = "receipts/{$transactionId}/{$filename}";

            // Store receipt
            $storedPath = $this->storeFile($file, $path);

            // Generate preview if it's an image
            $previewPath = null;
            if (in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png'])) {
                $previewPath = $this->generateReceiptPreview($file, $path . '_preview');
            }

            Log::info('Receipt uploaded successfully', [
                'transaction_id' => $transactionId,
                'path' => $storedPath,
                'preview_path' => $previewPath
            ]);

            return [
                'success' => true,
                'message' => 'Receipt uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'receipt_url' => $this->getPublicUrl($storedPath),
                    'preview_url' => $previewPath ? $this->getPublicUrl($previewPath) : null,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to upload receipt', [
                'transaction_id' => $transactionId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to upload receipt',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Upload bulk files
     */
    public function uploadBulkFiles(array $files, string $directory, array $options = []): array
    {
        $results = [];
        $successCount = 0;
        $totalSize = 0;

        foreach ($files as $file) {
            $result = $this->uploadFile($file, $directory, $options);
            $results[] = $result;
            
            if ($result['success']) {
                $successCount++;
                $totalSize += $result['data']['size'] ?? 0;
            }
        }

        return [
            'success' => $successCount > 0,
            'message' => "Uploaded {$successCount} out of " . count($files) . " files",
            'data' => [
                'successful' => $successCount,
                'total' => count($files),
                'total_size' => $totalSize,
                'results' => $results
            ]
        ];
    }

    /**
     * Generic file upload
     */
    public function uploadFile(UploadedFile $file, string $directory, array $options = []): array
    {
        try {
            $validation = $this->validateFile($file, $options);
            
            if (!$validation['valid']) {
                return [
                    'success' => false,
                    'message' => 'File validation failed',
                    'errors' => $validation['errors']
                ];
            }

            $filename = $this->generateFilename($file, $directory);
            $path = "{$directory}/{$filename}";

            $storedPath = $this->storeFile($file, $path);

            return [
                'success' => true,
                'message' => 'File uploaded successfully',
                'data' => [
                    'filename' => $filename,
                    'path' => $storedPath,
                    'url' => $this->getPublicUrl($storedPath),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType()
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to upload file', [
                'directory' => $directory,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to upload file',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete file
     */
    public function deleteFile(string $path): array
    {
        try {
            if (Storage::disk($this->disk)->exists($path)) {
                Storage::disk($this->disk)->delete($path);
                
                // Also delete related files (previews, thumbnails, etc.)
                $this->deleteRelatedFiles($path);
                
                Log::info('File deleted successfully', ['path' => $path]);
                
                return [
                    'success' => true,
                    'message' => 'File deleted successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'File not found'
            ];

        } catch (\Exception $e) {
            Log::error('Failed to delete file', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to delete file',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get file information
     */
    public function getFileInfo(string $path): array
    {
        try {
            if (!Storage::disk($this->disk)->exists($path)) {
                return [
                    'success' => false,
                    'message' => 'File not found'
                ];
            }

            $metadata = Storage::disk($this->disk)->getMetadata($path);
            $size = Storage::disk($this->disk)->size($path);
            $lastModified = Storage::disk($this->disk)->lastModified($path);

            return [
                'success' => true,
                'data' => [
                    'path' => $path,
                    'filename' => basename($path),
                    'size' => $size,
                    'mime_type' => $metadata['mimetype'] ?? null,
                    'last_modified' => $lastModified,
                    'url' => $this->getPublicUrl($path),
                    'metadata' => $metadata
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get file info', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to get file info',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate file download URL
     */
    public function generateDownloadUrl(string $path, int $expiryMinutes = 60): array
    {
        try {
            if (!Storage::disk($this->disk)->exists($path)) {
                return [
                    'success' => false,
                    'message' => 'File not found'
                ];
            }

            $url = Storage::disk($this->disk)->temporaryUrl(
                $path,
                now()->addMinutes($expiryMinutes)
            );

            return [
                'success' => true,
                'data' => [
                    'download_url' => $url,
                    'expires_at' => now()->addMinutes($expiryMinutes)->toISOString(),
                    'path' => $path
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to generate download URL', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to generate download URL',
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Validate image file
     */
    protected function validateImage(UploadedFile $file, array $rules): array
    {
        $errors = [];

        // Check file size
        if ($file->getSize() > $rules['max_size']) {
            $errors[] = 'File size exceeds maximum allowed size';
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $rules['allowed_types'])) {
            $errors[] = 'File type not allowed';
        }

        // Check dimensions for images
        if (in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $imageInfo = getimagesize($file->getPathname());
            if ($imageInfo) {
                $width = $imageInfo[0];
                $height = $imageInfo[1];

                if ($width < $rules['min_dimensions'][0] || $height < $rules['min_dimensions'][1]) {
                    $errors[] = 'Image dimensions too small';
                }

                if ($width > $rules['max_dimensions'][0] || $height > $rules['max_dimensions'][1]) {
                    $errors[] = 'Image dimensions too large';
                }
            }
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate document file
     */
    protected function validateDocument(UploadedFile $file, array $rules): array
    {
        $errors = [];

        // Check file size
        if ($file->getSize() > $rules['max_size']) {
            $errors[] = 'File size exceeds maximum allowed size';
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $rules['allowed_types'])) {
            $errors[] = 'File type not allowed';
        }

        // Check document type
        if (!in_array($rules['document_types'], $rules['document_types'])) {
            $errors[] = 'Invalid document type';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Validate generic file
     */
    protected function validateFile(UploadedFile $file, array $options): array
    {
        $errors = [];

        $maxSize = $options['max_size'] ?? 10 * 1024 * 1024; // 10MB default
        $allowedTypes = $options['allowed_types'] ?? ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'];

        // Check file size
        if ($file->getSize() > $maxSize) {
            $errors[] = 'File size exceeds maximum allowed size';
        }

        // Check file type
        $extension = strtolower($file->getClientOriginalExtension());
        if (!in_array($extension, $allowedTypes)) {
            $errors[] = 'File type not allowed';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Process avatar image
     */
    protected function processAvatar(UploadedFile $file): array
    {
        $image = Image::make($file);
        
        // Original image
        $original = $image->copy();
        
        // Optimized image (resize to reasonable dimensions)
        $optimized = $image->copy()->resize(800, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 85);

        return [
            'original' => $original->encode('jpg', 95),
            'optimized' => $optimized
        ];
    }

    /**
     * Generate thumbnail
     */
    protected function generateThumbnail($imageData, string $path): string
    {
        $image = Image::make($imageData);
        $thumbnail = $image->resize(150, 150, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 80);

        return $this->storeFile($thumbnail, $path);
    }

    /**
     * Generate document preview
     */
    protected function generateDocumentPreview(UploadedFile $file, string $path): string
    {
        $image = Image::make($file);
        $preview = $image->resize(800, 600, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 85);

        return $this->storeFile($preview, $path);
    }

    /**
     * Generate receipt preview
     */
    protected function generateReceiptPreview(UploadedFile $file, string $path): string
    {
        $image = Image::make($file);
        $preview = $image->resize(600, 800, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        })->encode('jpg', 85);

        return $this->storeFile($preview, $path);
    }

    /**
     * Store file
     */
    protected function storeFile($file, string $path): string
    {
        if ($file instanceof UploadedFile) {
            Storage::disk($this->disk)->putFileAs(
                dirname($path),
                $file,
                basename($path)
            );
        } else {
            Storage::disk($this->disk)->put($path, $file);
        }

        return $path;
    }

    /**
     * Generate avatar filename
     */
    protected function generateAvatarFilename(int $userId, string $extension): string
    {
        return "user_{$userId}_" . Str::random(10) . "_{$extension}";
    }

    /**
     * Generate KYC filename
     */
    protected function generateKycFilename(int $userId, string $documentType, string $extension): string
    {
        return "user_{$userId}_{$documentType}_" . Str::random(10) . "_{$extension}";
    }

    /**
     * Generate receipt filename
     */
    protected function generateReceiptFilename(int $transactionId, string $extension): string
    {
        return "transaction_{$transactionId}_" . Str::random(10) . "_{$extension}";
    }

    /**
     * Generate generic filename
     */
    protected function generateFilename(UploadedFile $file, string $directory): string
    {
        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Y_m_d_H_i_s');
        $random = Str::random(8);
        
        return "{$directory}_{$timestamp}_{$random}.{$extension}";
    }

    /**
     * Get public URL for file
     */
    protected function getPublicUrl(string $path): string
    {
        if ($this->disk === 'public') {
            return Storage::disk($this->disk)->url($path);
        }

        // For S3 or other cloud storage
        return Storage::disk($this->disk)->url($path);
    }

    /**
     * Delete related files
     */
    protected function deleteRelatedFiles(string $path): void
    {
        $basePath = pathinfo($path, PATHINFO_DIRNAME) . '/' . pathinfo($path, PATHINFO_FILENAME);
        $extensions = ['_optimized', '_thumb', '_preview'];

        foreach ($extensions as $ext) {
            $relatedPath = $basePath . $ext . '.jpg';
            if (Storage::disk($this->disk)->exists($relatedPath)) {
                Storage::disk($this->disk)->delete($relatedPath);
            }
        }
    }

    /**
     * Get storage statistics
     */
    public function getStorageStats(): array
    {
        try {
            $totalSize = 0;
            $fileCount = 0;
            $directories = ['avatars', 'kyc', 'receipts', 'documents'];

            foreach ($directories as $directory) {
                if (Storage::disk($this->disk)->exists($directory)) {
                    $files = Storage::disk($this->disk)->allFiles($directory);
                    $fileCount += count($files);
                    
                    foreach ($files as $file) {
                        $totalSize += Storage::disk($this->disk)->size($file);
                    }
                }
            }

            return [
                'success' => true,
                'data' => [
                    'total_size' => $totalSize,
                    'total_size_mb' => round($totalSize / (1024 * 1024), 2),
                    'file_count' => $fileCount,
                    'storage_disk' => $this->disk
                ]
            ];

        } catch (\Exception $e) {
            Log::error('Failed to get storage stats', ['error' => $e->getMessage()]);
            
            return [
                'success' => false,
                'message' => 'Failed to get storage stats',
                'error' => $e->getMessage()
            ];
        }
    }
}
