<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `boostings` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/boosting/view');
    }
} else {
    new Redirect('/cpanel/boosting/view');
}
if (isset($_POST['UpdateCategory']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `boostings` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' AND `id` != ' . $row['id'] . ' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $isInsert = $db->update('boostings', ['name' => Anti_xss($_POST['name']), 'slug' => create_slug(Anti_xss($_POST['name'])), 'stt' => Anti_xss($_POST['stt']), 'status' => Anti_xss($_POST['status'])], ' `id` = \'' . $row['id'] . '\' ');
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
                    Cập nhật danh mục ';
    echo $row['name'];
    echo '                </h1>
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
                                            Sự ưu tiên
                                            <i class="tio-info-outined" data-toggle="tooltip" data-placement="top" title="" data-original-title="The lowest number will get the highest priority"></i>

                                        </label>
                                        <input type="text" name="stt" value="';
    echo $row['stt'];
    echo '" class="form-control" placeholder="Vị trí hiển thị" required>
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
                                <a type="button" class="btn btn-danger" href="/cpanel/boosting/view">Quay Lại</a>
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
