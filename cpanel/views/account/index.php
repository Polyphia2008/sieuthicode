<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `subcategory` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/subcategory/view');
    }
    $detail = json_decode($row['detail'], true);
    if (!is_array($detail)) {
        $detail = [];
    }
    $schemaMissing = !isset($detail['data']) || !is_array($detail['data']) || count($detail['data']) === 0;
} else {
    new Redirect('/cpanel/subcategory/view');
}
echo '<main id="main-container">
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách tài khoản ';
echo $detail['name_product'];
echo '</h3>
                <div class="d-flex">
                    ';
if ($schemaMissing) {
    echo '<a href="/cpanel/subcategory/update/' . (int) $row['id'] . '" class="btn btn-sm btn-warning"><i class="fa fa-wrench"></i> Cấu hình Tài khoản/Mật khẩu</a>';
} else {
    echo '<button data-bs-toggle="modal" data-bs-target="#accountModal" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i class="ri-add-line fw-semibold align-middle"></i> Đăng tài khoản</button>';
}
echo '
                </div>
            </div>

            <div class="block-content">
                ';
if ($schemaMissing) {
    echo '<div class="alert alert-warning"><strong>Danh mục này chưa có cấu hình dữ liệu.</strong> Đây là lỗi từ phiên bản cũ khiến Tài khoản/Mật khẩu bị bỏ qua. Nhấn “Cấu hình Tài khoản/Mật khẩu”, kiểm tra hai trường mặc định rồi bấm LƯU NGAY trước khi đăng kho.</div>';
}
echo '
                <div class="row mb-2">
                    <div class="col-md-4">
                        <label class="form-label">Người đăng</label>
                        <input class="form-control" id="username" placeholder="Nhập Username" onchange="filter_datatable()" type="search">
                    </div>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead class="thead-light">
                            <tr>
                                <th width="5px;"><input type="checkbox" class="form-check-input" name="check_all" id="check_all"
                                        value="option1"></th>
                                <th>ID</th>
                                <th>Hình ảnh</th>
                                <th>Người đăng</th>
                                <th>Tài khoản</th>
                                <th>Giá tiền</th>
                                <th>Khuyến mại</th>
                                <th>Trạng thái</th>
                                <th>Chỉnh sửa</th>
                                <th>Xóa</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-sm-12 col-md-5 mb-2">
                            <button class="btn btn-danger btn-sm" type="button" onclick="deleteConfirm()"
                                name="btn_delete"><i class="fas fa-trash mr-1"></i>Xóa đã chọn</button>
                                <button class="btn btn-danger btn-sm" type="button" onclick="confirmActionSold()"
                                ><i class="fas fa-trash mr-1"></i>Xóa đã bán</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="modal fade" id="accountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="block-header">
                <h3 class="block-title">Thêm tài khoản</h3>
                <div class="block-options">
                    <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa fa-fw fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="block-content">
                <form class="form-data" id="form-data">
                    <input hidden name="type_category" value="';
echo $row['type_category'];
echo '">
                    <input hidden name="action" value="add">
                    <div class="row">
                        ';
if ($row['type'] == 'RANDOM') {
    echo '                            <div class="col-md-12">
                                <div class="mb-2">
                                    <label>Nhập dữ liệu</label>
                                    ';
    $placeholder = '';
    $i = 0;
    while ($i < count($detail['data'])) {
        $placeholder .= $detail['data'][$i]['label'];
        if ($i < count($detail['data']) - 1) {
            $placeholder .= '|';
        }
        ++$i;
    }
    echo '                                    <textarea type="text" class="form-control" name="data" placeholder="';
    echo $placeholder;
    echo '"></textarea>
                                    <small class="text-muted">Mỗi tài khoản một dòng. Dùng dấu | theo cấu hình; với Tài khoản/Mật khẩu cũng chấp nhận dấu : (ví dụ user:pass).</small>
                                </div>
                            </div>
                        ';
} else {
    echo '
                            ';
    $i = 0;
    while ($i < count($detail['data'])) {
        echo '                                <div class="col-md-6">
                                    <div class="mb-2">
                                        <label>';
        echo $detail['data'][$i]['label'];
        echo '</label>
                                        ';
        if ($detail['data'][$i]['type'] == 'input' || $detail['data'][$i]['type'] == 'password') {
            echo '
                                            <input class="form-control" type="text" placeholder="';
            echo $detail['data'][$i]['label'];
            echo '" name="';
            echo $detail['data'][$i]['name'];
            echo '">
                                        ';
        } else {
            if ($detail['data'][$i]['type'] == 'number') {
                echo '
                                            <input class="form-control" type="number" placeholder="';
                echo $detail['data'][$i]['label'];
                echo '" name="';
                echo $detail['data'][$i]['name'];
                echo '">
                                        ';
            } else {
                if ($detail['data'][$i]['type'] == 'select') {
                    echo '                                            <select class="form-control" name="';
                    echo $detail['data'][$i]['name'];
                    echo '">
                                                ';
                    $value = $detail['data'][$i]['value'];
                    $ArrayValue = explode('|', $value);
                    foreach ($ArrayValue as $optionValue) {
                        echo '<option value="' . $optionValue . '">' . $optionValue . '</option>';
                    }
                    echo '                                            </select>
                                        ';
                }
            }
        }
        echo '                                    </div>
                                </div>
                            ';
        ++$i;
    }
    echo '                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label>Giá tiền</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="cash" class="form-control" placeholder="Giá tiền">
                                        <div class="input-group-append">
                                            <span class="input-group-text">đ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-2">
                                    <label>Khuyến mại</label>
                                    <div class="input-group mb-3">
                                        <input type="number" name="sale" class="form-control" placeholder="Khuyến mại" value="0">
                                        <div class="input-group-append">
                                            <span class="input-group-text">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div class="mb-2">
                                    <label>Hình ảnh</label>
                                    <img class="w-100 active mb-1" id="img_1" src="/assets/back-end/img/image-size.png">
                                    <input name="image[]" type="file" class="form-control" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])" multiple>
                                    <small>Có thể chọn 1 hoặc nhiều ảnh</small>

                                </div>
                            </div>
                        ';
}
echo '
                    </div>

                </form>
            </div>
            <div class="block-content block-content-full text-end">
                <button type="button" class="btn btn-success ml-3" id="button" onclick="Upload()"><i class="fa fa-check"></i> Thêm ngay nào</button>
            </div>
        </div>
    </div>
