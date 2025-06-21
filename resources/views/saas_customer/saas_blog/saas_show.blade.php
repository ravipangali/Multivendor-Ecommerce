@extends('saas_customer.saas_layout.saas_layout')

@section('title', $post->meta_title ?: $post->title)
@section('meta_description', $post->meta_description ?: $post->excerpt)
@section('meta_keywords', $post->meta_keywords)

@section('content')
<!-- Blog Post Header -->
<section class="saas-blog-post-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="post-header-content">
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
                            @if($post->category)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('customer.blog.category', $post->category->slug) }}">
                                        {{ $post->category->name }}
                                    </a>
                                </li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ Str::limit($post->title, 50) }}
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Blog Post Content -->
<section class="saas-blog-post-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <article class="blog-post-article">
                    <!-- Post Header -->
                    <header class="post-header">
                        @if($post->category)
                            <div class="post-category">
                                <a href="{{ route('customer.blog.category', $post->category->slug) }}"
                                   class="category-link">{{ $post->category->name }}</a>
                            </div>
                        @endif

                        <h1 class="post-title">{{ $post->title }}</h1>

                        <div class="post-meta">
                            <div class="meta-item">
                                <i class="fas fa-user"></i>
                                <span>{{ $post->author->name }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $post->published_at->format('M d, Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="fas fa-eye"></i>
                                <span>{{ $post->views }} views</span>
                            </div>
                            @if($post->tags && count($post->tags) > 0)
                                <div class="meta-item">
                                    <i class="fas fa-tags"></i>
                                    <div class="post-tags">
                                        @foreach($post->tags as $tag)
                                            <span class="tag">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </header>

                    <!-- Featured Image -->
                    @if($post->featured_image_url)
                        <div class="post-featured-image">
                            <img src="{{ $post->featured_image_url }}"
                                 alt="{{ $post->title }}"
                                 class="img-fluid blog-detail-img">
                        </div>
                    @endif

                    <!-- Post Content -->
                    <div class="post-content">
                        {!! $post->content !!}
                    </div>

                    <!-- Post Footer -->
                    <footer class="post-footer">
                        @if($post->tags && count($post->tags) > 0)
                            <div class="post-tags-section">
                                <h6>Tags:</h6>
                                <div class="tags-list">
                                    @foreach($post->tags as $tag)
                                        <span class="tag-item">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Social Share -->
                        <div class="social-share">
                            <h6>Share this post:</h6>
                            <div class="share-buttons">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->fullUrl()) }}"
                                   target="_blank" class="share-btn facebook">
                                    <i class="fab fa-facebook-f"></i>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->fullUrl()) }}&text={{ urlencode($post->title) }}"
                                   target="_blank" class="share-btn twitter">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->fullUrl()) }}"
                                   target="_blank" class="share-btn linkedin">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' - ' . request()->fullUrl()) }}"
                                   target="_blank" class="share-btn whatsapp">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                            </div>
                        </div>
                    </footer>
                </article>

                <!-- Navigation -->
                @if($post->previous_post || $post->next_post)
                    <nav class="post-navigation">
                        <div class="row">
                            @if($post->previous_post)
                                <div class="col-md-6">
                                    <div class="nav-post prev-post">
                                        <span class="nav-label">Previous Post</span>
                                        <h6 class="nav-title">
                                            <a href="{{ route('customer.blog.show', $post->previous_post->slug) }}">
                                                {{ $post->previous_post->title }}
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            @endif

                            @if($post->next_post)
                                <div class="col-md-6">
                                    <div class="nav-post next-post text-md-end">
                                        <span class="nav-label">Next Post</span>
                                        <h6 class="nav-title">
                                            <a href="{{ route('customer.blog.show', $post->next_post->slug) }}">
                                                {{ $post->next_post->title }}
                                            </a>
                                        </h6>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </nav>
                @endif

                <!-- Related Posts -->
                @if($post->related_posts && $post->related_posts->count() > 0)
                    <section class="related-posts">
                        <h3 class="section-title">Related Posts</h3>
                        <div class="row">
                            @foreach($post->related_posts as $relatedPost)
                                <div class="col-md-6 mb-4">
                                    <article class="related-post-card">
                                        @if($relatedPost->featured_image_url)
                                            <div class="related-post-image">
                                                <a href="{{ route('customer.blog.show', $relatedPost->slug) }}">
                                                    <img src="{{ $relatedPost->featured_image_url }}"
                                                         alt="{{ $relatedPost->title }}"
                                                         class="img-fluid blog-related-img">
                                                </a>
                                            </div>
                                        @endif
                                        <div class="related-post-content">
                                            <div class="related-post-meta">
                                                @if($relatedPost->category)
                                                    <a href="{{ route('customer.blog.category', $relatedPost->category->slug) }}"
                                                       class="category-link">{{ $relatedPost->category->name }}</a>
                                                @endif
                                                <span class="date">{{ $relatedPost->published_at->format('M d, Y') }}</span>
                                            </div>
                                            <h5 class="related-post-title">
                                                <a href="{{ route('customer.blog.show', $relatedPost->slug) }}">
                                                    {{ $relatedPost->title }}
                                                </a>
                                            </h5>
                                            <p class="related-post-excerpt">{{ $relatedPost->excerpt }}</p>
                                        </div>
                                    </article>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
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
                                        <a href="{{ route('customer.blog.category', $category->slug) }}"
                                           class="{{ $post->category && $post->category->id == $category->id ? 'active' : '' }}">
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
                                @foreach($recentPosts as $recentPost)
                                    <div class="recent-post">
                                        @if($recentPost->featured_image_url)
                                            <div class="recent-post-image">
                                                <a href="{{ route('customer.blog.show', $recentPost->slug) }}">
                                                    <img src="{{ $recentPost->featured_image_url }}"
                                                         alt="{{ $recentPost->title }}"
                                                         class="img-fluid blog-recent-img">
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
                </aside>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.saas-blog-post-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 2rem 0 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.post-header-content {
    text-align: center;
}

