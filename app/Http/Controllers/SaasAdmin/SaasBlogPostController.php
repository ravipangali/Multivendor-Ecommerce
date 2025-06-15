<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasBlogPost;
use App\Models\SaasBlogCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SaasBlogPostController extends Controller
{
    /**
     * Process base64 images in content and convert them to uploaded files
     */
    private function processBase64Images($content)
    {
        if (empty($content)) {
            return $content;
        }

        // Find all base64 images in the content
        $pattern = '/<img[^>]+src=["\'](data:image\/[^;]+;base64,[^"\']+)["\'][^>]*>/i';

        $processedContent = preg_replace_callback($pattern, function($matches) {
            try {
                $dataUri = $matches[1];

                // Extract the base64 data and image type
                if (preg_match('/data:image\/([^;]+);base64,(.+)/', $dataUri, $imageMatches)) {
                    $imageType = $imageMatches[1];
                    $base64Data = $imageMatches[2];

                    // Decode the base64 data
                    $imageData = base64_decode($base64Data);

                    if ($imageData === false) {
                        Log::warning('Failed to decode base64 image data');
                        return $matches[0]; // Return original if decoding fails
                    }

                    // Create a unique filename
                    $filename = 'tinymce_images/' . Str::random(10) . '_' . time() . '.' . $imageType;

                    // Store the image
                    $stored = Storage::disk('public')->put($filename, $imageData);

                    if (!$stored) {
                        Log::warning('Failed to store processed base64 image');
                        return $matches[0]; // Return original if storage fails
                    }

                    // Generate the URL
                    $url = asset('storage/' . $filename);

                    // Replace the data URI with the file URL
                    return str_replace($dataUri, $url, $matches[0]);
                }

                return $matches[0]; // Return original if pattern doesn't match

            } catch (\Exception $e) {
                Log::error('Error processing base64 image: ' . $e->getMessage());
                return $matches[0]; // Return original on error
            }
        }, $content);

        return $processedContent;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SaasBlogPost::with(['author', 'category'])
                            ->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->search($search);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('featured')) {
            $query->where('is_featured', $request->featured === 'yes');
        }

        $posts = $query->paginate(20);
        $categories = SaasBlogCategory::active()->get();

        return view('saas_admin.saas_blog_post.saas_index', compact('posts', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = SaasBlogCategory::active()->get();
        $authors = User::where('role', 'admin')->orWhere('role', 'super_admin')->get();
        return view('saas_admin.saas_blog_post.saas_create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:saas_blog_posts,slug',
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'author_id' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:saas_blog_categories,id',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string',
        ]);

        $data = $request->all();

        // Process base64 images in content
        if (isset($data['content'])) {
            $data['content'] = $this->processBase64Images($data['content']);
        }

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $filename = 'blog_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $data['featured_image'] = $filename;
        }

        // Set default author if not provided
        if (!isset($data['author_id']) || !$data['author_id']) {
            $data['author_id'] = auth()->id();
        }

        // Convert tags string to array
        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        }

        $post = SaasBlogPost::create($data);

        return redirect()->route('admin.blog-posts.index')
                        ->with('success', 'Blog post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasBlogPost $blog_post)
    {
        $blog_post->load(['author', 'category']);
        return view('saas_admin.saas_blog_post.saas_show', compact('blog_post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasBlogPost $blog_post)
    {
        $categories = SaasBlogCategory::active()->get();
        $authors = User::where('role', 'admin')->orWhere('role', 'super_admin')->get();
        $post = $blog_post; // Alias for view compatibility
        return view('saas_admin.saas_blog_post.saas_edit', compact('post', 'categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasBlogPost $blog_post)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:saas_blog_posts,slug,' . $blog_post->id,
            'excerpt' => 'nullable|string',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'is_featured' => 'boolean',
            'author_id' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:saas_blog_categories,id',
            'published_at' => 'nullable|date',
            'tags' => 'nullable|string',
        ]);

        $data = $request->all();

        // Process base64 images in content
        if (isset($data['content'])) {
            $data['content'] = $this->processBase64Images($data['content']);
        }

        // Generate slug if not provided
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blog_post->featured_image) {
                Storage::disk('public')->delete($blog_post->featured_image);
            }

            $image = $request->file('featured_image');
            $filename = 'blog_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $data['featured_image'] = $filename;
        }

        // Convert tags string to array
        if (!empty($data['tags'])) {
            $data['tags'] = array_map('trim', explode(',', $data['tags']));
        } else {
            $data['tags'] = [];
        }

        $blog_post->update($data);

        return redirect()->route('admin.blog-posts.index')
                        ->with('success', 'Blog post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasBlogPost $blog_post)
    {
        // Delete featured image
        if ($blog_post->featured_image) {
            Storage::disk('public')->delete($blog_post->featured_image);
        }

        $blog_post->delete();

        return redirect()->route('admin.blog-posts.index')
                        ->with('success', 'Blog post deleted successfully.');
    }

    /**
     * Toggle post status.
     */
    public function toggleStatus(SaasBlogPost $blog_post)
    {
        $blog_post->update(['status' => !$blog_post->status]);

        $status = $blog_post->status ? 'published' : 'drafted';
        return redirect()->back()->with('success', "Post {$status} successfully.");
    }
}
