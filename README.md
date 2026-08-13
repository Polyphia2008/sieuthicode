# Hướng dẫn cài đặt source trên cPanel

## Yêu cầu hosting

- Apache/LiteSpeed có `mod_rewrite` và cho phép `.htaccess`.
- PHP **8.1 hoặc 8.2**.
- PHP extensions: `mysqli`, `curl`, `openssl`, `gd`, `mbstring`, `json`, `fileinfo`.
- MySQL/MariaDB hỗ trợ `utf8mb4`.

Không cần ionCube Loader. Các file phụ thuộc ionCube cũ đã được thay bằng PHP thường.

## Cài đặt

1. Trong cPanel, mở **MultiPHP Manager** và chọn PHP 8.1 hoặc 8.2 cho tên miền.
2. Mở **Select PHP Version / PHP Extensions** và bật các extension ở phần yêu cầu.
3. Upload toàn bộ source vào đúng **Document Root** của tên miền, thường là `public_html`.
   - Phải upload cả file ẩn `.htaccess`.
   - Không đặt thêm một thư mục con nếu tên miền vẫn trỏ tới `public_html`, vì các URL trong source bắt đầu bằng `/`.
4. Trong **MySQL Databases**:
   - Tạo database.
   - Tạo database user.
   - Thêm user vào database và chọn **ALL PRIVILEGES**.
5. Import file `shoprobloxv4 (2).sql` bằng phpMyAdmin.
6. Sửa `config.php`:

```php
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USERNAME', 'TENCPANEL_databaseuser');
define('DB_DATABASE', 'TENCPANEL_databasename');
define('DB_PASSWORD', 'MAT_KHAU_DATABASE');
```

7. Bản SQL công khai chỉ tạo tài khoản quản trị `admin` ở trạng thái chưa có mật khẩu. Trong phpMyAdmin, chọn database, mở tab **SQL** và chạy lệnh sau sau khi thay mật khẩu/email:

```sql
UPDATE `users`
SET `password` = SHA1('MAT_KHAU_ADMIN_MOI'),
    `email` = 'email-cua-ban@example.com',
    `token` = MD5(CONCAT(UUID(), RAND()))
WHERE `username` = 'admin';
```

8. Mở `/login`, đăng nhập bằng username `admin` và mật khẩu vừa đặt. Trang quản trị nằm tại `/cpanel/home`.
9. Vào trang quản trị để thay toàn bộ cấu hình liên hệ, API thẻ, ngân hàng, SMTP, Telegram, Google OAuth và Pusher.

## Quyền file/thư mục

Khuyến nghị trên cPanel PHP-FPM/LiteSpeed:

- Thư mục: `755`.
- File: `644`.
- Các thư mục con trong `upload/`: phải cho tài khoản hosting ghi được; thường `755`, một số hosting cần `775`.
- Thư mục `security/`: `755`. Hệ thống tự sinh RSA key riêng ở lần chạy đầu tiên; private key được đặt quyền `600` nếu hosting hỗ trợ.
- Không dùng `777` trừ khi nhà cung cấp hosting bắt buộc và bạn hiểu rủi ro.

## Google OAuth

Trong Google Cloud Console, cấu hình Authorized redirect URI chính xác:

```text
https://ten-mien-cua-ban.com/login/google
```

Sau đó nhập Client ID và Client Secret trong trang quản trị. Nếu chưa cấu hình, hãy tắt đăng nhập Google.

## Composer/vendor

Repository hiện có thư mục `vendor`, vì vậy có thể chạy ngay sau khi upload. Nếu bạn không upload `vendor`, chạy tại cPanel Terminal:

```bash
composer install --no-dev --optimize-autoloader
```

## Xử lý lỗi thường gặp

### HTTP 500 ngay khi mở trang

- Kiểm tra file `.htaccess` đã được upload.
- Chọn PHP 8.1/8.2.
- Bật `mysqli`, `curl`, `openssl`, `gd`.
- Mở `public_html/error_log` hoặc **Metrics → Errors** trong cPanel.
- Kiểm tra `config.php` và quyền database user.

### Trang chủ mở được nhưng URL khác bị 404

- `.htaccess` bị thiếu hoặc `mod_rewrite` chưa bật.
- Source không nằm đúng Document Root của tên miền.

### Báo không kết nối được cơ sở dữ liệu

- Tên database/user trên cPanel thường có tiền tố tài khoản.
- Phải thêm database user vào database với ALL PRIVILEGES.
- DB host đa số là `localhost`; dùng giá trị nhà cung cấp hosting đưa ra nếu khác.

### Không upload được hình ảnh

- Kiểm tra các thư mục trong `upload/` tồn tại và có quyền ghi.
- Kiểm tra giới hạn `upload_max_filesize` và `post_max_size` trong MultiPHP INI Editor.

## Lưu ý an toàn trước khi công khai

SQL cài đặt đã được loại bỏ log vận hành, giao dịch, token, thông tin người dùng thật và các khóa API đã xuất. Không đưa database đang hoạt động hoặc file RSA sinh trên hosting trở lại repository công khai.

## Chat hỗ trợ (chat-box)

Tính năng chat CSKH hai phía giữa thành viên và quản trị viên: thành viên chat tại `/chat-box`, admin quản lý hòm thư tại `/cpanel/chat-box`.

### Cài đặt mới (fresh install)

1. Import base SQL (`shoprobloxv4 (2).sql`) như hướng dẫn ở trên.
2. Import migration chat:

```bash
mysql DATABASE_NAME < database/migrations/20260812_chat_box.sql
```

### Nâng cấp (source cũ chưa có chat)

Chỉ cần chạy migration chat:

```bash
mysql DATABASE_NAME < database/migrations/20260812_chat_box.sql
```

Migration dùng `CREATE TABLE IF NOT EXISTS` nên **chạy lại nhiều lần vẫn an toàn** (idempotent), không xóa hay ghi đè dữ liệu chat hiện có.

### Yêu cầu server

- Apache có `mod_rewrite` bật và vhost đặt `AllowOverride All` (để `.htaccess` gốc và `upload/chat/.htaccess` có hiệu lực).
- PHP extensions: `mysqli`, `mbstring`, `curl`, `gd`, `fileinfo` (ngoài các extension ở phần yêu cầu chung).
- Thư mục `security/` và `upload/chat/` phải cho PHP/web server ghi được (`755`, một số hosting cần `775`; chạy bằng user web như www-data thì chown cho user đó).

### Bảo mật file đính kèm

- Thư mục `upload/chat/` bị **chặn truy cập trực tiếp** bằng `upload/chat/.htaccess` (`Require all denied`) — mở URL file trực tiếp sẽ nhận HTTP 403.
- File đính kèm chỉ được tải qua endpoint có xác thực `/model/chat/attachment?message_id=N`: chủ hội thoại hoặc admin mới nhận 200, thành viên khác 403, khách 401. Đường dẫn file lấy từ DB (không nhận từ query string), kiểm tra basename + realpath containment + MIME whitelist trước khi stream.
- **Không commit file runtime trong `upload/chat/`** (ảnh người dùng upload). `.gitignore` đã loại trừ; chỉ giữ `.gitkeep` và `.htaccess` được track.

### Kiến trúc realtime

- Dùng AJAX polling thuần (tin nhắn mỗi ~4 giây, badge chưa đọc mỗi ~10 giây) — tương thích hosting chia sẻ/cPanel.
- **Không cần WebSocket, NodeJS hay Redis.**
