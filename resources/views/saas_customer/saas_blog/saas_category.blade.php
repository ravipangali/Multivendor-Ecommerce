@extends('saas_customer.saas_layout.saas_layout')

@section('title', $category->meta_title ?: ($category->name . ' - Blog Category'))
@section('meta_description', $category->meta_description ?: ('Browse blog posts in ' . $category->name . ' category'))
@section('meta_keywords', $category->meta_keywords)

@section('content')
<!-- Category Header -->
<section class="saas-blog-category-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="category-header-content">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('customer.home') }}">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('customer.blog.index') }}">Blog</a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $category->name }}
                            </li>
                        </ol>
                    </nav>
                    <h1 class="category-title">{{ $category->name }}</h1>
                    @if($category->description)
                        <p class="category-description">{{ $category->description }}</p>
                    @endif
                    <div class="category-stats">
                        <span class="post-count">{{ $posts->total() }} {{ Str::plural('post', $posts->total()) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Category Content -->
<section class="saas-blog-category-content">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                @if($posts->count() > 0)
                    <div class="category-posts">
                        <div class="row">
                            @foreach($posts as $post)
                                <div class="col-md-6 mb-4">
                                    <article class="blog-card">
                                        @if($post->featured_image_url)
                                            <div class="blog-image">
                                                <a href="{{ route('customer.blog.show', $post->slug) }}">
                                                    <img src="{{ $post->featured_image_url }}"
                                                         alt="{{ $post->title }}"
                                                         class="img-fluid">
                                                </a>
                                            </div>
                                        @endif
                                        <div class="blog-content">
                                            <div class="blog-meta">
                                                <span class="category-badge">{{ $category->name }}</span>
                                                <span class="date">{{ $post->published_at->format('M d, Y') }}</span>
                                            </div>
                                            <h3 class="blog-title">
                                                <a href="{{ route('customer.blog.show', $post->slug) }}">{{ $post->title }}</a>
                                            </h3>
                                            <p class="blog-excerpt">{{ $post->excerpt }}</p>
                                            <div class="blog-footer">
                                                <div class="author">
                                                    <i class="fas fa-user"></i> {{ $post->author->name }}
                                                </div>
                                                <div class="views">
                                                    <i class="fas fa-eye"></i> {{ $post->views }} views
                                                </div>
                                            </div>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="blog-pagination">
                            {{ $posts->links() }}
                        </div>
                    </div>
                @else
                    <div class="no-posts">
                        <div class="text-center py-5">
                            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                            <h4>No posts in this category</h4>
                            <p class="text-muted">Check back later for new content in {{ $category->name }}!</p>
                            <a href="{{ route('customer.blog.index') }}" class="btn btn-primary mt-3">
                                <i class="fas fa-arrow-left"></i> Back to All Posts
                            </a>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="blog-sidebar">
                    <!-- All Categories -->
                    @if(isset($allCategories) && $allCategories->count() > 0)
                        <div class="sidebar-widget">
                            <h4 class="widget-title">All Categories</h4>
                            <ul class="category-list">
                                @foreach($allCategories as $cat)
                                    <li>
                                        <a href="{{ route('customer.blog.category', $cat->slug) }}"
                                           class="{{ $category->id == $cat->id ? 'active' : '' }}">
                                            {{ $cat->name }}
                                            <span class="post-count">({{ $cat->posts_count ?? 0 }})</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Recent Posts -->
                    @if(isset($recentPosts) && $recentPosts->count() > 0)
                        <div class="sidebar-widget">
                            <h4 class="widget-title">Recent Posts</h4>
                            <div class="recent-posts">
                                @foreach($recentPosts as $recentPost)
                                    <div class="recent-post">
                                        @if($recentPost->featured_image_url)
                                            <div class="recent-post-image">
                                                <a href="{{ route('customer.blog.show', $recentPost->slug) }}">
                                                    <img src="{{ $recentPost->featured_image_url }}"
                                                         alt="{{ $recentPost->title }}"
                                                         class="img-fluid">
                                                </a>
                                            </div>
                                        @endif
                                        <div class="recent-post-content">
                                            <h6 class="recent-post-title">
                                                <a href="{{ route('customer.blog.show', $recentPost->slug) }}">
                                                    {{ $recentPost->title }}
                                                </a>
                                            </h6>
                                            <div class="recent-post-meta">
                                                <span class="date">{{ $recentPost->published_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Back to Blog -->
                    <div class="sidebar-widget">
                        <div class="text-center">
                            <a href="{{ route('customer.blog.index') }}" class="btn btn-outline-primary">
                                <i class="fas fa-arrow-left"></i> Back to All Posts
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.saas-blog-category-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 3rem 0 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.category-header-content {
    text-align: center;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 1rem;
    justify-content: center;
}

.breadcrumb-item a {
    color: #64748b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.breadcrumb-item a:hover {
    color: #abcf37;
}

.breadcrumb-item.active {
    color: #1e293b;
    font-weight: 600;
}

.category-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem 0;
}

.category-description {
    font-size: 1.125rem;
    color: #64748b;
    margin: 0 0 1rem 0;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.category-stats {
    color: #64748b;
    font-size: 1rem;
}

.post-count {
    background: #abcf37;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
}

.saas-blog-category-content {
    padding: 4rem 0;
}

.blog-card {
    background: #ffffff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
}

.blog-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.blog-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.blog-card:hover .blog-image img {
    transform: scale(1.05);
}

.blog-content {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    height: calc(100% - 200px);
}

.blog-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.category-badge {
    background: #abcf37;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.date {
    color: #64748b;
    font-size: 0.875rem;
}

.blog-content .blog-title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.blog-content .blog-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.blog-content .blog-title a:hover {
    color: #abcf37;
}

.blog-excerpt {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: auto;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.blog-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #e2e8f0;
    font-size: 0.875rem;
    color: #64748b;
}

.no-posts {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.blog-pagination {
    margin-top: 3rem;
    display: flex;
    justify-content: center;
}

.blog-sidebar {
    padding-left: 2rem;
}

.sidebar-widget {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.widget-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 1.5rem;
    position: relative;
    padding-bottom: 0.5rem;
}

.widget-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 30px;
    height: 2px;
    background: #abcf37;
    border-radius: 1px;
}

.category-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.category-list li {
    margin-bottom: 0.75rem;
}

.category-list a {
    color: #64748b;
    text-decoration: none;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    transition: color 0.3s ease;
}

.category-list a:hover,
.category-list a.active {
    color: #abcf37;
}

.category-list .post-count {
    background: #f1f5f9;
    color: #64748b;
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: normal;
}

.recent-post {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #e2e8f0;
}

.recent-post:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.recent-post-image {
    flex-shrink: 0;
    width: 80px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
}

.recent-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.recent-post-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.recent-post-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.recent-post-title a:hover {
    color: #abcf37;
}

.recent-post-meta {
    font-size: 0.75rem;
    color: #64748b;
}

.btn-outline-primary {
    border: 2px solid #abcf37;
    color: #abcf37;
    background: transparent;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-outline-primary:hover {
    background: #abcf37;
    color: white;
}

@media (max-width: 991px) {
    .blog-sidebar {
        padding-left: 0;
        margin-top: 3rem;
    }
}

@media (max-width: 767px) {
    .category-title {
        font-size: 2rem;
    }

    .blog-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }

    .blog-footer {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>
@endpush
