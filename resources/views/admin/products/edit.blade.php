@extends('layouts.admin')
@section('title', 'Edit Product')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Products</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')

<div class="page-header">
    <div>
        <h4><i class="bi bi-pencil-square me-2" style="color:var(--teal)"></i>Edit Product</h4>
        <div class="page-header-sub" style="font-size:.84rem;color:var(--text-3)">{{ $product->name }}</div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('shop.product', $product->slug) }}" target="_blank"
           class="btn btn-sm" style="border:1.5px solid var(--border);color:var(--text-2);background:var(--surface)">
            <i class="bi bi-eye me-1"></i>View Live
        </a>
        <a href="{{ route('admin.products.index') }}"
           class="btn btn-sm" style="background:var(--surface-2);border:1.5px solid var(--border);color:var(--text-2)">
            <i class="bi bi-arrow-left me-1"></i>Back
        </a>
    </div>
</div>

{{-- Validation Errors --}}
@if($errors->any())
<div class="alert alert-danger mb-4">
    <i class="bi bi-exclamation-circle-fill me-2"></i>
    Please fix the following errors:
    <ul class="mb-0 mt-2">
        @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
</div>
@endif

<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row g-4">

        {{-- Main Column --}}
        <div class="col-lg-8">

            {{-- Basic Info --}}
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-info-circle me-2" style="color:var(--teal)"></i>Product Information</div>
                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label">Product Name <span style="color:#EF4444">*</span></label>
                        <input type="text" name="name"
                               class="form-control @error('name') is-invalid @enderror"
                               value="{{ old('name', $product->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description) }}</textarea>
                    </div>

                    <div class="mb-0">
                        <label class="form-label">Full Description</label>
                        <textarea name="description" class="form-control" rows="7">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Pricing & Inventory --}}
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-currency-exchange me-2" style="color:var(--teal)"></i>Pricing & Inventory</div>
                <div class="card-body">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Regular Price (৳) <span style="color:#EF4444">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" name="price" min="0"
                                       class="form-control @error('price') is-invalid @enderror"
                                       value="{{ old('price', $product->price) }}" required>
                                @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sale Price (৳)</label>
                            <div class="input-group">
                                <span class="input-group-text">৳</span>
                                <input type="number" step="0.01" name="sale_price" min="0"
                                       class="form-control @error('sale_price') is-invalid @enderror"
                                       value="{{ old('sale_price', $product->sale_price) }}"
                                       placeholder="Leave blank for no discount">
                                @error('sale_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">SKU</label>
                            <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Stock Quantity <span style="color:#EF4444">*</span></label>
                            <input type="number" name="stock" min="0"
                                   class="form-control @error('stock') is-invalid @enderror"
                                   value="{{ old('stock', $product->stock) }}" required>
                            @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Low Stock Alert</label>
                            <input type="number" name="low_stock_threshold" min="0" class="form-control"
                                   value="{{ old('low_stock_threshold', $product->low_stock_threshold) }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Gallery --}}
            <div class="admin-card">
                <div class="card-header">
                    <span><i class="bi bi-images me-2" style="color:var(--teal)"></i>Product Gallery</span>
                    <span style="font-size:.78rem;color:var(--text-3)">{{ $product->images->count() }} images</span>
                </div>
                <div class="card-body">
                    @if($product->images->count() > 0)
                    <div class="row g-3 mb-4">
                        @foreach($product->images as $img)
                        <div class="col-6 col-md-3 col-lg-2">
                            <div style="position:relative;border-radius:8px;overflow:hidden;border:1.5px solid var(--border)">
                                <img src="{{ asset('storage/' . $img->image_path) }}"
                                     style="width:100%;aspect-ratio:1;object-fit:cover" alt="Product Image">
                                <form action="{{ route('admin.products.images.destroy', $img->id) }}" method="POST"
                                      style="position:absolute;top:6px;right:6px">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            style="width:26px;height:26px;border-radius:50%;background:rgba(239,68,68,.9);border:none;color:#fff;display:flex;align-items:center;justify-content:center;cursor:pointer;font-size:.75rem"
                                            title="Remove image">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-3" style="color:var(--text-3);font-size:.875rem">
                        <i class="bi bi-images fs-3 d-block mb-2" style="opacity:.4"></i>
                        No gallery images yet.
                    </div>
                    @endif

                    {{-- Add more images --}}
                    <div style="background:var(--surface-2);border:2px dashed var(--border);border-radius:var(--radius);padding:20px;text-align:center">
                        <label class="form-label mb-2" style="cursor:pointer">
                            <i class="bi bi-cloud-upload" style="font-size:1.5rem;color:var(--text-3);display:block;margin-bottom:6px"></i>
                            <span style="font-size:.84rem;color:var(--text-2)">Click or drag to upload additional gallery images</span>
                        </label>
                        <input type="file" name="images[]" class="form-control" accept="image/*" multiple
                               style="max-width:280px;margin:0 auto">
                    </div>
                </div>
            </div>

        </div>

        {{-- Dynamic Product Variants --}}
        <div class="admin-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><i class="bi bi-diagram-3 me-2" style="color:var(--teal)"></i>Product Variants (Optional)</div>
                <button type="button" class="btn btn-sm py-1 px-2" style="border:1.5px solid var(--primary);color:var(--primary);font-weight:600" onclick="addVariant()"><i class="bi bi-plus-lg me-1"></i>Add Variant</button>
            </div>
            <div class="card-body" id="variantsContainer">
                <div style="font-size:0.875rem; color:var(--text-3); margin-bottom:10px;">Add dynamic variants like "Red", "Large", etc. Leave empty if this is a single product.</div>
            </div>
        </div>

        {{-- Custom Tabs --}}
        <div class="admin-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div><i class="bi bi-layout-text-window-reverse me-2" style="color:var(--teal)"></i>Custom Product Tabs</div>
                <button type="button" class="btn btn-sm py-1 px-2" style="border:1.5px solid var(--primary);color:var(--primary);font-weight:600" onclick="addTab()"><i class="bi bi-plus-lg me-1"></i>Add Tab</button>
            </div>
            <div class="card-body" id="tabsContainer">
                <div style="font-size:0.875rem; color:var(--text-3); margin-bottom:10px;">Dynamically add sections like "Specifications", "Warranty", "How to Use" to the product page.</div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">

            {{-- Publish --}}
            <div class="admin-card mb-4" style="border-color:var(--teal)">
                <div class="card-header" style="background:rgba(13,115,119,.08);border-bottom-color:rgba(13,115,119,.2)">
                    <span style="color:var(--teal)"><i class="bi bi-cloud-upload me-2"></i>Publish</span>
                </div>
                <div class="card-body">
                    <div class="mb-3 p-3" style="background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border)">
                        <input type="hidden" name="is_active" value="0">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                   id="isActive" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isActive">
                                <i class="bi bi-eye me-1" style="color:var(--teal)"></i>Active & Published
                            </label>
                        </div>
                        <input type="hidden" name="is_featured" value="0">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_featured" value="1"
                                   id="isFeatured" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label fw-600" for="isFeatured">
                                <i class="bi bi-star me-1" style="color:var(--gold)"></i>Featured Product
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-1"></i>Update Product
                        </button>
                        <button type="button" class="btn btn-danger"
                                onclick="confirmDelete('del-prod')" title="Delete Product">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Organization --}}
            <div class="admin-card mb-4">
                <div class="card-header"><i class="bi bi-folder2-open me-2" style="color:var(--teal)"></i>Organization</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category <span style="color:#EF4444">*</span></label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">— Select Category —</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label class="form-label">Tags</label>
                        <select name="tags[]" class="form-select" multiple style="height:120px">
                            @foreach($tags as $t)
                                <option value="{{ $t->id }}" {{ in_array($t->id, old('tags', $product->tags->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $t->name }}
                                </option>
                            @endforeach
                        </select>
                        <div style="font-size:.72rem;color:var(--text-3);margin-top:4px">Ctrl/Cmd + Click to multi-select</div>
                    </div>
                </div>
            </div>

            {{-- Featured Image --}}
            <div class="admin-card">
                <div class="card-header"><i class="bi bi-image me-2" style="color:var(--teal)"></i>Featured Image</div>
                <div class="card-body">
                    @if($product->featured_image)
                    <div class="mb-3">
                        <img src="{{ asset('storage/' . $product->featured_image) }}"
                             style="width:100%;border-radius:8px;border:1.5px solid var(--border);max-height:180px;object-fit:cover"
                             id="currentFeatured" alt="Featured Image">
                    </div>
                    @endif
                    <div id="newFeaturedPreview"></div>
                    <label class="form-label">{{ $product->featured_image ? 'Replace Image' : 'Upload Image' }}</label>
                    <input type="file" name="featured_image" id="featuredImg"
                           class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
                    @error('featured_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

        </div>
    </div>
</form>

<form id="del-prod" action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="d-none">
    @csrf @method('DELETE')
</form>

@push('scripts')
<script>
document.getElementById('featuredImg')?.addEventListener('change', function () {
    const preview = document.getElementById('newFeaturedPreview');
    const current = document.getElementById('currentFeatured');
    if (this.files[0]) {
        const url = URL.createObjectURL(this.files[0]);
        if (current) current.src = url;
        else {
            preview.innerHTML = `<img src="${url}" style="width:100%;border-radius:8px;border:1.5px solid var(--border);max-height:180px;object-fit:cover;margin-bottom:12px">`;
        }
    }
});

let variantIndex = 0;
function addVariant(data = null) {
    const container = document.getElementById('variantsContainer');
    const row = document.createElement('div');
    row.className = 'row g-2 mb-3 align-items-end p-3';
    row.style.background = 'var(--surface-2)';
    row.style.border = '1px solid var(--border)';
    row.style.borderRadius = '8px';
    const idHtml = data && data.id ? `<input type="hidden" name="variants[${variantIndex}][id]" value="${data.id}">` : '';
    const nameVal = data ? data.name : '';
    const skuVal = data && data.sku ? data.sku : '';
    const priceVal = data && data.price ? data.price : '';
    const stockVal = data ? data.stock : 0;
    row.innerHTML = `
        ${idHtml}
        <div class="col-md-3">
            <label class="form-label mb-1" style="font-size:.8rem">Variant Name *</label>
            <input type="text" name="variants[${variantIndex}][name]" class="form-control form-control-sm" placeholder="e.g. Red - XL" value="${nameVal}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label mb-1" style="font-size:.8rem">SKU</label>
            <input type="text" name="variants[${variantIndex}][sku]" class="form-control form-control-sm" placeholder="Optional" value="${skuVal}">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1" style="font-size:.8rem">Price (৳)</label>
            <input type="number" step="0.01" name="variants[${variantIndex}][price]" class="form-control form-control-sm" placeholder="Override" value="${priceVal}">
        </div>
        <div class="col-md-2">
            <label class="form-label mb-1" style="font-size:.8rem">Stock *</label>
            <input type="number" name="variants[${variantIndex}][stock]" class="form-control form-control-sm" value="${stockVal}" required>
        </div>
        <div class="col-md-2 text-end">
            <button type="button" class="btn btn-sm btn-danger px-2 py-1" onclick="this.closest('.row').remove()"><i class="bi bi-trash"></i></button>
        </div>
    `;
    container.appendChild(row);
    variantIndex++;
}

let tabIndex = 0;
function addTab(data = null) {
    const container = document.getElementById('tabsContainer');
    const box = document.createElement('div');
    box.className = 'mb-4 p-3';
    box.style.background = 'var(--surface-2)';
    box.style.border = '1px solid var(--border)';
    box.style.borderRadius = '8px';
    const idHtml = data && data.id ? `<input type="hidden" name="tabs[${tabIndex}][id]" value="${data.id}">` : '';
    const headingVal = data ? (data.heading || '').replace(/"/g, '&quot;') : '';
    const contentVal = data ? data.content : '';
    box.innerHTML = `
        ${idHtml}
        <div class="d-flex justify-content-between align-items-center mb-2">
            <label class="form-label mb-0" style="font-size:.85rem; font-weight:600">Tab Heading *</label>
            <button type="button" class="btn btn-sm btn-danger px-2 py-1" style="font-size:.7rem" onclick="this.closest('.p-3').remove()"><i class="bi bi-trash me-1"></i>Remove</button>
        </div>
        <input type="text" name="tabs[${tabIndex}][heading]" class="form-control mb-2" placeholder="e.g. Specifications" value="${headingVal}" required>
        
        <label class="form-label mb-1" style="font-size:.85rem; font-weight:600">Tab Content</label>
        <textarea name="tabs[${tabIndex}][content]" class="form-control" rows="4" placeholder="Tab HTML/Text content...">${contentVal}</textarea>
    `;
    container.appendChild(box);
    tabIndex++;
}

// Preload existing variants and tabs
const existingVariants = @json($product->variants);
if (existingVariants && existingVariants.length) {
    existingVariants.forEach(v => addVariant({
        id: v.id, name: v.name, sku: v.sku, price: v.price > 0 ? (v.price) : '', stock: v.stock
    }));
}
const existingTabs = @json($product->tabs);
if (existingTabs && existingTabs.length) {
    existingTabs.forEach(t => addTab({
        id: t.id, heading: t.heading, content: t.content
    }));
}
</script>
@endpush

@endsection
