<?php
// statically decompiled from orders.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
} else {
    if ($data_user['ctv'] != 1) {
        exit(JsonMsg('error', 'Bạn không có quyền truy cập vào trang này'));
    } else {
        if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
            exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
        } else {
            if (!isset($_POST['id']) || empty($_POST['id'])) {
                exit(jsonMsg('error', 'Vui lòng chọn đơn'));
            } else {
                $id = Anti_xss($_POST['id']);
                $row = $db->get_row(' SELECT * FROM `orders` WHERE `id` = \'' . Anti_xss($_POST['id']) . ('\' AND `receiver` = \'' . $data_user['username'] . '\''));
                $detail = json_decode($row['detail'], true);
                echo '<form method="POST" enctype="multipart/form-data">
    <div class="mb-3">
        <label for="name" class="form-label">Dịch vụ</label>
        <input type="text" id="name" name="name" class="form-control" value="';
                echo $row['name'];
                echo '" disabled>
    </div>
    <div class="mb-3">
        <label for="name" class="form-label">Người nhận đơn</label>
        <input type="text" id="receiver" name="receiver" class="form-control" value="';
                echo $row['receiver'];
                echo '">
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
        ';
                foreach ($detail['data'] as $item) {
                    echo '            <div class="col-md-4 mb-3">
                <label for="input_user" class="form-label">';
                    echo $item['label'];
                    echo '</label>
                <input type="text" class="form-control" value="';
                    echo $item['value'];
                    echo '" disabled>
            </div>
        ';
                }
                echo '    </div>
    <div class="mb-3">
        <label for="admin_note" class="form-label">Ghi chú admin</label>
        <textarea class="form-control" id="admin_note" name="admin_note" rows="3">';
                echo $row['admin_note'];
                echo '</textarea>
    </div>
    <div class="mb-3">
        <label for="admin_note" class="form-label">Hình ảnh</label>
        <img class="active mb-1" id="img_1" src="';
                echo DOMAIN . '/' . $row['image_path'];
                echo '" width="200px">

        <div class="custom-file text-left">
            <input type="file" id="thumb" class="form-control image-preview-before-upload" data-preview="#viewer" required="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
        </div>
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
