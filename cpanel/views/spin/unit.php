<?php
// statically decompiled from unit.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_GET['limit']) && $data_user['level'] == 'admin') {
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
$listDatatable = $db->get_list(' SELECT * FROM `units` WHERE ' . $where . ' ORDER BY `stt` ASC LIMIT ' . $from . ',' . $limit . ' ');
$totalDatatable = $db->num_rows(' SELECT * FROM `units` WHERE ' . $where . ' ORDER BY id DESC ');
$urlDatatable = pagination('/cpanel/spin/unit?', $from, $totalDatatable, $limit);
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Đơn vị
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thêm đơn vị</h3>
            </div>
            <div class="block-content">
                <form id="form-data" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label class="form-label">Sự ưu tiên</label>
                                <input type="number" name="stt" class="form-control" placeholder="Vị trí hiển thị" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tên đơn vị</label>
                                <input type="text" name="name_product" class="form-control" placeholder="Nhập tên đơn vị ví dụ (Robux)" required>
                                <input type="hidden" name="action" class="form-control" value="add" required>
                            </div>
                        </div>
                       
                        <div class="col-md-12 mb-2">
                            <div id="fields">
                                <label class="form-label">Thêm trường:</label>
                                <div class="row field mb-2">
                                    <div class="col-md-4 mb-1">
                                        <input type="text" name="fields[0][name]" class="form-control" placeholder="Tên trường" required>
                                    </div>
                                    <div class="col-md-4 mb-1">
                                        <select name="fields[0][type]" class="form-select field-type">
                                            <option value="text">Input Text</option>
                                            <option value="number">Number</option>
                                            <option value="select">Select</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 options-container mb-1"></div>
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
                                    CKEDITOR.replace(\'thele\');
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
                <h3 class="block-title">Danh sách đơn vị(';
echo $totalDatatable;
echo ')</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>ID</th>
                               
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
                                        <a class="btn btn-sm btn-success" href="/cpanel/spin/unit/package/';
    echo $category['id'];
    echo '">
                                            <i class="fa fa-list"></i>
                                            List gói
                                        </a>
                                        <a class="btn btn-sm btn-primary" href="/cpanel/spin/unit/update/';
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
        <div class="col-md-4 mb-1">
            <input type="text" name="fields[${index}][name]" class="form-control" placeholder="Tên trường" required>
        </div>
        <div class="col-md-4 mb-1">
            <select name="fields[${index}][type]" class="form-select field-type">
                <option value="text">Input Text</option>
                <option value="number">Number</option>
                <option value="select">Select</option>
            </select>
        </div>
        <div class="col-md-3 options-container mb-1"></div>
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
            let index = field.getAttribute("data-index");

            if (event.target.value === "select") {
                container.innerHTML = `<input type="text" name="fields[${index}][options]" class="form-control" placeholder="Nhập các tùy chọn, cách nhau bởi dấu phẩy">`;
            } else {
                container.innerHTML = "";
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
            url: \'/model/admin/unit\',
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
                $(\'#ThemChuyenMuc\').html(
                        \'Thêm Ngay\')
                    .prop(\'disabled\', false);
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
                action: \'updateTableUnit\',
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
            text: "Bạn đồng ý thực hiện xóa đơn vị " + id,
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
                        action: \'removeUnit\',
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