.breadcrumb {
    background: none;
    padding: 0;
    margin-bottom: 0;
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

.saas-blog-post-content {
    padding: 4rem 0;
}

.blog-post-article {
    background: #ffffff;
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    margin-bottom: 2rem;
}

.post-header {
    text-align: center;
    margin-bottom: 2rem;
}

.post-category {
    margin-bottom: 1rem;
}

.category-link {
    background: #abcf37;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.875rem;
    font-weight: 600;
    text-decoration: none;
    transition: background 0.3s ease;
}

.category-link:hover {
    background: #8fb12d;
    color: white;
}

.post-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1.5rem 0;
    line-height: 1.2;
}

.post-meta {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 2rem;
    flex-wrap: wrap;
    color: #64748b;
    font-size: 0.875rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.meta-item i {
    color: #abcf37;
}

.post-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.tag {
    background: #f1f5f9;
    color: #64748b;
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.75rem;
    font-weight: 500;
}

.post-featured-image {
    margin: 2rem 0;
    text-align: center;
}

.post-featured-image img {
    max-height: 500px;
    width: auto;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.post-content {
    font-size: 1.125rem;
    line-height: 1.8;
    color: #374151;
    margin: 2rem 0;
}

.post-content h1,
.post-content h2,
.post-content h3,
.post-content h4,
.post-content h5,
.post-content h6 {
    color: #1e293b;
    font-weight: 600;
    margin: 2rem 0 1rem 0;
    line-height: 1.3;
}

.post-content h1 { font-size: 2rem; }
.post-content h2 { font-size: 1.75rem; }
.post-content h3 { font-size: 1.5rem; }
.post-content h4 { font-size: 1.25rem; }
.post-content h5 { font-size: 1.125rem; }
.post-content h6 { font-size: 1rem; }

.post-content p {
    margin-bottom: 1.5rem;
}

.post-content a {
    color: #abcf37;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.post-content a:hover {
    color: #8fb12d;
}

.post-content ul,
.post-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.post-content li {
    margin-bottom: 0.5rem;
}

.post-content blockquote {
    border-left: 4px solid #abcf37;
    background: #f8fafc;
    padding: 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    border-radius: 0 8px 8px 0;
}

.post-footer {
    border-top: 1px solid #e2e8f0;
    padding-top: 2rem;
    margin-top: 2rem;
}

.post-tags-section {
    margin-bottom: 2rem;
}

.post-tags-section h6 {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1rem;
}

.tags-list {
    display: flex;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.tag-item {
    background: #abcf37;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
}

.social-share h6 {
    color: #1e293b;
    font-weight: 600;
    margin-bottom: 1rem;
}

.share-buttons {
    display: flex;
    gap: 1rem;
}

.share-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 45px;
    height: 45px;
    border-radius: 50%;
    color: white;
    text-decoration: none;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.share-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    color: white;
}

.share-btn.facebook { background: #1877f2; }
.share-btn.twitter { background: #1da1f2; }
.share-btn.linkedin { background: #0077b5; }
.share-btn.whatsapp { background: #25d366; }

.post-navigation {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    margin-bottom: 2rem;
}

.nav-post {
    padding: 1rem;
}

.nav-label {
    color: #64748b;
    font-size: 0.875rem;
    font-weight: 500;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.nav-title {
    margin: 0.5rem 0 0 0;
    font-size: 1rem;
    font-weight: 600;
}

.nav-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.nav-title a:hover {
    color: #abcf37;
}

.related-posts {
    background: #ffffff;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    margin-bottom: 2rem;
}

.section-title {
    font-size: 1.5rem;
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

.related-post-card {
    background: #f8fafc;
    border-radius: 8px;
    overflow: hidden;
    transition: transform 0.3s ease;
    height: 100%;
}

.related-post-card:hover {
    transform: translateY(-3px);
}

.related-post-image {
    height: 150px;
    overflow: hidden;
}

.related-post-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.related-post-card:hover .related-post-image img {
    transform: scale(1.05);
}

.related-post-content {
    padding: 1.5rem;
}

.related-post-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.related-post-title {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 1rem;
    line-height: 1.4;
}

.related-post-title a {
    color: #1e293b;
    text-decoration: none;
    transition: color 0.3s ease;
}

.related-post-title a:hover {
    color: #abcf37;
}

.related-post-excerpt {
    color: #64748b;
    font-size: 0.875rem;
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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

@media (max-width: 991px) {
    .blog-sidebar {
        padding-left: 0;
        margin-top: 3rem;
    }
}

@media (max-width: 767px) {
    .post-title {
        font-size: 2rem;
    }

    .post-meta {
        flex-direction: column;
        gap: 1rem;
    }

    .share-buttons {
        justify-content: center;
    }

    .blog-post-article {
        padding: 2rem 1.5rem;
    }

    .post-navigation {
        padding: 1.5rem;
    }

    .related-posts {
        padding: 1.5rem;
    }
}

/* Mobile-Responsive Blog Detail Image Styles */
.blog-detail-img {
    width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: cover;
    object-position: center;
    border-radius: var(--radius-lg);
}

.blog-related-img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}

.blog-recent-img {
    width: 100%;
    height: 60px;
    object-fit: cover;
    object-position: center;
}

/* Mobile specific blog detail image optimizations */
@media (max-width: 768px) {
    .blog-detail-img {
        max-height: 300px;
    }

    .blog-related-img {
        height: 120px;
    }

    .blog-recent-img {
        height: 50px;
    }

    .recent-post-image {
        width: 70px;
        height: 50px;
    }
}

@media (max-width: 480px) {
    .blog-detail-img {
        max-height: 250px;
    }

    .blog-related-img {
        height: 100px;
    }

    .blog-recent-img {
        height: 45px;
    }

    .recent-post-image {
        width: 60px;
        height: 45px;
    }
}

/* Mobile-Responsive Typography Styles */
@media (max-width: 768px) {
    /* Post title and meta */
    .post-title {
        font-size: 2rem !important;
        line-height: 1.2;
    }
    
    .post-subtitle {
        font-size: 1rem !important;
        line-height: 1.4;
    }
    
    .post-meta {
        font-size: 0.85rem !important;
    }
    
    .post-date {
        font-size: 0.8rem !important;
    }
    
    .post-author {
        font-size: 0.8rem !important;
    }
    
    .post-category {
        font-size: 0.8rem !important;
    }
    
    /* Post content */
    .post-content p {
        font-size: 1rem !important;
        line-height: 1.6;
    }
    
    .post-content h2 {
        font-size: 1.5rem !important;
        line-height: 1.3;
    }
    
    .post-content h3 {
        font-size: 1.25rem !important;
        line-height: 1.3;
    }
    
    .post-content h4 {
        font-size: 1.1rem !important;
        line-height: 1.3;
    }
    
    /* Tags */
    .tag-item {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.8rem !important;
    }
    
    /* Share buttons */
    .share-btn {
        font-size: 0.85rem !important;
        padding: 0.5rem 1rem !important;
    }
    
    /* Related posts */
    .related-post-title {
        font-size: 1rem !important;
        line-height: 1.3;
    }
    
    .related-post-excerpt {
        font-size: 0.85rem !important;
        line-height: 1.4;
    }
    
    /* Comments */
    .comment-author {
        font-size: 0.9rem !important;
    }
    
    .comment-date {
        font-size: 0.75rem !important;
    }
    
    .comment-text {
        font-size: 0.9rem !important;
        line-height: 1.5;
    }
    
    /* Sidebar */
    .widget-title {
        font-size: 1.1rem !important;
    }
    
    .recent-post-title {
        font-size: 0.85rem !important;
        line-height: 1.3;
    }
}

@media (max-width: 480px) {
    /* Extra small screens */
    .post-title {
        font-size: 1.75rem !important;
    }
    
    .post-subtitle {
        font-size: 0.9rem !important;
    }
    
    .post-meta {
        font-size: 0.8rem !important;
    }
    
    .post-date,
    .post-author,
    .post-category {
        font-size: 0.75rem !important;
    }
    
    .post-content p {
        font-size: 0.95rem !important;
    }
    
    .post-content h2 {
        font-size: 1.3rem !important;
    }
    
    .post-content h3 {
        font-size: 1.15rem !important;
    }
    
    .post-content h4 {
        font-size: 1rem !important;
    }
    
    .tag-item {
        font-size: 0.75rem !important;
        padding: 0.3rem 0.6rem !important;
    }
    
    .share-btn {
        font-size: 0.8rem !important;
        padding: 0.4rem 0.8rem !important;
    }
    
    .related-post-title {
        font-size: 0.9rem !important;
    }
    
    .related-post-excerpt {
        font-size: 0.8rem !important;
    }
    
    .comment-author {
        font-size: 0.85rem !important;
    }
    
    .comment-date {
        font-size: 0.7rem !important;
    }
    
    .comment-text {
        font-size: 0.85rem !important;
    }
    
    .widget-title {
        font-size: 1rem !important;
    }
    
    .recent-post-title {
        font-size: 0.8rem !important;
    }
}
</style>
@endpush
