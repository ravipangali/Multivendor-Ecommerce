<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\SaasProductImage;
use Illuminate\Support\Facades\Storage;

class SaasProductImages extends Component
{
    use WithFileUploads;

    public $productId = null;
    public $newImages = [];
    public $existingImages = [];
    public $deletedImages = [];
    public $imageCount = 0;

    public function mount($productId = null, $existingImages = [])
    {
        $this->productId = $productId;
        $this->existingImages = $existingImages;
        $this->deletedImages = [];
        $this->newImages = [];

        // Start with one image input
        $this->addImageInput();
    }

    public function addImageInput()
    {
        $this->imageCount++;
        $this->newImages[$this->imageCount] = null;
    }

    public function updatedNewImages($value, $key)
    {
        // Validate the uploaded image
        $this->validate([
            "newImages.$key" => 'image|max:5120', // 5MB Max
        ], [
            "newImages.$key.image" => 'The file must be an image',
            "newImages.$key.max" => 'The image must not be larger than 5MB',
        ]);

        // Add new input field if all current fields are used
        $allFieldsUsed = true;
        foreach ($this->newImages as $image) {
            if ($image === null) {
                $allFieldsUsed = false;
                break;
            }
        }

        if ($allFieldsUsed) {
            $this->addImageInput();
        }
    }

    public function removeNewImage($index)
    {
        unset($this->newImages[$index]);

        // If no image inputs remain, add one
        if (empty($this->newImages)) {
            $this->addImageInput();
        }
    }

    public function markImageForDeletion($imageId)
    {
        if (!in_array($imageId, $this->deletedImages)) {
            $this->deletedImages[] = $imageId;
        }
    }

    public function undoImageDeletion($imageId)
    {
        $key = array_search($imageId, $this->deletedImages);
        if ($key !== false) {
            unset($this->deletedImages[$key]);
            $this->deletedImages = array_values($this->deletedImages);
        }
    }

    public function getImagesToSave()
    {
        return [
            'new' => array_filter($this->newImages),
            'deleted' => $this->deletedImages
        ];
    }

    public function saveImages($productId)
    {
        // Filter out empty images
        $imagesToSave = array_filter($this->newImages);

        foreach ($imagesToSave as $image) {
            if ($image) {
                $filename = 'product_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->storeAs('public', $filename);

                // Create new product image
                $productImage = new SaasProductImage([
                    'product_id' => $productId,
                    'image_url' => $filename
                ]);
                $productImage->save();
            }
        }

        // Reset new images after saving
        $this->newImages = [];
        $this->addImageInput();

        // Return count of saved images
        return count($imagesToSave);
    }

    public function saveImagesAndGetDeletedIds($productId)
    {
        // Save new images
        $this->saveImages($productId);

        // Return deleted image IDs to be handled by controller
        return $this->deletedImages;
    }

    /**
     * Get array of files and their temporary paths to be used by controller
     */
    public function getFilesForController()
    {
        $result = [];
        foreach ($this->newImages as $key => $image) {
            if ($image) {
                $result[$key] = [
                    'file' => $image,
                    'name' => $image->getClientOriginalName()
                ];
            }
        }

        return [
            'files' => $result,
            'deleted_images' => $this->deletedImages
        ];
    }

    public function render()
    {
        return view('livewire.saas-product-images');
    }
}
