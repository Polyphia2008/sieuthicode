<?php
// statically decompiled from edit.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
// Sửa ngân hàng là chức năng nhạy cảm: chỉ superadmin (cả GET lẫn POST).
// Gọi require_superadmin TRƯỚC khi render header để không xuất HTML trước status 403.
require_superadmin(false);
// CSRF gate phải chạy TRƯỚC mọi output để HTTP 419 có hiệu lực.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['LuuNganHang'])) {
    verify_csrf_token();
}
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_GET['id']) && is_superadmin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row(' SELECT * FROM `bank` WHERE `id` = \'' . $id . '\'  ');
    if (!$row) {
        new Redirect('/cpanel/recharge/bank/config');
    }
} else {
    new Redirect('/cpanel/recharge/bank/config');
}
if (isset($_POST['LuuNganHang']) && is_superadmin_account($data_user)) {
    verify_csrf_token();
    $isUpdate = $db->update('bank', ['short_name' => strtoupper(Anti_xss($_POST['short_name'])), 'accountNumber' => Anti_xss($_POST['accountNumber']), 'accountName' => Anti_xss($_POST['accountName']), 'url_api' => Anti_xss($_POST['url_api']), 'status' => Anti_xss($_POST['status'])], ' `id` = \'' . $id . '\' ');
    if ($isUpdate) {
        insetLog($data_user['id'], 'Cập nhật thông tin ngân hàng (' . $config_listbank[Anti_xss($_POST['short_name'])] . ' - ' . $_POST['accountNumber'] . ') vào hệ thống.');
        exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
    } else {
        exit('<script type="text/javascript">if(!alert("Lưu thất bại !")){window.history.back().location.reload();}</script>');
    }
} else {
    echo '<div class="main-content app-content">
    <div class="container-fluid">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h1 class="page-title fw-semibold fs-18 mb-0">Chỉnh sửa ngân hàng ';
    echo $row['short_name'];
    echo '</h1>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item"><a href="/cpanel/recharge/bank/config">Ngân hàng</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Chỉnh sửa ngân hàng
                            VCB</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            CHỈNH SỬA NGÂN HÀNG
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="csrf_token" value="';
    echo generate_csrf_token();
    echo '">
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Ngân hàng <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="';
    echo $row['short_name'];
    echo '" list="options" name="short_name" placeholder="Nhập tên ngân hàng" required>
                                <datalist id="options">
                                    ';
    foreach ($config_listbank as $__key => $value) {
        $key = $__key;
        echo '                                        <option ';
        echo $row['short_name'] == $key ? 'selected' : '';
        echo ' value="';
        echo $key;
        echo '">
                                            ';
        echo $value;
        echo '</option>
                                    ';
    }
    echo '                                </datalist>
                            </div>

                            <div class="mb-4">
                                <label for="exampleInputEmail1">Account number</label>
                                <input type="text" class="form-control" name="accountNumber" value="';
    echo $row['accountNumber'];
    echo '" placeholder="Nhập số tài khoản" required>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Account name</label>
                                <input type="text" class="form-control" name="accountName" value="';
    echo $row['accountName'];
    echo '" placeholder="Nhập tên chủ tài khoản" required>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Trạng thái</label>
                                <select class="form-control" name="status">
                                    <option ';
    echo $row['status'] == 1 ? 'selected' : '';
    echo ' value="1">ON</option>
                                    <option ';
    echo $row['status'] == 0 ? 'selected' : '';
    echo ' value="0">OFF</option>
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="exampleInputEmail1">Link API LSGD</label>
                                <input type="text" class="form-control" name="url_api" value="';
    echo $row['url_api'];
    echo '" placeholder="Áp dụng khi cấu hình nạp tiền tự động.">
                            </div>
                            <a type="button" class="btn btn-hero btn-danger" href="/cpanel/recharge/bank/config"><i class="fa fa-fw fa-undo me-1"></i>
                                Back</a>
                            <button type="submit" name="LuuNganHang" class="btn btn-hero btn-success"><i class="fa fa-fw fa-save me-1"></i> Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
