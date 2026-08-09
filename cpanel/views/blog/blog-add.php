<?php
// statically decompiled from blog-add.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddBlog']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $url_icon = null;
        if (check_img('image')) {
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/blog/blog' . $rand . '.png';
            $tmp_name = $_FILES['image']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $url_icon = $category;
            }
        }
        $isInsert = $db->insert('posts', ['user_id' => $data_user['id'], 'image' => $url_icon, 'title' => Anti_xss($_POST['title']), 'slug' => create_slug(Anti_xss($_POST['title'])), 'noti' => Anti_xss($_POST['noti']), 'link' => Anti_xss($_POST['link']), 'category_id' => Anti_xss($_POST['category_id']), 'content' => isset($_POST['content']) ? base64_encode($_POST['content']) : null, 'status' => Anti_xss($_POST['status']), 'footer' => Anti_xss($_POST['footer']), 'created_at' => gettime()]);
        if ($isInsert) {
            insetLog($data_user['id'], 'Thêm bài viết ' . Anti_xss($_POST['title']) . ' vào hệ thống.');
            exit('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
        } else {
            exit('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
        }
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Viết bài mới
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">THÊM BÀI VIẾT MỚI</h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-4">
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Tiêu đề bài viết:
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <input name="title" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Ảnh nổi bật:
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <img width="200px" class="active mb-1" id="img_1" src="/assets/back-end/img/image-size.png">

                                <div class="custom-file text-left">
                                    <input type="file" name="image" class="form-control image-preview-before-upload" data-preview="#viewer" required="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
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
        echo '                                    <option value="';
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
                                <input name="link" placeholder="Đường dẫn nếu có" type="text" class="form-control" required="">
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Hiển thị thông báo <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-select" name="noti" required="">
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Hiển thị dưới chân trang <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-select" name="footer" required="">
                                    <option value="0">OFF</option>
                                    <option value="1">ON</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Nội dung chi tiết:</label>
                            <div class="col-sm-12">
                                <textarea class="content" id="content" name="content"></textarea>
                                <script>
                                    CKEDITOR.replace(\'content\'); 
                                </script>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <label class="col-sm-4 col-form-label" for="example-hf-email">Trạng thái: <span class="text-danger">*</span></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="status" required="">
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                </select>
                            </div>
                        </div>

                    </div>
                    <a type="button" class="btn btn-danger" href="/cpanel/blog/list"><i class="fa fa-fw fa-undo me-1"></i> Back</a>
                    <button type="submit" name="AddBlog" class="btn btn-primary"><i class="fa fa-fw fa-save me-1"></i> Submit</button>
                </form>

            </div>
        </div>

    </div>
</main>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
