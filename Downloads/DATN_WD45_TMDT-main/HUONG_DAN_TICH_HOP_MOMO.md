# Hướng dẫn tích hợp MoMo Payment Gateway

## Bước 1: Đăng ký tài khoản Merchant tại MoMo Developer Portal

1. Truy cập: https://developers.momo.vn/
2. Đăng ký/Đăng nhập bằng số điện thoại MoMo của bạn: **0977675028**
3. Tạo một ứng dụng mới (App) trong Developer Portal
4. Sau khi tạo ứng dụng, bạn sẽ nhận được:
   - **Partner Code** (Mã đối tác)
   - **Access Key** (Khóa truy cập)
   - **Secret Key** (Khóa bí mật)

## Bước 2: Cấu hình trong file .env

Mở file `.env` trong thư mục gốc của dự án và thêm các dòng sau:

```env
# MoMo Payment Configuration
MOMO_PARTNER_CODE=your_partner_code_here
MOMO_ACCESS_KEY=your_access_key_here
MOMO_SECRET_KEY=your_secret_key_here
MOMO_RETURN_URL=/payment/callback
MOMO_NOTIFY_URL=/payment/ipn

# Sử dụng endpoint production (khi đã được phê duyệt)
# MOMO_ENDPOINT=https://payment.momo.vn/v2/gateway/api/create

# Hoặc endpoint sandbox (để test)
MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create
```

## Bước 3: Thay thế thông tin

Thay thế các giá trị sau bằng thông tin thực từ MoMo Developer Portal:
- `your_partner_code_here` → Partner Code của bạn
- `your_access_key_here` → Access Key của bạn  
- `your_secret_key_here` → Secret Key của bạn

## Bước 4: Xóa cache config

Chạy lệnh sau để xóa cache config:

```bash
php artisan config:clear
php artisan cache:clear
```

## Bước 5: Kiểm tra

1. Tạo một đơn hàng test trên website
2. Chọn phương thức thanh toán MoMo
3. Kiểm tra xem có chuyển đến trang thanh toán MoMo không

## Lưu ý quan trọng:

### Môi trường Sandbox (Test):
- Endpoint: `https://test-payment.momo.vn/v2/gateway/api/create`
- Dùng để test trước khi đưa vào production
- Không cần phê duyệt từ MoMo

### Môi trường Production:
- Endpoint: `https://payment.momo.vn/v2/gateway/api/create`
- Cần đăng ký và được MoMo phê duyệt
- Chỉ dùng khi đã sẵn sàng nhận thanh toán thật

## Callback URLs:

Hệ thống đã được cấu hình sẵn các URL callback:
- **Return URL**: `https://yourdomain.com/payment/callback` - URL khách hàng quay lại sau khi thanh toán
- **IPN URL**: `https://yourdomain.com/payment/ipn` - URL MoMo gửi thông báo thanh toán

**Lưu ý**: Bạn cần cấu hình các URL này trong MoMo Developer Portal để khớp với domain của website.

## Hỗ trợ:

Nếu gặp vấn đề, vui lòng:
1. Kiểm tra lại thông tin trong file `.env`
2. Kiểm tra logs trong `storage/logs/laravel.log`
3. Liên hệ MoMo Support: https://developers.momo.vn/

