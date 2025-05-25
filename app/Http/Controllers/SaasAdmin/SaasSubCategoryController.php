<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasCategory;
use App\Models\SaasSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SaasSubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = SaasCategory::where('status', '1')->get();
        $subcategories = SaasSubCategory::with('category')->latest()->paginate(10);
        return view('saas_admin.saas_subcategory.saas_index', compact('subcategories', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = SaasCategory::where('status', '1')->get();
        return view('saas_admin.saas_subcategory.saas_create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:saas_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'nullable|string',
        ]);

        $subcategory = new SaasSubCategory();
        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;
        // Set slug if provided
        if (!empty($request->slug)) {
            $subcategory->slug = Str::slug($request->slug);
        }
        $subcategory->save();

        if ($request->hasFile('image')) {
            $subcategory->saveSubCategoryImage($request->file('image'));
        }

        toast('Subcategory created successfully', 'success');
        return redirect()->route('admin.subcategories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasSubCategory $subcategory)
    {
        return view('saas_admin.saas_subcategory.saas_show', compact('subcategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasSubCategory $subcategory)
    {
        $categories = SaasCategory::all();
        return view('saas_admin.saas_subcategory.saas_edit', compact('subcategory', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasSubCategory $subcategory)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:saas_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'slug' => 'nullable|string',
        ]);

        $subcategory->name = $request->name;
        $subcategory->category_id = $request->category_id;

        // Handle manual slug update
        if (!empty($request->slug)) {
            $subcategory->slug = Str::slug($request->slug);
        }

        $subcategory->save();

        if ($request->hasFile('image')) {
            $subcategory->saveSubCategoryImage($request->file('image'));
        }

        toast('Subcategory updated successfully', 'success');
        return redirect()->route('admin.subcategories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasSubCategory $subcategory)
    {
        if ($subcategory->image) {
            Storage::disk('public')->delete($subcategory->image);
        }

        $subcategory->delete();

        toast('Subcategory deleted successfully', 'success');
        return redirect()->route('admin.subcategories.index');
    }

    /**
     * Get subcategories by category
     */
    public function getByCategory($categoryId)
    {
        $subcategories = SaasSubCategory::where('category_id', $categoryId)
            ->where('status', 'active')
            ->orderBy('sort_order')
            ->get();

        return response()->json($subcategories);
    }
}
