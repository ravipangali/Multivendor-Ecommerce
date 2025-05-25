<?php

namespace App\Livewire;

use App\Models\SaasCategory;
use App\Models\SaasSubCategory;
use Livewire\Component;

class SaasCategorySubcategoryDropdown extends Component
{
    public $categoryId;
    public $subcategoryId;
    public $subcategories = [];
    public $categories = [];

    public function mount($categoryId = null, $subcategoryId = null)
    {
        $this->categories = SaasCategory::where('status', 1)->orderBy('name')->get();
        $this->categoryId = $categoryId;
        $this->subcategoryId = $subcategoryId;

        if ($this->categoryId) {
            $this->updatedCategoryId();
        }
    }

    public function updatedCategoryId()
    {
        if ($this->categoryId) {
            $this->subcategories = SaasSubCategory::where('category_id', $this->categoryId)
                ->orderBy('name')
                ->get();

                        // Reset subcategory if it doesn't belong to the selected category
            if ($this->subcategoryId) {
                $belongsToCategory = collect($this->subcategories)->contains(function ($subcategory) {
                    return $subcategory->id == $this->subcategoryId;
                });

                if (!$belongsToCategory) {
                    $this->subcategoryId = null;
                }
            }
        } else {
            $this->subcategories = [];
            $this->subcategoryId = null;
        }

        $this->dispatch('subcategories-updated');
    }

    public function render()
    {
        return view('livewire.saas-category-subcategory-dropdown');
    }
}
