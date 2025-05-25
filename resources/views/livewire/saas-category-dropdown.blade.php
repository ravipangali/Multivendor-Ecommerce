<div>
    <div class="mb-3">
        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
        <select class="form-select" id="category_id" name="category_id" wire:model.live="selectedCategoryId" required>
            <option value="">Select Category</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="subcategory_id" class="form-label">Subcategory</label>
        <select class="form-select" id="subcategory_id" name="subcategory_id" wire:model.live="selectedSubcategoryId" {{ count($subcategories) ? '' : 'disabled' }}>
            <option value="">Select Subcategory</option>
            @foreach($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="child_category_id" class="form-label">Child Category</label>
        <select class="form-select" id="child_category_id" name="child_category_id" wire:model.live="selectedChildcategoryId" {{ count($childcategories) ? '' : 'disabled' }}>
            <option value="">Select Child Category</option>
            @foreach($childcategories as $childcategory)
                <option value="{{ $childcategory->id }}">{{ $childcategory->name }}</option>
            @endforeach
        </select>
    </div>
</div>
