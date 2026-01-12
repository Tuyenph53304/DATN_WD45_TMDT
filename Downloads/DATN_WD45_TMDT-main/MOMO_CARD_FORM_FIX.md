# Khắc phục vấn đề: Thanh toán bằng thẻ hiển thị QR thay vì form nhập thẻ

## Vấn đề
Khi chọn thanh toán bằng thẻ (card/ATM), hệ thống vẫn hiển thị QR code thay vì form nhập thẻ.

## Nguyên nhân
Với MoMo API, khi sử dụng `payWithCC` hoặc `payWithATM`, URL thanh toán sẽ tự động hiển thị form nhập thẻ. Tuy nhiên, có thể do:
1. Demo API không hỗ trợ đầy đủ
2. RequestType không được xử lý đúng
3. URL redirect không đúng

## Giải pháp

### 1. Kiểm tra RequestType
Đảm bảo `requestType` được set đúng:
- `captureWallet`: Hiển thị QR code (ví MoMo)
- `payWithCC`: Hiển thị form nhập thẻ tín dụng
- `payWithATM`: Hiển thị form nhập thẻ ATM

### 2. Kiểm tra Logs
Bật debug mode và kiểm tra logs:
```env
APP_DEBUG=true
```

Xem logs tại `storage/logs/laravel.log` để kiểm tra:
- `requestType` có đúng không
- `payUrl` có được tạo đúng không
- Response từ MoMo API

### 3. Kiểm tra URL thanh toán
Sau khi redirect đến `payUrl`, kiểm tra:
- URL có chứa thông tin về loại thanh toán không
- Trang MoMo có hiển thị form nhập thẻ không

### 4. Test với các thẻ demo
Sử dụng các thẻ test:
- **Thẻ thành công:** `9704198526191432198`
- **CVV:** `123`
- **OTP:** `OTP`

## Lưu ý
- Với MoMo Demo API, có thể không hỗ trợ đầy đủ tính năng thanh toán bằng thẻ
- Nếu vẫn hiển thị QR, có thể cần đăng ký tài khoản MoMo Merchant thật
- Kiểm tra tài liệu MoMo mới nhất: https://developers.momo.vn/

## Debug Steps
1. Bật `APP_DEBUG=true` trong `.env`
2. Tạo đơn hàng với thanh toán bằng thẻ
3. Kiểm tra logs: `storage/logs/laravel.log`
4. Kiểm tra `requestType` trong logs
5. Kiểm tra `payUrl` có đúng không
6. Thử redirect đến `payUrl` và kiểm tra trang hiển thị

