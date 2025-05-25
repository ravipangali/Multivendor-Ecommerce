<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SaasCategory;
use App\Models\SaasSubCategory;
use App\Models\SaasChildCategory;

class SaasCategoryDropdown extends Component
{
    public $categories = [];
    public $subcategories = [];
    public $childcategories = [];

    public $selectedCategoryId = null;
    public $selectedSubcategoryId = null;
    public $selectedChildcategoryId = null;

    // For edit mode
    public $initialCategoryId = null;
    public $initialSubcategoryId = null;
    public $initialChildcategoryId = null;

    public function mount($categoryId = null, $subcategoryId = null, $childcategoryId = null)
    {
        $this->categories = SaasCategory::orderBy('name')->get();

        $this->initialCategoryId = $categoryId;
        $this->initialSubcategoryId = $subcategoryId;
        $this->initialChildcategoryId = $childcategoryId;

        // If initial values are provided, load the dependent dropdowns
        if ($this->initialCategoryId) {
            $this->selectedCategoryId = $this->initialCategoryId;
            $this->updatedSelectedCategoryId();

            if ($this->initialSubcategoryId) {
                $this->selectedSubcategoryId = $this->initialSubcategoryId;
                $this->updatedSelectedSubcategoryId();
            }

            if ($this->initialChildcategoryId) {
                $this->selectedChildcategoryId = $this->initialChildcategoryId;
            }
        }
    }

    public function updatedSelectedCategoryId()
    {
        $this->subcategories = $this->selectedCategoryId
            ? SaasSubCategory::where('category_id', $this->selectedCategoryId)
                ->orderBy('name')
                ->get()
            : [];

        $this->selectedSubcategoryId = null;
        $this->selectedChildcategoryId = null;
        $this->childcategories = [];

        $this->dispatch('categorySelected', $this->selectedCategoryId);
    }

    public function updatedSelectedSubcategoryId()
    {
        $this->childcategories = $this->selectedSubcategoryId
            ? SaasChildCategory::where('sub_category_id', $this->selectedSubcategoryId)
                ->orderBy('name')
                ->get()
            : [];

        $this->selectedChildcategoryId = null;

        $this->dispatch('subcategorySelected', $this->selectedSubcategoryId);
    }

    public function updatedSelectedChildcategoryId()
    {
        $this->dispatch('childcategorySelected', $this->selectedChildcategoryId);
    }

    public function render()
    {
        return view('livewire.saas-category-dropdown');
    }
}
