@extends('admin.layout')

@section('title', 'Tạo Sản phẩm mới - Admin Panel')
@section('page-title', 'Tạo Sản phẩm mới')

@push('styles')
<style>
  .variant-item {
    border: 2px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 15px;
    background: #f8f9fa;
  }
  .variant-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
  }
  .attribute-group {
    margin-bottom: 15px;
  }
</style>
@endpush

@section('content')
<div class="card">
  <div class="card-header">
    <h3 class="card-title"><i class="bi bi-plus-circle me-2"></i> Tạo Sản phẩm mới</h3>
    <div class="card-tools">
      <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-sm">
        <i class="bi bi-arrow-left me-1"></i> Quay lại
      </a>
    </div>
  </div>
  <div class="card-body">
    <form action="{{ route('admin.products.store') }}" method="POST" id="product-form">
      @csrf

      <!-- Product Basic Info -->
      <h5 class="mb-3"><i class="bi bi-info-circle me-2"></i> Thông tin cơ bản</h5>
      <div class="row">
        <div class="col-md-6">
          <div class="mb-3">
            <label for="category_id" class="form-label">Danh mục <span class="text-danger">*</span></label>
            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
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
            <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('name') is-invalid @enderror"
                   id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
        </div>
      </div>

      <div class="mb-3">
        <label for="description" class="form-label">Mô tả</label>
        <textarea class="form-control @error('description') is-invalid @enderror"
                  id="description" name="description" rows="4">{{ old('description') }}</textarea>
        @error('description')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="status" name="status" value="1"
                 {{ old('status', true) ? 'checked' : '' }}>
          <label class="form-check-label" for="status">
            Kích hoạt sản phẩm
          </label>
        </div>
      </div>

      <hr class="my-4">

      <!-- Variants Section -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i> Biến thể sản phẩm</h5>
        <button type="button" class="btn btn-sm btn-primary" id="add-variant-btn">
          <i class="bi bi-plus-circle me-1"></i> Thêm biến thể
        </button>
      </div>

      <div id="variants-container">
        <!-- Variants will be added here dynamically -->
      </div>

      <div class="alert alert-warning" id="no-variants-alert" style="display: none;">
        <i class="bi bi-exclamation-triangle me-2"></i> Vui lòng thêm ít nhất một biến thể sản phẩm.
      </div>

      <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">
          <i class="bi bi-check-circle me-1"></i> Tạo Sản phẩm
        </button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
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

document.addEventListener('DOMContentLoaded', function() {
  const addVariantBtn = document.getElementById('add-variant-btn');
  const variantsContainer = document.getElementById('variants-container');
  const noVariantsAlert = document.getElementById('no-variants-alert');

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
        <div class="attribute-group">
          <label class="form-label"><strong>${attr.name}:</strong></label>
          <select class="form-control attribute-select" name="variants[${index}][attribute_values][]" required>
            <option value="">Chọn ${attr.name}</option>
            ${attr.values.map(val => `<option value="${val.id}">${val.value}</option>`).join('')}
          </select>
        </div>
      `;
    });

    return `
      <div class="variant-item" data-variant-index="${index}">
        <div class="variant-header">
          <h6 class="mb-0">Biến thể #${index}</h6>
          <button type="button" class="btn btn-sm btn-danger remove-variant-btn">
            <i class="bi bi-trash"></i> Xóa
          </button>
        </div>
        <div class="row">
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label">SKU <span class="text-danger">*</span></label>
              <input type="text" class="form-control" name="variants[${index}][sku]" required placeholder="VD: SKU-001">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label">Giá (₫) <span class="text-danger">*</span></label>
              <input type="number" step="0.01" class="form-control" name="variants[${index}][price]" required min="0" placeholder="0">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
              <input type="number" class="form-control" name="variants[${index}][stock]" required min="0" placeholder="0">
            </div>
          </div>
          <div class="col-md-3">
            <div class="mb-3">
              <label class="form-label">URL ảnh</label>
              <input type="text" class="form-control" name="variants[${index}][image]" placeholder="https://...">
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-12">
            <label class="form-label"><strong>Thuộc tính:</strong></label>
            <div class="row">
              ${attributes.map(attr => `
                <div class="col-md-4 mb-2">
                  <label class="form-label small">${attr.name}:</label>
                  <select class="form-control form-control-sm attribute-select" name="variants[${index}][attribute_values][]" required>
                    <option value="">Chọn ${attr.name}</option>
                    ${attr.values.map(val => `<option value="${val.id}">${val.value}</option>`).join('')}
                  </select>
                </div>
              `).join('')}
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
    variantItems.forEach((item, index) => {
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
