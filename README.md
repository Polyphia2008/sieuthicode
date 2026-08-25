# Siêu Thị Code — Hướng dẫn cài đặt trên cPanel

Source đi kèm **trình cài đặt web ba bước** tại `/install`. Với cài đặt mới bạn
**không cần** import SQL thủ công, không cần chạy migration chat thủ công,
không cần sửa `config.php`, và không cần chạy SQL đổi mật khẩu admin — trình
cài đặt làm toàn bộ các bước đó.

## Yêu cầu hosting

- Apache/LiteSpeed có `mod_rewrite` và cho phép `.htaccess` (`AllowOverride All`).
- PHP **8.1 hoặc 8.2** (8.4 cũng hoạt động).
- PHP extensions: `mysqli`, `curl`, `openssl`, `gd`, `mbstring`, `json`, `fileinfo`.
- MySQL/MariaDB hỗ trợ `utf8mb4`.

Không cần ionCube Loader. Các file phụ thuộc ionCube cũ đã được thay bằng PHP thường.

## Cài đặt mới (fresh install)

1. Trong cPanel, mở **MultiPHP Manager** và chọn PHP 8.1/8.2 cho tên miền.
2. Mở **Select PHP Version / PHP Extensions** và bật các extension ở phần yêu cầu.
3. Upload toàn bộ source vào đúng **Document Root** của tên miền (thường là `public_html`).
   - Phải upload cả file ẩn `.htaccess`.
   - Không đặt thêm một thư mục con nếu tên miền vẫn trỏ tới `public_html`, vì các URL trong source bắt đầu bằng `/`.
4. Trong **MySQL Databases**:
   - **Tạo database** mới.
   - **Tạo database user** mới.
   - **Thêm user vào database và chọn ALL PRIVILEGES** (cần quyền CREATE/INSERT/ALTER...).
5. Mở trang chủ tên miền — bạn sẽ được tự động chuyển sang **`/install`**.
6. Làm theo **3 bước** của trình cài đặt:
   - **Bước 1 — Kết nối database**: nhập host/port/database name/username/password
     vừa tạo ở bước 4, bấm *Kiểm tra kết nối*.
   - **Bước 2 — Tài khoản quản trị**: nhập họ tên, tên đăng nhập, email, mật khẩu
     (tối thiểu 8 ký tự). Tài khoản này thay thế tài khoản `admin` mẫu trong SQL.
   - **Bước 3 — Xác nhận & cài đặt**: kiểm tra lại thông tin (không hiển thị mật
     khẩu) rồi bấm *Cài đặt ngay*.
7. Khi thấy "Cài đặt thành công", mở `/login` và đăng nhập bằng tài khoản vừa tạo.
   Trang quản trị nằm tại `/cpanel/home`.
8. Vào trang quản trị để cấu hình liên hệ, API thẻ, ngân hàng, SMTP, Telegram,
   Google OAuth và Pusher.

Trình cài đặt tự động: import base SQL (`shoprobloxv4 (2).sql`), import migration
chat (`database/migrations/20260812_chat_box.sql`), direct-admin chat/presence
(`database/migrations/20260818_chat_direct_admin.sql`), tạo tài khoản admin, ghi
`config.local.php` (quyền 600) và tạo `storage/installed.lock`. Sau khi cài xong,
`/install` **tự khoá** — mọi truy cập vào `/install` sẽ bị chuyển về trang chủ.

### An toàn dữ liệu

- Trình cài đặt **chỉ cài vào database trống**. Nếu database đã có bảng, nó từ
  chối để bảo vệ dữ liệu hiện có (không ghi đè, không xoá).
- Nếu quá trình cài bị gián đoạn (mất mạng, timeout, server restart), lần chạy
  lại sẽ tự dọn các bảng đã tạo dở (ghi nhận trong `storage/install.journal.json`)
  rồi cài lại sạch. **Không bao giờ xoá bảng tồn tại trước khi cài.**

## Quyền file/thư mục

Khuyến nghị trên cPanel PHP-FPM/LiteSpeed:

- Thư mục: `755`; file: `644`.
- `config.local.php` (do installer tạo): `600` — chứa thông tin database, không commit, không chia sẻ.
- `storage/`: cần cho PHP ghi được (`755`, một số hosting cần `775`). Chứa `installed.lock`, mutex và journal — đã bị `.htaccess` chặn truy cập trực tiếp.
- `security/`: cần cho PHP ghi được. Hệ thống tự sinh RSA key riêng ở lần chạy đầu tiên; private key đặt quyền `600` nếu hosting hỗ trợ.
- Các thư mục con trong `upload/`: phải cho tài khoản hosting ghi được.
- Không dùng `777` trừ khi nhà cung cấp hosting bắt buộc và bạn hiểu rủi ro.

## Nâng cấp website cũ (đã cài từ bản trước)

Website cũ — đã import SQL và **sửa credential trực tiếp trong `config.php`**
theo hướng dẫn cũ — **tiếp tục hoạt động bình thường** sau khi upload source mới:

