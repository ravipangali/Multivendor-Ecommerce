<div class="mb-3">
    <form wire:submit.prevent="applyFilter" class="row g-2">
        <div class="col-md-3">
            <select class="form-select" wire:model.live="categoryId">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select class="form-select" wire:model="subcategoryId" @if(count($subcategories) == 0) disabled @endif>
                <option value="">All Subcategories</option>
                @foreach($subcategories as $subcategory)
                    <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search child categories..." name="search" value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
        <div class="col-md-2">
            <button type="button" wire:click="resetFilter" class="btn btn-secondary w-100">Reset</button>
        </div>
    </form>
</div>
