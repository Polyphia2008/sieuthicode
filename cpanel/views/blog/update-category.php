<?php
// statically decompiled from update-category.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `post_category` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/blog/category');
    }
} else {
    new Redirect('/cpanel/blog/category');
}
if (isset($_POST['UpdateCategory']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `post_category` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' AND `id` != ' . $row['id'] . ' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            if (check_img('icon')) {
                unlink('../../..' . $row['icon']);
                $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
                $uploads_dir = '/upload/blog/icon' . $rand . '.png';
                $tmp_name = $_FILES['icon']['tmp_name'];
                $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
                if ($addlogo) {
                    $db->update('post_category', ['icon' => $uploads_dir], ' `id` = \'' . $row['id'] . '\' ');
                }
            }
            $isInsert = $db->update('post_category', ['name' => Anti_xss($_POST['name']), 'slug' => create_slug(Anti_xss($_POST['name'])), 'content' => isset($_POST['content']) ? base64_encode($_POST['content']) : null, 'status' => Anti_xss($_POST['status'])], ' `id` = \'' . $row['id'] . '\' ');
            if ($isInsert) {
                insetLog($data_user['id'], 'Cập nhật chuyên mục bài viết (' . $row['name'] . ' ID ' . $row['id'] . ').');
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
                    Cập nhật chuyên mục bài viết [';
    echo $row['name'];
    echo ']
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-content">
                <form id="form-data" action="" method="post" enctype="multipart/form-data" class="mb-2">
                    <div class="row mb-2">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tên chuyên mục</label>
                                <input class="form-control" name="name" type="text" placeholder="Nhập tên chuyên mục" value="';
    echo $row['name'];
    echo '">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Icon</label>
                                <img class="w-100 active lazyLoad" id="img_1" src="';
    echo $row['icon'];
    echo '">
                                <center>
                                    <span class="btn btn-default btn-file">
                                        <input name="icon" type="file" class="form-control" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                    </span>
                                </center>
                            </div>
                        </div>
                    </div>
                    

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Mô tả</label>
                                <textarea name="content" id="content" cols="50" rows="5">';
    echo base64_decode($row['content']);
    echo '</textarea>
                                <script>
                                    CKEDITOR.replace(\'content\');
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="form-label">Hiển thị</label>
                        <div class="col-sm-12">
                            <select class="form-control show-tick select2bs4" name="status" required>
                                <option ';
    echo $row['status'] == 1 ? 'selected' : '';
    echo ' value="1">Hiển thị
                                </option>
                                <option ';
    echo $row['status'] == 0 ? 'selected' : '';
    echo ' value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="UpdateCategory" class="btn btn-primary btn-block">
                        <span>LƯU NGAY</span></button>
                    <a type="button" href="/cpanel/blog/category" class="btn btn-danger btn-block waves-effect">
                        <span>TRỞ LẠI</span>
                    </a>
                </form>
            </div>
        </div>
    </div>
</main>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
