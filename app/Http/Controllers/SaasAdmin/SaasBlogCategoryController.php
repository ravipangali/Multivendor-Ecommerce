<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasBlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaasBlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SaasBlogCategory::withCount('blogPosts')
                    ->orderBy('position', 'asc')
                    ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $categories = $query->paginate(20);

        return view('saas_admin.saas_blog_category.saas_index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $parentCategories = SaasBlogCategory::active()->parent()->get();
        return view('saas_admin.saas_blog_category.saas_create', compact('parentCategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->merge([
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:saas_blog_categories,slug',
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'position' => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:saas_blog_categories,id',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'blog_category_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $data['image'] = $filename;
        }

        $category = SaasBlogCategory::create($data);

        return redirect()->route('admin.blog-categories.index')
                        ->with('success', 'Blog category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasBlogCategory $blogCategory)
    {
        $blogCategory->loadCount('blogPosts');
        return view('saas_admin.saas_blog_category.saas_show', compact('blogCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasBlogCategory $blogCategory)
    {
        $parentCategories = SaasBlogCategory::active()
                                          ->parent()
                                          ->where('id', '!=', $blogCategory->id)
                                          ->get();
        return view('saas_admin.saas_blog_category.saas_edit', compact('blogCategory', 'parentCategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasBlogCategory $blogCategory)
    {
        $request->merge([
            'status' => filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN),
        ]);

        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:saas_blog_categories,slug,' . $blogCategory->id,
            'description' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|boolean',
            'position' => 'nullable|integer|min:0',
            'parent_id' => 'nullable|exists:saas_blog_categories,id',
        ]);

        $data = $request->all();

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($blogCategory->image) {
                Storage::disk('public')->delete($blogCategory->image);
            }

            $image = $request->file('image');
            $filename = 'blog_category_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $data['image'] = $filename;
        }

        $blogCategory->update($data);

        return redirect()->route('admin.blog-categories.index')
                        ->with('success', 'Blog category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasBlogCategory $blogCategory)
    {
        // Check if category has blog posts
        if ($blogCategory->blogPosts()->count() > 0) {
            return redirect()->back()
                           ->with('error', 'Cannot delete category that contains blog posts.');
        }

        // Delete image
        if ($blogCategory->image) {
            Storage::disk('public')->delete($blogCategory->image);
        }

        $blogCategory->delete();

        return redirect()->route('admin.blog-categories.index')
                        ->with('success', 'Blog category deleted successfully.');
    }

    /**
     * Toggle category status.
     */
    public function toggleStatus(SaasBlogCategory $blogCategory)
    {
        $blogCategory->update(['status' => !$blogCategory->status]);

        $status = $blogCategory->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Category {$status} successfully.");
    }
}
