<?php
// statically decompiled from package.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && $data_user['level'] == 'admin') {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `subboostings` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/subboosting/view');
    }
    $detail = json_decode($row['detail'], true);
} else {
    new Redirect('/cpanel/subboosting/view');
}
if (isset($_POST['AddCategory']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `package_boostings` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $url_icon = null;
            if (check_img('thumb')) {
                $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
                $uploads_dir = '/upload/item/item' . $rand . '.png';
                $tmp_name = $_FILES['thumb']['tmp_name'];
                $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
                if ($addlogo) {
                    $url_icon = $package;
                }
            }
            $isInsert = $db->insert('package_boostings', ['sub_id' => $id, 'stt' => Anti_xss($_POST['stt']), 'name' => Anti_xss($_POST['name']), 'image' => $url_icon, 'price' => Anti_xss($_POST['price']), 'status' => Anti_xss($_POST['status']), 'thele' => base64_encode($_POST['thele']), 'created_at' => gettime()]);
            if ($isInsert) {
                insetLog($data_user['id'], 'Thêm sản phẩm (' . Anti_xss($_POST['name']) . ') vào hệ thống.');
                exit('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
            } else {
                exit('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
            }
        }
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thêm Sản Phẩm Vào Nhóm</h3>
            </div>
            <div class="block-content">
                <form id="form-data" action="" method="post" enctype="multipart/form-data">
                    <div class="row mb-3">

                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Sự ưu tiên</label>
                                <input type="number" name="stt" class="form-control" placeholder="Vị trí hiển thị" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" placeholder="Nhập tên sản phẩm" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giá sản phẩm</label>
                                <input type="text" name="price" class="form-control" placeholder="Nhập giá sản phẩm" required>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Ảnh thumb</label>
                                    <img class="w-100 active mb-1" id="img_1" src="/assets/back-end/img/image-size.png">

                                    <div class="custom-file text-left">
                                        <input type="file" name="thumb" class="form-control image-preview-before-upload" data-preview="#viewer"  accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-control" name="status" required>
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Mô tả</label>
                            <div class="form-group">
                                <textarea name="thele" id="thele"></textarea>
                                <script>
                                    CKEDITOR.replace(\'thele\'); // tham số là biến name của textarea
                                </script>
                            </div>
                        </div>

                    </div>
                    <div class="mb-3">
                        <button type="submit" name="AddCategory" class="btn btn-success">
                            Thêm Ngay
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">
                    Danh sách gói của nhóm [';
    echo $detail['name_product'];
    echo ']
                </h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">
                <table class="table table-bordered table-striped table-vcenter js-dataTable-responsive" id="datatable">
                    <thead>
                        <tr>
                            <th class="text-center">ID</th>
                            <th>Thao tác</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá sản phẩm</th>
                            <th>Trạng thái</th>
                            <th>Thời gian thêm</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
    foreach ($db->get_list('SELECT * FROM `package_boostings` WHERE `sub_id` = \'' . $id . '\' ORDER BY `id` DESC') as $package) {
        echo '                            <tr>
                                <td class="text-center">';
        echo $package['id'];
        echo '</td>
                                <td class="fw-semibold"> <a class="btn btn-sm btn-primary" href="/cpanel/subboosting/package/edit/';
        echo $package['id'];
        echo '">
                                        <i class="fa fa-fw fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="confirmAction(';
        echo $package['id'];
        echo ')">
                                        <i class="fa fa-fw fa-times"></i>
                                    </a>
                                </td>
                                <td>
                                    ';
        echo $package['name'];
        echo '                                </td>
                                <td>
                                    <b class="text-danger">';
        echo format_cash($package['price']);
        echo '</b>
                                </td>
                                <td>
                                    <span class="text-muted">';
        echo $package['status'] == 1 ? 'Hiển thị' : 'Tạm ẩn';
        echo '</span>
                                </td>
                                <td>
                                ';
        echo $package['created_at'];
        echo '                                </td>
                            </tr>
                        ';
    }
    echo '                    </tbody>
                </table>
            </div>
        </div>
    </div>

</main>
<script>
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa sản phẩm " + id,
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
                        action: \'removePackage\',
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
