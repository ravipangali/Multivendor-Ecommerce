<?php

namespace App\Http\Controllers\SaasCustomer;

use App\Http\Controllers\Controller;
use App\Models\SaasBlogPost;
use App\Models\SaasBlogCategory;
use Illuminate\Http\Request;

class SaasBlogController extends Controller
{
    /**
     * Display blog listing page.
     */
    public function index(Request $request)
    {
        $query = SaasBlogPost::published()
                            ->with(['author', 'category'])
                            ->orderBy('published_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Featured posts filter
        if ($request->filled('featured')) {
            $query->featured();
        }

        $posts = $query->paginate(12);

        // Get categories for sidebar
        $categories = SaasBlogCategory::active()
                                    ->withCount('blogPosts')
                                    ->orderBy('name', 'asc')
                                    ->get();

        // Get featured posts for sidebar
        $featuredPosts = SaasBlogPost::published()
                                   ->featured()
                                   ->limit(5)
                                   ->get();

        // Get recent posts for sidebar
        $recentPosts = SaasBlogPost::published()
                                 ->orderBy('published_at', 'desc')
                                 ->limit(5)
                                 ->get();

        return view('saas_customer.saas_blog.saas_index', compact(
            'posts',
            'categories',
            'featuredPosts',
            'recentPosts'
        ));
    }

    /**
     * Display a specific blog post.
     */
    public function show($slug)
    {
        $post = SaasBlogPost::published()
                           ->with(['author', 'category'])
                           ->where('slug', $slug)
                           ->firstOrFail();

        // Increment views
        $post->incrementViews();

        // Get related posts
        $relatedPosts = $post->related_posts;

        // Get categories for sidebar
        $categories = SaasBlogCategory::active()
                                    ->withCount('blogPosts')
                                    ->orderBy('name', 'asc')
                                    ->get();

        // Get recent posts for sidebar
        $recentPosts = SaasBlogPost::published()
                                 ->where('id', '!=', $post->id)
                                 ->orderBy('published_at', 'desc')
                                 ->limit(5)
                                 ->get();

        return view('saas_customer.saas_blog.saas_show', compact(
            'post',
            'relatedPosts',
            'categories',
            'recentPosts'
        ));
    }

    /**
     * Display posts by category.
     */
    public function category($slug, Request $request)
    {
        $category = SaasBlogCategory::active()
                                  ->where('slug', $slug)
                                  ->firstOrFail();

        $query = SaasBlogPost::published()
                            ->with(['author', 'category'])
                            ->where('category_id', $category->id)
                            ->orderBy('published_at', 'desc');

        // Search within category
        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        $posts = $query->paginate(12);

        // Get all categories for sidebar
        $categories = SaasBlogCategory::active()
                                    ->withCount('blogPosts')
                                    ->orderBy('name', 'asc')
                                    ->get();

        // Get recent posts for sidebar
        $recentPosts = SaasBlogPost::published()
                                 ->orderBy('published_at', 'desc')
                                 ->limit(5)
                                 ->get();

        return view('saas_customer.saas_blog.saas_category', compact(
            'category',
            'posts',
            'categories',
            'recentPosts'
        ));
    }

    /**
     * Display search results.
     */
    public function search(Request $request)
    {
        $search = $request->get('q', '');

        if (empty($search)) {
            return redirect()->route('customer.blog.index');
        }

        $posts = SaasBlogPost::published()
                            ->with(['author', 'category'])
                            ->search($search)
                            ->orderBy('published_at', 'desc')
                            ->paginate(12);

        // Get categories for sidebar
        $categories = SaasBlogCategory::active()
                                    ->withCount('blogPosts')
                                    ->orderBy('name', 'asc')
                                    ->get();

        return view('saas_customer.saas_blog.saas_search', compact(
            'posts',
            'categories',
            'search'
        ));
    }

    /**
     * Get latest blog posts for home page.
     */
    public static function getLatestPosts($limit = 6)
    {
        return SaasBlogPost::published()
                          ->with(['author', 'category'])
                          ->orderBy('published_at', 'desc')
                          ->limit($limit)
                          ->get();
    }

    /**
     * Get featured posts for home page.
     */
    public static function getFeaturedPosts($limit = 3)
    {
        return SaasBlogPost::published()
                          ->featured()
                          ->with(['author', 'category'])
                          ->orderBy('published_at', 'desc')
                          ->limit($limit)
                          ->get();
    }
}
