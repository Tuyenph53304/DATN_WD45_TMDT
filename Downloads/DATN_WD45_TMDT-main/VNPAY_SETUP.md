# Hướng dẫn cấu hình VNPay

## ⚠️ Lỗi "Sai chữ ký" - Cách khắc phục

Lỗi "Sai chữ ký" xảy ra khi chữ ký (signature) không khớp với VNPay. Đây là lỗi phổ biến nhất khi tích hợp VNPay.

### Nguyên nhân chính:
1. **Hash Secret Key không đúng** - Thông tin demo có thể không hoạt động
2. **Return URL không khớp** - URL phải là full URL và khớp với đăng ký
3. **Cách tạo hash không đúng** - Cần tuân thủ đúng chuẩn VNPay

### Giải pháp:

### 1. Đăng ký tài khoản VNPay Sandbox (QUAN TRỌNG)

**Thông tin demo có thể không hoạt động!** Bạn **PHẢI** đăng ký tài khoản thật:

1. Truy cập: https://sandbox.vnpayment.vn/
2. Đăng ký tài khoản merchant mới
3. Sau khi đăng ký, vào phần **Cấu hình** để lấy:
   - **TMN Code** (Terminal ID) - Ví dụ: `2QXUI4J4`
   - **Hash Secret** (Secret Key) - Ví dụ: `RAOCTZKRVBMGIESULXWYDFLAPNVHQKBI`
4. **Lưu ý**: Thông tin demo trong code có thể đã hết hạn hoặc không hợp lệ

### 2. Cấu hình trong file `.env`

Thêm các dòng sau vào file `.env`:

```env
VNPAY_TMN_CODE=your_tmn_code_here
VNPAY_HASH_SECRET=your_hash_secret_here
VNPAY_RETURN_URL=/payment/callback
```

### 3. Kiểm tra Return URL (QUAN TRỌNG)

Return URL phải là **full URL** và **KHỚP CHÍNH XÁC** với URL đã đăng ký trong VNPay.

**Cách kiểm tra URL hiện tại:**
- Mở trình duyệt, vào trang thanh toán
- Xem log file `storage/logs/laravel.log` để thấy `return_url` được gửi đi

**Cấu hình trong `.env`:**
```env
# Option 1: Dùng full URL (KHUYẾN NGHỊ)
VNPAY_RETURN_URL=https://yourdomain.com/payment/callback

# Option 2: Để hệ thống tự tạo (sẽ dùng APP_URL từ .env)
VNPAY_RETURN_URL=/payment/callback
```

**Lưu ý**: 
- URL phải có `https://` hoặc `http://`
- Không có dấu `/` ở cuối
- Phải khớp 100% với URL đã đăng ký trong VNPay

### 4. Test với thông tin demo (nếu có)

Nếu VNPay cung cấp thông tin demo, sử dụng:
- TMN Code: `2QXUI4J4`
- Hash Secret: `RAOCTZKRVBMGIESULXWYDFLAPNVHQKBI`

### 5. Kiểm tra logs để debug

Khi `APP_DEBUG=true`, hệ thống sẽ log thông tin chi tiết:

**Xem log:**
```bash
tail -f storage/logs/laravel.log
```

**Hoặc mở file:** `storage/logs/laravel.log`

**Tìm các dòng log:**
- `VNPay Payment URL Data` - Thông tin hash được tạo
- `VNPay Callback Received` - Dữ liệu callback từ VNPay
- `VNPay Callback Verification Failed` - Lỗi xác thực

**Kiểm tra:**
- `hashdata`: Chuỗi được dùng để tạo hash
- `secure_hash`: Hash được tạo ra
- `return_url`: URL callback
- `tmn_code`: Mã merchant
- `hash_secret_length`: Độ dài secret key (thường là 32 ký tự)

### 6. Lưu ý quan trọng

- **Return URL** phải khớp chính xác với URL đã đăng ký trong VNPay
- **Hash Secret** phải đúng và không có khoảng trắng
- **Amount** phải là số nguyên (đã nhân 100)
- Tất cả tham số phải được encode đúng cách

### 7. Test thẻ thanh toán

Sử dụng thẻ test từ VNPay:
- Ngân hàng: NCB
- Số thẻ: `9704198526191432198`
- Tên chủ thẻ: `NGUYEN VAN A`
- Ngày phát hành: `07/15`
- Mã OTP: `123456`

## Liên hệ hỗ trợ

Nếu vẫn gặp lỗi, liên hệ:
- VNPay Support: https://sandbox.vnpayment.vn/
- Email: support@vnpayment.vn

