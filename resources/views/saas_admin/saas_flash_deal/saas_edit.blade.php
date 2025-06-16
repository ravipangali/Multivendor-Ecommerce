@extends('saas_admin.saas_layouts.saas_layout')

@section('title', 'Edit Flash Deal')

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Flash Deal: {{ $flashDeal->title }}</h5>
                <div>
                    <a href="{{ route('admin.flash-deals.show', $flashDeal->id) }}" class="btn btn-info">
                        <i class="align-middle" data-feather="eye"></i> View Deal
                    </a>
                    <a href="{{ route('admin.flash-deal-products.index', ['flash_deal_id' => $flashDeal->id]) }}" class="btn btn-success">
                        <i class="align-middle" data-feather="tag"></i> Manage Products
                    </a>
                    <a href="{{ route('admin.flash-deals.index') }}" class="btn btn-secondary">
                        <i class="align-middle" data-feather="arrow-left"></i> Back to Flash Deals
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.flash-deals.update', $flashDeal->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Main Content -->
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Flash Deal Information</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Flash Deal Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $flashDeal->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>



                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="start_time" class="form-label">Start Time <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control @error('start_time') is-invalid @enderror" 
                                                   id="start_time" name="start_time"
                                                   value="{{ old('start_time', $flashDeal->start_time->format('Y-m-d\TH:i')) }}" required>
                                            @error('start_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="end_time" class="form-label">End Time <span class="text-danger">*</span></label>
                                            <input type="datetime-local" class="form-control @error('end_time') is-invalid @enderror" 
                                                   id="end_time" name="end_time"
                                                   value="{{ old('end_time', $flashDeal->end_time->format('Y-m-d\TH:i')) }}" required>
                                            <small class="text-muted">End time must be after start time</small>
                                            @error('end_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-md-4">
                        <!-- Publishing Options -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Publishing</h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted mb-0">
                                    <i class="align-middle me-2" data-feather="info"></i>
                                    Flash deal will be active during the specified time period.
                                </p>


                            </div>
                        </div>

                        <!-- Banner Image -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Banner Image</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <input type="file" class="form-control @error('banner_image') is-invalid @enderror" 
                                           id="banner_image" name="banner_image" accept="image/*">
                                    <small class="text-muted">Recommended size: 1200×400px, Max: 2MB. Leave empty to keep current image.</small>
                                    @error('banner_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mt-3">
                                    <label class="form-label">Current Banner:</label>
                                    <div class="border p-3 text-center">
                                        @if($flashDeal->banner_image)
                                            <img src="{{ asset('storage/'.$flashDeal->banner_image) }}" alt="{{ $flashDeal->title }}" class="img-fluid" style="max-height: 200px;">
                                        @else
                                            <div class="text-muted">No banner image uploaded</div>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3" id="image-preview-container" style="display: none;">
                                    <label class="form-label">New Banner Preview:</label>
                                    <div class="border p-3 text-center">
                                        <img id="image-preview" src="#" alt="Banner Preview" style="max-height: 200px; max-width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="align-middle" data-feather="save"></i> Update Flash Deal
                                    </button>
                                    <a href="{{ route('admin.flash-deals.index') }}" class="btn btn-secondary">
                                        <i class="align-middle" data-feather="x"></i> Cancel
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('banner_image');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const imagePreview = document.getElementById('image-preview');

    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreviewContainer.style.display = 'block';
            }

            reader.readAsDataURL(this.files[0]);
        } else {
            imagePreviewContainer.style.display = 'none';
        }
    });

    // Update end_time min value when start_time changes
    document.getElementById('start_time').addEventListener('change', function() {
        document.getElementById('end_time').min = this.value;
    });


});
</script>
@endpush
