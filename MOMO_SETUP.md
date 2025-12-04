# Hướng dẫn cấu hình MoMo Payment Gateway

## 1. Đăng ký tài khoản MoMo Business

1. Truy cập: https://business.momo.vn/
2. Đăng ký tài khoản doanh nghiệp
3. Sau khi đăng ký, bạn sẽ nhận được:
   - **Partner Code**: Mã đối tác
   - **Access Key**: Khóa truy cập
   - **Secret Key**: Khóa bí mật (quan trọng, không được tiết lộ)

## 2. Cấu hình môi trường

Thêm các biến sau vào file `.env`:

```env
# MoMo Payment Configuration
MOMO_PARTNER_CODE=your_partner_code
MOMO_ACCESS_KEY=your_access_key
MOMO_SECRET_KEY=your_secret_key
MOMO_RETURN_URL=/payment/callback
MOMO_NOTIFY_URL=/payment/ipn
MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create
```

### Thông tin Sandbox (Test)

Nếu bạn đang test, có thể sử dụng thông tin demo sau (đã được cấu hình mặc định):

```env
MOMO_PARTNER_CODE=MOMOBKUN20180529
MOMO_ACCESS_KEY=klm05TvNBzhg7h7j
MOMO_SECRET_KEY=at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa
```

**Lưu ý**: Thông tin demo này chỉ dùng để test. Để thanh toán thật, bạn cần đăng ký tài khoản thật.

## 3. Cấu hình Return URL và IPN URL

### Return URL
- URL này sẽ được MoMo redirect về sau khi người dùng thanh toán
- Mặc định: `http://your-domain.com/payment/callback`
- Phải là URL công khai (không được là localhost)

### IPN URL (Instant Payment Notification)
- URL này MoMo sẽ gọi để thông báo kết quả thanh toán
- Mặc định: `http://your-domain.com/payment/ipn`
- Phải là URL công khai và có thể truy cập từ internet

### Nếu đang phát triển trên localhost

Bạn cần sử dụng công cụ như **ngrok** để tạo public URL:

```bash
# Cài đặt ngrok
# Windows: tải từ https://ngrok.com/download

# Chạy ngrok
ngrok http 8000

# Sử dụng URL được cung cấp, ví dụ: https://abc123.ngrok.io
# Cập nhật .env:
MOMO_RETURN_URL=https://abc123.ngrok.io/payment/callback
MOMO_NOTIFY_URL=https://abc123.ngrok.io/payment/ipn
```

## 4. Kiểm tra cấu hình

Sau khi cấu hình xong, kiểm tra:

1. **Kiểm tra file config**: `config/payment.php` phải có section `momo`
2. **Kiểm tra .env**: Các biến môi trường phải được set đúng
3. **Kiểm tra routes**: 
   - `/payment/callback` (GET) - Callback từ MoMo
   - `/payment/ipn` (POST) - IPN từ MoMo

## 5. Test thanh toán

1. Thêm sản phẩm vào giỏ hàng
2. Điền thông tin và chọn địa chỉ giao hàng
3. Click "Thanh toán MoMo"
4. Bạn sẽ được chuyển đến trang thanh toán MoMo
5. Sử dụng thông tin test để thanh toán:
   - **Số điện thoại**: 0123456789
   - **OTP**: 123456 (hoặc theo hướng dẫn của MoMo sandbox)

## 6. Xử lý lỗi thường gặp

### Lỗi "Invalid signature"
- **Nguyên nhân**: Secret key không đúng hoặc cách tính signature sai
- **Giải pháp**: 
  - Kiểm tra lại `MOMO_SECRET_KEY` trong `.env`
  - Đảm bảo không có khoảng trắng thừa
  - Xem log trong `storage/logs/laravel.log` để debug

### Lỗi "Connection timeout"
- **Nguyên nhân**: Không kết nối được đến MoMo API
- **Giải pháp**:
  - Kiểm tra kết nối internet
  - Kiểm tra firewall
  - Kiểm tra `MOMO_ENDPOINT` có đúng không

### Lỗi "Order not found"
- **Nguyên nhân**: Order ID không khớp
- **Giải pháp**:
  - Kiểm tra log để xem order ID được gửi và nhận
  - Đảm bảo order được tạo trước khi gọi API MoMo

### IPN không hoạt động
- **Nguyên nhân**: URL không công khai hoặc server không thể nhận POST request
- **Giải pháp**:
  - Sử dụng ngrok để tạo public URL
  - Kiểm tra firewall cho phép POST request
  - Kiểm tra route `/payment/ipn` có tồn tại không

## 7. So sánh với VNPay

### Ưu điểm của MoMo:
- ✅ API đơn giản hơn, dễ tích hợp
- ✅ Signature generation đơn giản hơn (chỉ cần hash_hmac SHA256)
- ✅ Có IPN (Instant Payment Notification) để đảm bảo cập nhật trạng thái đơn hàng
- ✅ Tài liệu rõ ràng, dễ hiểu

### Nhược điểm:
- ⚠️ Cần URL công khai (không thể dùng localhost trực tiếp)
- ⚠️ Cần đăng ký tài khoản doanh nghiệp để sử dụng thật

## 8. Chuyển sang Production

Khi sẵn sàng chuyển sang production:

1. Đăng ký tài khoản MoMo Business thật
2. Lấy thông tin thật (Partner Code, Access Key, Secret Key)
3. Cập nhật `.env` với thông tin thật
4. Thay đổi endpoint:
   ```env
   MOMO_ENDPOINT=https://payment.momo.vn/v2/gateway/api/create
   ```
5. Cấu hình Return URL và IPN URL với domain thật
6. Test kỹ trước khi go-live

## 9. Log và Debug

Tất cả các log được ghi vào `storage/logs/laravel.log`. Để xem log:

```bash
# Windows PowerShell
Get-Content storage/logs/laravel.log -Tail 100 | Select-String -Pattern "MoMo"

# Linux/Mac
tail -f storage/logs/laravel.log | grep MoMo
```

Các thông tin được log:
- Request data khi tạo payment URL
- Callback data từ MoMo
- IPN data từ MoMo
- Signature verification results
- Payment success/failure

## 10. Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra log trong `storage/logs/laravel.log`
2. Kiểm tra tài liệu MoMo: https://developers.momo.vn/
3. Liên hệ support MoMo: support@momo.vn

