<?php
// statically decompiled from edit.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
// Nạp init TRƯỚC header để có thể gate CSRF trước khi header.php in bất kỳ
// output nào — nếu verify sau output, http_response_code(419) sẽ bị nuốt.
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
// CSRF gate cho mọi POST action của trang (cộng/trừ tiền, cộng/trừ item, lưu user).
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && (isset($_POST['btnCongTien']) || isset($_POST['btnTruTien'])
        || isset($_POST['btnCongTienItem']) || isset($_POST['btnTruTienItem'])
        || isset($_POST['btnSaveUser']))) {
    verify_csrf_token(); // missing/empty/wrong -> HTTP 419 + exit (chưa in gì)
}
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $user = $db->get_row(' SELECT * FROM `users` WHERE `id` = \'' . Anti_xss($_GET['id']) . '\'  ');
    if (!$user) {
        new Redirect('/cpanel/users/list');
    }
} else {
    new Redirect('/cpanel/users/list');
}
// Admin thường KHÔNG được chạm vào tài khoản superadmin (cộng/trừ tiền, sửa, khoá...).
$__targetIsSuperadmin = is_superadmin_account($user);
$__actorIsSuperadmin = is_superadmin_account($data_user);
$__forbidAdminOnSuperadmin = $__targetIsSuperadmin && !$__actorIsSuperadmin;
if (isset($_POST['btnCongTien']) && is_admin_account($data_user)) {
    verify_csrf_token();
    if ($__forbidAdminOnSuperadmin) {
        exit('<script type="text/javascript">if(!alert("Chỉ superadmin mới được thao tác trên tài khoản superadmin!")){window.history.back().location.reload();}</script>');
    }
    $value = Anti_xss($_POST['amount']);
    $ghichu = Anti_xss($_POST['reason']);
    $wallet = Anti_xss($_POST['wallet']);
    if ($value <= 0) {
        exit('<script type="text/javascript">if(!alert("Số tiền nhập không hợp lệ!")){window.history.back().location.reload();}</script>');
    } else {
        if ($wallet == 2) {
            $db->cong('users', 'debit', $value, ' `username` = \'' . $user['username'] . '\' ');
            PlusCredits($user['id'], $value, $ghichu);
        } else {
            PlusCredits($user['id'], $value, $ghichu);
        }
        exit('<script type="text/javascript">if(!alert("Cộng tiền thành công!")){window.history.back().location.reload();}</script>');
    }
} else {
    if (isset($_POST['btnTruTien']) && is_admin_account($data_user)) {
        verify_csrf_token();
        if ($__forbidAdminOnSuperadmin) {
            exit('<script type="text/javascript">if(!alert("Chỉ superadmin mới được thao tác trên tài khoản superadmin!")){window.history.back().location.reload();}</script>');
        }
        $value = Anti_xss($_POST['amount']);
        $ghichu = Anti_xss($_POST['reason']);
        if ($value <= 0) {
            exit('<script type="text/javascript">if(!alert("Số tiền nhập không hợp lệ!")){window.history.back().location.reload();}</script>');
        } else {
            RemoveCredits($user['id'], $value, $ghichu);
            exit('<script type="text/javascript">if(!alert("Trừ tiền thành công!")){window.history.back().location.reload();}</script>');
        }
    } else {
        if (isset($_POST['btnCongTienItem']) && is_admin_account($data_user)) {
            verify_csrf_token();
            if ($__forbidAdminOnSuperadmin) {
                exit('<script type="text/javascript">if(!alert("Chỉ superadmin mới được thao tác trên tài khoản superadmin!")){window.history.back().location.reload();}</script>');
            }
            $value = Anti_xss($_POST['amount']);
            $ghichu = Anti_xss($_POST['reason']);
            $wallet = Anti_xss($_POST['wallet']);
            if ($value <= 0) {
                exit('<script type="text/javascript">if(!alert("Số item nhập không hợp lệ!")){window.history.back().location.reload();}</script>');
            } else {
                PlusCreditsItem($user['id'], $value, $ghichu);
                exit('<script type="text/javascript">if(!alert("Cộng tiền thành công!")){window.history.back().location.reload();}</script>');
            }
        } else {
            if (isset($_POST['btnTruTienItem']) && is_admin_account($data_user)) {
                verify_csrf_token();
                if ($__forbidAdminOnSuperadmin) {
                    exit('<script type="text/javascript">if(!alert("Chỉ superadmin mới được thao tác trên tài khoản superadmin!")){window.history.back().location.reload();}</script>');
                }
                $value = Anti_xss($_POST['amount']);
                $ghichu = Anti_xss($_POST['reason']);
                if ($value <= 0) {
                    exit('<script type="text/javascript">if(!alert("Số item nhập không hợp lệ!")){window.history.back().location.reload();}</script>');
                } else {
                    RemoveCreditsItem($user['id'], $value, $ghichu);
                    exit('<script type="text/javascript">if(!alert("Trừ tiền thành công!")){window.history.back().location.reload();}</script>');
                }
            } else {
                if (isset($_POST['btnSaveUser']) && is_admin_account($data_user)) {
                    verify_csrf_token();
                    // Chỉ superadmin được đổi vai trò (level) hoặc sửa tài khoản superadmin.
                    $newLevel = (string) ($_POST['level'] ?? $user['level']);
                    if (!$__actorIsSuperadmin) {
                        if ($__targetIsSuperadmin) {
                            exit('<script type="text/javascript">if(!alert("Chỉ superadmin mới được chỉnh sửa tài khoản superadmin!")){window.history.back().location.reload();}</script>');
                        }
                        if ($newLevel !== (string) $user['level']) {
                            exit('<script type="text/javascript">if(!alert("Chỉ superadmin mới được thay đổi vai trò tài khoản!")){window.history.back().location.reload();}</script>');
                        }
                    }
                    // Whitelist role: giá trị lạ (forged POST) bị bỏ qua, giữ nguyên role hiện tại.
                    if (!in_array($newLevel, ['member', 'admin', 'superadmin'], true)) {
                        $newLevel = (string) $user['level'];
                    }
                    // Thao tác này có làm mất ACTIVE superadmin cuối cùng không?
                    // (demote khỏi superadmin HOẶC ban một superadmin đang active)
                    $__wouldLoseLastActive = $__targetIsSuperadmin
                        && (int) ($user['banned'] ?? 0) === 0
                        && ($newLevel !== 'superadmin' || (string) ($_POST['banned'] ?? '0') === '1');

                    // TOCTOU: khoá hàng users của target trong một transaction, đếm
                    // lại số ACTIVE superadmin bên trong khoá, rồi mới UPDATE. Hai
                    // request đồng thời demote/ban hai superadmin sẽ được serialize
                    // qua row-lock — request sau thấy count đã đổi và bị chặn.
                    if ($__wouldLoseLastActive) {
                        $db->query('START TRANSACTION');
                        // Khoá hàng target (ngăn request khác sửa cùng lúc).
                        $db->get_row('SELECT `id` FROM `users` WHERE `id` = \''
                            . (int) $user['id'] . '\' FOR UPDATE');
                        if (count_superadmins($db) <= 1) {
                            $db->query('ROLLBACK');
                            exit('<script type="text/javascript">if(!alert("Không thể hạ quyền hoặc khoá superadmin cuối cùng của hệ thống!")){window.history.back().location.reload();}</script>');
                        }
                        $isUpdate = $db->update('users', ['username' => Anti_xss($_POST['username']), 'level' => Anti_xss($newLevel), 'banned' => Anti_xss($_POST['banned']), 'token' => Anti_xss($_POST['token']), 'email' => Anti_xss($_POST['email']), 'cost' => Anti_xss($_POST['cost']), 'phone' => Anti_xss($_POST['phone']), 'ctv' => Anti_xss($_POST['ctv']), 'ctv_account' => Anti_xss($_POST['ctv_account']), 'ctv_boosting' => Anti_xss($_POST['ctv_boosting']), 'chietkhau_banacc' => Anti_xss($_POST['chietkhau_banacc']), 'maxprice' => Anti_xss($_POST['maxprice']), 'role_category' => null, 'status_2fa' => Anti_xss($_POST['status_2fa']), 'chietkhau' => Anti_xss($_POST['chietkhau']), 'login_attempts' => 0], ' `id` = \'' . $user['id'] . '\' ');
                        if ($isUpdate) {
                            $db->query('COMMIT');
                            if (!empty($_POST['password'])) {
                                $db->update('users', ['password' => sha1(Anti_xss($_POST['password']))], ' `id` = \'' . $user['id'] . '\' ');
                            }
                            exit('<script type="text/javascript">if(!alert("Cập nhật thông tin thành công")){window.history.back().location.reload();}</script>');
                        }
                        $db->query('ROLLBACK');
                        exit('<script type="text/javascript">if(!alert("Cập nhật thông tin thất bại")){window.history.back().location.reload();}</script>');
                    }

                    $group = $_POST['group'];
                    $group_string = null;
                    if ($group !== null) {
                        $group_string = Anti_xss(implode(',', $group));
                    }
                    $isUpdate = $db->update('users', ['username' => Anti_xss($_POST['username']), 'level' => Anti_xss($newLevel), 'banned' => Anti_xss($_POST['banned']), 'token' => Anti_xss($_POST['token']), 'email' => Anti_xss($_POST['email']), 'cost' => Anti_xss($_POST['cost']), 'phone' => Anti_xss($_POST['phone']), 'ctv' => Anti_xss($_POST['ctv']), 'ctv_account' => Anti_xss($_POST['ctv_account']), 'ctv_boosting' => Anti_xss($_POST['ctv_boosting']), 'chietkhau_banacc' => Anti_xss($_POST['chietkhau_banacc']), 'maxprice' => Anti_xss($_POST['maxprice']), 'role_category' => $group_string, 'status_2fa' => Anti_xss($_POST['status_2fa']), 'chietkhau' => Anti_xss($_POST['chietkhau']), 'login_attempts' => 0], ' `id` = \'' . $user['id'] . '\' ');
                    if ($isUpdate) {
                        if (!empty($_POST['password'])) {
                            $password = Anti_xss($_POST['password']);
                            $db->update('users', ['password' => sha1($password)], ' `id` = \'' . $user['id'] . '\' ');
                        }
                        exit('<script type="text/javascript">if(!alert("Cập nhật thông tin thành công")){window.history.back().location.reload();}</script>');
                    } else {
                        exit('<script type="text/javascript">if(!alert("Cập nhật thông tin thất bại")){window.history.back().location.reload();}</script>');
                    }
                } else {
                    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><a type="button" class="btn btn-dark btn-raised-shadow btn-wave btn-sm me-1" href="/cpanel/users/list"><i class="fa-solid fa-arrow-left"></i></a> Chỉnh sửa thành viên ';
                    echo $user['username'];
                    echo '</h4>
        </div>
        <div class="row gx-5 mb-5">
            <div class="col-12">
                <div class="mt-4 mt-md-0">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-addCredit" class="btn btn-sm btn-wave btn-success me-1 mb-3 push">
                        <i class="fa fa-fw fa-plus"></i> Cộng số dư
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-removeCredit" class="btn btn-sm btn-wave btn-danger me-1 mb-3 push">
                        <i class="fa fa-fw fa-minus"></i> Trừ số dư
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-addCreditItem" class="btn btn-sm btn-wave btn-alt-warning me-1 mb-3 push">
                        <i class="fa fa-fw fa-plus"></i> Cộng items
                    </button>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modal-removeCreditItem" class="btn btn-sm btn-wave btn-alt-danger me-1 mb-3 push">
                        <i class="fa fa-fw fa-minus"></i> Trừ items
                    </button>
                    <a type="button" href="/cpanel/logs?user_id=';
                    echo $user['id'];
                    echo '" target="_blank" class="btn btn-sm btn-wave btn-primary me-1 mb-3 push">
                        <i class="fa fa-fw fa-history"></i> Nhật ký hoạt động
                    </a>
                    <a type="button" href="/cpanel/transactions?user_id=';
                    echo $user['id'];
                    echo '" target="_blank" class="btn btn-sm btn-wave btn-info me-1 mb-3 push">
                        <i class="fa fa-fw fa-history"></i> Biến động số dư
                    </a>
                </div>
            </div>
            <div class="col-12">
                <div class="card custom-card shadow-none mb-0">
                    <div class="card-body">
                        <form action="" method="POST">
                            <input type="hidden" name="csrf_token" value="';
                    echo generate_csrf_token();
                    echo '">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Username (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['username'];
                    echo '" name="username" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Email (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control" value="';
                    echo $user['email'];
                    echo '" name="email" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Phone (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-phone"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['phone'];
                    echo '" name="phone" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Token (<span class="text-danger">*</span>)</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fa-solid fa-key"></i>
                                    </span>
                                    <input type="text" class="form-control" value="';
                    echo $user['token'];
                    echo '" name="token" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Mật khẩu (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-key"></i>
                                            </span>
                                            <input type="text" class="form-control" placeholder="**********" name="password">
                                        </div>
                                        <i>Nhập mật khẩu cần thay đổi, hệ thống sẽ tự động mã hóa (bỏ trống nếu không muốn thay đổi)</i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Secret Key Google 2FA</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class=\'bx bx-key\'></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['secretkey'];
                    echo '" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">ON/OFF Google 2FA (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class=\'bx bxs-key\'></i>
                                            </span>
                                            <select class="form-control select2bs4" name="status_2fa">
                                                <option ';
                    echo $user['status_2fa'] == 1 ? 'selected' : '';
                    echo ' value="1">
                                                    ON
                                                </option>
                                                <option ';
                    echo $user['status_2fa'] == 0 ? 'selected' : '';
                    echo ' value="0">
                                                    OFF</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Chiết khấu giảm giá (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-percent"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['chietkhau'];
                    echo '" name="chietkhau">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Chiết khấu bán tài khoản game (<span class="text-danger">*</span>)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-percent"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['chietkhau_banacc'];
                    echo '" name="chietkhau_banacc">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Admin Role (<span class="text-danger">*</span>)</label>
                                        ';
                    // Admin thường KHÔNG được thấy select đổi role: hiển thị readonly.
                    // Server vẫn giữ nguyên role khi POST bị forged (guard btnSaveUser phía trên).
                    $__roleLabels = ['member' => 'User (Khách hàng)', 'admin' => 'Quản trị viên (Admin)', 'superadmin' => 'Super Admin (toàn quyền)'];
                    if (!$__actorIsSuperadmin) {
                        echo '<input type="text" class="form-control" value="';
                        echo isset($__roleLabels[$user['level']]) ? $__roleLabels[$user['level']] : (string) $user['level'];
                        echo '" readonly>
                                        <input type="hidden" name="level" value="';
                        echo (string) $user['level'];
                        echo '">';
                    } else {
                        echo '<select class="form-control select2bs4" name="level">
                                            <option ';
                        echo $user['level'] == 'member' ? 'selected' : '';
                        echo ' value="member">
                                                User (Khách
                                                hàng)
                                            </option>
                                            <option ';
                        echo $user['level'] == 'admin' ? 'selected' : '';
                        echo ' value="admin">
                                                Quản trị viên (Admin)</option>
                                            <option ';
                        echo $user['level'] == 'superadmin' ? 'selected' : '';
                        echo ' value="superadmin">
                                                Super Admin (toàn quyền)</option>
                                        </select>';
                    }
                    echo '
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Cộng tác viên (<span class="text-danger">*</span>)</label>
                                        <select class="form-control select2bs4" name="ctv">
                                            <option ';
                    echo $user['ctv'] == 1 ? 'selected' : '';
                    echo ' value="1">
                                                Có
                                            </option>
                                            <option ';
                    echo $user['ctv'] == 0 ? 'selected' : '';
                    echo ' value="0">
                                                Không</option>
                                        </select>
                                    </div>
                                </div>
                               
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Cộng tác viên bán tài khoản game(<span class="text-danger">*</span>)</label>
                                        <select class="form-control select2bs4" name="ctv_account">
                                            <option ';
                    echo $user['ctv_account'] == 1 ? 'selected' : '';
                    echo ' value="1">
                                                Được phép
                                            </option>
                                            <option ';
                    echo $user['ctv_account'] == 0 ? 'selected' : '';
                    echo ' value="0">
                                                Không</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Cộng tác viên cày thuê(<span class="text-danger">*</span>)</label>
                                        <select class="form-control select2bs4" name="ctv_boosting">
                                            <option ';
                    echo $user['ctv_boosting'] == 1 ? 'selected' : '';
                    echo ' value="1">
                                                Được phép
                                            </option>
                                            <option ';
                    echo $user['ctv_boosting'] == 0 ? 'selected' : '';
                    echo ' value="0">
                                                Không</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label>Cộng tác viên được phép đăng acc vào danh mục (có thể chọn nhiều mục)</label>
                                        <select class="form-control js-select2" name="group[]" multiple placeholder="Chọn danh mục cần bán">
                                            ';
                    $selected_roles = explode(',', $user['role_category']);
                    foreach ($db->get_list('SELECT * FROM `subcategory` WHERE `status` = 1') as $group) {
                        $detail = json_decode($group['detail'], true);
                        $selected = in_array($group['id'], $selected_roles) ? 'selected' : '';
                        echo '                                                <option value="';
                        echo $group['id'];
                        echo '" ';
                        echo $selected;
                        echo '>
                                                    ';
                        echo $detail['name_product'];
                        echo '</option>
                                            ';
                    }
                    echo '                                        </select>


                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label>Tối đa số tiền acc CTV được phép đăng</label>
                                        <input type="number" class="form-control" value="';
                    echo $user['maxprice'];
                    echo '" name="maxprice">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <div class="mb-4">
                                            <label class="form-label">Banned (<span class="text-danger">*</span>)</label>
                                            <select class="form-control select2bs4" name="banned">
                                                <option ';
                    echo $user['banned'] == '1' ? 'selected' : '';
                    echo ' value="1">
                                                    Banned
                                                </option>
                                                <option ';
                    echo $user['banned'] == '0' ? 'selected' : '';
                    echo ' value="0">
                                                    Live</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Item</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wallet"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['coin'];
                    echo '" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Số dư CTV</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wallet"></i>
                                            </span>
                                            <input type="text" name="cost" class="form-control" value="';
                    echo $user['cost'];
                    echo '">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">CTV đã giao dịch</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wallet"></i>
                                            </span>
                                            <input type="text" name="transacted" class="form-control" value="';
                    echo $user['transacted'];
                    echo '">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Money</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wallet"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['money'];
                    echo 'đ" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Total Money</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-money-bill"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['total_money'];
                    echo 'đ" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label class="form-label">Used Money</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class=\'fa-solid fa-money-bill\'></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['total_money'] - $user['money'];
                    echo 'đ" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">IP Login</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-wifi"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['ip'];
                    echo '" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Device Login</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-desktop"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['device'];
                    echo '" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">First Login</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo $user['create_date'];
                    echo '" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Last Login</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fa-solid fa-calendar-days"></i>
                                            </span>
                                            <input type="text" class="form-control" value="';
                    echo date('Y-m-d H:i:s', $user['time_session']);
                    echo '" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a type="button" class="btn btn-danger" href="/cpanel/users/list"><i class="fa fa-fw fa-undo"></i> Back</a>
                            <button type="submit" class="btn btn-primary" name="btnSaveUser"><i class="bi bi-download"></i>
                                Save</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    </div>
    <div class="modal fade" id="modal-addCredit" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="';
                echo generate_csrf_token();
                echo '">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa fa-plus"></i> CỘNG SỐ DƯ
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="notice_debit" class="alert alert-warning alert-dismissible fade show custom-alert-icon shadow-sm" style="display: none;" role="alert">
                            Khi chọn <b>VÍ GHI NỢ</b>, số dư sẽ được cộng trước cho user trong trường hợp auto bank deplay, khi
                            auto bank hoạt động trở lại, hệ thống sẽ tự động trừ lại số tiền đã cộng.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"><i class="bi bi-x"></i></button>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Loại ví:</label>
                            <div class="col-sm-8">
                                <select class="form-control" name="wallet">
                                    <option value="1">VÍ CHÍNH</option>
                                    <option value="2">VÍ GHI NỢ</option>
                                </select>
                            </div>
                        </div>
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var selectWallet = document.querySelector(\'select[name="wallet"]\');
                                var noticeDebit = document.getElementById(\'notice_debit\');

                                selectWallet.addEventListener(\'change\', function() {
                                    if (this.value === "2") {
                                        noticeDebit.style.display = \'block\';
                                    } else {
                                        noticeDebit.style.display = \'none\';
                                    }
                                });
                            });
                        </script>

                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Amount:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="amount" placeholder="Nhập số tiền cần cộng" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Lý do (nếu có):</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal"><i class="fa fa-fw fa-times me-1"></i> Close</button>
                        <button type="submit" name="btnCongTien" class="btn btn-hero btn-success"><i class="fa fa-fw fa-plus me-1"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-removeCredit" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="';
                echo generate_csrf_token();
                echo '">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa fa-minus"></i> Balance </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Amount</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="amount" placeholder="Please enter the amount to be deducted" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Reason</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal"><i class="fa fa-fw fa-times me-1"></i> Close</button>
                        <button type="submit" name="btnTruTien" class="btn btn-hero btn-success"><i class="fa fa-fw fa-minus me-1"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-addCreditItem" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="';
                echo generate_csrf_token();
                echo '">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa fa-plus"></i> CỘNG ITEMS
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Amount:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="amount" placeholder="Nhập số item cần cộng" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Lý do (nếu có):</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal"><i class="fa fa-fw fa-times me-1"></i> Close</button>
                        <button type="submit" name="btnCongTienItem" class="btn btn-hero btn-success"><i class="fa fa-fw fa-plus me-1"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-removeCreditItem" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="';
                echo generate_csrf_token();
                echo '">
                    <div class="modal-header">
                        <h6 class="modal-title" id="staticBackdropLabel2"><i class="fa fa-minus"></i> Trừ Items </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Amount</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="amount" placeholder="Số item cần trừ" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Lý do (nếu có):</label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="reason"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-hero btn-danger" data-bs-dismiss="modal"><i class="fa fa-fw fa-times me-1"></i> Close</button>
                        <button type="submit" name="btnTruTienItem" class="btn btn-hero btn-success"><i class="fa fa-fw fa-minus me-1"></i> Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(\'.js-select2\').select2();
    </script>
    ';
                    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
                }
            }
        }
    }
}
