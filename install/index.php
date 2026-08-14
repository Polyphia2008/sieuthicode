<?php
/**
 * Trình cài đặt Siêu Thị Code — wizard 3 bước (theme DashLite/NioBoard nk-*).
 *
 * Trang này chỉ render form; mọi hành động thay đổi trạng thái đi qua
 * install/api.php (POST + CSRF + rate limit + mutex). Không in password,
 * không hotlink CDN: toàn bộ asset theme nằm trong assets/installer/
 * (bundle đã sanitize, chỉ tham chiếu file local).
 * Logic API/stepper của project nằm ở assets/js/installer.js.
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
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Cài đặt Siêu Thị Code</title>
<link rel="stylesheet" href="/assets/installer/css/ws.theme.css?v=<?php echo $assetV('/assets/installer/css/ws.theme.css'); ?>">
<link rel="stylesheet" href="/assets/installer/css/custom.css?v=<?php echo $assetV('/assets/installer/css/custom.css'); ?>">
<link rel="stylesheet" href="/assets/installer/css/fuiToast.min.css?v=<?php echo $assetV('/assets/installer/css/fuiToast.min.css'); ?>">
<link rel="stylesheet" href="/assets/installer/css/remixicon.min.css?v=<?php echo $assetV('/assets/installer/css/remixicon.min.css'); ?>">
</head>
<body class="nk-body npc-default pg-survey no-touch nk-nio-theme" data-csrf="<?php echo installer_e($csrf); ?>" data-api="/install/api.php">
<div id="fui-toast"></div>
<div class="nk-app-root">
    <div class="nk-main">
        <div class="nk-wrap nk-wrap-nosidebar">
            <div class="nk-content">
                <div class="nk-split nk-split-page nk-split-lg">

                    <!-- Panel trái (tối): lời chào + minh hoạ local -->
                    <div class="nk-split-content bg-dark is-dark p-5 d-flex justify-between flex-column text-center w-50">
                        <span class="logo-link nk-sidebar-logo" aria-hidden="true"></span>
                        <div class="text-block">
                            <img class="nk-survey-gfx mb-5" style="pointer-events:none;user-select:none;"
                                 src="/assets/installer/img/survey.svg" alt="">
                            <h3 class="text-white">Chào mừng bạn đến với <b class="td-home">Siêu&nbsp;Thị&nbsp;Code</b></h3>
                            <p>Trình cài đặt sẽ thiết lập cơ sở dữ liệu và tài khoản quản trị trong
                               3 bước đơn giản. Mọi thao tác đều an toàn và có thể thử lại nếu bị gián đoạn.</p>
                        </div>
                        <p>&copy; Siêu Thị Code — an toàn, không gửi dữ liệu ra ngoài.</p>
                    </div>

                    <!-- Panel phải (sáng): form cài đặt -->
                    <div class="nk-split-content nk-split-stretch bg-white p-5 d-flex justify-center align-center flex-column">
                        <div class="wide-xs-fix">

                            <!-- Progress -->
                            <div class="nk-stepper-progress stepper-progress mb-4" id="progress-wrap">
                                <div class="d-flex justify-between mb-2">
                                    <span class="stepper-progress-count" id="progress-label">Kết nối database</span>
                                    <span class="stepper-progress-count" id="progress-count">Bước 1/3</span>
                                </div>
                                <div class="progress progress-md" id="progress-bar" role="progressbar"
                                     aria-valuemin="1" aria-valuemax="3" aria-valuenow="1" aria-label="Tiến trình cài đặt">
                                    <div class="progress-bar stepper-progress-bar" id="progress-fill"></div>
                                </div>
                            </div>

                            <!-- Bước 1: Database -->
                            <section class="nk-stepper-step active" id="step-1" aria-labelledby="t1">
                                <h5 class="title mb-3" id="t1" tabindex="-1">Kết nối database</h5>
                                <p class="mb-3">Nhập thông tin MySQL được tạo trong cPanel của bạn.</p>

                                <div class="alert alert-primary alert-icon mb-4">
                                    <em class="icon ni ni-alert-circle"></em>
                                    <strong>Hướng dẫn trong cPanel &rarr; MySQL Databases:</strong>
                                    <ol class="mb-0 mt-1 ps-3">
                                        <li>Tạo một <strong>database</strong> mới.</li>
                                        <li>Tạo một <strong>database user</strong> mới.</li>
                                        <li>Thêm user vào database và chọn <strong>ALL PRIVILEGES</strong>.</li>
                                    </ol>
                                </div>

                                <form id="form-db" autocomplete="off" novalidate>
                                    <div class="row g-3">
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="db_host">Database host (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="db_host" name="db_host" value="localhost" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="db_port">Port (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="number" class="form-control" id="db_port" name="db_port" value="3306" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="db_name">Database name (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="db_name" name="db_name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="db_user">Database username (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="db_user" name="db_user" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="db_pass">Database password</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="db_pass" name="db_pass">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-4">
                                        <button class="btn btn-primary" type="submit">Kiểm tra kết nối&ensp;<i class="ri-database-2-line"></i></button>
                                    </div>
                                    <div class="mt-3" id="msg-1" role="status" aria-live="polite"></div>
                                </form>
                            </section>

                            <!-- Bước 2: Tài khoản quản trị -->
                            <section class="nk-stepper-step" id="step-2" aria-labelledby="t2">
                                <h5 class="title mb-3" id="t2" tabindex="-1">Tài khoản quản trị</h5>
                                <p class="mb-3">Tài khoản này có toàn quyền quản trị website.</p>

                                <form id="form-admin" autocomplete="off" novalidate>
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label class="form-label" for="admin_name">Họ và tên (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="admin_name" name="admin_name" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="admin_username">Tên đăng nhập (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="text" class="form-control" id="admin_username" name="admin_username" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="admin_email">Email (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="email" class="form-control" id="admin_email" name="admin_email" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="admin_password">Mật khẩu (tối thiểu 8 ký tự) (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="admin_password" name="admin_password" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label class="form-label" for="admin_password_confirm">Xác nhận mật khẩu (<b class="text-danger">*</b>)</label>
                                                <div class="form-control-wrap">
                                                    <input type="password" class="form-control" id="admin_password_confirm" name="admin_password_confirm" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pt-4 d-flex gap-2">
                                        <button class="btn btn-primary" type="submit">Lưu thông tin&ensp;<i class="ri-arrow-right-line"></i></button>
                                        <button class="btn btn-dim btn-primary" type="button" id="back-2"><i class="ri-arrow-left-line"></i>&ensp;Quay lại</button>
                                    </div>
                                    <div class="mt-3" id="msg-2" role="status" aria-live="polite"></div>
                                </form>
                            </section>

                            <!-- Bước 3: Xác nhận & cài đặt -->
                            <section class="nk-stepper-step" id="step-3" aria-labelledby="t3">
                                <h5 class="title mb-3" id="t3" tabindex="-1">Xác nhận &amp; cài đặt</h5>
                                <p class="mb-3">Kiểm tra lại thông tin rồi bấm "Cài đặt ngay".</p>

                                <div class="card card-bordered mb-4">
                                    <div class="card-inner">
                                        <div id="summary" aria-label="Tóm tắt cấu hình"></div>
                                    </div>
                                </div>

                                <div class="pt-2 d-flex gap-2">
                                    <button class="btn btn-primary text-nowrap" id="do-install" type="button">Cài đặt ngay&ensp;<i class="ri-settings-2-line"></i></button>
                                    <button class="btn btn-dim btn-primary" type="button" id="back-3"><i class="ri-arrow-left-line"></i>&ensp;Quay lại</button>
                                </div>
                                <div class="mt-3" id="msg-3" role="status" aria-live="polite"></div>
                            </section>

                            <!-- Hoàn tất -->
                            <section class="nk-stepper-step" id="step-done" aria-labelledby="td">
                                <div class="pt-4 pb-2 text-center">
                                    <em class="icon icon-circle icon-circle-xxl mb-4 ni ni-check bg-success-dim"></em>
                                    <h5 class="title mb-2" id="td" tabindex="-1">Cài đặt thành công</h5>
                                    <p class="mb-4">Website của bạn đã sẵn sàng. Trình cài đặt đã tự khoá để bảo mật.</p>
                                    <a class="btn btn-primary" href="/login">Đăng nhập quản trị&ensp;<i class="ri-login-box-line"></i></a>
                                </div>
                            </section>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/installer/js/bundle.js?v=<?php echo $assetV('/assets/installer/js/bundle.js'); ?>" defer></script>
<script src="/assets/installer/js/fuiToast.min.js?v=<?php echo $assetV('/assets/installer/js/fuiToast.min.js'); ?>" defer></script>
<script src="/assets/js/installer.js?v=<?php echo $assetV('/assets/js/installer.js'); ?>" defer></script>
</body>
</html>
