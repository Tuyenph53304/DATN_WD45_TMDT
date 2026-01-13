@extends('admin.layout')

@section('title', 'Chỉnh sửa Sản phẩm - Admin Panel')
@section('page-title', 'Chỉnh sửa Sản phẩm')

@push('styles')
<style>
  .variant-item {
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    padding: 20px;
    margin-bottom: 20px;
    background: #f8fafc;
    transition: all 0.3s ease;
  }

  .variant-item:hover {
    border-color: #667eea;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
  }

  .variant-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #e2e8f0;
  }

  .image-grid-container {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
    margin-top: 15px;
  }

  .image-slot {
    position: relative;
    aspect-ratio: 1;
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.3s ease;
    overflow: hidden;
  }

  .image-slot:hover {
    border-color: #667eea;
    background: #f1f5f9;
  }

  .image-slot.has-image {
    border-style: solid;
    border-color: #e2e8f0;
  }

  .image-slot.primary-image {
    border-color: #667eea;
    border-width: 3px;
  }

  .image-slot-content {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 10px;
  }

  .image-slot img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .image-slot .add-image-btn {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 2rem;
  }

  .image-slot .add-image-btn span {
    font-size: 0.85rem;
    margin-top: 8px;
    color: #64748b;
  }

  .image-slot .image-actions {
    position: absolute;
    top: 5px;
    right: 5px;
    display: flex;
    gap: 5px;
    opacity: 0;
    transition: opacity 0.3s ease;
  }

  .image-slot:hover .image-actions {
    opacity: 1;
  }

  .image-slot .image-actions button {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.9rem;
  }

  .image-slot .set-primary-btn {
    background: rgba(102, 126, 234, 0.9);
    color: white;
  }

  .image-slot .remove-image-btn {
    background: rgba(239, 68, 68, 0.9);
    color: white;
  }

  .image-slot .primary-badge {
    position: absolute;
    top: 5px;
    left: 5px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 0.75rem;
    font-weight: 600;
  }

  .image-slot input[type="file"] {
    display: none;
  }
</style>
@endpush

