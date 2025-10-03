<?php

namespace App\Livewire;

use App\Models\Product;
use App\Services\FileUploadService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;

class ProductImageUpload extends Component
{
    use WithFileUploads;

    public Product $product;
    public $images = [];
    public $uploading = false;
    public $showSuccess = false;
    public $showError = false;
    public $successMessage = '';
    public $errorMessage = '';
    public $productImages = [];

    protected $rules = [
        'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
    ];

    protected $messages = [
        'images.*.image' => 'Each file must be an image.',
        'images.*.mimes' => 'Each image must be: jpeg, png, jpg, gif, or webp.',
        'images.*.max' => 'Each image must be less than 5MB.',
    ];

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->loadProductImages();
    }

    public function loadProductImages()
    {
        $this->productImages = $this->product->images ?? [];
    }

    public function uploadImages()
    {
        if (empty($this->images)) {
            $this->showErrorMessage('Please select at least one image to upload.');
            return;
        }

        $this->validate();

        try {
            $this->uploading = true;
            $fileUploadService = new FileUploadService();
            
            $currentImages = $this->product->images ?? [];
            $uploadResults = [];

            foreach ($this->images as $image) {
                $result = $fileUploadService->uploadFile(
                    $image,
                    'products/' . $this->product->id,
                    'images'
                );

                if ($result['success']) {
                    $currentImages[] = [
                        'path' => $result['path'],
                        'url' => $result['url'],
                        'original_name' => $result['original_name'],
                        'size' => $result['size'],
                        'uploaded_at' => now()->toISOString(),
                        'is_main' => empty($currentImages) // First image becomes main
                    ];
                    $uploadResults[] = $result;
                } else {
                    throw new \Exception($result['error']);
                }
            }

            // Update product with new images
            $this->product->update([
                'images' => $currentImages
            ]);

            // If this is the first image, set it as the main product image
            if (empty($this->product->image) && !empty($currentImages)) {
                $this->product->update([
                    'image' => $currentImages[0]['url']
                ]);
            }

            $this->loadProductImages();
            $this->images = []; // Clear selected images
            $this->showSuccessMessage(count($uploadResults) . ' image(s) uploaded successfully!');

            Log::info('Product images uploaded via Livewire', [
                'product_id' => $this->product->id,
                'images_count' => count($uploadResults)
            ]);

        } catch (\Exception $e) {
            Log::error('Product image upload failed via Livewire', [
                'product_id' => $this->product->id,
                'error' => $e->getMessage()
            ]);

            $this->showErrorMessage('Upload failed: ' . $e->getMessage());
        } finally {
            $this->uploading = false;
        }
    }

    public function deleteImage($imagePath)
    {
        try {
            $currentImages = $this->product->images ?? [];
            
            // Find and remove the image
            $updatedImages = array_filter($currentImages, function ($image) use ($imagePath) {
                return $image['path'] !== $imagePath;
            });

            if (count($updatedImages) === count($currentImages)) {
                $this->showErrorMessage('Image not found.');
                return;
            }

            // Delete physical file
            $fileUploadService = new FileUploadService();
            $fileUploadService->deleteFile($imagePath);

            // Update product
            $this->product->update([
                'images' => array_values($updatedImages)
            ]);

            // If we deleted the main image, set a new main image
            if ($this->product->image === $imagePath && !empty($updatedImages)) {
                $this->product->update([
                    'image' => $updatedImages[0]['url']
                ]);
            } elseif ($this->product->image === $imagePath) {
                $this->product->update(['image' => null]);
            }

            $this->loadProductImages();
            $this->showSuccessMessage('Image deleted successfully!');

            Log::info('Product image deleted via Livewire', [
                'product_id' => $this->product->id,
                'image_path' => $imagePath
            ]);

        } catch (\Exception $e) {
            Log::error('Product image deletion failed via Livewire', [
                'product_id' => $this->product->id,
                'error' => $e->getMessage()
            ]);

            $this->showErrorMessage('Deletion failed: ' . $e->getMessage());
        }
    }

    public function setMainImage($imagePath)
    {
        try {
            $currentImages = $this->product->images ?? [];
            
            // Update images array to mark the new main image
            foreach ($currentImages as &$image) {
                $image['is_main'] = ($image['path'] === $imagePath);
            }

            // Update product
            $this->product->update([
                'images' => $currentImages,
                'image' => $imagePath
            ]);

            $this->loadProductImages();
            $this->showSuccessMessage('Main image updated successfully!');

            Log::info('Product main image set via Livewire', [
                'product_id' => $this->product->id,
                'main_image' => $imagePath
            ]);

        } catch (\Exception $e) {
            Log::error('Setting main product image failed via Livewire', [
                'product_id' => $this->product->id,
                'error' => $e->getMessage()
            ]);

            $this->showErrorMessage('Failed to set main image: ' . $e->getMessage());
        }
    }

    private function showSuccessMessage($message)
    {
        $this->successMessage = $message;
        $this->showSuccess = true;
        $this->showError = false;
    }

    private function showErrorMessage($message)
    {
        $this->errorMessage = $message;
        $this->showError = true;
        $this->showSuccess = false;
    }

    public function hideMessages()
    {
        $this->showSuccess = false;
        $this->showError = false;
    }

    public function render()
    {
        return view('livewire.product-image-upload');
    }
}