- **Không** bị redirect sang `/install` (hệ thống tự nhận diện database đã có
  schema hợp lệ và credential đã cấu hình trong `config.php`).
- **Không** đổi admin, **không** import lại SQL, **không** mất dữ liệu.
- Credential trong `config.php` được giữ nguyên, không bị ghi đè.
- Ở request đầu tiên, hệ thống tự tạo `storage/installed.lock` (sau khi xác minh
  đủ schema) để khoá installer — đây là thao tác an toàn, chỉ ghi một file marker.

### Bổ sung chat-box cho source cũ chưa có chat

Chỉ cần chạy migration chat (idempotent — chạy lại nhiều lần vẫn an toàn):

```bash
mysql DATABASE_NAME < database/migrations/20260812_chat_box.sql
mysql DATABASE_NAME < database/migrations/20260818_chat_direct_admin.sql
mysql DATABASE_NAME < database/migrations/20260819_notifications_history.sql
mysql DATABASE_NAME < database/migrations/20260825_traffic_tasks.sql
```

Migration `20260818_chat_direct_admin.sql` nâng cấp chat cũ thành hội thoại riêng
với từng admin/superadmin và thêm bảng trạng thái online. Hãy chạy file này sau
migration chat cơ bản khi cập nhật website đang hoạt động.

### Bổ sung superadmin + đăng nhập 2/7 ngày cho website cũ

Với website đã cài từ bản trước, chạy migration sau (idempotent — chạy lại hai
lần liên tiếp vẫn an toàn, không lỗi, không đổi thêm dữ liệu):

```bash
mysql DATABASE_NAME < database/migrations/20260814_superadmin_sessions.sql
```

Migration này làm ba việc:

1. **Bảo đảm luôn có một superadmin ĐANG HOẠT ĐỘNG**: nếu hệ thống không còn
   superadmin nào `banned = 0` (ví dụ superadmin duy nhất đã bị khoá), migration
   tự promote tài khoản **admin đang hoạt động có id nhỏ nhất** lên superadmin.
   Tài khoản `member`/`ctv` không bao giờ được promote. Một superadmin đã bị ban
   **không** làm migration bỏ qua mãi mãi.
2. **Bổ sung index cho bảng `auth_tokens`** (`token`, `user_id`, `expires_at`)
   phục vụ đăng nhập duy trì 2/7 ngày (DB chỉ lưu SHA-256 hash của token).
3. **Dọn token đã hết hạn** còn sót lại trong `auth_tokens`.

Có thể kiểm tra kết quả sau khi chạy:

```sql
SELECT id, username, level, banned FROM users WHERE level = 'superadmin';
SHOW INDEX FROM auth_tokens;
```

> **Lưu ý:** sao lưu database trước khi chạy migration (xem mục Sao lưu bên
> dưới). Migration không chứa credential nào và không cần chỉnh sửa file.

### Chuyển từ `config.php` sang `config.local.php` (tuỳ chọn)

Không bắt buộc. Nếu muốn tách credential khỏi `config.php` (để cập nhật source
sau này không ghi đè), tạo file `config.local.php` ở thư mục gốc với nội dung:

```php
<?php
return [
    'db' => [
        'host' => 'localhost',
        'port' => 3306,
        'username' => 'TENCPANEL_databaseuser',
        'password' => 'MAT_KHAU_DATABASE',
        'database' => 'TENCPANEL_databasename',
    ],
];
```

Đặt quyền `600` cho file này. Khi cả hai cùng tồn tại, `config.local.php` được
ưu tiên, sau đó tới biến môi trường `DB_*`, cuối cùng là `config.php`.

## Sao lưu (backup)

Trước khi nâng cấp source hoặc thay đổi lớn:

1. **Database**: cPanel → **Backup** → *Download a MySQL Database Backup*, hoặc
   phpMyAdmin → *Export* (chọn Quick, đủ dùng cho đa số trường hợp).
2. **File**: tải về toàn bộ Document Root, hoặc cPanel → **Backup** →
   *Download a Full Website Backup*. Tối thiểu giữ lại:
   - `config.php` và/hoặc `config.local.php` (credential database),
   - `security/` (RSA key — mất key sẽ mất khả năng giải mã dữ liệu đã mã hoá),
   - `upload/` (ảnh, file đính kèm chat),
   - `storage/installed.lock` (không bắt buộc; nếu thiếu, hệ thống tự tạo lại khi
     database hợp lệ).

## Xử lý lỗi thường gặp (troubleshooting)

### HTTP 500 ngay khi mở trang

- Kiểm tra file `.htaccess` đã được upload.
- Chọn PHP 8.1/8.2; bật `mysqli`, `curl`, `openssl`, `gd`.
- Mở `public_html/error_log` hoặc **Metrics → Errors** trong cPanel.

### Trang chủ mở được nhưng URL khác bị 404

- `.htaccess` bị thiếu hoặc `mod_rewrite` chưa bật / vhost chưa `AllowOverride All`.
- Source không nằm đúng Document Root của tên miền.

