<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `tick` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/tick/list');
    }
} else {
    new Redirect('/cpanel/tick/list');
}
if (isset($_POST['Update']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $url_icon = null;
        if (check_img('image')) {
            unlink('../../..' . $row['icon']);
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/tick/tick' . $rand . '.png';
            $tmp_name = $_FILES['image']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $db->update('tick', ['icon' => $uploads_dir], ' `id` = \'' . $row['id'] . '\' ');
            }
        }
        $isInsert = $db->update('tick', ['name' => Anti_xss($_POST['name'])], ' `id` = \'' . $row['id'] . '\' ');
        if ($isInsert) {
            insetLog($data_user['id'], 'Cập nhật tick');
            exit('<script type="text/javascript">if(!alert("Cập nhật thành công !")){window.history.back().location.reload();}</script>');
        } else {
            exit('<script type="text/javascript">if(!alert("Cập nhật thất bại !")){window.history.back().location.reload();}</script>');
        }
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Chỉnh sửa
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">CHỈNH SỬA</h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-4">
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Ảnh:
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <img width="200px" class="active mb-1" id="img_1" src="';
    echo $row['icon'];
    echo '">

                                <div class="custom-file text-left">
                                    <input type="file" name="image" class="form-control image-preview-before-upload" data-preview="#viewer" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tên:
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input name="name" type="text" class="form-control" required="" value="';
    echo $row['name'];
    echo '">
                            </div>
                        </div>
                        
                    </div>
                    <a type="button" class="btn btn-danger" href="/cpanel/tick/list"><i class="fa fa-fw fa-undo me-1"></i> Quay lại</a>
                    <button type="submit" name="Update" class="btn btn-primary"><i class="fa fa-fw fa-save me-1"></i> Cập Nhập</button>
                </form>

            </div>
        </div>

    </div>
</main>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
