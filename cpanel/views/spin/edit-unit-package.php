<?php
// statically decompiled from edit-unit-package.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && $data_user['level'] == 'admin') {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `package_units` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/spin/unit');
    }
} else {
    new Redirect('/cpanel/spin/unit');
}
if (isset($_POST['AddPackage']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `package_units` WHERE `name` = \'' . Anti_xss($_POST['name']) . ('\' AND `id` != \'' . $id . '\''))) {
            exit('<script type="text/javascript">if(!alert("Gói này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $isInsert = $db->update('package_units', ['stt' => Anti_xss($_POST['stt']), 'name' => Anti_xss($_POST['name']), 'value' => Anti_xss($_POST['value']), 'status' => Anti_xss($_POST['status'])], ' `id` = \'' . $row['id'] . '\' ');
            if ($isInsert) {
                insetLog($data_user['id'], 'Cập nhật gói (' . $row['name'] . ' ID ' . $row['id'] . ').');
                exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
            } else {
                exit('<script type="text/javascript">if(!alert("Lưu thất bại !")){window.history.back().location.reload();}</script>');
            }
        }
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Cập nhật gói [';
    echo $row['name'];
    echo ']</h3>
            </div>
            <div class="block-content">
                <form id="form-data" action="" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">

                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Sự ưu tiên</label>
                                <input type="number" name="stt" class="form-control" value="';
    echo $row['stt'];
    echo '" placeholder="Vị trí hiển thị" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tên gói</label>
                                <input type="text" name="name" class="form-control" value="';
    echo $row['name'];
    echo '" placeholder="Nhập tên gói" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giá trị gói</label>
                                <input type="text" name="value" class="form-control" value="';
    echo $row['value'];
    echo '" placeholder="Giá trị gói" required>
                            </div>
                        </div>
                       
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
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
                    <div class="mb-3">
                        <button type="submit" name="AddPackage" class="btn btn-success">
                            Cập Nhật
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
