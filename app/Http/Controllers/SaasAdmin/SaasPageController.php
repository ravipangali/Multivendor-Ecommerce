<?php

namespace App\Http\Controllers\SaasAdmin;

use App\Http\Controllers\Controller;
use App\Models\SaasPage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class SaasPageController extends Controller
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
        $query = SaasPage::with('author')->orderBy('position', 'asc')->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'active');
        }

        $pages = $query->paginate(20);

        return view('saas_admin.saas_page.saas_index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $authors = User::where('role', 'admin')->orWhere('role', 'super_admin')->get();
        return view('saas_admin.saas_page.saas_create', compact('authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:saas_pages,slug',
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'template' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'in_footer' => 'boolean',
            'in_header' => 'boolean',
            'position' => 'nullable|integer|min:0',
            'author_id' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
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
            $filename = 'page_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $data['featured_image'] = $filename;
        }

        // Set default author if not provided
        if (!isset($data['author_id']) || !$data['author_id']) {
            $data['author_id'] = auth()->id();
        }

        $page = SaasPage::create($data);

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(SaasPage $page)
    {
        $page->load('author');
        return view('saas_admin.saas_page.saas_show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SaasPage $page)
    {
        $authors = User::where('role', 'admin')->orWhere('role', 'super_admin')->get();
        return view('saas_admin.saas_page.saas_edit', compact('page', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SaasPage $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:saas_pages,slug,' . $page->getKey(),
            'content' => 'required|string',
            'excerpt' => 'nullable|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'template' => 'nullable|string|max:100',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'boolean',
            'in_footer' => 'boolean',
            'in_header' => 'boolean',
            'position' => 'nullable|integer|min:0',
            'author_id' => 'nullable|exists:users,id',
            'published_at' => 'nullable|date',
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
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }

            $image = $request->file('featured_image');
            $filename = 'page_images/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public', $filename);
            $data['featured_image'] = $filename;
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SaasPage $page)
    {
        // Delete featured image
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }

        $page->delete();

        return redirect()->route('admin.pages.index')
                        ->with('success', 'Page deleted successfully.');
    }

    /**
     * Toggle page status.
     */
    public function toggleStatus(SaasPage $page)
    {
        $page->update(['status' => !$page->status]);

        $status = $page->status ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Page {$status} successfully.");
    }
}
