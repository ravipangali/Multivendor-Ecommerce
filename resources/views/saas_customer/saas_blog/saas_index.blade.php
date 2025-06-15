@extends('saas_customer.saas_layout.saas_layout')

@section('title', 'Blog - Latest News & Updates')
@section('meta_description', 'Stay updated with our latest blog posts, news, and insights.')

@section('content')
<!-- Blog Header -->
<section class="saas-blog-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="blog-header-content">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('customer.home') }}">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Blog
                            </li>
                        </ol>
                    </nav>
                    <h1 class="blog-title">Our Blog</h1>
                    <p class="blog-subtitle">Stay updated with our latest news, tips, and insights</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Content -->
<section class="saas-blog-content">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Search Bar -->
                <div class="blog-search-bar mb-4">
                    <form action="{{ route('customer.blog.search') }}" method="GET" class="search-form">
                        <div class="input-group">
                            <input type="text"
                                   name="q"
                                   class="form-control"
                                   placeholder="Search blog posts..."
                                   value="{{ request('q') }}">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Featured Posts -->
                @if(isset($featuredPosts) && $featuredPosts->count() > 0)
                    <div class="featured-posts mb-5">
                        <h2 class="section-title">Featured Posts</h2>
                        <div class="row">
                            @foreach($featuredPosts as $post)
                                <div class="col-md-6 mb-4">
                                    <article class="blog-card featured">
                                        @if($post->featured_image_url)
                                            <div class="blog-image">
                                                <a href="{{ route('customer.blog.show', $post->slug) }}">
                                                    <img src="{{ $post->featured_image_url }}"
                                                         alt="{{ $post->title }}"
                                                         class="img-fluid">
                                                </a>
                                                <span class="featured-badge">Featured</span>
                                            </div>
                                        @endif
                                        <div class="blog-content">
                                            <div class="blog-meta">
                                                @if($post->category)
                                                    <a href="{{ route('customer.blog.category', $post->category->slug) }}"
                                                       class="category-link">{{ $post->category->name }}</a>
                                                @endif
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
                    </div>
                @endif

                <!-- Regular Posts -->
                <div class="blog-posts">
                    <h2 class="section-title">Latest Posts</h2>

                    @if(isset($posts) && $posts->count() > 0)
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
                                                @if($post->category)
                                                    <a href="{{ route('customer.blog.category', $post->category->slug) }}"
                                                       class="category-link">{{ $post->category->name }}</a>
                                                @endif
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
                    @else
                        <div class="no-posts">
                            <div class="text-center py-5">
                                <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                                <h4>No blog posts found</h4>
                                <p class="text-muted">Check back later for new content!</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <aside class="blog-sidebar">
                    <!-- Categories -->
                    @if(isset($categories) && $categories->count() > 0)
                        <div class="sidebar-widget">
                            <h4 class="widget-title">Categories</h4>
                            <ul class="category-list">
                                @foreach($categories as $category)
                                    <li>
                                        <a href="{{ route('customer.blog.category', $category->slug) }}">
                                            {{ $category->name }}
                                            <span class="post-count">({{ $category->posts_count ?? 0 }})</span>
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
                                @foreach($recentPosts as $post)
                                    <div class="recent-post">
                                        @if($post->featured_image_url)
                                            <div class="recent-post-image">
                                                <a href="{{ route('customer.blog.show', $post->slug) }}">
                                                    <img src="{{ $post->featured_image_url }}"
                                                         alt="{{ $post->title }}"
                                                         class="img-fluid">
                                                </a>
                                            </div>
                                        @endif
                                        <div class="recent-post-content">
                                            <h6 class="recent-post-title">
                                                <a href="{{ route('customer.blog.show', $post->slug) }}">{{ $post->title }}</a>
                                            </h6>
                                            <div class="recent-post-meta">
                                                <span class="date">{{ $post->published_at->format('M d, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.saas-blog-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 3rem 0 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.blog-header-content {
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

.blog-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem 0;
}

.blog-subtitle {
    font-size: 1.125rem;
    color: #64748b;
    margin: 0;
}

.saas-blog-content {
    padding: 4rem 0;
}

.section-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 0.5rem;
}

.section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: #abcf37;
    border-radius: 2px;
}

.blog-search-bar .input-group {
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}

.blog-search-bar .form-control {
    border: none;
    padding: 1rem;
    font-size: 1rem;
}

.blog-search-bar .btn {
    background: #abcf37;
    border: none;
    padding: 1rem 1.5rem;
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

.blog-card.featured {
    border: 2px solid #abcf37;
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

.featured-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: #abcf37;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
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

.category-link {
    background: #abcf37;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.3s ease;
}

.category-link:hover {
    background: #8fb12d;
    color: white;
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

.category-list a:hover {
    color: #abcf37;
}

.post-count {
    background: #f1f5f9;
    color: #64748b;
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
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

@media (max-width: 991px) {
    .blog-sidebar {
        padding-left: 0;
        margin-top: 3rem;
    }
}

@media (max-width: 767px) {
    .blog-title {
        font-size: 2rem;
    }

    .section-title {
        font-size: 1.5rem;
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
