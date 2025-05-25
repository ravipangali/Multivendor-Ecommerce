<?php

namespace App\Livewire;

use App\Models\SaasCategory;
use App\Models\SaasSubCategory;
use Livewire\Component;

class SaasCategorySubcategoryFilter extends Component
{
    public $categoryId;
    public $subcategoryId;
    public $subcategories = [];
    public $categories = [];
    public $redirectRoute;

    protected $queryString = ['categoryId', 'subcategoryId'];

    public function mount($redirectRoute = 'admin.childcategories.index', $categoryId = null, $subcategoryId = null)
    {
        $this->redirectRoute = $redirectRoute;
        $this->categories = SaasCategory::where('status', 1)->orderBy('name')->get();
        $this->categoryId = $categoryId;
        $this->subcategoryId = $subcategoryId;

        if ($this->categoryId) {
            $this->updatedCategoryId();
        }
    }

    public function updatedCategoryId()
    {
        $this->subcategoryId = null;

        if ($this->categoryId) {
            $this->subcategories = SaasSubCategory::where('category_id', $this->categoryId)
                ->orderBy('name')
                ->get();
        } else {
            $this->subcategories = [];
        }
    }

    public function applyFilter()
    {
        return redirect()->route($this->redirectRoute, [
            'category' => $this->categoryId,
            'subcategory' => $this->subcategoryId,
        ]);
    }

    public function resetFilter()
    {
        $this->reset(['categoryId', 'subcategoryId']);
        $this->subcategories = [];

        return redirect()->route($this->redirectRoute);
    }

    public function render()
    {
        return view('livewire.saas-category-subcategory-filter');
    }
}
