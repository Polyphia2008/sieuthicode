<?php
// statically decompiled from blog-update.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && $data_user['level'] == 'admin') {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `posts` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/blog/list');
    }
} else {
    new Redirect('/cpanel/blog/list');
}
if (isset($_POST['UpdateBlog']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $url_icon = null;
        if (check_img('image')) {
            unlink('../../..' . $row['image']);
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/blog/blog' . $rand . '.png';
            $tmp_name = $_FILES['image']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $db->update('posts', ['image' => $uploads_dir], ' `id` = \'' . $row['id'] . '\' ');
            }
        }
        $isInsert = $db->update('posts', ['title' => Anti_xss($_POST['title']), 'slug' => create_slug(Anti_xss($_POST['title'])), 'content' => isset($_POST['content']) ? base64_encode($_POST['content']) : null, 'status' => Anti_xss($_POST['status']), 'footer' => Anti_xss($_POST['footer'])], ' `id` = \'' . $row['id'] . '\' ');
        if ($isInsert) {
            insetLog($data_user['id'], 'Cập nhật bài viết ' . Anti_xss($_POST['title']) . '');
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
                    Chỉnh sửa bài viết
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">CHỈNH SỬA BÀI VIẾT</h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-4">
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tiêu đề bài viết:
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input name="title" type="text" class="form-control" required="" value="';
    echo $row['title'];
    echo '">
                            </div>
                        </div>
                        <div class="row mb-4">
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
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Chuyên mục <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-select" name="category_id" required="">
                                    <option value="">-- Chọn chuyên mục --</option>
                                    ';
    foreach ($db->get_list('SELECT * FROM `post_category` WHERE `status`  = 1') as $category) {
        echo '                                        <option ';
        echo $row['category_id'] == $category['id'] ? 'selected' : '';
        echo ' value="';
        echo $category['id'];
        echo '">';
        echo $category['name'];
        echo '</option>
                                    ';
    }
    echo '                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Đường dẫn:
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input name="link" type="text" class="form-control" required="" value="';
    echo $row['link'];
    echo '">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Hiển thị thông báo: <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="noti" required="">
                                    <option ';
    echo $row['noti'] == 1 ? 'selected' : '';
    echo ' value="1">ON</option>
                                    <option ';
    echo $row['noti'] == 0 ? 'selected' : '';
    echo ' value="0">OFF</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Hiển thị dưới chân trang: <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="footer" required="">
                                    <option ';
    echo $row['footer'] == 1 ? 'selected' : '';
    echo ' value="1">ON</option>
                                    <option ';
    echo $row['footer'] == 0 ? 'selected' : '';
    echo ' value="0">OFF</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nội dung chi tiết:</label>
                            <div class="col-sm-12">
                                <textarea class="content" id="content" name="content">';
    echo base64_decode($row['content']);
    echo '</textarea>
                                <script>
                                    CKEDITOR.replace(\'content\');
                                </script>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Trạng thái: <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="status" required="">
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
                    <a type="button" class="btn btn-danger" href="/cpanel/blog/list"><i class="fa fa-fw fa-undo me-1"></i> Back</a>
                    <button type="submit" name="UpdateBlog" class="btn btn-primary"><i class="fa fa-fw fa-save me-1"></i> Submit</button>
                </form>

            </div>
        </div>

    </div>
</main>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
