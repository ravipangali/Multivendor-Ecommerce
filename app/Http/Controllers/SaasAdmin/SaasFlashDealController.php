<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasFlashDeal;
use App\Models\SaasProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SaasFlashDealController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashDeals = SaasFlashDeal::latest()->paginate(10);
        return view('saas_admin.saas_flash_deal.saas_index', compact('flashDeals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('saas_admin.saas_flash_deal.saas_create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $flashDeal = new SaasFlashDeal();
        $flashDeal->title = $request->title;
        $flashDeal->start_time = $request->start_time;
        $flashDeal->end_time = $request->end_time;
        $flashDeal->save();

        if ($request->hasFile('banner_image')) {
            $flashDeal->saveBannerImage($request->file('banner_image'));
        }

        toast('Flash deal created successfully', 'success');
        return redirect()->route('admin.flash-deals.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasFlashDeal $flashDeal)
    {
        $flashDeal->load('products');
        return view('saas_admin.saas_flash_deal.saas_show', compact('flashDeal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasFlashDeal $flashDeal)
    {
        return view('saas_admin.saas_flash_deal.saas_edit', compact('flashDeal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasFlashDeal $flashDeal)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $flashDeal->title = $request->title;
        $flashDeal->start_time = $request->start_time;
        $flashDeal->end_time = $request->end_time;
        $flashDeal->save();

        if ($request->hasFile('banner_image')) {
            $flashDeal->saveBannerImage($request->file('banner_image'));
        }

        toast('Flash deal updated successfully', 'success');
        return redirect()->route('admin.flash-deals.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasFlashDeal $flashDeal)
    {
        // Delete banner image if exists
        if ($flashDeal->banner_image) {
            Storage::disk('public')->delete($flashDeal->banner_image);
        }

        // This will also delete flash deal products due to cascade
        $flashDeal->delete();

        toast('Flash deal deleted successfully', 'success');
        return redirect()->route('admin.flash-deals.index');
    }
}
