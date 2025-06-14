<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasProduct;
use App\Models\SaasCategory;
use App\Models\SaasSubCategory;
use App\Models\SaasBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaasCustomerSearchController extends Controller
{
    public function saasSearch(Request $request)
    {
        $query = SaasProduct::where('is_active', true)->with(['images', 'brand', 'category', 'reviews']);

        // Search term
        if ($request->has('q') && $request->q) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('short_description', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('brand', function($brandQuery) use ($searchTerm) {
                      $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
                  })
                  ->orWhereHas('category', function($catQuery) use ($searchTerm) {
                      $catQuery->where('name', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Category filter
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

        // Subcategory filter
        if ($request->has('subcategory') && $request->subcategory) {
            $query->whereHas('subcategory', function($q) use ($request) {
                $q->where('slug', $request->subcategory);
            });
        }

        // Brand filter
        if ($request->has('brand') && $request->brand) {
            if (is_array($request->brand)) {
                $query->whereHas('brand', function($q) use ($request) {
                    $q->whereIn('slug', $request->brand);
                });
            } else {
                $query->whereHas('brand', function($q) use ($request) {
                    $q->where('slug', $request->brand);
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
            $sizes = is_array($request->sizes) ? $request->sizes : [$request->sizes];
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
            $colors = is_array($request->colors) ? $request->colors : [$request->colors];
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

        $products = $query->paginate(20)->withQueryString();

        // Get filter data for sidebar
        $categories = SaasCategory::where('status', 'active')->withCount('products')->get();
        $brands = SaasBrand::withCount('products')->having('products_count', '>', 0)->get();

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
        $priceRange = SaasProduct::where('is_active', true)->selectRaw('MIN(price - (price * discount / 100)) as min_price, MAX(price - (price * discount / 100)) as max_price')->first();

        return view('saas_customer.saas_search_results', compact(
            'products',
            'categories',
            'brands',
            'availableSizes',
            'availableColors',
            'priceRange'
        ));
    }

    public function saasGetSubcategories(Request $request)
    {
        $categoryId = $request->category_id;

        $subcategories = SaasSubCategory::where('category_id', $categoryId)
            ->where('status', 'active')
            ->get(['id', 'name', 'slug']);

        return response()->json($subcategories);
    }

    public function saasGetFilterData(Request $request)
    {
        $data = [];

        // Get categories
        $data['categories'] = SaasCategory::where('status', 'active')
            ->withCount('products')
            ->having('products_count', '>', 0)
            ->get(['id', 'name', 'slug', 'products_count']);

        // Get brands
        $data['brands'] = SaasBrand::withCount('products')
            ->having('products_count', '>', 0)
            ->get(['id', 'name', 'slug', 'products_count']);

        // Get price range
        $data['price_range'] = SaasProduct::where('is_active', true)
            ->selectRaw('MIN(price) as min_price, MAX(price) as max_price')
            ->first();

        return response()->json($data);
    }
}
