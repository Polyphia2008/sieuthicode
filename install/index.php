<?php
/**
 * Trình cài đặt Siêu Thị Code — wizard 3 bước.
 *
 * Trang này chỉ render form; mọi hành động thay đổi trạng thái đi qua
 * install/api.php (POST + CSRF + rate limit + mutex). Không in password.
 *
 * Giao diện dùng stylesheet/JS gốc của dự án (assets/css/installer.css,
 * assets/js/installer.js) — không sao chép asset của bên thứ ba, không
 * hotlink CDN, icon dùng inline SVG tự vẽ.
 */

require_once __DIR__ . '/bootstrap.php';

installer_guard_not_installed(false);

$csrf = installer_csrf_token();
$csrfJson = json_encode($csrf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);

// Cache-busting theo filemtime (không dùng rand()/time()).
$assetV = function ($path) {
    $f = APP_ROOT . $path;
    return is_file($f) ? (string) filemtime($f) : '1';
};

// Helper render icon inline SVG (stroke-based, theo currentColor).
$ico = function ($name) {
    $paths = [
        'db'      => '<ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v6c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/><path d="M4 11v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/>',
        'right'   => '<path d="M5 12h14M13 6l6 6-6 6"/>',
        'left'    => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
        'settings'=> '<circle cx="12" cy="12" r="3"/><path d="M19 12a7 7 0 0 0-.1-1.2l2-1.6-2-3.4-2.4 1a7 7 0 0 0-2-1.2L14 3h-4l-.4 2.6a7 7 0 0 0-2 1.2l-2.5-1-2 3.4 2 1.6A7 7 0 0 0 5 12c0 .4 0 .8.1 1.2l-2 1.6 2 3.4 2.4-1a7 7 0 0 0 2 1.2L10 21h4l.4-2.6a7 7 0 0 0 2-1.2l2.5 1 2-3.4-2-1.6c.1-.4.1-.8.1-1.2z"/>',
        'login'   => '<path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><path d="M10 17l5-5-5-5"/><path d="M15 12H3"/>',
        'info'    => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
    ];
    $d = isset($paths[$name]) ? $paths[$name] : '';
    return '<svg class="ins-ic" viewBox="0 0 24 24" aria-hidden="true" focusable="false">'
        . $d . '</svg>';
};
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Cài đặt Siêu Thị Code</title>
<link rel="stylesheet" href="/assets/css/installer.css?v=<?php echo $assetV('/assets/css/installer.css'); ?>">
</head>
<body class="ins-body" data-csrf="<?php echo installer_e($csrf); ?>" data-api="/install/api.php">

