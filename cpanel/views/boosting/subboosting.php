<?php
// statically decompiled from subboosting.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_GET['limit']) && is_admin_account($data_user)) {
    $limit = min(200, max(1, (int) $_GET['limit']));
} else {
    $limit = 12;
}
if (isset($_GET['page'])) {
    $page = max(1, (int) $_GET['page']);
} else {
    $page = 1;
}
$from = ($page - 1) * $limit;
$where = ' `id` > 0 ';
$listDatatable = $db->get_list(' SELECT * FROM `subboostings` WHERE ' . $where . ' ORDER BY `stt` ASC LIMIT ' . $from . ',' . $limit . ' ');
$totalDatatable = $db->num_rows(' SELECT * FROM `subboostings` WHERE ' . $where . ' ORDER BY id DESC ');
$urlDatatable = pagination('/cpanel/subboosting/view?', $from, $totalDatatable, $limit);
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Thiết lập danh mục phụ
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thêm danh mục phụ</h3>
            </div>
            <div class="block-content">
                <form id="form-data" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Chuyên mục</label>
                                <select class="form-control select2bs4" name="category">
                                    ';
foreach ($db->get_list('SELECT * FROM `boostings` WHERE `status` = 1 ') as $category) {
    echo '                                        <option value="';
    echo $category['id'];
    echo '">';
    echo $category['name'];
    echo '</option>
                                    ';
}
echo '                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Thể loại</label>
                                <select class="form-control select2bs4" name="type">
                                    <option value="caythue">Cày thuê</option>
                                    <option value="item">Bán Item</option>
                                    <option value="robux">Bán Robux</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Sự ưu tiên</label>
                                <input type="number" name="stt" class="form-control" placeholder="Vị trí hiển thị" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tên nhóm</label>
                                <input type="text" name="name_product" class="form-control" placeholder="Nhập tên danh mục phụ" required>
                                <input type="hidden" name="action" class="form-control" value="add" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giao dịch ảo</label>
                                <input type="number" name="fake" class="form-control" placeholder="Số lượng giao dịch ảo" required>

                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Hệ số</label>
                                <input type="text" name="coefficient" class="form-control" placeholder="Nếu là bán robux thì nhập" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Đơn vị</label>
                                <input type="text" name="unit" class="form-control" placeholder="Nếu là bán robux thì nhập ví dụ: robux" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Mua tối thiểu</label>
                                <input type="text" name="min" class="form-control" placeholder="Nếu là bán robux thì nhập" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Mua tối đa</label>
                                <input type="text" name="max" class="form-control" placeholder="Nếu là bán robux thì nhập" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Hướng dẫn nếu có</label>
                                <input type="text" name="link" class="form-control" placeholder="Nhập link hướng dẫn" required>
                            </div>
                        </div>


                        <div class="col-md-12 mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Ảnh thumb</label>
                                    <img class="w-100 active mb-1" id="img_1" src="/assets/back-end/img/image-size.png">

                                    <div class="custom-file text-left">
                                        <input type="file" name="thumb" class="form-control image-preview-before-upload" data-preview="#viewer" required="" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div id="fields">
                                <label class="form-label">Thêm trường:</label>
                                <div class="row field mb-2">
                                    <div class="col-md-3 mb-1">
                                        <input type="text" name="fields[0][name]" class="form-control" placeholder="Tên trường" required>
                                    </div>
                                    <div class="col-md-2 mb-1">
                                        <select name="fields[0][type]" class="form-select field-type">
                                            <option value="text">Input Text</option>
                                            <option value="number">Number</option>
                                            <option value="email">Email</option>
                                            <option value="select">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 options-container mb-1"></div>
                                    <div class="col-md-3 options-content mb-1"></div>
                                    <div class="col-md-1 d-flex align-items-center mb-1">
                                        <button type="button" class="btn btn-danger btn-sm remove-field">X</button>
                                    </div>
                                </div>
                            </div>

                            <button type="button" id="add-field" class="btn btn-primary mt-2">Thêm trường</button>
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
                        <button type="submit" onclick="Upload()" id="ThemChuyenMuc" class="btn btn-success">
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
                <h3 class="block-title">Danh sách chuyên mục phụ (';
echo $totalDatatable;
echo ')</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="text-center">Ảnh</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">
                                    Sự ưu tiên
                                </th>
                                <th class="text-center">
                                    Trạng thái
                                </th>
                                <th class="text-center">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
foreach ($listDatatable as $category) {
    $detail = json_decode($category['detail'], true);
    echo '                                <tr onchange="updateForm(\'';
    echo $category['id'];
    echo '\')">
                                    <td>';
    echo $category['id'];
    echo '</td>
                                    <td width="10%"><img width="100%" src="';
    echo DOMAIN . '/' . $detail['thumb'];
    echo '" /></td>
                                    <td class="text-center">';
    echo $detail['name_product'];
    echo '</td>

                                    <td class="text-center" width="8%"><input id="stt';
    echo $category['id'];
    echo '" class="form-control" type="number" value="';
    echo $category['stt'];
    echo '"></td>
                                    <td class="text-center">
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
                                    <td class="text-center fs-sm">
                                        <a class="btn btn-sm btn-success" href="/cpanel/subboosting/package/';
    echo $category['id'];
    echo '">
                                            <i class="fa fa-list"></i>
                                            List gói
                                        </a>
                                        <a class="btn btn-sm btn-primary" href="/cpanel/subboosting/update/';
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
echo '                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <p class="dataTables_info">Showing ';
echo $limit;
echo ' of ';
echo format_cash($totalDatatable);
echo ' Results</p>
                    </div>
                    <div class="col-sm-12 col-md-7 mb-3">
                        ';
echo $limit < $totalDatatable ? $urlDatatable : '';
echo '                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
<script>
    document.getElementById("add-field").addEventListener("click", function() {
        let index = document.querySelectorAll(".field").length;
        let fieldDiv = document.createElement("div");
        fieldDiv.classList.add("row", "field", "mb-2");
        fieldDiv.setAttribute("data-index", index);

        fieldDiv.innerHTML = `
        <div class="col-md-3 mb-1">
            <input type="text" name="fields[${index}][name]" class="form-control" placeholder="Tên trường" required>
        </div>
        <div class="col-md-2 mb-1">
            <select name="fields[${index}][type]" class="form-select field-type">
                <option value="text">Input Text</option>
                <option value="number">Number</option>
                <option value="email">Email</option>
                <option value="select">Select</option>
            </select>
        </div>
        <div class="col-md-3 options-container mb-1"></div>
        <div class="col-md-3 options-content mb-1"></div>
        <div class="col-md-1 d-flex align-items-center mb-1">
            <button type="button" class="btn btn-danger btn-sm remove-field">X</button>
        </div>
    `;

        document.getElementById("fields").appendChild(fieldDiv);
    });

    document.addEventListener("click", function(event) {
        if (event.target.classList.contains("remove-field")) {
            event.target.closest(".field").remove();
        }
    });

    document.addEventListener("change", function(event) {
        if (event.target.classList.contains("field-type")) {
            let field = event.target.closest(".field");
            let container = field.querySelector(".options-container");
            let content = field.querySelector(".options-content");
            let index = field.getAttribute("data-index");

            if (event.target.value === "select") {
                container.innerHTML = `
                <input type="text" name="fields[${index}][options]" class="form-control" placeholder="Nhập các tùy chọn, cách nhau bởi dấu phẩy">
            `;
                content.innerHTML = `
                <textarea name="fields[${index}][optionContent]" class="form-control" placeholder="Nhập nội dung tùy chọn"></textarea>
            `;
            } else {
                container.innerHTML = "";
                content.innerHTML = "";
            }
        }
    });
</script>
<script>
    function Upload() {
        $(\'#ThemChuyenMuc\').html(\'<i class="fa fa-spinner"></i> Đang xử lý...\').prop(\'disabled\',
            true);
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $.ajax({
            url: \'/model/admin/subboosting\',
            type: \'POST\',
            dataType: \'JSON\',
            data: new FormData($(\'form#form-data\')[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: function(respone) {
                if (respone.status == \'success\') {
                    showMessage(respone.msg, respone.status);
                    setTimeout("location.href = \'\';", 2000);
                } else {
                    showMessage(respone.msg, respone.status);
                }
                $(\'#ThemChuyenMuc\').html(\'THÊM NGAY\').prop(\'disabled\', false);
            },
            error: function(xhr, status, error) {
                let errorMessage = "Đã xảy ra lỗi không xác định!";

                if (xhr.status === 0) {
                    errorMessage = "Không thể kết nối đến máy chủ. Vui lòng kiểm tra kết nối mạng.";
                } else if (xhr.status >= 400 && xhr.status < 500) {
                    errorMessage = "Lỗi yêu cầu: " + xhr.status + " - " + xhr.responseText;
                } else if (xhr.status >= 500) {
                    errorMessage = "Lỗi máy chủ: " + xhr.status + ". Vui lòng thử lại sau.";
                } else if (status === "parsererror") {
                    errorMessage = "Phản hồi JSON không hợp lệ.";
                } else if (status === "timeout") {
                    errorMessage = "Yêu cầu bị timeout. Vui lòng thử lại.";
                } else {
                    errorMessage = "Lỗi: " + error;
                }

                showMessage(errorMessage, \'error\');
                $(\'#ThemChuyenMuc\').html(\'THÊM NGAY\').prop(\'disabled\', false);
            }
        });
    }
</script>
<script>
    function updateForm(id) {
        $.ajax({
            url: "/model/admin/update",
            method: "POST",
            dataType: "JSON",
            data: {
                action: \'updateTableSubboosting\',
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
            text: "Bạn đồng ý thực hiện xóa danh mục " + id,
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
                        action: \'removeSubboosting\',
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
