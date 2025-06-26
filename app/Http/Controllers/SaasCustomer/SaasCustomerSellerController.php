<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\SaasProduct;
use App\Models\SaasProductReview;
use App\Models\SaasCategory;
use App\Models\SaasBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaasCustomerSellerController extends Controller
{
    public function saasSellerProfile($id, Request $request)
    {
        $seller = User::where('id', $id)
            ->where('role', 'seller')
            ->where('is_active', true)
            ->with(['sellerProfile'])
            ->firstOrFail();

        // Get seller's products with filters
        $query = SaasProduct::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
            ->with(['images', 'brand', 'category', 'reviews']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $categories = is_array($request->category) ? $request->category : explode(',', $request->category);
            $query->whereHas('category', function($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $brands = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $query->whereHas('brand', function($q) use ($brands) {
                $q->whereIn('slug', $brands);
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->whereRaw('(price - (price * discount / 100)) >= ?', [$request->min_price]);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereRaw('(price - (price * discount / 100)) <= ?', [$request->max_price]);
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('(price - (price * discount / 100)) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('(price - (price * discount / 100)) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'popular':
                $query->withCount('orderItems')->orderBy('order_items_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);

        // Get seller statistics
        $totalProducts = SaasProduct::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
            ->count();
        $totalReviews = SaasProductReview::where('seller_id', $seller->id)->count();
        $averageRating = SaasProductReview::where('seller_id', $seller->id)->avg('rating') ?? 0;
        $totalSales = DB::table('saas_order_items')
            ->join('saas_orders', 'saas_order_items.order_id', '=', 'saas_orders.id')
            ->where('saas_order_items.seller_id', $seller->id)
            ->where('saas_orders.order_status', 'delivered')
            ->sum('saas_order_items.quantity');

        // Get categories for this seller's products
        $categories = SaasCategory::whereHas('products', function($q) use ($seller) {
            $q->where('seller_id', $seller->id)->where('is_active', true);
        })
        ->where('status', 'active')
        ->withCount(['products' => function($q) use ($seller) {
            $q->where('seller_id', $seller->id)->where('is_active', true);
        }])
        ->get();

        // Get brands for this seller's products
        $brands = SaasBrand::whereHas('products', function($q) use ($seller) {
            $q->where('seller_id', $seller->id)->where('is_active', true);
        })
        ->withCount(['products' => function($q) use ($seller) {
            $q->where('seller_id', $seller->id)->where('is_active', true);
        }])
        ->get();

        // Get price range for this seller's products
        $priceRange = SaasProduct::where('seller_id', $seller->id)
            ->where('is_active', true)
            ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
            ->selectRaw('
                MIN(price - (price * discount / 100)) as min_price,
                MAX(price - (price * discount / 100)) as max_price
            ')
            ->first();

        // Get recent reviews
        $recentReviews = SaasProductReview::where('seller_id', $seller->id)
            ->with(['customer', 'product.images'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('saas_customer.saas_seller_profile', compact(
            'seller',
            'products',
            'totalProducts',
            'totalReviews',
            'averageRating',
            'totalSales',
            'categories',
            'brands',
            'priceRange',
            'recentReviews'
        ));
    }

    public function saasSellersListing(Request $request)
    {
        $query = User::where('role', 'seller')
            ->where('is_active', true)
            ->with(['sellerProfile']);

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhereHas('sellerProfile', function($profile) use ($request) {
                      $profile->where('business_name', 'like', '%' . $request->search . '%')
                              ->orWhere('business_description', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Location filter
        if ($request->has('location') && $request->location) {
            $query->whereHas('sellerProfile', function($q) use ($request) {
                $q->where('business_address', 'like', '%' . $request->location . '%');
            });
        }

        // Rating filter
        if ($request->has('rating') && $request->rating) {
            $query->whereHas('productReviews', function($q) use ($request) {
                $q->havingRaw('AVG(rating) >= ?', [$request->rating]);
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'newest');
        switch ($sortBy) {
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'rating':
                $query->withAvg('productReviews', 'rating')->orderBy('product_reviews_avg_rating', 'desc');
                break;
            case 'products':
                $query->withCount('products')->orderBy('products_count', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $sellers = $query->paginate(12);

        // Add statistics for each seller
        foreach ($sellers as $seller) {
            $seller->total_products = SaasProduct::where('seller_id', $seller->id)
                ->where('is_active', true)
                ->where('seller_publish_status', SaasProduct::SELLER_PUBLISH_STATUS_APPROVED)
                ->count();
            $seller->total_reviews = SaasProductReview::where('seller_id', $seller->id)->count();
            $seller->average_rating = SaasProductReview::where('seller_id', $seller->id)->avg('rating') ?? 0;
        }

        return view('saas_customer.saas_sellers_listing', compact('sellers'));
    }

    public function saasBrandsListing(Request $request)
    {
        $query = SaasBrand::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])->having('products_count', '>', 0);

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'name_asc');
        switch ($sortBy) {
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            case 'products':
                $query->orderBy('products_count', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', 'asc');
        }

        $brands = $query->paginate(16);

        return view('saas_customer.saas_brands_listing', compact('brands'));
    }
}
