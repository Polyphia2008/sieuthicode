<?php
// statically decompiled from order-items.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
} else {
    if (!is_admin_account($data_user)) {
        exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
    } else {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
            exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
        } else {
            if (!isset($_POST['id']) || empty($_POST['id'])) {
                exit(jsonMsg('error', 'Vui lòng chọn đơn'));
            } else {
                $id = Anti_xss($_POST['id']);
                $row = $db->get_row(' SELECT * FROM `order_items` WHERE `id` = \'' . Anti_xss($_POST['id']) . '\'  ');
                echo '<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Nhóm</label>
        <input type="text" id="name" name="name" class="form-control" value="';
                echo $row['name_package'];
                echo '" disabled>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Vật phẩm</label>
        <input type="text" class="form-control" value="';
                echo $row['value'];
                echo ' ';
                echo $row['unit'];
                echo '" disabled>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="code" class="form-label">Mã đơn</label>
            <input type="text" id="code" name="code" class="form-control" value="';
                echo $row['code'];
                echo '" disabled>
        </div>
        <div class="col-md-6">
            <label for="payment" class="form-label">Thanh toán</label>
            <input type="text" id="payment" name="payment" class="form-control" value="';
                echo format_cash($row['payment']);
                echo ' ₫" disabled>
        </div>
    </div>
    <div class="mb-3 row">
        <div class="col-md-4">
            <label for="input_user" class="form-label">Tài khoản</label>
            <input type="text" id="input_user" name="input_user" class="form-control" value="';
                echo decodecryptData($row['input_user']);
                echo '" disabled>
        </div>
        <div class="col-md-4">
            <label for="input_pass" class="form-label">Mật khẩu</label>
            <input type="text" id="input_pass" name="input_pass" class="form-control" value="';
                echo decodecryptData($row['input_pass']);
                echo '" disabled>
        </div>
        <div class="col-md-4">
            <label for="input_auth" class="form-label">Đăng nhập</label>
            <input type="text" id="input_auth" name="input_auth" class="form-control" value="';
                echo $row['input_extra'];
                echo '" disabled>
        </div>
    </div>
    <div class="mb-3">
        <label for="admin_note" class="form-label">Ghi chú admin</label>
        <textarea class="form-control" id="admin_note" name="admin_note" rows="3">';
                echo $row['admin_note'];
                echo '</textarea>
    </div>
    <div class="mb-3">
        <label for="order_note" class="form-label">Ghi chú khách</label>
        <textarea class="form-control" id="order_note" name="order_note" rows="3">';
                echo $row['order_note'];
                echo '</textarea>
    </div>
    <div class="mb-3">
        <label for="status" class="form-label">Trạng thái</label>
        <select class="form-select" id="status" name="status" required>
            <option value="pending" ';
                echo $row['status'] == 'pending' ? 'selected' : '';
                echo '>Chờ xử lý</option>
            <option value="processing" ';
                echo $row['status'] == 'processing' ? 'selected' : '';
                echo '>Đang xử lý</option>
            <option value="completed" ';
                echo $row['status'] == 'completed' ? 'selected' : '';
                echo '>Hoàn thành</option>
            <option value="error_refund" ';
                echo $row['status'] == 'error_refund' ? 'selected' : '';
                echo '>Lỗi Đơn / hoàn tiền</option>
            <option value="cancelled" ';
                echo $row['status'] == 'cancelled' ? 'selected' : '';
                echo '>Hủy đơn</option>
            <option value="cancelled_refund" ';
                echo $row['status'] == 'cancelled_refund' ? 'selected' : '';
                echo '>Hủy đơn hoàn tiền</option>
        </select>
    </div>
    <div class="mb-3">
        <button class="btn btn-primary" type="button" id="change" onclick="updateOrder(';
                echo $row['id'];
                echo ')">Cập nhật</button>
    </div>
</form>';
            }
        }
    }
}