</div>
<script>
    function Upload() {
        $(\'#button\').html(\'<i class="fas fa-spinner fa-pulse"></i> Đang xử lý...\').prop(\'disabled\',
            true);
        $.ajax({
            url: \'/model/admin/account\',
            type: \'POST\',
            dataType: \'JSON\',
            data: new FormData($(\'form#form-data\')[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: function(respone) {
                if (respone.status == \'success\') {
                    showMessage(respone.msg, respone.status);
                    document.getElementById("form-data").reset();
                    $("#img_1").attr("src",
                        "/assets/back-end/img/image-size.png"
                    );
                } else {
                    showMessage(respone.msg, respone.status);
                }
                $(\'#button\').html(
                        \'<i class="fa fa-check"></i> Thêm ngay nào\')
                    .prop(\'disabled\', false);
            }
        });
    }
    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa tài khoản " + id,
            icon: \'warning\',
            showCancelButton: true,
            confirmButtonColor: \'#3085d6\',
            cancelButtonColor: \'#d33\',
            confirmButtonText: \'Đồng ý\',
            cancelButtonText: \'Hủy\'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: \'/model/admin/account\',
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        action: \'delete\',
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
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });

    function postRemove(id) {
        $.ajax({
            url: "/model/admin/account",
            type: \'POST\',
            dataType: "JSON",
            data: {
                action: \'delete\',
                id: id
            },
            success: function(response) {
                if (response.status == \'success\') {
                    Toast.fire({
                        icon: "success",
                        title: "Đã xóa thành công item " + id
                    });
                } else {
                    Toast.fire({
                        icon: "error",
                        title: "Đã xảy ra lỗi khi xoá item " + id
                    });
                }
            }
        });
    }

    function deleteConfirm() {
        var checkbox = document.getElementsByName(\'checkbox_accounts\');
        var hasChecked = false;

        // Kiểm tra xem có checkbox nào được chọn không
        for (var i = 0; i < checkbox.length; i++) {
            if (checkbox[i].checked === true) {
                hasChecked = true;
                break;
            }
        }

        if (!hasChecked) {
            Swal.fire(\'Thất Bại\', "Vui lòng chọn ít nhất một bản ghi để xóa!", \'error\');
            return; // Dừng lại nếu không có bản ghi nào được chọn
        }

        // Xác nhận trước khi xóa
        var result = confirm("Bạn có thực sự muốn xóa các bản ghi đã chọn?");
        if (result) {
            for (var i = 0; i < checkbox.length; i++) {
                if (checkbox[i].checked === true) {
                    postRemove(checkbox[i].value);
                }
            }
            setTimeout(function() {
                location.reload();
            }, 2000);
        }
    }

    $(document).ready(function() {
        $(\'#check_all\').on(\'click\', function() {
            if (this.checked) {
                $(\'.form-check-input\').each(function() {
                    this.checked = true;
                });
            } else {
                $(\'.form-check-input\').each(function() {
                    this.checked = false;
                });
            }
        });
        $(\'.form-check-input\').on(\'click\', function() {
            if ($(\'.form-check-input:checked\').length == $(\'.form-check-input\').length) {
                $(\'#check_all\').prop(\'checked\', true);
            } else {
                $(\'#check_all\').prop(\'checked\', false);
            }
        });
    });

    const confirmActionSold = () => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa tất cả tài khoản đã bán",
            icon: \'warning\',
            showCancelButton: true,
            confirmButtonColor: \'#3085d6\',
            cancelButtonColor: \'#d33\',
            confirmButtonText: \'Đồng ý\',
            cancelButtonText: \'Hủy\'
        }).then(async (confirm) => {
            if (confirm.isConfirmed) {
                await Item();
            }
        });
    }

    const Item = async () => {
        Swal.fire({
            icon: "info",
            title: "Đang xử lý!",
            html: "Không được tắt trang này, vui lòng đợi trong giây lát!",
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {},
        });

        $.ajax({
            url: \'/model/admin/account\',
            method: "POST",
            dataType: "JSON",
            data: {
                action: \'deleteAllSold\'
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
</script>
<script>
    datatable();

    function datatable(username = \'\', data = \'\') {
        var dataTable = $(\'#table1\').DataTable({
            "processing": true,
            "serverSide": true,
            "order": [],
            "searching": false,
            "columnDefs": [{
                "targets": 0, // Cột đầu tiên (index 0)
                "orderable": false // Không cho phép sắp xếp
            }],
            "ajax": {
                url: "/model/admin/listaccount",
                dataType: \'JSON\',
                type: "POST",
                data: {
                    data: data,
                    type: \'';
echo $row['type_category'];
echo '\',
                    username: username,
                },
            },
        });
        $("div.row").addClass(\'table-responsive\');

    }

    function filter_datatable() {
        var data = $("#filter_data").serializeArray();
        var username = $("#username").val();
        $(\'#table1\').DataTable().destroy();
        datatable(username, data);
    }
</script>
';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
