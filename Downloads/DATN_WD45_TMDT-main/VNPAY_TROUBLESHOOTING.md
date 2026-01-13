# VNPay Troubleshooting - Lỗi "Sai chữ ký"

## ⚠️ Vấn đề phổ biến: Lỗi "Sai chữ ký"

### Nguyên nhân chính:

1. **Return URL là localhost** ⚠️ **QUAN TRỌNG NHẤT**
   - VNPay **KHÔNG CHẤP NHẬN** localhost (`127.0.0.1` hoặc `localhost`)
   - Cần dùng domain công khai hoặc ngrok

2. **Hash Secret Key không đúng**
   - Thông tin demo có thể đã hết hạn
   - Cần đăng ký tài khoản VNPay thật

3. **TMN Code không đúng**
   - Mã merchant demo có thể không hợp lệ

## 🔧 Giải pháp nhanh

### Giải pháp 1: Sử dụng Ngrok (Khuyến nghị cho test local)

1. **Cài đặt Ngrok:**
   ```bash
   # Download từ https://ngrok.com/
   # Hoặc dùng chocolatey (Windows)
   choco install ngrok
   ```

2. **Chạy Ngrok:**
   ```bash
   ngrok http 8000
   ```

3. **Lấy URL từ Ngrok:**
   - Sẽ có dạng: `https://abc123.ngrok.io`
   - Copy URL này

4. **Cập nhật `.env`:**
   ```env
   APP_URL=https://abc123.ngrok.io
   VNPAY_RETURN_URL=https://abc123.ngrok.io/payment/callback
   ```

5. **Clear config cache:**
   ```bash
   php artisan config:clear
   ```

### Giải pháp 2: Đăng ký tài khoản VNPay Sandbox

1. Truy cập: https://sandbox.vnpayment.vn/
2. Đăng ký tài khoản merchant
3. Lấy thông tin từ phần **Cấu hình**:
   - TMN Code
   - Hash Secret
   - Đăng ký Return URL (phải là domain công khai)

4. **Cập nhật `.env`:**
   ```env
   VNPAY_TMN_CODE=your_real_tmn_code
   VNPAY_HASH_SECRET=your_real_hash_secret
   VNPAY_RETURN_URL=https://yourdomain.com/payment/callback
   ```

### Giải pháp 3: Kiểm tra logs

1. **Xem log:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Tìm dòng:** `VNPay Payment URL Data`

3. **Kiểm tra:**
   - `return_url`: Phải là HTTPS và không phải localhost
   - `hash_secret_length`: Phải là 32 (hoặc độ dài secret key của bạn)
   - `tmn_code`: Phải đúng với VNPay

## 📝 Checklist

- [ ] Return URL không phải localhost
- [ ] Return URL là HTTPS (hoặc HTTP nếu VNPay cho phép)
- [ ] Return URL khớp với URL đã đăng ký trong VNPay
- [ ] TMN Code đúng
- [ ] Hash Secret đúng (32 ký tự)
- [ ] Đã clear config cache: `php artisan config:clear`

## 🚀 Test nhanh với Ngrok

```bash
# Terminal 1: Chạy Laravel
php artisan serve

# Terminal 2: Chạy Ngrok
ngrok http 8000

# Copy URL từ Ngrok (ví dụ: https://abc123.ngrok.io)
# Cập nhật .env:
APP_URL=https://abc123.ngrok.io
VNPAY_RETURN_URL=https://abc123.ngrok.io/payment/callback

# Clear cache
php artisan config:clear

# Test lại thanh toán
```

## ⚡ Lưu ý quan trọng

1. **Localhost KHÔNG hoạt động** - Phải dùng domain công khai
2. **Return URL phải khớp 100%** - Kể cả `http://` vs `https://`
3. **Hash Secret phải đúng** - Thông tin demo có thể không hợp lệ
4. **Clear cache sau khi đổi config** - `php artisan config:clear`

