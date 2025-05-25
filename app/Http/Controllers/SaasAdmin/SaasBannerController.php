<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SaasBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = SaasBanner::latest()->paginate(10);
        return view('saas_admin.saas_banner.saas_index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $positions = ['homepage', 'top', 'sidebar'];
        return view('saas_admin.saas_banner.saas_create', compact('positions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link_url' => 'nullable|url',
            'position' => 'required|in:homepage,top,sidebar',
            'is_active' => 'required|boolean',
        ]);

        $banner = new SaasBanner();
        $banner->title = $request->title;
        $banner->link_url = $request->link_url;
        $banner->position = $request->position;
        $banner->is_active = $request->is_active;
        $banner->save();

        if ($request->hasFile('image')) {
            $banner->saveBannerImage($request->file('image'));
        }

        toast('Banner created successfully', 'success');
        return redirect()->route('admin.banners.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasBanner $banner)
    {
        return view('saas_admin.saas_banner.saas_show', compact('banner'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasBanner $banner)
    {
        $positions = ['homepage', 'top', 'sidebar'];
        return view('saas_admin.saas_banner.saas_edit', compact('banner', 'positions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasBanner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link_url' => 'nullable|url',
            'position' => 'required|in:homepage,top,sidebar',
            'is_active' => 'required|boolean',
        ]);

        $banner->title = $request->title;
        $banner->link_url = $request->link_url;
        $banner->position = $request->position;
        $banner->is_active = $request->is_active;
        $banner->save();

        if ($request->hasFile('image')) {
            $banner->saveBannerImage($request->file('image'));
        }

        toast('Banner updated successfully', 'success');
        return redirect()->route('admin.banners.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasBanner $banner)
    {
        if ($banner->image) {
            Storage::disk('public')->delete($banner->image);
        }

        $banner->delete();

        toast('Banner deleted successfully', 'success');
        return redirect()->route('admin.banners.index');
    }
}
