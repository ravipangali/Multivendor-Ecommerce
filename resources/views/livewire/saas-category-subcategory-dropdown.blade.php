<div>
    <div class="mb-3">
        <label class="form-label">Parent Category <span class="text-danger">*</span></label>
        <select class="form-select" wire:model.live="categoryId" name="category_id" required>
            <option value="">Select Parent Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="form-label">Subcategory <span class="text-danger">*</span></label>
        <select class="form-select" wire:model.live="subcategoryId" name="sub_category_id" required @if(count($subcategories) == 0) disabled @endif>
            <option value="">Select Subcategory</option>
            @foreach($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
            @endforeach
        </select>
        @if(count($subcategories) == 0 && $categoryId)
            <small class="text-danger">No subcategories found for this category</small>
        @endif
    </div>
</div>
