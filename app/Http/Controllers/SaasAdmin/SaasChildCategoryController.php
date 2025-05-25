<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasChildCategory;
use App\Models\SaasSubCategory;
use App\Models\SaasCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SaasChildCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SaasChildCategory::with('subCategory.category');

        // Apply category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('subCategory', function($q) use ($request) {
                $q->where('category_id', $request->category);
            });
        }

        // Apply subcategory filter
        if ($request->has('subcategory') && $request->subcategory) {
            $query->where('sub_category_id', $request->subcategory);
        }

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $childCategories = $query->latest()->paginate(10);
        return view('saas_admin.saas_childcategory.saas_index', compact('childCategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_admin.saas_childcategory.saas_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:saas_sub_categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'nullable|string',
        ]);

        $childcategory = new SaasChildCategory();
        $childcategory->sub_category_id = $request->sub_category_id;
        $childcategory->name = $request->name;

        // Set slug if provided
        if (!empty($request->slug)) {
            $childcategory->slug = Str::slug($request->slug);
        }

        $childcategory->save();

        if ($request->hasFile('image')) {
            $childcategory->saveChildCategoryImage($request->file('image'));
        }

        toast('Child category created successfully', 'success');
        return redirect()->route('admin.childcategories.index');
    }

    /**
     * Display the specified resource.
     */
    // public function show(SaasChildCategory $childcategory)
    // {
    //     return view('saas_admin.saas_childcategory.saas_show', compact('childCategory'));
    // }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasChildCategory $childcategory)
    {
        $parentCategoryId = $childcategory->subCategory->category_id;
        return view('saas_admin.saas_childcategory.saas_edit', compact('childcategory', 'parentCategoryId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasChildCategory $childcategory)
    {
        $request->validate([
            'sub_category_id' => 'required|exists:saas_sub_categories,id',
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'nullable|string',
        ]);

        $childcategory->sub_category_id = $request->sub_category_id;
        $childcategory->name = $request->name;

        // Handle manual slug update
        if (!empty($request->slug)) {
            $childcategory->slug = Str::slug($request->slug);
        }

        $childcategory->save();

        if ($request->hasFile('image')) {
            $childcategory->saveChildCategoryImage($request->file('image'));
        }

        toast('Child category updated successfully', 'success');
        return redirect()->route('admin.childcategories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasChildCategory $childcategory)
    {
        // Check if child category has products before deleting
        if ($childcategory->products()->count() > 0) {
            toast('Cannot delete child category because it has associated products', 'error');
            return redirect()->route('admin.childcategories.index');
        }

        if ($childcategory->image) {
            Storage::disk('public')->delete($childcategory->image);
        }

        $childcategory->delete();

        toast('Child category deleted successfully', 'success');
        return redirect()->route('admin.childcategories.index');
    }

    /**
     * Get child categories by subcategory
     */
    public function getBySubcategory($subcategoryId)
    {
        $childCategories = SaasChildCategory::where('sub_category_id', $subcategoryId)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return response()->json($childCategories);
    }
}