@section('content')
<div class="modern-card">
  <div class="modern-card-header">
    <h3 class="modern-card-title">
      <i class="bi bi-pencil"></i>
      Chỉnh sửa Sản phẩm: {{ $product->name }}
    </h3>
    <a href="{{ route('admin.products.index') }}" class="btn btn-modern" style="background: #f1f5f9; color: #475569;">
      <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
  </div>

  <div class="card-body" style="padding: 30px;">
    <form action="{{ route('admin.products.update', $product) }}" method="POST" id="product-form" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <!-- Product Basic Info -->
      <h5 class="mb-4" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-info-circle me-2"></i> Thông tin cơ bản
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="category_id" class="form-label" style="font-weight: 600;">Danh mục <span class="text-danger">*</span></label>
            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required 
                    style="border: 2px solid #e2e8f0; border-radius: 10px;">
              <option value="">Chọn danh mục</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                  {{ $category->name }}
                </option>
              @endforeach
            </select>
            @error('category_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
        <div class="col-md-6">
          <div class="mb-3">
            <label for="name" class="form-label" style="font-weight: 600;">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name', $product->name) }}" required 
                   style="border: 2px solid #e2e8f0; border-radius: 10px;">
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-4">
        <label for="description" class="form-label" style="font-weight: 600;">Mô tả</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="4" 
                  style="border: 2px solid #e2e8f0; border-radius: 10px;">{{ old('description', $product->description) }}</textarea>
        @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Product Images -->
      <h5 class="mb-4" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-images me-2"></i> Ảnh sản phẩm
        <small class="text-muted ms-2">(Ô đầu tiên là ảnh đại diện)</small>
      </h5>
      <div class="mb-4">
        <div class="image-grid-container" id="product-images-grid">
          @php
            $existingImages = $product->images->sortBy('sort_order');
            $primaryImageId = $existingImages->first()->id ?? null;
            $imageSlots = 5;
            $filledSlots = $existingImages->count();
          @endphp
          
          @for($i = 0; $i < $imageSlots; $i++)
          <div class="image-slot {{ $i == 0 && $primaryImageId ? 'primary-image' : '' }} {{ $i < $filledSlots ? 'has-image' : '' }}" 
               data-slot-index="{{ $i }}">
            @if($i < $filledSlots)
              @php $image = $existingImages->values()[$i]; @endphp
              <input type="hidden" name="existing_images[{{ $i }}][id]" value="{{ $image->id }}">
              <input type="hidden" name="existing_images[{{ $i }}][sort_order]" value="{{ $i }}">
              @if($i == 0)
              <span class="primary-badge">Ảnh đại diện</span>
              @endif
              <img src="{{ asset('storage/' . $image->image_path) }}" 
                   alt="Product image {{ $i + 1 }}"
                   onerror="this.src='{{ asset('assets/img/default-150x150.png') }}'">
              <div class="image-actions">
                @if($i != 0)
                <button type="button" class="set-primary-btn" onclick="setPrimaryImage({{ $i }})" title="Đặt làm ảnh đại diện">
                  <i class="bi bi-star"></i>
                </button>
                @endif
                <button type="button" class="remove-image-btn" onclick="removeImageSlot({{ $i }}, {{ $image->id }})" title="Xóa ảnh">
                  <i class="bi bi-x"></i>
                </button>
              </div>
            @else
              <div class="image-slot-content">
                <input type="file" name="images[{{ $i }}]" class="image-file-input" 
                       accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" 
                       onchange="handleImageUpload(this, {{ $i }})">
                <div class="add-image-btn">
                  <i class="bi bi-plus-circle"></i>
                  <span>Thêm ảnh</span>
                </div>
              </div>
            @endif
          </div>
          @endfor
        </div>
        <p class="text-muted mt-2 small">
          <i class="bi bi-info-circle me-1"></i> Tối đa 5 ảnh. Ô đầu tiên sẽ được sử dụng làm ảnh đại diện sản phẩm.
        </p>
      </div>

      <div class="mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                 {{ old('status', $product->status) ? 'checked' : '' }} style="width: 20px; height: 20px;">
          <label class="form-check-label" for="status" style="font-weight: 600; margin-left: 10px;">
            Kích hoạt sản phẩm
          </label>
        </div>
      </div>

      <hr class="my-4" style="border-color: #e2e8f0;">

      <!-- Variants Section -->
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="mb-0" style="font-weight: 700; color: #1e293b;">
          <i class="bi bi-box-seam me-2"></i> Biến thể sản phẩm
        </h5>
        <button type="button" class="btn btn-gradient btn-modern" id="add-variant-btn">
          <i class="bi bi-plus-circle me-1"></i> Thêm biến thể
        </button>
      </div>

      <div id="variants-container">
        @foreach($product->variants as $index => $variant)
        <div class="variant-item" data-variant-index="{{ $index }}">
          <input type="hidden" name="variants[{{ $index }}][id]" value="{{ $variant->id }}">
          <div class="variant-header">
            <h6>Biến thể #{{ $index + 1 }}</h6>
            <button type="button" class="btn btn-sm btn-modern remove-variant-btn" 
                    style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
              <i class="bi bi-trash"></i> Xóa
            </button>
          </div>
          <div class="row g-3">
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label" style="font-weight: 600;">SKU <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="variants[{{ $index }}][sku]" 
                       value="{{ $variant->sku }}" required style="border: 2px solid #e2e8f0; border-radius: 10px;">
              </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label" style="font-weight: 600;">Giá (₫) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" class="form-control" name="variants[{{ $index }}][price]" 
                       value="{{ $variant->price }}" required min="0" style="border: 2px solid #e2e8f0; border-radius: 10px;">
              </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label" style="font-weight: 600;">Giá cũ (₫)</label>
                <input type="number" step="0.01" class="form-control" name="variants[{{ $index }}][old_price]" 
                       value="{{ $variant->old_price }}" min="0" style="border: 2px solid #e2e8f0; border-radius: 10px;">
              </div>
            </div>
            <div class="col-md-3">
              <div class="mb-3">
                <label class="form-label" style="font-weight: 600;">Số lượng tồn kho <span class="text-danger">*</span></label>
                <input type="number" class="form-control" name="variants[{{ $index }}][stock]" 
                       value="{{ $variant->stock }}" required min="0" style="border: 2px solid #e2e8f0; border-radius: 10px;">
              </div>
            </div>
          </div>
          <div class="row g-3">
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" style="font-weight: 600;">Ảnh biến thể</label>
                @if($variant->image)
                <div class="mb-2">
                  <img src="{{ str_starts_with($variant->image, 'http') ? $variant->image : asset('storage/' . $variant->image) }}" 
                       alt="Variant image" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                </div>
                @endif
                <input type="file" class="form-control variant-image-input" 
                       name="variants[{{ $index }}][image]" accept="image/*"
                       style="border: 2px solid #e2e8f0; border-radius: 10px;">
              </div>
            </div>
            <div class="col-md-6">
              <div class="mb-3">
                <label class="form-label" style="font-weight: 600;">Hoặc URL ảnh</label>
                <input type="url" class="form-control" name="variants[{{ $index }}][image_url]" 
                       value="{{ str_starts_with($variant->image ?? '', 'http') ? $variant->image : '' }}" 
                       placeholder="https://..." style="border: 2px solid #e2e8f0; border-radius: 10px;">
              </div>
            </div>
          </div>
          <div class="row g-3 mt-2">
            <div class="col-12">
              <label class="form-label" style="font-weight: 600;"><strong>Cấu hình:</strong></label>
              <div class="row">
                @foreach($attributes as $attr)
                <div class="col-md-4 mb-3">
                  <label class="form-label" style="font-weight: 600;">{{ $attr->name }} <span class="text-danger">*</span></label>
                  <select class="form-control attribute-select" name="variants[{{ $index }}][attribute_values][]" required 
                          style="border: 2px solid #e2e8f0; border-radius: 10px;">
                    <option value="">Chọn {{ $attr->name }}</option>
                    @foreach($attr->attributeValues as $attrValue)
                    <option value="{{ $attrValue->id }}" 
                            {{ $variant->attributeValues->contains($attrValue->id) ? 'selected' : '' }}>
                      {{ $attrValue->value }}
                    </option>
                    @endforeach
                  </select>
                </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <div class="alert alert-warning" id="no-variants-alert" style="display: {{ $product->variants->count() == 0 ? 'block' : 'none' }}; border-radius: 12px;">
        <i class="bi bi-exclamation-triangle me-2"></i> Vui lòng thêm ít nhất một biến thể sản phẩm.
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-gradient btn-modern">
          <i class="bi bi-check-circle me-1"></i> Cập nhật
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-modern" style="background: #f1f5f9; color: #475569;">
          <i class="bi bi-x-circle me-1"></i> Hủy
        </a>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
let variantCount = {{ $product->variants->count() }};
const attributes = @json($attributes);
let removedImageIds = [];

// Handle image upload for specific slot
window.handleImageUpload = function(input, slotIndex) {
  const file = input.files[0];
  if (!file) return;

  // Validate file type
  const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
  if (!validTypes.includes(file.type)) {
    alert(`Định dạng ảnh không hợp lệ! Chỉ chấp nhận: JPG, PNG, GIF, WEBP`);
    input.value = '';
    return;
  }

  if (file.size > 2048 * 1024) {
    alert(`Ảnh ${file.name} vượt quá 2MB!`);
    input.value = '';
    return;
  }

  const slot = document.querySelector(`.image-slot[data-slot-index="${slotIndex}"]`);
  if (!slot) return;

  const reader = new FileReader();
  reader.onload = function(e) {
    // Remove existing content
    const existingContent = slot.querySelector('.image-slot-content');
    if (existingContent) existingContent.remove();

    // Remove existing image if any
    const existingImage = slot.querySelector('img');
    if (existingImage) existingImage.remove();

    // Add image preview
    const isPrimary = slotIndex === 0;
    slot.classList.add('has-image');
    if (isPrimary) {
      slot.classList.add('primary-image');
    }

    const imageHtml = `
      ${isPrimary ? '<span class="primary-badge">Ảnh đại diện</span>' : ''}
      <img src="${e.target.result}" alt="Product image ${slotIndex + 1}">
      <div class="image-actions">
        ${!isPrimary ? `<button type="button" class="set-primary-btn" onclick="setPrimaryImage(${slotIndex})" title="Đặt làm ảnh đại diện">
          <i class="bi bi-star"></i>
        </button>` : ''}
        <button type="button" class="remove-image-btn" onclick="removeImageSlot(${slotIndex})" title="Xóa ảnh">
          <i class="bi bi-x"></i>
        </button>
      </div>
    `;
    
    slot.innerHTML = imageHtml;
    
    // CRITICAL: File inputs MUST remain in the form to be submitted
    // Move input to form (not slot) but keep it accessible
    const form = document.getElementById('product-form');
    if (form) {
      // Remove input from slot first
      input.remove();
      
      // Add input to form (hidden but still submitable)
      input.style.position = 'absolute';
      input.style.left = '-9999px';
      input.style.opacity = '0';
      input.style.width = '1px';
      input.style.height = '1px';
      input.style.pointerEvents = 'none';
      form.appendChild(input);
    } else {
      // Fallback: keep in slot but make it invisible
      input.style.position = 'absolute';
      input.style.left = '-9999px';
      input.style.opacity = '0';
      input.style.width = '1px';
      input.style.height = '1px';
      slot.appendChild(input);
    }
    
    // Create new input for future changes
    const newInput = document.createElement('input');
    newInput.type = 'file';
    newInput.name = `images[${slotIndex}]`;
    newInput.className = 'image-file-input';
    newInput.accept = 'image/jpeg,image/png,image/jpg,image/gif,image/webp';
    newInput.style.display = 'none';
    newInput.onchange = function() { handleImageUpload(this, slotIndex); };
    slot.appendChild(newInput);
  };
  
  reader.onerror = function() {
    alert('Có lỗi xảy ra khi đọc file ảnh!');
    input.value = '';
  };
  
  reader.readAsDataURL(file);
};

// Set primary image
window.setPrimaryImage = function(slotIndex) {
  const slots = document.querySelectorAll('.image-slot.has-image');
  const targetSlot = document.querySelector(`.image-slot[data-slot-index="${slotIndex}"]`);
  const firstSlot = document.querySelector(`.image-slot[data-slot-index="0"]`);

  if (!targetSlot || !firstSlot || slotIndex === 0 || !targetSlot.classList.contains('has-image')) return;

  // Get current images data
  const targetImage = targetSlot.querySelector('img');
  const firstImage = firstSlot.querySelector('img');
  const targetHiddenInputs = targetSlot.querySelectorAll('input[type="hidden"]');
  const firstHiddenInputs = firstSlot.querySelectorAll('input[type="hidden"]');

  if (!targetImage || !firstImage) return;

  // Swap image sources
  const tempSrc = targetImage.src;
  targetImage.src = firstImage.src;
  firstImage.src = tempSrc;

  // Swap hidden inputs
  const targetId = targetHiddenInputs[0]?.value;
  const firstId = firstHiddenInputs[0]?.value;
  
  if (targetId && firstId) {
    targetHiddenInputs[0].value = firstId;
    firstHiddenInputs[0].value = targetId;
    
    // Update sort_order
    const targetSortOrder = targetHiddenInputs[1]?.value;
    const firstSortOrder = firstHiddenInputs[1]?.value;
    if (targetSortOrder !== undefined && firstSortOrder !== undefined) {
      targetHiddenInputs[1].value = firstSortOrder;
      firstHiddenInputs[1].value = targetSortOrder;
    }
  }

  // Update badges
  const targetBadge = targetSlot.querySelector('.primary-badge');
  const firstBadge = firstSlot.querySelector('.primary-badge');
  
  if (targetBadge) targetBadge.remove();
  if (!firstBadge) {
    const badge = document.createElement('span');
    badge.className = 'primary-badge';
    badge.textContent = 'Ảnh đại diện';
    firstSlot.insertBefore(badge, firstSlot.firstChild);
  }

  // Update buttons
  const targetSetPrimaryBtn = targetSlot.querySelector('.set-primary-btn');
  const firstSetPrimaryBtn = firstSlot.querySelector('.set-primary-btn');
  
  if (targetSetPrimaryBtn) {
    targetSetPrimaryBtn.remove();
  }
  if (!firstSetPrimaryBtn && slotIndex !== 0) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'set-primary-btn';
    btn.onclick = function() { setPrimaryImage(0); };
    btn.title = 'Đặt làm ảnh đại diện';
    btn.innerHTML = '<i class="bi bi-star"></i>';
    const actions = firstSlot.querySelector('.image-actions');
    if (actions) {
      actions.insertBefore(btn, actions.firstChild);
    }
  }

  // Update classes
  targetSlot.classList.remove('primary-image');
  firstSlot.classList.add('primary-image');
  
  // Update sort order for all images
  updateImageSortOrder();
};

