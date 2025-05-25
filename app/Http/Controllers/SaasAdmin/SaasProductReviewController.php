<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasProductReview;
use App\Models\SaasProduct;
use Illuminate\Http\Request;

class SaasProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $productId = $request->product_id;

        if ($productId) {
            $reviews = SaasProductReview::with(['product', 'customer'])
                ->where('product_id', $productId)
                ->latest()
                ->paginate(15);
            $product = SaasProduct::findOrFail($productId);
        } else {
            $reviews = SaasProductReview::with(['product', 'customer'])
                ->latest()
                ->paginate(15);
            $product = null;
        }

        return view('saas_admin.saas_product_review.saas_index', compact('reviews', 'product'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Typically, reviews are created by customers, not by admins
        toast('Reviews are typically created by customers', 'info');
        return redirect()->route('admin.product-reviews.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Typically, reviews are created by customers, not by admins
        toast('Reviews are typically created by customers', 'info');
        return redirect()->route('admin.product-reviews.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasProductReview $productReview)
    {
        $productReview->load(['product', 'customer']);
        $review = $productReview; // Using $review variable to match the blade file
        return view('saas_admin.saas_product_review.saas_show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasProductReview $productReview)
    {
        // Typically, reviews should not be edited by admins
        toast('Reviews should not be edited by admins', 'info');
        return redirect()->route('admin.product-reviews.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasProductReview $productReview)
    {
        // Typically, reviews should not be edited by admins
        return redirect()->route('admin.product-reviews.index')
            ->with('info', 'Reviews should not be edited by admins');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasProductReview $productReview)
    {
        // Admin can delete inappropriate reviews
        $productReview->delete();

        toast('Review deleted successfully', 'success');
        return redirect()->route('admin.product-reviews.index');
    }
}
