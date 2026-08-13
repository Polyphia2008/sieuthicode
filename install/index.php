<?php
/**
 * Trình cài đặt Siêu Thị Code — wizard 3 bước.
 *
 * Trang này chỉ render form; mọi hành động thay đổi trạng thái đi qua
 * install/api.php (POST + CSRF + rate limit + mutex). Không in password,
 * không hotlink CDN. CSS/JS nằm ở assets/css/installer.css và
 * assets/js/installer.js.
 */

require_once __DIR__ . '/bootstrap.php';

installer_guard_not_installed(false);

$csrf = installer_csrf_token();
$csrfJson = json_encode($csrf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Cài đặt Siêu Thị Code</title>
<link rel="stylesheet" href="/assets/css/installer.css">
</head>
<body data-csrf="<?php echo installer_e($csrf); ?>" data-api="/install/api.php">
<div class="stc-shell">

  <!-- Panel trái (tối): lời chào + minh hoạ local -->
  <aside class="stc-side" aria-label="Giới thiệu">
    <div class="stc-brand">
      <span class="stc-brand__logo" aria-hidden="true">
        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M3 7.5A2.5 2.5 0 0 1 5.5 5h13A2.5 2.5 0 0 1 21 7.5v9a2.5 2.5 0 0 1-2.5 2.5h-13A2.5 2.5 0 0 1 3 16.5v-9Z" fill="#fff" opacity=".92"/>
          <path d="M7 10h10M7 13.5h6" stroke="#3556d4" stroke-width="1.8" stroke-linecap="round"/>
        </svg>
      </span>
      <span>
        <span class="stc-brand__name">Siêu Thị Code</span><br>
        <span class="stc-brand__tag">Trình cài đặt website</span>
      </span>
    </div>

    <div class="stc-side__hello">
      <h1>Chào mừng bạn đến với Siêu&nbsp;Thị&nbsp;Code</h1>
      <p>Trình cài đặt sẽ thiết lập cơ sở dữ liệu và tài khoản quản trị trong
         3 bước đơn giản. Mọi thao tác đều an toàn và có thể thử lại nếu bị gián đoạn.</p>
    </div>

    <div class="stc-art" aria-hidden="true">
      <svg viewBox="0 0 300 180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Minh hoạ cài đặt">
        <defs>
          <linearGradient id="g1" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#4f7cff"/><stop offset="1" stop-color="#6b93ff"/>
          </linearGradient>
          <linearGradient id="g2" x1="0" y1="0" x2="1" y2="1">
            <stop offset="0" stop-color="#22304a"/><stop offset="1" stop-color="#101828"/>
          </linearGradient>
        </defs>
        <rect x="20" y="30" width="260" height="130" rx="14" fill="url(#g2)" stroke="#2a3a5f"/>
        <rect x="38" y="50" width="120" height="12" rx="6" fill="url(#g1)"/>
        <rect x="38" y="72" width="180" height="8" rx="4" fill="#2a3a5f"/>
        <rect x="38" y="88" width="150" height="8" rx="4" fill="#2a3a5f"/>
        <rect x="38" y="104" width="170" height="8" rx="4" fill="#2a3a5f"/>
        <circle cx="240" cy="120" r="22" fill="none" stroke="#2fbf71" stroke-width="3"/>
        <path d="M232 120l6 6 12-12" stroke="#2fbf71" stroke-width="3" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
        <circle cx="60" cy="140" r="5" fill="#4f7cff"/>
        <circle cx="82" cy="140" r="5" fill="#2a3a5f"/>
        <circle cx="104" cy="140" r="5" fill="#2a3a5f"/>
      </svg>
    </div>

    <p class="stc-side__foot">&copy; Siêu Thị Code — an toàn, không gửi dữ liệu ra ngoài.</p>
  </aside>

  <!-- Panel phải (sáng): form cài đặt -->
  <main class="stc-main">
    <div class="stc-card">
      <h1 class="stc-card__title">Cài đặt website</h1>
      <p class="stc-card__sub">Hoàn thành 3 bước bên dưới để đưa website vào hoạt động.</p>

      <!-- Progress -->
      <div class="stc-progress">
        <div class="stc-progress__meta">
          <span class="stc-progress__label" id="progress-label">Kết nối database</span>
          <span class="stc-progress__count" id="progress-count">Bước 1/3</span>
        </div>
        <div class="stc-progress__bar" id="progress-bar" role="progressbar"
             aria-valuemin="1" aria-valuemax="3" aria-valuenow="1" aria-label="Tiến trình cài đặt">
          <div class="stc-progress__fill" id="progress-fill"></div>
        </div>
      </div>

      <!-- Bước 1: Database -->
      <section class="stc-step is-on" id="step-1" aria-labelledby="t1">
        <h2 class="stc-step__title" id="t1" tabindex="-1">Kết nối database</h2>
        <p class="stc-step__desc">Nhập thông tin MySQL được tạo trong cPanel của bạn.</p>

        <div class="stc-help">
          <strong>Hướng dẫn trong cPanel &rarr; MySQL Databases:</strong>
          <ol>
            <li>Tạo một <strong>database</strong> mới.</li>
            <li>Tạo một <strong>database user</strong> mới.</li>
            <li>Thêm user vào database và chọn <strong>ALL PRIVILEGES</strong>.</li>
          </ol>
        </div>

        <form id="form-db" autocomplete="off" novalidate>
          <div class="stc-grid2">
            <div class="stc-field">
              <label for="db_host">Database host</label>
              <input id="db_host" name="db_host" value="localhost" required>
            </div>
            <div class="stc-field">
              <label for="db_port">Port</label>
              <input id="db_port" name="db_port" type="number" value="3306" required>
            </div>
          </div>
          <div class="stc-field">
            <label for="db_name">Database name</label>
            <input id="db_name" name="db_name" required>
          </div>
          <div class="stc-field">
            <label for="db_user">Database username</label>
            <input id="db_user" name="db_user" required>
          </div>
          <div class="stc-field">
            <label for="db_pass">Database password</label>
            <input id="db_pass" name="db_pass" type="password">
          </div>
          <button class="stc-btn" type="submit">Kiểm tra kết nối</button>
          <div class="stc-msg" id="msg-1" role="status" aria-live="polite"></div>
        </form>
      </section>

      <!-- Bước 2: Tài khoản quản trị -->
      <section class="stc-step" id="step-2" aria-labelledby="t2">
        <h2 class="stc-step__title" id="t2" tabindex="-1">Tài khoản quản trị</h2>
        <p class="stc-step__desc">Tài khoản này có toàn quyền quản trị website.</p>

        <form id="form-admin" autocomplete="off" novalidate>
          <div class="stc-field">
            <label for="admin_name">Họ và tên</label>
            <input id="admin_name" name="admin_name" required>
          </div>
          <div class="stc-field">
            <label for="admin_username">Tên đăng nhập</label>
            <input id="admin_username" name="admin_username" required>
          </div>
          <div class="stc-field">
            <label for="admin_email">Email</label>
            <input id="admin_email" name="admin_email" type="email" required>
          </div>
          <div class="stc-field">
            <label for="admin_password">Mật khẩu (tối thiểu 8 ký tự)</label>
            <input id="admin_password" name="admin_password" type="password" required>
          </div>
          <div class="stc-field">
            <label for="admin_password_confirm">Xác nhận mật khẩu</label>
            <input id="admin_password_confirm" name="admin_password_confirm" type="password" required>
          </div>
          <button class="stc-btn" type="submit">Lưu thông tin</button>
          <button class="stc-btn stc-btn--ghost" type="button" id="back-2">Quay lại</button>
          <div class="stc-msg" id="msg-2" role="status" aria-live="polite"></div>
        </form>
      </section>

      <!-- Bước 3: Xác nhận & cài đặt -->
      <section class="stc-step" id="step-3" aria-labelledby="t3">
        <h2 class="stc-step__title" id="t3" tabindex="-1">Xác nhận &amp; cài đặt</h2>
        <p class="stc-step__desc">Kiểm tra lại thông tin rồi bấm "Cài đặt ngay".</p>

        <div class="stc-sum" id="summary" aria-label="Tóm tắt cấu hình"></div>

        <button class="stc-btn" id="do-install" type="button">Cài đặt ngay</button>
        <button class="stc-btn stc-btn--ghost" type="button" id="back-3">Quay lại</button>
        <div class="stc-msg" id="msg-3" role="status" aria-live="polite"></div>
      </section>

      <!-- Hoàn tất -->
      <section class="stc-step" id="step-done" aria-labelledby="td">
        <div class="stc-done">
          <div class="stc-done__badge" aria-hidden="true">&#10003;</div>
          <h1 class="stc-step__title" id="td" tabindex="-1">Cài đặt thành công</h1>
          <p class="stc-step__desc">Website của bạn đã sẵn sàng. Trình cài đặt đã tự khoá để bảo mật.</p>
          <a class="stc-btn" href="/login">Đăng nhập quản trị</a>
        </div>
      </section>
    </div>
  </main>
</div>

<script src="/assets/js/installer.js" defer></script>
</body>
</html>