// Remove image from slot
window.removeImageSlot = function(slotIndex, imageId) {
  if (imageId) {
    removedImageIds.push(imageId);
    
    // Add hidden input to mark this image for deletion
    const form = document.getElementById('product-form');
    if (form) {
      // Check if delete input already exists
      const existingDeleteInput = form.querySelector(`input[name="deleted_images[]"][value="${imageId}"]`);
      if (!existingDeleteInput) {
        const deleteInput = document.createElement('input');
        deleteInput.type = 'hidden';
        deleteInput.name = 'deleted_images[]';
        deleteInput.value = imageId;
        form.appendChild(deleteInput);
      }
    }
  }

  const slot = document.querySelector(`.image-slot[data-slot-index="${slotIndex}"]`);
  if (!slot) return;

  // Remove all existing inputs
  const existingInputs = slot.querySelectorAll('input[type="file"], input[type="hidden"]');
  existingInputs.forEach(input => input.remove());

  // Reset slot
  slot.classList.remove('has-image', 'primary-image');
  
  const slotContent = `
    <div class="image-slot-content">
      <input type="file" name="images[${slotIndex}]" class="image-file-input" 
             accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" 
             onchange="handleImageUpload(this, ${slotIndex})">
      <div class="add-image-btn">
        <i class="bi bi-plus-circle"></i>
        <span>Thêm ảnh</span>
      </div>
    </div>
  `;
  slot.innerHTML = slotContent;
};