<div class="ins-root">

    <!-- Panel trái (tối): lời chào + minh hoạ inline SVG -->
    <aside class="ins-pane ins-pane-aside">
        <div class="text-block">
            <!-- Minh hoạ cài đặt: inline SVG gốc của dự án (không dùng ảnh ngoài). -->
            <svg class="ins-hero" viewBox="0 0 220 160" role="img" aria-label="Minh hoạ trình cài đặt">
                <defs>
                    <linearGradient id="insG1" x1="0" y1="0" x2="1" y2="1">
                        <stop offset="0" stop-color="#f2556f"/><stop offset="1" stop-color="#d92c4f"/>
                    </linearGradient>
                </defs>
                <rect x="30" y="24" width="160" height="104" rx="10" fill="#ffffff" opacity="0.08"/>
                <rect x="42" y="38" width="136" height="12" rx="6" fill="#ffffff" opacity="0.25"/>
                <rect x="42" y="58" width="92" height="8" rx="4" fill="#ffffff" opacity="0.18"/>
                <rect x="42" y="72" width="112" height="8" rx="4" fill="#ffffff" opacity="0.14"/>
                <circle cx="60" cy="104" r="9" fill="url(#insG1)"/>
                <circle cx="110" cy="104" r="9" fill="#ffffff" opacity="0.35"/>
                <circle cx="160" cy="104" r="9" fill="#ffffff" opacity="0.35"/>
                <path d="M69 104h32M119 104h32" stroke="#ffffff" stroke-opacity="0.4" stroke-width="3" stroke-linecap="round"/>
                <circle cx="60" cy="104" r="4" fill="#fff"/>
                <path d="M100 138h20" stroke="url(#insG1)" stroke-width="4" stroke-linecap="round"/>
            </svg>
            <h3 class="text-white">Chào mừng bạn đến với <b>Siêu&nbsp;Thị&nbsp;Code</b></h3>
            <p>Trình cài đặt sẽ thiết lập cơ sở dữ liệu và tài khoản quản trị trong
               3 bước đơn giản. Mọi thao tác đều an toàn và có thể thử lại nếu bị gián đoạn.</p>
        </div>
        <p class="ins-tagline">&copy; Siêu Thị Code — an toàn, không gửi dữ liệu ra ngoài.</p>
    </aside>

    <!-- Panel phải (sáng): form cài đặt -->
    <main class="ins-pane ins-pane-main">
        <div class="ins-pane-inner">

            <!-- Progress -->
            <div id="progress-wrap">
                <div class="ins-progress-meta">
                    <span id="progress-label">Kết nối database</span>
                    <span id="progress-count">Bước 1/3</span>
                </div>
                <div class="ins-progress" id="progress-bar" role="progressbar"
                     aria-valuemin="1" aria-valuemax="3" aria-valuenow="1" aria-label="Tiến trình cài đặt">
                    <div class="ins-progress-fill" id="progress-fill"></div>
                </div>
            </div>

            <!-- Bước 1: Database -->
            <section class="ins-step active" id="step-1" aria-labelledby="t1">
                <h5 class="title mb-3" id="t1" tabindex="-1">Kết nối database</h5>
                <p class="mb-3">Nhập thông tin MySQL được tạo trong cPanel của bạn.</p>

                <div class="alert alert-primary mb-4">
                    <strong>Hướng dẫn trong cPanel &rarr; MySQL Databases:</strong>
                    <ol>
                        <li>Tạo một <strong>database</strong> mới.</li>
                        <li>Tạo một <strong>database user</strong> mới.</li>
                        <li>Thêm user vào database và chọn <strong>ALL PRIVILEGES</strong>.</li>
                    </ol>
                </div>

                <form id="form-db" autocomplete="off" novalidate>
                    <div class="ins-grid">
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="db_host">Database host (<b class="text-danger">*</b>)</label>
                                <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                            </div>
                        </div>
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="db_port">Port (<b class="text-danger">*</b>)</label>
                                <input type="number" class="form-control" id="db_port" name="db_port" value="3306" required>
                            </div>
                        </div>
                        <div class="ins-col">
                            <div class="form-group">
                                <label class="form-label" for="db_name">Database name (<b class="text-danger">*</b>)</label>
                                <input type="text" class="form-control" id="db_name" name="db_name" required>
                            </div>
                        </div>
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="db_user">Database username (<b class="text-danger">*</b>)</label>
                                <input type="text" class="form-control" id="db_user" name="db_user" required>
                            </div>
                        </div>
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="db_pass">Database password</label>
                                <input type="password" class="form-control" id="db_pass" name="db_pass">
                            </div>
                        </div>
                    </div>
                    <div class="pt-4">
                        <button class="btn btn-primary" type="submit">Kiểm tra kết nối&ensp;<?php echo $ico('db'); ?></button>
                    </div>
                    <div class="ins-msg" id="msg-1" role="status" aria-live="polite"></div>
                </form>
            </section>

            <!-- Bước 2: Tài khoản quản trị -->
            <section class="ins-step" id="step-2" aria-labelledby="t2">
                <h5 class="title mb-3" id="t2" tabindex="-1">Tài khoản quản trị</h5>
                <p class="mb-3">Tài khoản này có toàn quyền quản trị website.</p>

                <form id="form-admin" autocomplete="off" novalidate>
                    <div class="ins-grid">
                        <div class="ins-col">
                            <div class="form-group">
                                <label class="form-label" for="admin_name">Họ và tên (<b class="text-danger">*</b>)</label>
                                <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                            </div>
                        </div>
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="admin_username">Tên đăng nhập (<b class="text-danger">*</b>)</label>
                                <input type="text" class="form-control" id="admin_username" name="admin_username" required>
                            </div>
                        </div>
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="admin_email">Email (<b class="text-danger">*</b>)</label>
                                <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                            </div>
                        </div>
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="admin_password">Mật khẩu (tối thiểu 8 ký tự) (<b class="text-danger">*</b>)</label>
                                <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                            </div>
                        </div>
                        <div class="ins-col ins-col-6">
                            <div class="form-group">
                                <label class="form-label" for="admin_password_confirm">Xác nhận mật khẩu (<b class="text-danger">*</b>)</label>
                                <input type="password" class="form-control" id="admin_password_confirm" name="admin_password_confirm" required>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4 d-flex gap-2">
                        <button class="btn btn-primary" type="submit">Lưu thông tin&ensp;<?php echo $ico('right'); ?></button>
                        <button class="btn btn-dim" type="button" id="back-2"><?php echo $ico('left'); ?>&ensp;Quay lại</button>
                    </div>
                    <div class="ins-msg" id="msg-2" role="status" aria-live="polite"></div>
                </form>
            </section>

            <!-- Bước 3: Xác nhận & cài đặt -->
            <section class="ins-step" id="step-3" aria-labelledby="t3">
                <h5 class="title mb-3" id="t3" tabindex="-1">Xác nhận &amp; cài đặt</h5>
                <p class="mb-3">Kiểm tra lại thông tin rồi bấm "Cài đặt ngay".</p>

                <div class="ins-card mb-4">
                    <div class="ins-card-inner">
                        <div id="summary" aria-label="Tóm tắt cấu hình"></div>
                    </div>
                </div>

                <div class="pt-2 d-flex gap-2">
                    <button class="btn btn-primary text-nowrap" id="do-install" type="button">Cài đặt ngay&ensp;<?php echo $ico('settings'); ?></button>
                    <button class="btn btn-dim" type="button" id="back-3"><?php echo $ico('left'); ?>&ensp;Quay lại</button>
                </div>
                <div class="ins-msg" id="msg-3" role="status" aria-live="polite"></div>
            </section>

            <!-- Hoàn tất -->
            <section class="ins-step" id="step-done" aria-labelledby="td">
                <div class="pt-4 pb-2 text-center">
                    <span class="ins-done-icon">
                        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false"
                             fill="none" stroke="currentColor" stroke-width="2.5"
                             stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 6 9 17l-5-5"/>
                        </svg>
                    </span>
                    <h5 class="title mb-2" id="td" tabindex="-1">Cài đặt thành công</h5>
                    <p class="mb-4">Website của bạn đã sẵn sàng. Trình cài đặt đã tự khoá để bảo mật.</p>
                    <a class="btn btn-primary" href="/login">Đăng nhập quản trị&ensp;<?php echo $ico('login'); ?></a>
                </div>
            </section>

        </div>
    </main>

</div>

<script src="/assets/js/installer.js?v=<?php echo $assetV('/assets/js/installer.js'); ?>" defer></script>
</body>
</html>
