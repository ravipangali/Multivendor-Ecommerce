<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaasBrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $brands = SaasBrand::latest()->paginate(10);
        return view('saas_admin.saas_brand.saas_index', compact('brands'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_admin.saas_brand.saas_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_brands',
            'slug' => 'nullable|string|max:255|unique:saas_brands',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand = new SaasBrand();
        $brand->name = $request->name;

        // Handle slug (if provided, use it; otherwise, auto-generated in model)
        if ($request->filled('slug')) {
            $brand->slug = Str::slug($request->slug);
        }

        $brand->save();

        if ($request->hasFile('image')) {
            $brand->saveBrandImage($request->file('image'));
        }

        toast('Brand created successfully', 'success');
        return redirect()->route('admin.brands.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasBrand $brand)
    {
        return view('saas_admin.saas_brand.saas_show', compact('brand'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasBrand $brand)
    {
        return view('saas_admin.saas_brand.saas_edit', compact('brand'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasBrand $brand)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:saas_brands,name,' . $brand->id,
            'slug' => 'nullable|string|max:255|unique:saas_brands,slug,' . $brand->id,
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $brand->name = $request->name;

        // Handle slug (if provided, use it; otherwise, auto-generated in model)
        if ($request->filled('slug')) {
            $brand->slug = Str::slug($request->slug);
        }

        $brand->save();

        if ($request->hasFile('image')) {
            $brand->saveBrandImage($request->file('image'));
        }

        toast('Brand updated successfully', 'success');
        return redirect()->route('admin.brands.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasBrand $brand)
    {
        // Check if brand has products before deleting
        if ($brand->products()->count() > 0) {
            toast('Cannot delete brand because it has associated products', 'error');
            return redirect()->route('admin.brands.index');
        }

        if ($brand->image) {
            Storage::disk('public')->delete($brand->image);
        }

        $brand->delete();

        toast('Brand deleted successfully', 'success');
        return redirect()->route('admin.brands.index');
    }
}
