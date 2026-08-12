<?php
// statically decompiled from category.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddCategory']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $url_icon = null;
        if (check_img('image')) {
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/blog/icon' . $rand . '.png';
            $tmp_name = $_FILES['image']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $url_icon = $uploads_dir;
            }
        }
        $isInsert = $db->insert('post_category', ['name' => Anti_xss($_POST['name']), 'slug' => create_slug(Anti_xss($_POST['name'])), 'icon' => $url_icon, 'content' => base64_encode($_POST['content']), 'created_at' => gettime()]);
        if ($isInsert) {
            insetLog($data_user['id'], 'Thêm chuyên mục bài viết ' . Anti_xss($_POST['name']) . ' vào hệ thống.');
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
                    Chuyên mục bài viết
                </h1>
            </div>
            <div class="mt-4 mt-md-0">
                <a class="btn btn-sm btn-success" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-block-popout">
                    <i class="fa fa-plus"></i> Thêm chuyên mục
                </a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">DANH SÁCH CHUYÊN MỤC BÀI VIẾT</h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">

                <table class="table table-borderless table-striped table-vcenter" id="datatable">
                    <thead>
                        <tr>
                            <th>Tên chuyên mục</th>
                            <th>Ảnh</th>
                            <th>Trạng Thái</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
    foreach ($db->get_list(' SELECT * FROM `post_category` ORDER BY `id` DESC') as $category) {
        echo '                            <tr onchange="updateForm(\'';
        echo $category['id'];
        echo '\')">
                                <td>';
        echo $category['name'];
        echo '</td>
                                <td><img width="100px" src="';
        echo $category['icon'];
        echo '" /></td>
                                <td>
                                    <form action="" method="post">
                                        <div class="form-check form-switch form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="status';
        echo $category['id'];
        echo '" value="1" ';
        echo $category['status'] == 1 ? 'checked=""' : '';
        echo '>
                                        </div>
                                    </form>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-primary" href="/cpanel/blog/category/update/';
        echo $category['id'];
        echo '">
                                        <i class="fa fa-fw fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="confirmAction(';
        echo $category['id'];
        echo ')">
                                        <i class="fa fa-fw fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                        ';
    }
    echo '                    </tbody>
                </table>

            </div>
        </div>

    </div>
</main>
<div class="modal fade" id="modal-block-popout" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout modal-lg" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Thêm chuyên mục bài viết</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="block-content">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Tên chuyên mục</label>
                            <input class="form-control" type="text" name="name" placeholder="Nhập tên chuyên mục" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Hình ảnh</label>
                            <input class="form-control" type="file" name="image" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Mô tả</label>
                            <textarea name="content" id="content"></textarea>
                            <script>
                                CKEDITOR.replace(\'content\');
                            </script>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button class="btn btn-sm btn-success" type="submit" name="AddCategory">Thêm Ngay</button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

    function updateForm(id) {
        $.ajax({
            url: "/model/admin/update",
            method: "POST",
            dataType: "JSON",
            data: {
                action: \'updateTableCategoryBlog\',
                id: id,
                stt: $(\'#stt\' + id).val(),
                status: $(\'#status\' + id + \':checked\').val()
            },
            success: function(result) {
                if (result.status == \'success\') {
                    showMessage(result.msg, result.status);
                } else {
                    showMessage(result.msg, result.status);
                }
            },
            error: function() {
                alert(html(result));
                location.reload();
            }
        });
    }

    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa chuyên mục bài viết " + id,
            icon: \'warning\',
            showCancelButton: true,
            confirmButtonColor: \'#3085d6\',
            cancelButtonColor: \'#d33\',
            confirmButtonText: \'Đồng ý\',
            cancelButtonText: \'Hủy\'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: \'/model/admin/delete\',
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        action: \'removeCategoryBlog\',
                        id: id
                    },
                    success: function(result) {
                        if (result.status == \'success\') {
                            Swal.fire(\'Thành công\',
                                `${result.msg}`,
                                \'success\').then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire(\'Thất Bại\', result.msg, \'error\');
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire(\'Thất Bại\', xhr.responseText, \'error\');
                    }
                });
            }
        });
    }
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
