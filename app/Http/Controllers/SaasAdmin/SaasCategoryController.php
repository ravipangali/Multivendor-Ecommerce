<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SaasCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = SaasCategory::latest()->paginate(10);
        return view('saas_admin.saas_category.saas_index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_admin.saas_category.saas_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_categories',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:1,0',
            'featured' => 'required|in:1,0',
            'slug' => 'nullable|string',
        ]);

        $category = new SaasCategory();
        $category->name = $request->name;
        $category->status = $request->status;
        $category->featured = $request->featured;
        if (!empty($request->slug)) {
            $category->slug = Str::slug($request->slug);
        }
        $category->save();

        if ($request->hasFile('image')) {
            $category->saveCategoryImage($request->file('image'));
        }

        toast('Category created successfully', 'success');
        return redirect()->route('admin.categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasCategory $category)
    {
        return view('saas_admin.saas_category.saas_show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasCategory $category)
    {
        return view('saas_admin.saas_category.saas_edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_categories,name,' . $category->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'status' => 'required|in:1,0',
            'featured' => 'required|in:1,0',
            'slug' => 'nullable|string',
        ]);

        $category->name = $request->name;
        $category->status = $request->status;
        $category->featured = $request->featured;

        // Handle manual slug update
        if (!empty($request->slug)) {
            $category->slug = Str::slug($request->slug);
        }

        $category->save();

        if ($request->hasFile('image')) {
            $category->saveCategoryImage($request->file('image'));
        }

        toast('Category updated successfully', 'success');
        return redirect()->route('admin.categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasCategory $category)
    {
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        toast('Category deleted successfully', 'success');
        return redirect()->route('admin.categories.index');
    }
}
