<?php
// statically decompiled from edit.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `category_items` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/items/category');
    }
} else {
    new Redirect('/cpanel/items/category');
}
if (isset($_POST['UpdateCategory']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `category_items` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' AND `id` != ' . $row['id'] . ' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $isInsert = $db->update('category_items', ['name' => Anti_xss($_POST['name']), 'unit' => Anti_xss($_POST['unit']), 'factor' => Anti_xss($_POST['factor']), 'min_value' => Anti_xss($_POST['min_value']), 'max_value' => Anti_xss($_POST['max_value']), 'status' => Anti_xss($_POST['status'])], ' `id` = \'' . $row['id'] . '\' ');
            if ($isInsert) {
                insetLog($data_user['id'], 'Edit Category (' . $row['name'] . ' ID ' . $row['id'] . ').');
                exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
            } else {
                exit('<script type="text/javascript">if(!alert("Lưu thất bại !")){window.history.back().location.reload();}</script>');
            }
        }
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Cập nhật thể loại [';
    echo $row['name'];
    echo ']
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label">
                                            Tên danh mục
                                        </label>
                                        <input type="text" name="name" value="';
    echo $row['name'];
    echo '" class="form-control" placeholder="Danh mục mới" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="form-label">
                                            Đơn vị
                                        </label>
                                        <input type="text" name="unit" value="';
    echo $row['unit'];
    echo '" class="form-control" placeholder="Đơn vị" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="form-label">
                                            Hệ số
                                        </label>
                                        <input type="text" name="factor" value="';
    echo $row['factor'];
    echo '" class="form-control" placeholder="Hệ số" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="form-label">
                                            Số tiền mua tối thiểu
                                        </label>
                                        <input type="text" name="min_value" value="';
    echo $row['min_value'];
    echo '" class="form-control" placeholder="Nhập số tiền mua tối thiểu" required>
                                    </div>
                                    <div class="form-group mb-2">
                                        <label class="form-label">
                                            Số tiền mua tối đa
                                        </label>
                                        <input type="text" name="max_value" value="';
    echo $row['max_value'];
    echo '" class="form-control" placeholder="Nhập số tiền mua tối đa" required>
                                    </div>

                                    <div class="form-group mb-2">
                                        <label class="form-label" for="symbol_right">Trạng thái</label>
                                        <select class="form-control" name="status" required>
                                            <option ';
    echo $row['status'] == 1 ? 'selected' : '';
    echo ' value="1">ON</option>
                                            <option ';
    echo $row['status'] == 0 ? 'selected' : '';
    echo ' value="0">OFF</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a type="button" class="btn btn-danger" href="/cpanel/items/category">Quay Lại</a>
                                <button type="submit" name="UpdateCategory" class="btn btn-success">
                                    Lưu Ngay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
