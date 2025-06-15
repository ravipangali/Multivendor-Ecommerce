@extends('saas_customer.saas_layout.saas_layout')

@section('title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description ?: $page->excerpt)
@section('meta_keywords', $page->meta_keywords)

@section('content')
<!-- Page Header -->
<section class="saas-page-header">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="page-header-content">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">
                                <a href="{{ route('customer.home') }}">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                {{ $page->title }}
                            </li>
                        </ol>
                    </nav>
                    <h1 class="page-title">{{ $page->title }}</h1>
                    @if($page->excerpt)
                        <p class="page-subtitle">{{ $page->excerpt }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Page Content -->
<section class="saas-page-content">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <article class="page-article">
                    @if($page->featured_image_url)
                        <div class="page-featured-image">
                            <img src="{{ $page->featured_image_url }}"
                                 alt="{{ $page->title }}"
                                 class="img-fluid rounded">
                        </div>
                    @endif

                    <div class="page-content">
                        {!! $page->content !!}
                    </div>

                    <div class="page-meta">
                        @if($page->author)
                            <div class="author-info">
                                <i class="fas fa-user"></i>
                                <span>{{ $page->author->name }}</span>
                            </div>
                        @endif

                        <div class="publish-date">
                            <i class="fas fa-calendar"></i>
                            <span>{{ $page->published_at ? $page->published_at->format('M d, Y') : $page->created_at->format('M d, Y') }}</span>
                        </div>

                        @if($page->updated_at != $page->created_at)
                            <div class="update-date">
                                <i class="fas fa-edit"></i>
                                <span>Updated: {{ $page->updated_at->format('M d, Y') }}</span>
                            </div>
                        @endif
                    </div>
                </article>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.saas-page-header {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    padding: 3rem 0 2rem;
    border-bottom: 1px solid #e2e8f0;
}

.page-header-content {
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

.page-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 1rem 0;
    line-height: 1.2;
}

.page-subtitle {
    font-size: 1.125rem;
    color: #64748b;
    margin: 0;
    line-height: 1.6;
}

.saas-page-content {
    padding: 4rem 0;
}

.page-article {
    background: #ffffff;
    border-radius: 12px;
    padding: 3rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
}

.page-featured-image {
    margin-bottom: 2rem;
    text-align: center;
}

.page-featured-image img {
    max-height: 400px;
    width: auto;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.page-content {
    font-size: 1.125rem;
    line-height: 1.7;
    color: #374151;
    margin-bottom: 2rem;
}

.page-content h1,
.page-content h2,
.page-content h3,
.page-content h4,
.page-content h5,
.page-content h6 {
    color: #1e293b;
    font-weight: 600;
    margin: 2rem 0 1rem 0;
    line-height: 1.3;
}

.page-content h1 { font-size: 2rem; }
.page-content h2 { font-size: 1.75rem; }
.page-content h3 { font-size: 1.5rem; }
.page-content h4 { font-size: 1.25rem; }
.page-content h5 { font-size: 1.125rem; }
.page-content h6 { font-size: 1rem; }

.page-content p {
    margin-bottom: 1.5rem;
}

.page-content a {
    color: #abcf37;
    text-decoration: underline;
    transition: color 0.3s ease;
}

.page-content a:hover {
    color: #8fb12d;
}

.page-content ul,
.page-content ol {
    margin: 1.5rem 0;
    padding-left: 2rem;
}

.page-content li {
    margin-bottom: 0.5rem;
}

.page-content blockquote {
    border-left: 4px solid #abcf37;
    padding: 1rem 1.5rem;
    margin: 2rem 0;
    background: #f8fafc;
    border-radius: 0 8px 8px 0;
    font-style: italic;
    color: #64748b;
}

.page-content code {
    background: #f1f5f9;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.875rem;
    color: #e11d48;
}

.page-content pre {
    background: #1e293b;
    color: #f8fafc;
    padding: 1.5rem;
    border-radius: 8px;
    overflow-x: auto;
    margin: 1.5rem 0;
}

.page-content pre code {
    background: none;
    padding: 0;
    color: inherit;
}

.page-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    background: #ffffff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.page-content th,
.page-content td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid #e2e8f0;
}

.page-content th {
    background: #f8fafc;
    font-weight: 600;
    color: #1e293b;
}

.page-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.page-meta {
    border-top: 1px solid #e2e8f0;
    padding-top: 2rem;
    margin-top: 2rem;
    display: flex;
    flex-wrap: wrap;
    gap: 2rem;
    color: #64748b;
    font-size: 0.875rem;
}

.page-meta > div {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.page-meta i {
    color: #abcf37;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-title {
        font-size: 2rem;
    }

    .page-article {
        padding: 2rem 1.5rem;
    }

    .page-content {
        font-size: 1rem;
    }

    .saas-page-content {
        padding: 2rem 0;
    }

    .page-meta {
        flex-direction: column;
        gap: 1rem;
    }
}

@media (max-width: 576px) {
    .page-title {
        font-size: 1.75rem;
    }

    .page-article {
        padding: 1.5rem 1rem;
    }

    .saas-page-header {
        padding: 2rem 0 1.5rem;
    }
}
</style>
@endpush
