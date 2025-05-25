<div>
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Product Variations</h5>
        </div>
        <div class="card-body">
            <div class="form-check form-switch mb-3">
                <input class="form-check-input" type="checkbox" id="has_variations" wire:model.live="hasVariations" name="has_variations" value="1">
                <label class="form-check-label" for="has_variations">This product has multiple variations</label>
                <small class="d-block text-muted">Enable if this product comes in different options like size, color, etc.</small>
            </div>

            @if($hasVariations)
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> When using variations, the stock will be automatically calculated from the sum of variation stocks.
                </div>

                <div class="variations-container">
                    @foreach($variations as $index => $variation)
                        <div class="variation-card card mb-3 {{ $variation['is_primary'] ? 'border-primary' : '' }}" wire:key="variation-{{ $index }}">
                            <div class="card-header d-flex justify-content-between align-items-center {{ $variation['is_primary'] ? 'bg-primary text-white' : '' }}">
                                <span>Variation #{{ $index + 1 }} {{ $variation['is_primary'] ? '(Primary)' : '' }}</span>
                                <div>
                                    @if(!$variation['is_primary'])
                                        <button type="button" class="btn btn-sm btn-primary me-2" wire:click="setPrimaryVariation({{ $index }})">
                                            Set as Primary
                                        </button>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger" wire:click="removeVariation({{ $index }})">
                                        Remove
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Attribute</label>
                                            <select class="form-select" wire:model.live="variations.{{ $index }}.attribute_id">
                                                <option value="">Select Attribute</option>
                                                @foreach($productAttributes as $attribute)
                                                    <option value="{{ $attribute->id }}">{{ $attribute->name }}</option>
                                                @endforeach
                                            </select>
                                            @error("variations.{$index}.attribute_id")
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Value</label>
                                            <select class="form-select" wire:model.live="variations.{{ $index }}.attribute_value_id">
                                                <option value="">Select Value</option>
                                                @foreach($this->getAttributeValues($index, $variation['attribute_id'] ?? null) as $value)
                                                    <option value="{{ $value->id }}">{{ $value->value }}</option>
                                                @endforeach
                                            </select>
                                            @error("variations.{$index}.attribute_value_id")
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Price <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rs</span>
                                                <input type="number" class="form-control" wire:model.live="variations.{{ $index }}.price" step="0.01" min="0">
                                            </div>
                                            @error("variations.{$index}.price")
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">Stock <span class="text-danger">*</span></label>
                                            <input type="number" class="form-control" wire:model.live="variations.{{ $index }}.stock" min="0">
                                            @error("variations.{$index}.stock")
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="mb-3">
                                            <label class="form-label">SKU <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" wire:model.live="variations.{{ $index }}.sku">
                                            @error("variations.{$index}.sku")
                                                <div class="text-danger mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Hidden fields for form submission -->
                                <input type="hidden" name="variations[{{ $index }}][attribute_id]" value="{{ $variation['attribute_id'] }}">
                                <input type="hidden" name="variations[{{ $index }}][attribute_value_id]" value="{{ $variation['attribute_value_id'] }}">
                                <input type="hidden" name="variations[{{ $index }}][price]" value="{{ $variation['price'] }}">
                                <input type="hidden" name="variations[{{ $index }}][stock]" value="{{ $variation['stock'] }}">
                                <input type="hidden" name="variations[{{ $index }}][sku]" value="{{ $variation['sku'] }}">
                                <input type="hidden" name="variations[{{ $index }}][is_primary]" value="{{ $variation['is_primary'] ? 1 : 0 }}">
                                @if(isset($variation['id']))
                                    <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variation['id'] }}">
                                @endif
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center mt-3">
                        <button type="button" class="btn btn-success" wire:click="addVariation">
                            <i class="fa fa-plus"></i> Add Another Variation
                        </button>
                    </div>
                </div>

                <!-- Message about automatic stock calculation -->
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-info-circle"></i> The main product stock will be automatically updated based on the sum of all variation stocks.
                </div>
            @endif
        </div>
    </div>

    <!-- Hidden field to track deleted variations -->
    @foreach($deletedVariations as $id)
        <input type="hidden" name="deleted_variations[]" value="{{ $id }}">
    @endforeach
</div>
