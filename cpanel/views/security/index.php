<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddIP']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $isInsert = $db->insert('ip_white', ['ip' => Anti_xss($_POST['ip'])]);
        if ($isInsert) {
            insetLog($data_user['id'], 'Thêm IP truy cập ADMIN vào hệ thống.');
            exit('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
        } else {
            exit('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
        }
    }
} else {
    if (isset($_POST['SaveSettings']) && $data_user['level'] == 'admin') {
        if ($db->site('status_demo') != 0) {
            exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
        } else {
            foreach ($_POST as $__key => $value) {
                $key = $__key;
                $db->update('options', ['value' => $value], ' `key` = \'' . $key . '\' ');
            }
            exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
        }
    } else {
        echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-users"></i> Bảo mật</h4>
        </div>
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    BẢO MẬT
                </div>
                <div class="d-flex">
                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-block-popout" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i class="fa fa-plus"></i> Thêm IP</a>
                </div>
            </div>
            <div class="block-content">
                <div class="row">
                    <div class="col-lg-5">
                        <div class="block block-rounded block-bordered">
                            <div class="block-header border-bottom">
                                <h3 class="block-title">Cấu hình</h3>
                            </div>
                            <form action="" method="POST" class="block-content">
                                <label>Trạng thái</label>
                                <select class="form-control mb-3" name="status_security">
                                    <option ';
        echo $db->site('status_security') == 1 ? 'selected' : '';
        echo ' value="1">ON
                                    </option>
                                    <option ';
        echo $db->site('status_security') == 0 ? 'selected' : '';
        echo ' value="0">
                                        OFF
                                    </option>
                                </select>
                                <h5>Hệ thống sẽ bật tính năng xác minh IP khi truy cập Admin nếu bạn chọn ON (lưu ý: bạn phải nhập IP của bạn vào danh sách phía dưới trước khi chọn ON chức năng này.)</h5>
                                <p>SIEUTHICODE xin lấy phí 100.000đ của quý khách nếu quý khách cần vào Hosting format lại dữ liệu IP (trường hợp này sẽ sãy ra khi quý khách chưa cấu hình IP mà đã chọn ON hoặc IP của quý khách bị thay đổi do nhà mạng v.v).</p>
                                <div class="mb-4">
                                    <button type="submit" class="btn btn-alt-primary" name="SaveSettings">Lưu Ngay</button>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="block block-rounded block-bordered">
                            <div class="block-header border-bottom">
                                <h3 class="block-title">Danh sách IP được phép truy cập ADMIN</h3>
                            </div>
                            <div class="block-content">
                                <div class="block-content block-content-full overflow-x-auto">
                                    <table class="table table-borderless table-striped table-vcenter" id="datatable">
                                        <thead>
                                            <tr>
                                                <th class="text-center">IP</th>
                                                <th class="text-center">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ';
        foreach ($db->get_list(' SELECT * FROM `ip_white` ORDER BY `id` ASC') as $tag) {
            echo '                                                <tr>
                                                    <td class="text-center">';
            echo $tag['ip'];
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
        echo '                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-block-popout" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
        <div class="modal-dialog modal-dialog-popout" role="document">
            <div class="modal-content">
                <div class="block block-rounded block-themed block-transparent mb-0">
                    <div class="block-header bg-primary-dark">
                        <h3 class="block-title">Thêm IP</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa fa-fw fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="block-content">
                            <div class="mb-4">
                                <label class="form-label" for="example-text-input">Nhập IP của bạn:</label>
                                <input class="form-control" type="text" name="ip" placeholder="Vui lòng nhập IP cho phép truy cập" required>
                            </div>
                            <p>Hệ thống chỉ cho phép IP có trong danh sách phía dưới truy cập quản trị, vui lòng
                                lấy IP tại <a target="_blank" href="https://www.ipchicken.com/">https://www.ipchicken.com/</a> sau đó nhập
                                vào ô phía trên và THÊM NGAY.</p>
                            <p>IP của bạn hiện tại là: <b style="color: red;">';
        echo myip();
        echo '</b></p>
                        </div>
                        <div class="block-content block-content-full text-end bg-body">
                            <button class="btn btn-sm btn-success" type="submit" name="AddIP">Thêm Ngay</button>
                            <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </form>
                </div>
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
            text: "Bạn đồng ý thực hiện xóa IP này ra khỏi hệ thống " + id,
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
                        action: \'removeIpWhite\',
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
}
