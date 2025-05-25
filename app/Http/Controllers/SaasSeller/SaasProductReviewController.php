<?php

namespace App\Http\Controllers\SaasSeller;

use App\Http\Controllers\Controller;
use App\Models\SaasProduct;
use App\Models\SaasProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaasProductReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sellerId = Auth::id();
        $rating = $request->rating;

        $reviewsQuery = SaasProductReview::with(['customer', 'product'])
            ->where('seller_id', $sellerId);

        if ($rating) {
            $reviewsQuery->where('rating', $rating);
        }

        $reviews = $reviewsQuery->latest()->paginate(10);

        return view('saas_seller.saas_product_review.saas_index', compact('reviews', 'rating'));
    }

    /**
     * Display reviews for a specific product.
     */
    public function productReviews(SaasProduct $product)
    {
        // Check if the product belongs to the authenticated seller
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('seller.products.index')
                ->with('error', 'You are not authorized to view reviews for this product.');
        }

        $reviews = SaasProductReview::with('customer')
            ->where('product_id', $product->id)
            ->latest()
            ->paginate(10);

        return view('saas_seller.saas_product_review.saas_product_reviews', compact('product', 'reviews'));
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasProductReview $review)
    {
        // Check if the review belongs to the authenticated seller
        if ($review->seller_id !== Auth::id()) {
            return redirect()->route('seller.reviews.index')
                ->with('error', 'You are not authorized to view this review.');
        }

        $review->load(['customer', 'product']);
        return view('saas_seller.saas_product_review.saas_show', compact('review'));
    }

    /**
     * Respond to a review.
     */
    public function respond(Request $request, SaasProductReview $review)
    {
        // Check if the review belongs to the authenticated seller
        if ($review->seller_id !== Auth::id()) {
            return redirect()->route('seller.reviews.index')
                ->with('error', 'You are not authorized to respond to this review.');
        }

        $request->validate([
            'seller_response' => 'required|string|max:1000',
        ]);

        $review->seller_response = $request->seller_response;
        $review->save();

        return redirect()->route('seller.reviews.show', $review->id)
            ->with('success', 'Response to review saved successfully');
    }

    /**
     * Report a review as inappropriate.
     */
    public function report(Request $request, SaasProductReview $review)
    {
        // Check if the review belongs to the authenticated seller
        if ($review->seller_id !== Auth::id()) {
            return redirect()->route('seller.reviews.index')
                ->with('error', 'You are not authorized to report this review.');
        }

        $request->validate([
            'report_reason' => 'required|string|max:1000',
        ]);

        $review->is_reported = true;
        $review->report_reason = $request->report_reason;
        $review->save();

        // Here you would typically send a notification to admins
        // Notification::route('mail', config('app.admin_email'))->notify(new ReviewReported($review));

        return redirect()->route('seller.reviews.show', $review->id)
            ->with('success', 'Review has been reported to administrators');
    }

    /**
     * Get review analytics/statistics.
     */
    public function analytics()
    {
        $sellerId = Auth::id();

        // Overall rating statistics
        $totalReviews = SaasProductReview::where('seller_id', $sellerId)->count();
        $averageRating = SaasProductReview::where('seller_id', $sellerId)->avg('rating') ?? 0;

        // Rating distribution
        $ratingDistribution = [
            5 => SaasProductReview::where('seller_id', $sellerId)->where('rating', 5)->count(),
            4 => SaasProductReview::where('seller_id', $sellerId)->where('rating', 4)->count(),
            3 => SaasProductReview::where('seller_id', $sellerId)->where('rating', 3)->count(),
            2 => SaasProductReview::where('seller_id', $sellerId)->where('rating', 2)->count(),
            1 => SaasProductReview::where('seller_id', $sellerId)->where('rating', 1)->count(),
        ];

        // Top rated products
        $topRatedProducts = SaasProduct::where('seller_id', $sellerId)
            ->has('reviews')
            ->withAvg('reviews as average_rating', 'rating')
            ->withCount('reviews')
            ->orderByDesc('average_rating')
            ->limit(5)
            ->get();

        // Most reviewed products
        $mostReviewedProducts = SaasProduct::where('seller_id', $sellerId)
            ->has('reviews')
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->limit(5)
            ->get();

        return view('saas_seller.saas_product_review.saas_analytics', compact(
            'totalReviews',
            'averageRating',
            'ratingDistribution',
            'topRatedProducts',
            'mostReviewedProducts'
        ));
    }
}
