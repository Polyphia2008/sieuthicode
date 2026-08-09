<?php
// statically decompiled from unit-package.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && $data_user['level'] == 'admin') {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `units` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/spin/unit');
    }
    $detail = json_decode($row['detail'], true);
} else {
    new Redirect('/cpanel/spin/unit');
}
if (isset($_POST['AddPackage']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `package_units` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $isInsert = $db->insert('package_units', ['unit_id' => $id, 'stt' => Anti_xss($_POST['stt']), 'name' => Anti_xss($_POST['name']), 'value' => Anti_xss($_POST['value']), 'status' => Anti_xss($_POST['status'])]);
            if ($isInsert) {
                insetLog($data_user['id'], 'Thêm gói (' . Anti_xss($_POST['name']) . ') vào hệ thống.');
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
                <h3 class="block-title">Thêm gói vào đơn vị</h3>
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
                                <label class="form-label">Tên gói</label>
                                <input type="text" name="name" class="form-control" placeholder="Nhập tên gói" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giá trị gói</label>
                                <input type="text" name="value" class="form-control" placeholder="Giá trị gói" required>
                            </div>
                        </div>
                       
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-control" name="status" required>
                                    <option value="1">ON</option>
                                    <option value="0">OFF</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="AddPackage" class="btn btn-success">
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
                    Danh sách gói [';
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
                            <th>Tên gói</th>
                            <th>Trạng thái</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
    foreach ($db->get_list('SELECT * FROM `package_units` WHERE `unit_id` = \'' . $id . '\' ORDER BY `id` DESC') as $package) {
        echo '                            <tr>
                                <td class="text-center">';
        echo $package['id'];
        echo '</td>
                                <td class="fw-semibold"> <a class="btn btn-sm btn-primary" href="/cpanel/spin/unit/package/edit/';
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
                                    <span class="text-muted">';
        echo $package['status'] == 1 ? 'Hiển thị' : 'Tạm ẩn';
        echo '</span>
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
                        action: \'removePackageUnit\',
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