// Update image sort order
function updateImageSortOrder() {
  const slots = document.querySelectorAll('.image-slot.has-image');
  slots.forEach((slot, index) => {
    const slotIndex = parseInt(slot.dataset.slotIndex);
    const hiddenInputs = slot.querySelectorAll('input[type="hidden"]');
    hiddenInputs.forEach(input => {
      if (input.name.includes('sort_order')) {
        input.value = index;
      }
    });
  });
}

document.addEventListener('DOMContentLoaded', function() {
  const addVariantBtn = document.getElementById('add-variant-btn');
  const variantsContainer = document.getElementById('variants-container');
  const noVariantsAlert = document.getElementById('no-variants-alert');

  // Handle click on image slot content to trigger file input
  document.addEventListener('click', function(e) {
    const slotContent = e.target.closest('.image-slot-content');
    if (slotContent) {
      const slot = slotContent.closest('.image-slot');
      if (slot) {
        const fileInput = slot.querySelector('.image-file-input');
        if (fileInput && !slot.classList.contains('has-image')) {
          fileInput.click();
        }
      }
    }
  });

  // Variant handling
  addVariantBtn.addEventListener('click', function() {
    addVariant();
  });

  function addVariant() {
    variantCount++;
    const variantHtml = createVariantHtml(variantCount);
    variantsContainer.insertAdjacentHTML('beforeend', variantHtml);
    updateNoVariantsAlert();
  }

  function createVariantHtml(index) {
    let attributesHtml = '';
    attributes.forEach(attr => {
      attributesHtml += `
        <div class="col-md-4 mb-3">
          <label class="form-label" style="font-weight: 600;">${attr.name} <span class="text-danger">*</span></label>
          <select class="form-control attribute-select" name="variants[${index}][attribute_values][]" required 
                  style="border: 2px solid #e2e8f0; border-radius: 10px;">
            <option value="">Chọn ${attr.name}</option>
            ${attr.attribute_values.map(val => `<option value="${val.id}">${val.value}</option>`).join('')}
          </select>
        </div>
      `;
    });

    return `
      <div class="variant-item" data-variant-index="${index}">
        <div class="variant-header">
          <h6>Biến thể #${index + 1}</h6>
          <button type="button" class="btn btn-sm btn-modern remove-variant-btn" 
                  style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white;">
            <i class="bi bi-trash"></i> Xóa
          </button>
        </div>
        <div class="row g-3">
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label" style="font-weight: 600;">SKU <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="variants[${index}][sku]" required 
                     placeholder="VD: SKU-001" style="border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label" style="font-weight: 600;">Giá (₫) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" class="form-control" name="variants[${index}][price]" required 
                     min="0" placeholder="0" style="border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label" style="font-weight: 600;">Giá cũ (₫)</label>
              <input type="number" step="0.01" class="form-control" name="variants[${index}][old_price]" 
                     min="0" placeholder="0" style="border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label" style="font-weight: 600;">Số lượng tồn kho <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="variants[${index}][stock]" required 
                     min="0" placeholder="0" style="border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
          </div>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label" style="font-weight: 600;">Ảnh biến thể</label>
              <input type="file" class="form-control variant-image-input" 
                     name="variants[${index}][image]" accept="image/*"
                     style="border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
          </div>
          <div class="col-md-6">
            <div class="mb-3">
              <label class="form-label" style="font-weight: 600;">Hoặc URL ảnh</label>
              <input type="url" class="form-control" name="variants[${index}][image_url]" 
                     placeholder="https://..." style="border: 2px solid #e2e8f0; border-radius: 10px;">
            </div>
          </div>
        </div>
        <div class="row g-3 mt-2">
          <div class="col-12">
            <label class="form-label" style="font-weight: 600;"><strong>Cấu hình:</strong></label>
            <div class="row">
              ${attributesHtml}
            </div>
          </div>
        </div>
      </div>
    `;
  }

  // Remove variant
  variantsContainer.addEventListener('click', function(e) {
    if (e.target.closest('.remove-variant-btn')) {
      const variantItem = e.target.closest('.variant-item');
      variantItem.remove();
      updateNoVariantsAlert();
    }
  });

  function updateNoVariantsAlert() {
    const variantItems = variantsContainer.querySelectorAll('.variant-item');
    if (variantItems.length === 0) {
      noVariantsAlert.style.display = 'block';
    } else {
      noVariantsAlert.style.display = 'none';
    }
  }

  // Ensure file inputs are properly submitted
  const productForm = document.getElementById('product-form');
  if (productForm) {
    productForm.addEventListener('submit', function(e) {
      // Collect all file inputs and ensure they're in the form
      const allFileInputs = document.querySelectorAll('.image-file-input');
      allFileInputs.forEach(input => {
        if (input.files && input.files.length > 0) {
          // Ensure input is in the form
          if (!input.closest('form')) {
            productForm.appendChild(input);
          }
          // Make sure input is not disabled
          input.disabled = false;
        }
      });
    });
  }
});
</script>
@endpush
