<div>
    <div class="mb-4">
        <!-- Existing Images Section -->
        @if(!empty($existingImages))
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="card-subtitle">Current Images</h6>
                </div>

                <div class="row g-3">
                    @foreach($existingImages as $image)
                        <div class="col-sm-6 col-md-3 col-lg-2" wire:key="existing-image-{{ $image->id }}">
                            <div class="card h-100 {{ in_array($image->id, $deletedImages) ? 'border-danger' : '' }}">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/' . $image->raw_image_url) }}"
                                         class="card-img-top"
                                         style="height: 120px; object-fit: cover;"
                                         alt="Product Image">

                                    <div class="position-absolute top-0 end-0 p-1">
                                        @if(in_array($image->id, $deletedImages))
                                            <button type="button" class="btn btn-sm btn-success rounded-circle"
                                                    wire:click="undoImageDeletion({{ $image->id }})">
                                                <i data-feather="rotate-ccw" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        @else
                                            <button type="button" class="btn btn-sm btn-danger rounded-circle"
                                                    wire:click="markImageForDeletion({{ $image->id }})">
                                                <i data-feather="trash" style="width: 14px; height: 14px;"></i>
                                            </button>
                                        @endif
                                    </div>

                                    @if(in_array($image->id, $deletedImages))
                                        <div class="position-absolute top-0 start-0 w-100 h-100 bg-danger d-flex align-items-center justify-content-center" style="opacity: 0.2;">
                                        </div>
                                        <div class="position-absolute bottom-0 start-0 w-100 p-1">
                                            <span class="badge bg-danger w-100">To be deleted</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- New Images Section -->
        <div class="card">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="card-subtitle mb-0">Upload Additional Images</h6>
                    <button type="button" class="btn btn-sm btn-primary" wire:click="addImageInput">
                        <i data-feather="plus" class="align-middle" style="width: 14px; height: 14px;"></i>
                        Add Image
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    @foreach($newImages as $index => $image)
                        <div class="col-sm-6 col-md-3 col-lg-2" wire:key="new-image-input-{{ $index }}">
                            @if($image)
                                <!-- Image Selected -->
                                <div class="card h-100 border-success">
                                    <div class="position-relative">
                                        <img src="{{ $image->temporaryUrl() }}"
                                             class="card-img-top"
                                             style="height: 120px; object-fit: cover;"
                                             alt="New Image">

                                        <div class="position-absolute top-0 end-0 p-1">
                                            <button type="button" class="btn btn-sm btn-danger rounded-circle"
                                                    wire:click="removeNewImage({{ $index }})">
                                                X
                                            </button>
                                        </div>

                                        <div class="position-absolute bottom-0 start-0 w-100 p-1">
                                            <span class="badge bg-success w-100">Ready to upload</span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Upload Input -->
                                <div class="card h-100 border-dashed bg-light">
                                    <div class="card-body p-2 text-center">
                                        <label for="image-upload-{{ $index }}" class="d-block cursor-pointer">
                                            <div class="py-3">
                                                <i data-feather="image" style="width: 24px; height: 24px;" class="text-primary mb-2"></i>
                                                <p class="mb-1 small">Select image</p>
                                                <small class="text-muted d-block" style="font-size: 0.7rem;">Max 5MB</small>
                                            </div>
                                        </label>
                                        <input type="file"
                                               id="image-upload-{{ $index }}"
                                               class="d-none"
                                               wire:model.live="newImages.{{ $index }}"
                                               accept="image/*">
                                        @error("newImages.$index")
                                            <div class="text-danger mt-1" style="font-size: 0.7rem;">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <div class="mt-3">
                    @if(count(array_filter($newImages)) > 0)
                        <div class="alert alert-info">
                            <p class="mb-0">{{ count(array_filter($newImages)) }} new image(s) will be saved when you submit the form.</p>
                        </div>
                    @endif
                </div>

                <style>
                    .border-dashed {
                        border: 1px dashed #ccc;
                        transition: all 0.2s;
                    }
                    .border-dashed:hover {
                        border-color: #6c757d;
                    }
                    .cursor-pointer {
                        cursor: pointer;
                    }
                </style>
            </div>
        </div>
    </div>

    <!-- Hidden fields for tracking deleted images -->
    @foreach($deletedImages as $id)
        <input type="hidden" name="deleted_images[]" value="{{ $id }}">
    @endforeach
</div>
