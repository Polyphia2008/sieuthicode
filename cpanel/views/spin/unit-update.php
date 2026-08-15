<?php
// statically decompiled from unit-update.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `units` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/spin/unit');
    }
    $detail = json_decode($row['detail'], true);
} else {
    new Redirect('/cpanel/spin/unit');
}
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Cập nhật danh mục ';
echo $detail['name_product'];
echo '                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-content">
                <form id="form-data" enctype="multipart/form-data" class="mb-2">
                    <input hidden name="action" value="update">
                    <input hidden name="id" value="';
echo $row['id'];
echo '">
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label class="form-label">Sự ưu tiên</label>
                                <input class="form-control" name="stt" type="number" placeholder="Vị trí hiển thị" value="';
echo $row['stt'];
echo '">
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tên đơn vị</label>
                                <input class="form-control" name="name_product" type="text" placeholder="Tên đơn vị" value="';
echo $detail['name_product'];
echo '">
                            </div>
                        </div>
                     
                    </div>
                   
                    <div class="col-md-12 mb-2">
                        <label class="form-label">Thêm trường:</label>
                        ';
$i = 2;
while ($i < count($detail['data'])) {
    echo '                            <div class="row field mb-2">
                                <div class="col-md-4 mb-1">
                                    <input type="text" name="fields[';
    echo $i;
    echo '][name]" class="form-control" value="';
    echo $detail['data'][$i]['label'];
    echo '" placeholder="Tên trường" required>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <select name="fields[';
    echo $i;
    echo '][type]" class="form-select field-type">
                                        <option value="text" ';
    echo $detail['data'][$i]['type'] == 'text' ? 'selected' : '';
    echo '>Input Text</option>
                                        <option value="number" ';
    echo $detail['data'][$i]['type'] == 'number' ? 'selected' : '';
    echo '>Number</option>
                                        <option value="select" ';
    echo $detail['data'][$i]['type'] == 'select' ? 'selected' : '';
    echo '>Select</option>
                                    </select>
                                </div>
                                <div class="col-md-3 options-container mb-1">
                                    ';
    if ($detail['data'][$i]['type'] == 'select') {
        echo '                                        <input type="text" name="fields[';
        echo $i;
        echo '][options]" value="';
        echo $detail['data'][$i]['option'];
        echo '" class="form-control" placeholder="Nhập các tùy chọn, cách nhau bởi dấu phẩy">
                                    ';
    }
    echo '                                </div>
                                <div class="col-md-1 d-flex align-items-center mb-1">
                                    <button type="button" class="btn btn-danger btn-sm remove-field">X</button>
                                </div>
                            </div>
                        ';
    ++$i;
}
echo '                    </div>

                    <div id="fields">

                    </div>
                    <button type="button" id="add-field" class="btn btn-primary mt-2">Thêm trường</button>

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="form-group">
                                <label class="form-label">Thể Lệ</label>
                                <textarea name="thele" id="thele" cols="50" rows="5">';
echo $detail['thele'];
echo '</textarea>
                                <script>
                                    CKEDITOR.replace(\'thele\'); // tham số là biến name của textarea
                                </script>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label class="form-label">Hiển thị</label>
                        <div class="col-sm-12">
                            <select class="form-control show-tick select2bs4" name="status" required>
                                <option ';
echo $row['status'] == 1 ? 'selected' : '';
echo ' value="1">Hiển thị
                                </option>
                                <option ';
echo $row['status'] == 0 ? 'selected' : '';
echo ' value="0">Ẩn</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" id="LuuChuyenMuc" class="btn btn-primary btn-block" onclick="Upload()">
                        <span>LƯU NGAY</span></button>
                    <a type="button" href="/cpanel/spin/unit" class="btn btn-danger btn-block waves-effect">
                        <span>TRỞ LẠI</span>
                    </a>
                </form>
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
        $(\'#LuuChuyenMuc\').html(\'<i class="fa fa-spinner"></i> Đang xử lý...\').prop(\'disabled\', true);

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
                $(\'#LuuChuyenMuc\').html(\'LƯU NGAY\').prop(\'disabled\', false);
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
                $(\'#LuuChuyenMuc\').html(\'LƯU NGAY\').prop(\'disabled\', false);
            }
        });
    }
</script>
';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
