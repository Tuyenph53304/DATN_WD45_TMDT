# Khắc phục lỗi "QR hết hạn hoặc không tồn tại" - MoMo Demo

## Vấn đề
Khi sử dụng thông tin demo của MoMo, bạn có thể gặp lỗi:
- "QR hết hạn hoặc không tồn tại"
- "Giao dịch hết hạn"
- "Thông tin không hợp lệ"

## Nguyên nhân
Thông tin demo (sandbox) của MoMo có thể:
1. Đã hết hạn sau một thời gian
2. Không còn được hỗ trợ
3. Cần đăng ký tài khoản merchant thật để sử dụng

## Giải pháp

### Giải pháp 1: Đăng ký tài khoản MoMo Merchant (Khuyến nghị)

1. **Truy cập MoMo Developer Portal**
   - Website: https://developers.momo.vn/
   - Đăng nhập bằng số điện thoại MoMo: **0977675028**

2. **Tạo ứng dụng mới**
   - Vào mục "Ứng dụng" → "Tạo ứng dụng mới"
   - Điền thông tin ứng dụng
   - Chọn môi trường: **Sandbox** (để test) hoặc **Production** (để sử dụng thật)

3. **Lấy thông tin API**
   Sau khi tạo ứng dụng, bạn sẽ nhận được:
   - **Partner Code** (Mã đối tác)
   - **Access Key** (Khóa truy cập)
   - **Secret Key** (Khóa bí mật)

4. **Cấu hình trong file .env**
   ```env
   MOMO_PARTNER_CODE=your_partner_code_here
   MOMO_ACCESS_KEY=your_access_key_here
   MOMO_SECRET_KEY=your_secret_key_here
   MOMO_ENDPOINT=https://test-payment.momo.vn/v2/gateway/api/create
   ```

5. **Xóa cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Giải pháp 2: Sử dụng tạm thời phương thức COD

Nếu chưa thể đăng ký MoMo ngay, bạn có thể:
1. Tạm thời sử dụng phương thức **Thanh toán khi nhận hàng (COD)**
2. Sau khi có thông tin MoMo, cấu hình lại

### Giải pháp 3: Kiểm tra logs để debug

1. Bật chế độ debug trong `.env`:
   ```env
   APP_DEBUG=true
   ```

2. Kiểm tra logs trong `storage/logs/laravel.log` để xem chi tiết lỗi:
   ```bash
   tail -f storage/logs/laravel.log
   ```

3. Xem thông tin request/response từ MoMo trong logs

## Các mã lỗi phổ biến

- **1001**: Giao dịch bị từ chối - Vui lòng thử lại hoặc liên hệ hỗ trợ
- **1002**: Giao dịch đang được xử lý - Vui lòng đợi và kiểm tra lại sau
- **1003**: Giao dịch bị hủy - Vui lòng thử lại
- **1004**: Giao dịch không thành công - Vui lòng kiểm tra lại thông tin thanh toán
- **1005**: Giao dịch hết hạn (QR hết hạn) ⚠️ **LỖI PHỔ BIẾN**
  - Nguyên nhân: QR code hoặc thông tin demo đã hết hạn
  - Giải pháp: Đăng ký tài khoản MoMo Merchant hoặc thử lại sau vài phút
- **1006**: Giao dịch thất bại - Vui lòng thử lại hoặc chọn phương thức thanh toán khác
- **1007**: Thông tin không hợp lệ - Vui lòng kiểm tra lại cấu hình MoMo trong file .env

## Xử lý mã lỗi 1005 (Giao dịch hết hạn)

Mã lỗi **1005** là lỗi phổ biến nhất khi sử dụng MoMo demo. Hệ thống đã được cấu hình để:
1. ✅ Tự động nhận diện mã lỗi 1005
2. ✅ Hiển thị thông báo rõ ràng với hướng dẫn khắc phục
3. ✅ Log chi tiết để debug
4. ✅ Gợi ý giải pháp cụ thể

## Lưu ý quan trọng

1. **Sandbox vs Production**
   - **Sandbox**: Dùng để test, không cần phê duyệt, nhưng có thể hết hạn
   - **Production**: Cần đăng ký và được MoMo phê duyệt, ổn định hơn

2. **Callback URLs**
   - Đảm bảo cấu hình đúng Return URL và IPN URL trong MoMo Developer Portal
   - URLs phải là HTTPS và accessible từ internet

3. **Thời gian hiệu lực QR**
   - QR code có thời gian hiệu lực ngắn (thường 5-15 phút)
   - Nếu QR hết hạn, cần tạo đơn hàng mới

## Hỗ trợ

Nếu vẫn gặp vấn đề:
1. Kiểm tra lại thông tin trong file `.env`
2. Xem logs chi tiết trong `storage/logs/laravel.log`
3. Liên hệ MoMo Support: https://developers.momo.vn/
4. Kiểm tra tài liệu API: https://developers.momo.vn/docs

