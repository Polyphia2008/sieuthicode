<?php
// statically decompiled from edit-package.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `package_boostings` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/subboosting/view');
    }
} else {
    new Redirect('/cpanel/subboosting/view');
}
if (isset($_POST['AddCategory']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `package_boostings` WHERE `name` = \'' . Anti_xss($_POST['name']) . ('\' AND `id` != \'' . $id . '\''))) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $url_icon = null;
            if (check_img('image')) {
                unlink('../../..' . $row['image']);
                $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
                $uploads_dir = '/upload/item/item' . $rand . '.png';
                $tmp_name = $_FILES['image']['tmp_name'];
                $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
                if ($addlogo) {
                    $db->update('package_boostings', ['image' => $uploads_dir], ' `id` = \'' . $row['id'] . '\' ');
                }
            }
            $isInsert = $db->update('package_boostings', ['stt' => Anti_xss($_POST['stt']), 'name' => Anti_xss($_POST['name']), 'status' => Anti_xss($_POST['status']), 'price' => Anti_xss($_POST['price']), 'thele' => base64_encode($_POST['thele'])], ' `id` = \'' . $row['id'] . '\' ');
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
                <h3 class="block-title">Cập nhật sản phẩm [';
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
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" value="';
    echo $row['name'];
    echo '" placeholder="Nhập tên sản phẩm" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giá sản phẩm</label>
                                <input type="text" name="price" class="form-control" value="';
    echo $row['price'];
    echo '" placeholder="Nhập giá sản phẩm" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Ảnh nổi bật:
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <img width="200px" class="active mb-1" id="img_1" src="';
    echo $row['image'];
    echo '">

                                <div class="custom-file text-left">
                                    <input type="file" name="image" class="form-control image-preview-before-upload" data-preview="#viewer" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
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

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Mô tả</label>
                            <div class="form-group">
                                <textarea name="thele" id="thele">';
    echo base64_decode($row['thele']);
    echo '</textarea>
                                <script>
                                    CKEDITOR.replace(\'thele\'); // tham số là biến name của textarea
                                </script>
                            </div>
                        </div>

                    </div>
                    <div class="mb-3">
                        <button type="submit" name="AddCategory" class="btn btn-success">
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
