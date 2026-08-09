<?php
// statically decompiled from edit-subboosting.php  [structured; all 1 record(s) structured]

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
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Sự ưu tiên</label>
                                <input class="form-control" name="stt" type="number" placeholder="Vị trí hiển thị" value="';
echo $row['stt'];
echo '">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Tên sản phẩm</label>
                                <input class="form-control" name="name_product" type="text" placeholder="Tên sản phẩm" value="';
echo $detail['name_product'];
echo '">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Chuyên mục</label>
                                <select class="form-control select2bs4" name="category">
                                    ';
foreach ($db->get_list('SELECT * FROM `boostings` WHERE `status` = 1 ') as $category) {
    echo '                                        <option ';
    echo $row['category'] == $category['id'] ? 'selected' : '';
    echo ' value="';
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
                                    <option value="caythue" ';
echo $row['type'] == 'caythue' ? 'selected' : '';
echo '>Cày thuê</option>
                                    <option value="item" ';
echo $row['type'] == 'item' ? 'selected' : '';
echo '>Bán Item</option>
                                    <option value="robux" ';
echo $row['type'] == 'robux' ? 'selected' : '';
echo '>Bán Robux</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Hệ số</label>
                                <input type="text" name="coefficient" class="form-control" placeholder="Nếu là bán robux thì nhập" value="';
echo isset($detail['coefficient']) ? $detail['coefficient'] : '';
echo '" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Đơn vị</label>
                                <input type="text" name="unit" class="form-control" placeholder="Nếu là bán robux thì nhập ví dụ: robux" value="';
echo isset($detail['unit']) ? $detail['unit'] : '';
echo '" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Mua tối thiểu</label>
                                <input type="text" name="min" class="form-control" placeholder="Nếu là bán robux thì nhập" value="';
echo isset($detail['min']) ? $detail['min'] : '';
echo '" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Mua tối đa</label>
                                <input type="text" name="max" class="form-control" placeholder="Nếu là bán robux thì nhập" value="';
echo isset($detail['max']) ? $detail['max'] : '';
echo '" required>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giao dịch ảo</label>
                                <input class="form-control" name="fake" type="number" placeholder="Số lượng giao dịch ảo" value="';
echo $row['fake'];
echo '">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Link hướng dẫn nếu có</label>
                                <input class="form-control" name="link" type="text" placeholder="Nhập link hướng dẫn" value="';
echo $row['link'];
echo '">
                            </div>
                        </div>



                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Ảnh thumb</label>
                                <img class="w-100 active lazyLoad" id="img_1" src="';
echo DOMAIN . '/' . $detail['thumb'];
echo '">
                                <center>
                                    <span class="btn btn-default btn-file">
                                        <input name="thumb" type="file" class="form-control" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                    </span>
                                </center>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-2">
                        <label class="form-label">Thêm trường:</label>
                        ';
$i = 2;
while ($i < count($detail['data'])) {
    echo '                            <div class="row field mb-2">
                                <div class="col-md-3 mb-1">
                                    <input type="text" name="fields[';
    echo $i;
    echo '][name]" class="form-control" value="';
    echo $detail['data'][$i]['label'];
    echo '" placeholder="Tên trường" required>
                                </div>
                                <div class="col-md-2 mb-1">
                                    <select name="fields[';
    echo $i;
    echo '][type]" class="form-select field-type">
                                        <option value="text" ';
    echo $detail['data'][$i]['type'] == 'text' ? 'selected' : '';
    echo '>Input Text</option>
                                        <option value="number" ';
    echo $detail['data'][$i]['type'] == 'number' ? 'selected' : '';
    echo '>Number</option>
                                        <option value="email" ';
    echo $detail['data'][$i]['type'] == 'email' ? 'selected' : '';
    echo '>Email</option>
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
                                <div class="col-md-3 options-content mb-1">
                                    ';
    if ($detail['data'][$i]['type'] == 'select') {
        echo '                                        <textarea name="fields[';
        echo $i;
        echo '][optionContent]" class="form-control" placeholder="Nhập nội dung tùy chọn">';
        echo $detail['data'][$i]['content'];
        echo '</textarea>
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
                    <a type="button" href="/cpanel/subboosting/view" class="btn btn-danger btn-block waves-effect">
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
        $(\'#LuuChuyenMuc\').html(\'<i class="fa fa-spinner"></i> Đang xử lý...\').prop(\'disabled\', true);

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
