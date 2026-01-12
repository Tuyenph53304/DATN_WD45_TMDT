# Hướng dẫn thanh toán bằng thẻ với MoMo Demo

## Tổng quan

Hệ thống đã được cập nhật để hỗ trợ thanh toán bằng thẻ với MoMo Demo API. Bạn có thể thanh toán bằng:
- **Ví MoMo** (captureWallet)
- **Thẻ tín dụng/Ghi nợ** (payWithCC)
- **Thẻ ATM nội địa** (payWithATM)

## Thẻ test MoMo Demo

Khi sử dụng MoMo Demo API, bạn có thể sử dụng các thẻ test sau:

### Thẻ thành công (Thanh toán thành công)
- **Số thẻ:** `9704198526191432198`
- **CVV:** `123`
- **OTP:** `OTP`
- **Ngày hết hạn:** Bất kỳ ngày trong tương lai

### Thẻ thất bại (Thanh toán thất bại)
- **Số thẻ:** `9704198526191432206`
- **CVV:** `123`
- **OTP:** `OTP`
- **Ngày hết hạn:** Bất kỳ ngày trong tương lai

### Thẻ hết hạn (Thẻ đã hết hạn)
- **Số thẻ:** `9704198526191432214`
- **CVV:** `123`
- **OTP:** `OTP`
- **Ngày hết hạn:** Bất kỳ ngày trong quá khứ

## Cách sử dụng

### 1. Trong trang Checkout

1. Chọn phương thức thanh toán: **Thanh toán qua MoMo**
2. Chọn loại thanh toán:
   - **Ví MoMo**: Thanh toán qua ví điện tử MoMo
   - **Thẻ tín dụng/Ghi nợ**: Thanh toán bằng thẻ Visa/MasterCard
   - **Thẻ ATM nội địa**: Thanh toán bằng thẻ ATM nội địa
3. Nhấn **Thanh toán MoMo**
4. Nếu chọn thanh toán bằng thẻ, bạn sẽ được chuyển đến trang thanh toán MoMo
5. Nhập thông tin thẻ test ở trên

### 2. Xử lý sau thanh toán

Khi thanh toán thành công:
- ✅ Đơn hàng tự động chuyển sang trạng thái **"Thành công"** (completed)
- ✅ Trạng thái thanh toán: **"Đã thanh toán"** (paid)
- ✅ Lưu mã giao dịch (transaction_id) vào đơn hàng
- ✅ Lưu thông tin thanh toán chi tiết vào logs

### 3. Kiểm tra thông tin thanh toán

Thông tin thanh toán được lưu trong:
- **Bảng orders:**
  - `payment_status`: Trạng thái thanh toán (paid/unpaid/failed)
  - `transaction_id`: Mã giao dịch từ MoMo
  - `status`: Trạng thái đơn hàng (completed khi thanh toán thành công)

- **Logs:** `storage/logs/laravel.log`
  - Chi tiết thông tin thanh toán
  - Transaction ID
  - Số tiền
  - Loại thanh toán (payType)
  - Thời gian thanh toán

## Cấu hình

### File `.env`

```env
# MoMo Demo Configuration
MOMO_PARTNER_CODE=MOMOBKUN20180529
MOMO_ACCESS_KEY=klm05TvNBzhg7h7j
MOMO_SECRET_KEY=at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa
MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create
MOMO_RETURN_URL=/payment/callback
MOMO_NOTIFY_URL=/payment/ipn
```

### Request Types

- `captureWallet`: Thanh toán qua ví MoMo
- `payWithCC`: Thanh toán bằng thẻ tín dụng/Ghi nợ
- `payWithATM`: Thanh toán bằng thẻ ATM nội địa

## Lưu ý

1. **MoMo Demo**: Chỉ dùng để test, không phải thanh toán thật
2. **Thẻ test**: Chỉ hoạt động với MoMo Demo API
3. **Thời gian hiệu lực**: QR code và payment URL có thời gian hiệu lực ngắn
4. **Production**: Khi chuyển sang production, cần đăng ký tài khoản MoMo Merchant thật

## Troubleshooting

### Lỗi "QR hết hạn"
- Thử lại sau vài phút
- Tạo đơn hàng mới

### Lỗi "Thẻ không hợp lệ"
- Kiểm tra lại số thẻ test
- Đảm bảo đang sử dụng MoMo Demo API

### Thanh toán thành công nhưng đơn hàng không cập nhật
- Kiểm tra callback URL trong MoMo Developer Portal
- Kiểm tra logs trong `storage/logs/laravel.log`
- Đảm bảo IPN URL đã được cấu hình đúng

## Hỗ trợ

Nếu gặp vấn đề:
1. Kiểm tra logs: `storage/logs/laravel.log`
2. Kiểm tra cấu hình trong file `.env`
3. Xem tài liệu MoMo: https://developers.momo.vn/

