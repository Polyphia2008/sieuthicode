<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
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
    // Recover categories created by the old index-2 bug. Those rows have an
    // empty data schema, so the RANDOM import modal has no placeholder and
    // purchased accounts contain no credentials. Show a safe default schema
    // that the administrator can save immediately.
    if (!isset($detail['data']) || !is_array($detail['data']) || count($detail['data']) === 0) {
        $detail['data'] = [
            [
                'id' => 0,
                'label' => 'Tài khoản',
                'type' => 'input',
                'name' => 'taikhoan',
                'value' => '',
                'show' => 'off',
            ],
            [
                'id' => 1,
                'label' => 'Mật khẩu',
                'type' => 'password',
                'name' => 'matkhau',
                'value' => '',
                'show' => 'off',
            ],
        ];
    }
} else {
    new Redirect('/cpanel/subcategory/view');
}
if (isset($_POST['UpdateCategory']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `categories` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' AND `id` != ' . $row['id'] . ' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $isInsert = $db->update('categories', ['name' => Anti_xss($_POST['name']), 'stt' => Anti_xss($_POST['stt']), 'status' => Anti_xss($_POST['status'])], ' `id` = \'' . $row['id'] . '\' ');
            if ($isInsert) {
                insetLog($data_user['id'], 'Edit Category (' . $row['name'] . ' ID ' . $row['id'] . ').');
                exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
            } else {
                exit('<script type="text/javascript">if(!alert("Lưu thất bại !")){window.history.back().location.reload();}</script>');
            }
        }
    }
} else {
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
                        <div class="col-md-2 mb-2">
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
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Chuyên mục</label>
                                <select class="form-control select2bs4" name="category">
                                    ';
    foreach ($db->get_list('SELECT * FROM `categories` WHERE `status` = 1 ') as $category) {
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
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Loại tài khoản</label>
                                <select class="form-control select2bs4" name="type" disabled>
                                    <option value="ACCOUNT" ';
    echo $row['type'] == 'ACCOUNT' ? 'selected="selected"' : '';
    echo '>Tài
                                        khoản
                                    </option>
                                    <option value="RANDOM" ';
    echo $row['type'] == 'RANDOM' ? 'selected="selected"' : '';
    echo '>Vận may
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Chọn nhãn dán</label>
                                <select class="form-control select2bs4" name="tag">
                                    <option value="">Không hiển thị</option>
                                    ';
    foreach ($db->get_list('SELECT * FROM `tag` WHERE `id` != \'0\'', 0) as $info) {
        echo '                                        <option value="';
        echo $info['images'];
        echo '" ';
        echo $info['images'] == $detail['tag'] ? 'selected="selected"' : '';
        echo '>
                                            ';
        echo $info['name'];
        echo '</option>
                                    ';
    }
    echo '                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giao dịch ảo</label>
                                <input class="form-control" name="fake" type="number" placeholder="Giao dịch ảo" value="';
    echo $row['fake'];
    echo '">
                            </div>
                        </div>
                        ';
    if ($row['type'] == 'RANDOM') {
        echo '                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label class="form-label">Giá tiền</label>
                                    <input name="price" class="form-control" value="';
        echo $detail['cash'];
        echo '">
                                </div>
                            </div>
                        ';
    }
    echo '                    </div>
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
                    ';
    echo '<div class="alert alert-info">Với danh mục RANDOM, hãy cấu hình <strong>Tài khoản</strong> và <strong>Mật khẩu</strong>. Khi đăng kho, nhập mỗi tài khoản một dòng theo định dạng <code>taikhoan|matkhau</code>. Để trống ô Tùy chọn trừ khi kiểu dữ liệu là “Chọn dữ liệu”. Chọn “Không hiển thị trước khi mua” cho thông tin đăng nhập.</div>';
    // Render every configured field. The old decompiled loop started at 2,
    // which hid the first two fields (normally Tài khoản and Mật khẩu).
    $i = 0;
    while ($i < count($detail['data'])) {
        echo '                        <div class="removeclass';
        echo $i + 1;
        echo '">
                            <div class="row">
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">Kiểu dữ liệu:</label>
                                        <select class="form-control select2bs4" name="data_type[]">
                                            <option value="input" ';
        echo $detail['data'][$i]['type'] == 'input' ? 'selected="selected"' : '';
        echo '>
                                                Nhập dữ liệu số & chữ</option>
                                            <option value="select" ';
        echo $detail['data'][$i]['type'] == 'select' ? 'selected="selected"' : '';
        echo '>
                                                Chọn dữ liệu</option>
                                            <option value="number" ';
        echo $detail['data'][$i]['type'] == 'number' ? 'selected="selected"' : '';
        echo '>
                                                Nhập dữ liệu số</option>
                                            <option value="password" ';
        echo $detail['data'][$i]['type'] == 'password' ? 'selected="selected"' : '';
        echo '>
                                                Nhập mật khẩu</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">Tên hiển thị:</label>
                                        <input class="form-control" name="data_name[]" type="text" placeholder="Tên hiển thị" value="';
        echo $detail['data'][$i]['label'];
        echo '">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">Tùy chọn (chỉ dùng cho kiểu Chọn dữ liệu)</label>
                                        <input class="form-control" type="text" name="data_value[]" placeholder="Phân cách dữ liệu bằng ký tự |" value="';
        echo $detail['data'][$i]['value'];
        echo '">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">Hiển thị trước khi mua</label>
                                        <div class="input-group">
                                            <select class="form-control select2bs4" name="data_show[]">
                                                <option value="on" ';
        echo $detail['data'][$i]['show'] == 'on' ? 'selected="selected"' : '';
        echo '>
                                                    Hiển thị cho người dùng</option>
                                                <option value="off" ';
        echo $detail['data'][$i]['show'] == 'off' ? 'selected="selected"' : '';
        echo '>
                                                    Không hiển thị cho người dùng</option>
                                            </select>
                                            <div class="input-group-append">
                                                ';
        if ($i == '0') {
            echo '                                                    <button class="btn btn-success" type="button" onclick="add_();"><i class="fa fa-plus"></i></button>
                                                ';
        } else {
            echo '                                                    <button class="btn btn-danger" type="button" onclick="remove(';
            echo $i + 1;
            echo ');"><i class="fa fa-minus"></i></button>
                                                ';
        }
        echo '                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ';
        ++$i;
    }
    echo '                    <div id="gift"></div>

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
                    <a type="button" href="/cpanel/subcategory/view" class="btn btn-danger btn-block waves-effect">
                        <span>TRỞ LẠI</span>
                    </a>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    var room = ';
    echo count($detail['data']);
    echo ';

    function add_() {
        room++;
        var objTo = document.getElementById(\'gift\');
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "removeclass" + room);
        var rdiv = \'removeclass\' + room;
        divtest.innerHTML = `<div class="row">
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="form-label">Kiểu dữ liệu:</label>
                                    <select class="form-control" name="data_type[]">
                                        <option value="input">Nhập dữ liệu số & chữ</option>
                                        <option value="select">Chọn dữ liệu</option>
                                        <option value="number">Nhập dữ liệu số</option>
                                        <option value="password">Nhập mật khẩu</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="form-label">Tên hiển thị:</label>
                                    <input class="form-control" name="data_name[]" type="text" placeholder="Tên hiển thị">
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="form-label">Tùy chọn (chỉ dùng cho kiểu Chọn dữ liệu)</label>
                                    <input class="form-control" type="text" name="data_value[]" placeholder="Phân cách dữ liệu bằng ký tự |">
                                </div>
                            </div>
                            <div class="col-md-3 mb-2">
                                <div class="form-group">
                                    <label class="form-label">Hiển thị trước khi mua</label>
                                    <div class="input-group">
                                        <select class="form-control" name="data_show[]">
                                            <option value="on">Hiển thị trước khi mua</option>
                                            <option value="off">Không hiển thị trước khi mua</option>
                                        </select>
                                        <div class="input-group-append">
                                            <button class="btn btn-danger" type="button" onclick="remove(${room});"><i class="fa fa-minus"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
        objTo.appendChild(divtest);
    }

    function remove(rid) {
        room = rid - 1;
        $(\'.removeclass\' + rid).remove();
    }

    function Upload() {
        $(\'#LuuChuyenMuc\').html(\'<i class="fa fa-spinner"></i> Đang xử lý...\').prop(\'disabled\',
            true);
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $.ajax({
            url: \'/model/admin/subcategory\',
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
                $(\'#LuuChuyenMuc\').html(\'THÊM NGAY\').prop(\'disabled\', false);
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
                $(\'#LuuChuyenMuc\').html(\'THÊM NGAY\').prop(\'disabled\', false);
            }
        });
    }
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
