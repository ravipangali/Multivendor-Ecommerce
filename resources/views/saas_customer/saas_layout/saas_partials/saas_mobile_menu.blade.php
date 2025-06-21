<nav id="menu" class="stylehome1">
  <ul>
    <li class="title my-3 bb1 pl20 fz20 fw500 pb-3">Departments</li>

    @if(isset($navigationCategories) && $navigationCategories->count() > 0)
      @foreach($navigationCategories as $category)
        <li>
          <span>
            <i class="flaticon-diamond mr20"></i>{{ $category->name }}
          </span>
          @if($category->subcategories->count() > 0)
            <ul>
              @foreach($category->subcategories as $subcategory)
                <li>
                  <a href="{{ route('customer.products') }}?category={{ $category->slug }}&subcategory={{ $subcategory->slug }}">
                    {{ $subcategory->name }}
                  </a>
                  @if($subcategory->childCategories->count() > 0)
                    <ul class="mobile-submenu">
                      @foreach($subcategory->childCategories as $childCategory)
                        <li>
                          <a href="{{ route('customer.products') }}?category={{ $category->slug }}&subcategory={{ $subcategory->slug }}&child_category={{ $childCategory->slug }}">
                            {{ $childCategory->name }}
                          </a>
                        </li>
                      @endforeach
                      <li class="break"></li>
                      <li>
                        <a href="{{ route('customer.products') }}?category={{ $category->slug }}&subcategory={{ $subcategory->slug }}">
                          View All {{ $subcategory->name }} <small><i class="fa fa-arrow-right"></i></small>
                        </a>
                      </li>
                    </ul>
                  @endif
                </li>
              @endforeach
            </ul>
          @endif
        </li>
      @endforeach
    @else
      <li>
        <span>
          <i class="flaticon-diamond mr20"></i>No Categories Available
        </span>
        <ul>
          <li><a href="{{ route('customer.products') }}">Browse All Products</a></li>
        </ul>
      </li>
    @endif

    <!-- Additional Menu Items -->
    <li class="title my-3 bb1 pl20 fz20 fw500 pb-3">More</li>
    <li><a href="{{ route('customer.blog.index') }}"><i class="fas fa-blog mr20"></i>Blog</a></li>
    @if($settings->apply_share_link)
    <li><a href="{{ $settings->apply_share_link }}" target="_blank"><i class="fas fa-money-check-dollar mr20"></i>Apply Share</a></li>
    @endif
    <li><a href="{{ route('customer.about') }}"><i class="fas fa-user mr20"></i>About Us</a></li>
    <li><a href="{{ route('customer.contact') }}"><i class="flaticon-phone mr20"></i>Contact Us</a></li>
  </ul>
</nav>
