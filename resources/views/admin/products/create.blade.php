@extends('admin.layout')

@section('title', 'Tạo Sản phẩm mới - Admin Panel')
@section('page-title', 'Tạo Sản phẩm mới')

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

  .variant-header h6 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
  }

  .attribute-group {
    margin-bottom: 15px;
  }

  .image-upload-area {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    padding: 20px;
    text-align: center;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .image-upload-area:hover {
    border-color: #667eea;
    background: #f8fafc;
  }

  .image-upload-area.dragover {
    border-color: #667eea;
    background: rgba(102, 126, 234, 0.1);
  }

  .image-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-top: 15px;
  }

  .image-preview-item {
    position: relative;
    width: 120px;
    height: 120px;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid #e2e8f0;
  }

  .image-preview-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .image-preview-item .remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.9);
    color: white;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }

  .image-preview-item .remove-image:hover {
    background: #ef4444;
    transform: scale(1.1);
  }
</style>
@endpush

@section('content')
<div class="modern-card">
  <div class="modern-card-header">
    <h3 class="modern-card-title">
      <i class="bi bi-plus-circle"></i>
      Tạo Sản phẩm mới
    </h3>
    <a href="{{ route('admin.products.index') }}" class="btn btn-modern" style="background: #f1f5f9; color: #475569;">
      <i class="bi bi-arrow-left me-1"></i> Quay lại
    </a>
  </div>

  <div class="card-body" style="padding: 30px;">
    <form action="{{ route('admin.products.store') }}" method="POST" id="product-form" enctype="multipart/form-data">
      @csrf

      <!-- Product Basic Info -->
      <h5 class="mb-4" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-info-circle me-2"></i> Thông tin cơ bản
      </h5>
      <div class="row g-3 mb-4">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="category_id" class="form-label" style="font-weight: 600;">Danh mục <span class="text-danger">*</span></label>
            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required style="border: 2px solid #e2e8f0; border-radius: 10px;">
              <option value="">Chọn danh mục</option>
              @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                   id="name" name="name" value="{{ old('name') }}" required 
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
                  style="border: 2px solid #e2e8f0; border-radius: 10px;">{{ old('description') }}</textarea>
        @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <!-- Product Images -->
      <h5 class="mb-4" style="font-weight: 700; color: #1e293b;">
        <i class="bi bi-images me-2"></i> Ảnh sản phẩm
      </h5>
      <div class="mb-4">
        <div class="image-upload-area" id="product-images-upload">
          <i class="bi bi-cloud-upload" style="font-size: 3rem; color: #94a3b8;"></i>
          <p class="mt-3 mb-0" style="color: #64748b;">
            <strong>Kéo thả ảnh vào đây</strong> hoặc click để chọn
          </p>
          <p class="small text-muted mt-2">Có thể chọn nhiều ảnh (JPG, PNG, GIF, WEBP - tối đa 2MB/ảnh)</p>
        </div>
        <input type="file" name="images[]" id="product-images-input" multiple 
               accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" style="display: none;">
        <div class="image-preview-container" id="product-images-preview"></div>
      </div>

      <div class="mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                 {{ old('status', true) ? 'checked' : '' }} style="width: 20px; height: 20px;">
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
        <!-- Variants will be added here dynamically -->
      </div>

      <div class="alert alert-warning" id="no-variants-alert" style="display: none; border-radius: 12px;">
        <i class="bi bi-exclamation-triangle me-2"></i> Vui lòng thêm ít nhất một biến thể sản phẩm.
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-gradient btn-modern">
          <i class="bi bi-check-circle me-1"></i> Tạo Sản phẩm
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
let variantCount = 0;
const attributes = @json($attributes);
let productImages = [];

document.addEventListener('DOMContentLoaded', function() {
  const addVariantBtn = document.getElementById('add-variant-btn');
  const variantsContainer = document.getElementById('variants-container');
  const noVariantsAlert = document.getElementById('no-variants-alert');
  const productImagesUpload = document.getElementById('product-images-upload');
  const productImagesInput = document.getElementById('product-images-input');
  const productImagesPreview = document.getElementById('product-images-preview');

  // Image upload handling
  productImagesUpload.addEventListener('click', () => productImagesInput.click());
  productImagesInput.addEventListener('change', handleImageSelect);

  productImagesUpload.addEventListener('dragover', (e) => {
    e.preventDefault();
    productImagesUpload.classList.add('dragover');
  });

  productImagesUpload.addEventListener('dragleave', () => {
    productImagesUpload.classList.remove('dragover');
  });

  productImagesUpload.addEventListener('drop', (e) => {
    e.preventDefault();
    productImagesUpload.classList.remove('dragover');
    const files = Array.from(e.dataTransfer.files).filter(file => file.type.startsWith('image/'));
    handleImageFiles(files);
  });

  function handleImageSelect(e) {
    const files = Array.from(e.target.files);
    handleImageFiles(files);
  }

  function handleImageFiles(files) {
    files.forEach(file => {
      if (file.size > 2048 * 1024) {
        alert(`Ảnh ${file.name} vượt quá 2MB!`);
        return;
      }
      productImages.push(file);
      const reader = new FileReader();
      reader.onload = (e) => {
        const previewItem = document.createElement('div');
        previewItem.className = 'image-preview-item';
        previewItem.innerHTML = `
          <img src="${e.target.result}" alt="${file.name}">
          <button type="button" class="remove-image" onclick="removeProductImage(this, '${file.name}')">
            <i class="bi bi-x"></i>
          </button>
        `;
        productImagesPreview.appendChild(previewItem);
      };
      reader.readAsDataURL(file);
    });
    updateProductImagesInput();
  }

  window.removeProductImage = function(btn, fileName) {
    productImages = productImages.filter(img => img.name !== fileName);
    btn.closest('.image-preview-item').remove();
    updateProductImagesInput();
  };

  function updateProductImagesInput() {
    const dt = new DataTransfer();
    productImages.forEach(file => dt.items.add(file));
    productImagesInput.files = dt.files;
  }

  // Variant handling
  addVariantBtn.addEventListener('click', function() {
    addVariant();
  });

  // Add first variant by default
  addVariant();

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
          <h6>Biến thể #${index}</h6>
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

  // Form validation
  document.getElementById('product-form').addEventListener('submit', function(e) {
    const variantItems = variantsContainer.querySelectorAll('.variant-item');
    if (variantItems.length === 0) {
      e.preventDefault();
      alert('Vui lòng thêm ít nhất một biến thể sản phẩm!');
      return false;
    }

    // Validate each variant
    let isValid = true;
    variantItems.forEach((item) => {
      const sku = item.querySelector('input[name*="[sku]"]').value;
      const price = item.querySelector('input[name*="[price]"]').value;
      const stock = item.querySelector('input[name*="[stock]"]').value;
      const attributeSelects = item.querySelectorAll('.attribute-select');

      if (!sku || !price || !stock) {
        isValid = false;
      }

      attributeSelects.forEach(select => {
        if (!select.value) {
          isValid = false;
        }
      });
    });

    if (!isValid) {
      e.preventDefault();
      alert('Vui lòng điền đầy đủ thông tin cho tất cả biến thể!');
      return false;
    }
  });
});
</script>
@endpush
