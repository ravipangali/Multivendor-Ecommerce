<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasBanner;
use App\Models\SaasBrand;
use App\Models\SaasCategory;
use App\Models\SaasProduct;
use App\Models\SaasProductVariation;
use App\Models\SaasFlashDeal;
use App\Models\SaasProductReview;
use App\Models\SaasCart;
use App\Models\SaasWishlist;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SaasCustomerController extends Controller
{
    public function home()
    {
        // Get active banners for slider
        $sliderBanners = SaasBanner::active()->position('top')->take(5)->get();

        // Get promotional banners
        $promotionalBanners = SaasBanner::active()->position('homepage')->orderBy('id', 'desc')->take(3)->get();

        // Get featured categories
        $featuredCategories = SaasCategory::where('featured', 1)
            ->where('status', 1)
            ->with('products')
            ->take(9)->get();

        // Get all active categories for menu
        $categories = SaasCategory::where('status', 'active')
            ->with(['subcategories' => function($query) {
                $query->where('status', 'active')->take(6);
            }])
            ->take(10)->get();

        // Get featured products
        $featuredProducts = SaasProduct::where('is_featured', true)
            ->where('is_active', true)
            ->with(['images', 'brand', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->take(8)->get();

        // Get new arrivals
        $newArrivals = SaasProduct::where('is_active', true)
            ->with(['images', 'brand', 'reviews'])
            ->orderBy('created_at', 'desc')
            ->take(16)->get();

        // Get top selling products (based on order items count)
        $topSellingProducts = SaasProduct::where('is_active', true)
            ->withCount('orderItems')
            ->with(['images', 'brand', 'reviews'])
            ->orderBy('order_items_count', 'desc')
            ->take(12)->get();

        // Get popular brands
        $popularBrands = SaasBrand::withCount('products')
            ->having('products_count', '>', 0)
            ->orderBy('products_count', 'desc')
            ->take(10)->get();

        // Get demand products (products in wishlists)
        $demandProducts = SaasProduct::where('is_active', true)
            ->withCount('wishlists')
            ->with(['images', 'brand', 'reviews'])
            ->having('wishlists_count', '>', 0)
            ->orderBy('wishlists_count', 'desc')
            ->take(6)->get();

        // Get flash deals
        $activeFlashDeals = SaasFlashDeal::where('start_time', '<=', now())
            ->where('end_time', '>=', now())
            ->with(['products.images', 'products.brand'])
            ->first();

        return view('saas_customer.saas_home', compact(
            'sliderBanners',
            'promotionalBanners',
            'featuredCategories',
            'categories',
            'featuredProducts',
            'newArrivals',
            'topSellingProducts',
            'popularBrands',
            'demandProducts',
            'activeFlashDeals'
        ));
    }

    public function saasProductListing(Request $request)
    {
        $query = SaasProduct::where('is_active', true)->with(['images', 'brand', 'category', 'reviews']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $categories = is_array($request->category) ? $request->category : explode(',', $request->category);
            $query->whereHas('category', function($q) use ($categories) {
                $q->whereIn('slug', $categories);
            });
        }

        // Filter by subcategory
        if ($request->has('subcategory') && $request->subcategory) {
            $subcategories = is_array($request->subcategory) ? $request->subcategory : explode(',', $request->subcategory);
            $query->whereHas('subcategory', function($q) use ($subcategories) {
                $q->whereIn('slug', $subcategories);
            });
        }

        // Filter by brand
        if ($request->has('brand') && $request->brand) {
            $brands = is_array($request->brand) ? $request->brand : explode(',', $request->brand);
            $query->whereHas('brand', function($q) use ($brands) {
                $q->whereIn('slug', $brands);
            });
        }

        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('short_description', 'like', '%' . $request->search . '%');
            });
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->whereRaw('(price - (price * discount / 100)) >= ?', [$request->min_price]);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereRaw('(price - (price * discount / 100)) <= ?', [$request->max_price]);
        }

        // Size filter
        if ($request->has('sizes') && $request->sizes) {
            $sizes = is_array($request->sizes) ? $request->sizes : explode(',', $request->sizes);
            $query->whereHas('variations', function($q) use ($sizes) {
                $q->whereHas('attribute', function($attr) {
                    $attr->where('name', 'like', '%size%');
                })->whereHas('attributeValue', function($val) use ($sizes) {
                    $val->whereIn('value', $sizes);
                });
            });
        }

        // Color filter
        if ($request->has('colors') && $request->colors) {
            $colors = is_array($request->colors) ? $request->colors : explode(',', $request->colors);
            $query->whereHas('variations', function($q) use ($colors) {
                $q->whereHas('attribute', function($attr) {
                    $attr->where('name', 'like', '%color%');
                })->whereHas('attributeValue', function($val) use ($colors) {
                    $val->whereIn('value', $colors);
                });
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

        $products = $query->paginate(20);

        // Get filter data
        $categories = SaasCategory::where('status', true)
            ->with(['subcategories' => function($q) {
                $q->withCount(['products' => function($query) {
                      $query->where('is_active', true);
                  }]);
            }])
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();

        $brands = SaasBrand::withCount(['products' => function($q) {
            $q->where('is_active', true);
        }])
        ->having('products_count', '>', 0)
        ->get();

        // Get available sizes from product variations
        $availableSizes = DB::table('saas_product_variations')
            ->join('saas_attributes', 'saas_product_variations.attribute_id', '=', 'saas_attributes.id')
            ->join('saas_attribute_values', 'saas_product_variations.attribute_value_id', '=', 'saas_attribute_values.id')
            ->join('saas_products', 'saas_product_variations.product_id', '=', 'saas_products.id')
            ->where('saas_attributes.name', 'like', '%size%')
            ->where('saas_products.is_active', true)
            ->select('saas_attribute_values.value')
            ->distinct()
            ->orderBy('saas_attribute_values.value')
            ->get()
            ->pluck('value');

        // Get available colors from product variations
        $availableColors = DB::table('saas_product_variations')
            ->join('saas_attributes', 'saas_product_variations.attribute_id', '=', 'saas_attributes.id')
            ->join('saas_attribute_values', 'saas_product_variations.attribute_value_id', '=', 'saas_attribute_values.id')
            ->join('saas_products', 'saas_product_variations.product_id', '=', 'saas_products.id')
            ->where('saas_attributes.name', 'like', '%color%')
            ->where('saas_products.is_active', true)
            ->select('saas_attribute_values.value')
            ->distinct()
            ->orderBy('saas_attribute_values.value')
            ->get()
            ->pluck('value');

        // Get price range
        $priceRange = SaasProduct::where('is_active', true)
            ->selectRaw('
                MIN(price - (price * discount / 100)) as min_price,
                MAX(price - (price * discount / 100)) as max_price
            ')
            ->first();

        return view('saas_customer.saas_product_listing', compact(
            'products', 'categories', 'brands', 'availableSizes', 'availableColors', 'priceRange'
        ));
    }

    public function saasCategoryProducts($slug, Request $request)
    {
        $category = SaasCategory::where('slug', $slug)->where('status', true)->firstOrFail();

        $query = SaasProduct::where('is_active', true)
            ->where('category_id', $category->id)
            ->with(['images', 'brand', 'category', 'subcategory', 'reviews']);

        // Filter by subcategory
        if ($request->has('subcategory') && $request->subcategory) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('slug', $request->subcategory);
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

        // Size filter
        if ($request->has('sizes') && $request->sizes) {
            $sizes = is_array($request->sizes) ? $request->sizes : explode(',', $request->sizes);
            $query->whereHas('variations', function($q) use ($sizes) {
                $q->whereHas('attribute', function($attr) {
                    $attr->where('name', 'like', '%size%');
                })->whereHas('attributeValue', function($val) use ($sizes) {
                    $val->whereIn('value', $sizes);
                });
            });
        }

        // Color filter
        if ($request->has('colors') && $request->colors) {
            $colors = is_array($request->colors) ? $request->colors : explode(',', $request->colors);
            $query->whereHas('variations', function($q) use ($colors) {
                $q->whereHas('attribute', function($attr) {
                    $attr->where('name', 'like', '%color%');
                })->whereHas('attributeValue', function($val) use ($colors) {
                    $val->whereIn('value', $colors);
                });
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

        $products = $query->paginate(20);

        // Get subcategories for this category
        $subcategories = $category->subcategories()
            ->withCount(['products' => function($q) {
                $q->where('is_active', true);
            }])
            ->get();

        // Get brands for products in this category
        $brands = SaasBrand::whereHas('products', function($q) use ($category) {
            $q->where('category_id', $category->id)->where('is_active', true);
        })
        ->withCount(['products' => function($q) use ($category) {
            $q->where('category_id', $category->id)->where('is_active', true);
        }])
        ->get();

        // Get available sizes for products in this category
        $availableSizes = DB::table('saas_product_variations')
            ->join('saas_attributes', 'saas_product_variations.attribute_id', '=', 'saas_attributes.id')
            ->join('saas_attribute_values', 'saas_product_variations.attribute_value_id', '=', 'saas_attribute_values.id')
            ->join('saas_products', 'saas_product_variations.product_id', '=', 'saas_products.id')
            ->where('saas_attributes.name', 'like', '%size%')
            ->where('saas_products.category_id', $category->id)
            ->where('saas_products.is_active', true)
            ->select('saas_attribute_values.value')
            ->distinct()
            ->orderBy('saas_attribute_values.value')
            ->get()
            ->pluck('value');

        // Get available colors for products in this category
        $availableColors = DB::table('saas_product_variations')
            ->join('saas_attributes', 'saas_product_variations.attribute_id', '=', 'saas_attributes.id')
            ->join('saas_attribute_values', 'saas_product_variations.attribute_value_id', '=', 'saas_attribute_values.id')
            ->join('saas_products', 'saas_product_variations.product_id', '=', 'saas_products.id')
            ->where('saas_attributes.name', 'like', '%color%')
            ->where('saas_products.category_id', $category->id)
            ->where('saas_products.is_active', true)
            ->select('saas_attribute_values.value')
            ->distinct()
            ->orderBy('saas_attribute_values.value')
            ->get()
            ->pluck('value');

        // Get price range for this category
        $priceRange = SaasProduct::where('is_active', true)
            ->where('category_id', $category->id)
            ->selectRaw('
                MIN(price - (price * discount / 100)) as min_price,
                MAX(price - (price * discount / 100)) as max_price
            ')
            ->first();

        return view('saas_customer.saas_category_products', compact(
            'category', 'products', 'subcategories', 'brands', 'availableSizes', 'availableColors', 'priceRange'
        ));
    }

    public function saasBrandProducts($slug, Request $request)
    {
        $brand = SaasBrand::where('slug', $slug)->firstOrFail();

        $query = SaasProduct::where('is_active', true)
            ->where('brand_id', $brand->id)
            ->with(['images', 'brand', 'category', 'subcategory', 'reviews']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            if (is_array($request->category)) {
                $query->whereHas('category', function($q) use ($request) {
                    $q->whereIn('slug', $request->category);
                });
            } else {
                $query->whereHas('category', function($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }
        }

        // Price range filter
        if ($request->has('min_price') && $request->min_price) {
            $query->whereRaw('(price - (price * discount / 100)) >= ?', [$request->min_price]);
        }

        if ($request->has('max_price') && $request->max_price) {
            $query->whereRaw('(price - (price * discount / 100)) <= ?', [$request->max_price]);
        }

        // Size filter
        if ($request->has('sizes') && $request->sizes) {
            $sizes = is_array($request->sizes) ? $request->sizes : explode(',', $request->sizes);
            $query->whereHas('variations', function($q) use ($sizes) {
                $q->whereHas('attribute', function($attr) {
                    $attr->where('name', 'like', '%size%');
                })->whereHas('attributeValue', function($val) use ($sizes) {
                    $val->whereIn('value', $sizes);
                });
            });
        }

        // Color filter
        if ($request->has('colors') && $request->colors) {
            $colors = is_array($request->colors) ? $request->colors : explode(',', $request->colors);
            $query->whereHas('variations', function($q) use ($colors) {
                $q->whereHas('attribute', function($attr) {
                    $attr->where('name', 'like', '%color%');
                })->whereHas('attributeValue', function($val) use ($colors) {
                    $val->whereIn('value', $colors);
                });
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

        $products = $query->paginate(20);

        // Get categories for products of this brand
        $categories = SaasCategory::whereHas('products', function($q) use ($brand) {
            $q->where('brand_id', $brand->id)->where('is_active', true);
        })
        ->where('status', 'active')
        ->withCount(['products' => function($q) use ($brand) {
            $q->where('brand_id', $brand->id)->where('is_active', true);
        }])
        ->get();

        // Get available sizes for products of this brand
        $availableSizes = DB::table('saas_product_variations')
            ->join('saas_attributes', 'saas_product_variations.attribute_id', '=', 'saas_attributes.id')
            ->join('saas_attribute_values', 'saas_product_variations.attribute_value_id', '=', 'saas_attribute_values.id')
            ->join('saas_products', 'saas_product_variations.product_id', '=', 'saas_products.id')
            ->where('saas_attributes.name', 'like', '%size%')
            ->where('saas_products.brand_id', $brand->id)
            ->where('saas_products.is_active', true)
            ->select('saas_attribute_values.value')
            ->distinct()
            ->orderBy('saas_attribute_values.value')
            ->get()
            ->pluck('value');

        // Get available colors for products of this brand
        $availableColors = DB::table('saas_product_variations')
            ->join('saas_attributes', 'saas_product_variations.attribute_id', '=', 'saas_attributes.id')
            ->join('saas_attribute_values', 'saas_product_variations.attribute_value_id', '=', 'saas_attribute_values.id')
            ->join('saas_products', 'saas_product_variations.product_id', '=', 'saas_products.id')
            ->where('saas_attributes.name', 'like', '%color%')
            ->where('saas_products.brand_id', $brand->id)
            ->where('saas_products.is_active', true)
            ->select('saas_attribute_values.value')
            ->distinct()
            ->orderBy('saas_attribute_values.value')
            ->get()
            ->pluck('value');

        // Get price range for this brand
        $priceRange = SaasProduct::where('is_active', true)
            ->where('brand_id', $brand->id)
            ->selectRaw('
                MIN(price - (price * discount / 100)) as min_price,
                MAX(price - (price * discount / 100)) as max_price
            ')
            ->first();

        return view('saas_customer.saas_brand_products', compact(
            'brand', 'products', 'categories', 'availableSizes', 'availableColors', 'priceRange'
        ));
    }

    public function saasProductDetail($slug)
    {
        $product = SaasProduct::where('slug', $slug)
            ->where('is_active', true)
            ->with([
                'images',
                'brand',
                'category',
                'subcategory',
                'childCategory',
                'variations.attributeValue.attribute',
                'reviews.customer',
                'seller'
            ])
            ->firstOrFail();

        // Increment views
        $product->increment('views');

        // Get related products
        $relatedProducts = SaasProduct::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images', 'brand', 'reviews'])
            ->inRandomOrder()
            ->take(8)
            ->get();

        // Check if product is in user's wishlist
        $isInWishlist = false;
        if (Auth::check()) {
            $isInWishlist = SaasWishlist::where('customer_id', Auth::id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('saas_customer.saas_product_detail', compact('product', 'relatedProducts', 'isInWishlist'));
    }

    public function saasGetProductVariations(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:saas_products,id',
            'attribute_id' => 'required|exists:saas_attributes,id',
            'attribute_value_id' => 'required|exists:saas_attribute_values,id',
        ]);

        $variations = SaasProductVariation::where('product_id', $request->product_id)
            ->where('attribute_id', $request->attribute_id)
            ->where('attribute_value_id', $request->attribute_value_id)
            ->where('stock', '>', 0)
            ->get();

        return response()->json([
            'success' => true,
            'variations' => $variations
        ]);
    }
}
