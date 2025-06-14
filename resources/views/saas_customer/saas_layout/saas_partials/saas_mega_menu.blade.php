<div id="mega-menu">
  <a class="btn-mega after_none" href="#">
    <img class="me-2" src="{{ asset('saas_frontend/images/desktop-nav-menu-white.svg') }}" alt="Desktop Menu Icon">
    <span class="fw500 fz16 color-white vam">Browse Categories</span>
  </a>
  <ul class="menu">
    @if(isset($navigationCategories) && $navigationCategories->count() > 0)
      @foreach($navigationCategories as $category)
        <li>
          <a class="dropdown" href="{{ route('customer.products') }}?category={{ $category->slug }}">
            <span class="menu-icn flaticon-diamond"></span>
            <span class="menu-title">{{ $category->name }}</span>
          </a>
          @if($category->subcategories->count() > 0)
            <div class="drop-menu">
              @foreach($category->subcategories->chunk(3) as $subcategoryChunk)
                @foreach($subcategoryChunk as $subcategory)
                  <div class="one-third">
                    <div class="cat-title">{{ $subcategory->name }}</div>
                    <ul class="mb0">
                      @if($subcategory->childCategories->count() > 0)
                        @foreach($subcategory->childCategories as $childCategory)
                          <li>
                            <a href="{{ route('customer.products') }}?category={{ $category->slug }}&subcategory={{ $subcategory->slug }}&child_category={{ $childCategory->slug }}">
                              {{ $childCategory->name }}
                            </a>
                          </li>
                        @endforeach
                      @else
                        <li>
                          <a href="{{ route('customer.products') }}?category={{ $category->slug }}&subcategory={{ $subcategory->slug }}">
                            Shop All {{ $subcategory->name }}
                          </a>
                        </li>
                      @endif
                      <li class="break"></li>
                      <li>
                        <a href="{{ route('customer.products') }}?category={{ $category->slug }}&subcategory={{ $subcategory->slug }}">
                          View All {{ $subcategory->name }} <small><i class="fa fa-arrow-right"></i></small>
                        </a>
                      </li>
                    </ul>
                  </div>
                @endforeach
              @endforeach
            </div>
          @endif
        </li>
      @endforeach
    @else
      <!-- Fallback static menu if no categories found -->
      <li>
        <a class="dropdown" href="#">
          <span class="menu-icn flaticon-diamond"></span>
          <span class="menu-title">All Categories</span>
        </a>
        <div class="drop-menu">
          <div class="one-third">
            <div class="cat-title">No Categories Available</div>
            <ul class="mb0">
              <li><a href="{{ route('customer.products') }}">Browse All Products</a></li>
            </ul>
          </div>
        </div>
      </li>
    @endif

    <!-- See All Categories Link -->
    <li>
      <a href="{{ route('customer.products') }}">
        <span class="menu-icn flaticon-feeding-bottle"></span>
        <span class="menu-title">See All Categories</span>
      </a>
    </li>
  </ul>
</div>
