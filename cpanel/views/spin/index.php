<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddSpin']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $cover = null;
        $image = null;
        $play = null;
        if (check_img('cover')) {
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/spin/spin' . $rand . '.png';
            $tmp_name = $_FILES['cover']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $cover = $uploads_dir;
            }
        }
        if (check_img('image')) {
            $rands = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dirs = '/upload/spin/spin' . $rands . '.png';
            $tmp_names = $_FILES['image']['tmp_name'];
            $addlogos = move_uploaded_file($tmp_names, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dirs);
            if ($addlogos) {
                $image = $uploads_dirs;
            }
        }
        if (check_img('play')) {
            $randss = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dirss = '/upload/spin/spin' . $randss . '.png';
            $tmp_namess = $_FILES['play']['tmp_name'];
            $addlogoss = move_uploaded_file($tmp_namess, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dirss);
            if ($addlogoss) {
                $play = $uploads_dirss;
            }
        }
        $isInsert = $db->insert('spin_quests', ['stt' => Anti_xss($_POST['stt']), 'cover' => $cover, 'image' => $image, 'play' => $play, 'name' => Anti_xss($_POST['name']), 'prizes' => json_encode([]), 'price' => Anti_xss($_POST['price']), 'status' => Anti_xss($_POST['status']), 'created_at' => gettime(), 'updated_at' => gettime()]);
        if ($isInsert) {
            insetLog($data_user['id'], 'Thêm vòng quay ' . Anti_xss($_POST['name']) . ' vào hệ thống.');
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
                    Vòng quay
                </h1>
            </div>
            <div class="mt-4 mt-md-0">
                <a class="btn btn-sm btn-success" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-block-popout">
                    <i class="fa fa-plus"></i> Thêm vòng quay
                </a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách vòng quay</h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">

                <table class="table table-borderless table-striped table-vcenter" id="datatable">
                    <thead>
                        <tr>
                            <th>Ảnh</th>
                            <th>Tên</th>
                            <th class="text-center">Sự Ưu Tiên</th>
                            <th class="text-center">Trạng Thái</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
    foreach ($db->get_list(' SELECT * FROM `spin_quests` ORDER BY `stt` ASC') as $spin) {
        echo '                            <tr onchange="updateForm(\'';
        echo $spin['id'];
        echo '\')">

                                <td><img width="200px" src="';
        echo $spin['cover'];
        echo '" /></td>
                                <td>';
        echo $spin['name'];
        echo '</td>
                                <td class="text-center" width="8%"><input id="stt';
        echo $spin['id'];
        echo '" class="form-control" type="number" value="';
        echo $spin['stt'];
        echo '"></td>
                                <td class="text-center">
                                    <form action="" method="post">
                                        <div class="form-check form-switch form-check-inline">
                                            <input class="form-check-input" type="checkbox" id="status';
        echo $spin['id'];
        echo '" value="1" ';
        echo $spin['status'] == 1 ? 'checked=""' : '';
        echo '>
                                        </div>
                                    </form>
                                </td>
                                <td class="text-center fs-sm">
                                    <a class="btn btn-sm btn-primary" href="/cpanel/spin/update/';
        echo $spin['id'];
        echo '">
                                        <i class="fa fa-fw fa-edit"></i>
                                    </a>
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="confirmAction(';
        echo $spin['id'];
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
                    <h3 class="block-title">Thêm vòng quay mới</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="block-content">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Sự ưu tiên</label>
                            <input class="form-control" type="number" name="stt" placeholder="Vị trí hiển thị" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Ảnh Bìa</label>
                            <input class="form-control" type="file" name="cover" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Ảnh Vòng Quay</label>
                            <input class="form-control" type="file" name="image" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Nút Quay</label>
                            <input class="form-control" type="file" name="play" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Tên Vòng Quay</label>
                            <input class="form-control" type="text" name="name" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Giá Mỗi Lượt</label>
                            <input class="form-control" type="text" name="price" required>
                        </div>
                        <div class="mb-4">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-control" id="status" name="status">
                                <option value="1">Hoạt động</option>
                                <option value="0">Không hoạt động</option>
                            </select>
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button class="btn btn-sm btn-success" type="submit" name="AddSpin">Thêm Ngay</button>
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
                action: \'updateTableSpin\',
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
            text: "Bạn đồng ý thực hiện xóa vòng quay " + id,
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
                        action: \'removeSpin\',
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
