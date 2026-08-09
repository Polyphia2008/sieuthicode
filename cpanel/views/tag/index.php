<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddTag']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $url_icon = null;
        if (check_img('image')) {
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/tag/tag' . $rand . '.png';
            $tmp_name = $_FILES['image']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $url_icon = $tag;
            }
        }
        $isInsert = $db->insert('tag', ['name' => Anti_xss($_POST['name']), 'images' => $url_icon, 'create_date' => gettime()]);
        if ($isInsert) {
            insetLog($data_user['id'], 'Thêm nhãn dán mới vào hệ thống.');
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
                    Nhãn dán
                </h1>
            </div>
            <div class="mt-4 mt-md-0">
                <a class="btn btn-sm btn-success" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-block-popout">
                    <i class="fa fa-plus"></i> Thêm nhãn dán
                </a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách nhãn dán</h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">

                <table class="table table-borderless table-striped table-vcenter" id="datatable">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th class="text-center">Tên</th>
                            <th class="text-center">Thời gian thêm</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
    foreach ($db->get_list(' SELECT * FROM `tag` ORDER BY `id` ASC') as $tag) {
        echo '                            <tr>

                                <td><img width="50px" src="';
        echo $tag['images'];
        echo '" /></td>
                                <td class="text-center">';
        echo $tag['name'];
        echo '</td>
                                <td class="text-center">';
        echo $tag['create_date'];
        echo '</td>
                                <td class="text-center fs-sm">
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="confirmAction(';
        echo $tag['id'];
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
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Thêm nhãn dán</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="block-content">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Tên nhãn dán</label>
                            <input class="form-control" type="text" name="name" placeholder="Nhập tên nhãn dán" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Hình ảnh</label>
                            <input class="form-control" type="file" name="image" required>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button class="btn btn-sm btn-success" type="submit" name="AddTag">Thêm Ngay</button>
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

    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa nhãn dán " + id,
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
                        action: \'removeTag\',
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