### Báo không kết nối được cơ sở dữ liệu

- Tên database/user trên cPanel thường có **tiền tố tài khoản** (vd: `user_shop`).
- Phải thêm database user vào database với **ALL PRIVILEGES** — thiếu quyền
  CREATE/INSERT/ALTER sẽ khiến bước kiểm tra của installer báo lỗi.
- DB host đa số là `localhost`; dùng giá trị nhà cung cấp đưa ra nếu khác.
- Nếu website **đang chạy** bỗng hiện "Không thể kết nối cơ sở dữ liệu": đây là
  lỗi database tạm thời, **không phải** lỗi chưa cài đặt — trình cài đặt sẽ không
  tự mở lại. Kiểm tra dịch vụ MySQL và credential.

### Trình cài đặt báo "Database này đã có dữ liệu"

- Đây là cơ chế bảo vệ: installer không ghi đè database không trống. Nếu đây là
  website cũ, giữ nguyên cấu hình hiện tại (không cài lại). Nếu muốn cài mới hoàn
  toàn, hãy tạo một database trống khác.

### Import SQL bị timeout / cài đặt dừng giữa chừng

- File SQL gốc khá lớn; trên hosting yếu việc import có thể vượt giới hạn thời
  gian PHP. Nếu bị gián đoạn, **cứ mở lại `/install` và chạy lại** — journal trong
  `storage/` sẽ dọn các bảng cài dở rồi cài lại sạch, không làm hỏng dữ liệu cũ.
- Nếu lỗi lặp lại: tăng `max_execution_time` trong **MultiPHP INI Editor** (vd 300),
  và/hoặc nhờ nhà cung cấp tăng giới hạn; sau đó chạy lại installer.

### Không upload được hình ảnh

- Kiểm tra các thư mục trong `upload/` tồn tại và có quyền ghi.
- Kiểm tra `upload_max_filesize` và `post_max_size` trong MultiPHP INI Editor.

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

## Chat hỗ trợ (chat-box)

Tính năng chat CSKH hai phía giữa thành viên và quản trị viên: thành viên chat tại `/chat-box`, admin quản lý hòm thư tại `/cpanel/chat-box`.

- **Fresh install**: trình cài đặt đã import migration chat tự động — không cần thao tác thêm.
- **Nâng cấp từ source cũ**: chỉ cần chạy migration chat như mục nâng cấp ở trên.
- **Kiến trúc realtime**: AJAX polling thuần (tin nhắn mỗi ~4 giây, badge chưa đọc mỗi ~10 giây) — tương thích hosting chia sẻ/cPanel. **Không cần WebSocket, NodeJS hay Redis.**

### Bảo mật file đính kèm

- Thư mục `upload/chat/` bị **chặn truy cập trực tiếp** bằng `upload/chat/.htaccess` (`Require all denied`) — mở URL file trực tiếp sẽ nhận HTTP 403.
- File đính kèm chỉ được tải qua endpoint có xác thực `/model/chat/attachment?message_id=N`: chủ hội thoại hoặc admin mới nhận 200, thành viên khác 403, khách 401. Đường dẫn file lấy từ DB (không nhận từ query string), kiểm tra basename + realpath containment + MIME whitelist trước khi stream.
- **Không commit file runtime trong `upload/chat/`** (ảnh người dùng upload). `.gitignore` đã loại trừ; chỉ giữ `.gitkeep` và `.htaccess` được track.

## Lưu ý an toàn trước khi công khai

SQL cài đặt đã được loại bỏ log vận hành, giao dịch, token, thông tin người dùng thật và các khóa API đã xuất. Không đưa database đang hoạt động, `config.local.php`, `storage/installed.lock`, file RSA sinh trên hosting hoặc cookie/token phiên trở lại repository công khai.

## Cron xóa dữ liệu tài khoản đã mua sau 2 tuần

Website đang hoạt động nên cấu hình cron cPanel chạy mỗi ngày một lần:

```bash
0 3 * * * /usr/local/bin/php /home/CPANEL_USER/public_html/models/cron/purchase-history.php
```

Thay `CPANEL_USER` và đường dẫn PHP theo hosting. Script chỉ chạy ở CLI, xóa lịch sử
quá 14 ngày, review liên quan và bản ghi account chứa credential. Khách hàng được
cảnh báo và có nút tải toàn bộ credential thành file TXT trước khi dữ liệu hết hạn.

## Nhiệm vụ Traffic

- User: `/kiem-tien-online`
- Admin duyệt/tạo nhiệm vụ: `/cpanel/traffic`
- Token Link4m/LAYMA/Link2m chỉ nhập trong panel superadmin và được mã hóa trong DB.
- Không hardcode token API vào source public. Token từng dán vào chat/log phải được thu hồi và tạo mới.
- API rút gọn không cung cấp callback hoàn thành nên phần thưởng dùng quy trình admin duyệt minh chứng thủ công.
