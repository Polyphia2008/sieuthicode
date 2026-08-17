<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_POST['AddCategory']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `categories` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $isInsert = $db->insert('categories', ['stt' => Anti_xss($_POST['stt']), 'name' => Anti_xss($_POST['name'])]);
            if ($isInsert) {
                insetLog($data_user['id'], 'Thêm chuyên mục (' . Anti_xss($_POST['name']) . ') vào hệ thống.');
                exit('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
            } else {
                exit('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
            }
        }
    }
} else {
    if (isset($_GET['limit'])) {
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
    $name = '';
    if (!empty($_GET['name'])) {
        $name = Anti_xss($_GET['name']);
        $where .= ' AND `name` LIKE "%' . $name . '%" ';
    }
    $listDatatable = $db->get_list(' SELECT * FROM `subcategory` WHERE ' . $where . ' ORDER BY `stt` ASC LIMIT ' . $from . ',' . $limit . ' ');
    $totalDatatable = $db->num_rows(' SELECT * FROM `subcategory` WHERE ' . $where . ' ORDER BY id DESC ');
    $urlDatatable = pagination('/cpanel/subcategory/view?name=' . $name . '&', $from, $totalDatatable, $limit);
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
                        <div class="col-md-2 mb-2">
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
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Chuyên mục</label>
                                <select class="form-control js-select2" name="category">
                                    ';
    foreach ($db->get_list('SELECT * FROM `categories` WHERE `status` = 1 ') as $category) {
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
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Loại tài khoản</label>
                                <select class="form-control js-select2" name="type">
                                    <option value="ACCOUNT">Tài khoản</option>
                                    <option value="RANDOM">Random</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label class="form-label">Chọn nhãn dán</label>
                                <select class="form-control js-select2" name="tag">
                                    <option value="">Không hiển thị</option>
                                    ';
    foreach ($db->get_list('SELECT * FROM `tag` WHERE `id` != \'0\'') as $info) {
        echo '                                        <option value="';
        echo $info['images'];
        echo '">';
        echo $info['name'];
        echo '</option>
                                    ';
    }
    echo '                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giá tiền</label>
                                <input class="form-control" name="cash" type="number" placeholder="Nếu là vận may thì nhập">
                            </div>
                        </div>
                        <div class="col-md-3 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giao dịch ảo</label>
                                <input class="form-control" name="fake" type="number" placeholder="Nhập số lượng giao dịch ảo">
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">Ảnh thumb</label>
                                    <img class="w-100 active mb-1" id="img_1" src="/assets/back-end/img/image-size.png">

                                    <div class="custom-file text-left">
                                        <input type="file" name="thumb" class="form-control image-preview-before-upload" data-preview="#viewer" accept=".jpg,.png,.jpeg,.gif,.webp,image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                        <div class="text-center my-2 text-muted">— hoặc nhập URL ảnh trực tiếp —</div>
                                        <input type="url" name="thumb_url" class="form-control" placeholder="https://example.com/anh-san-pham.jpg" oninput="if(this.value){document.getElementById(\'img_1\').src=this.value}">
                                        <small class="text-muted">Chọn một trong hai: tải file lên hoặc URL JPG/PNG/GIF/WEBP công khai, tối đa 5MB.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 mb-2">
                            <div class="alert alert-info">
                                <strong>Cấu hình dữ liệu tài khoản:</strong> Với loại RANDOM, hãy giữ hai trường mặc định
                                <strong>Tài khoản</strong> và <strong>Mật khẩu</strong>. Khi đăng kho, nhập mỗi tài khoản một dòng theo
                                định dạng <code>taikhoan|matkhau</code> hoặc <code>taikhoan:matkhau</code>. Ô “Tùy chọn” chỉ dùng cho kiểu “Chọn dữ liệu”.
                                Chọn “Không hiển thị trước khi mua” cho thông tin đăng nhập; người mua vẫn thấy đầy đủ sau thanh toán.
                            </div>
                            <div class="row">
                                <div class="col-md-3 mb-2 ">
                                    <div class="form-group">
                                        <label class="form-label">Kiểu dữ liệu:</label>
                                        <select class="form-control select2bs4" name="data_type[]">
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
                                        <input class="form-control" name="data_name[]" type="text" placeholder="Ví dụ: Tài khoản" value="Tài khoản">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">Tùy chọn (chỉ dùng cho kiểu Chọn dữ liệu)</label>
                                        <input class="form-control" type="text" name="data_value[]" placeholder="Ví dụ: Server 1|Server 2">
                                    </div>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <div class="form-group">
                                        <label class="form-label">Hiển thị trước khi mua</label>
                                        <div class="input-group">
                                            <select class="form-control select2bs4" name="data_show[]">
                                                <option value="off" selected>Không hiển thị trước khi mua</option>
                                                <option value="on">Hiển thị trước khi mua</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-success" type="button" onclick="add_();"><i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="gift"></div>

                        <div class="col-md-12 mb-2">
                            <label class="form-label">Chi tiết nhóm</label>
                            <div class="form-group">
                                <textarea name="thele" id="thele"></textarea>
                                <script>
                                    CKEDITOR.replace(\'thele\'); // tham số là biến name của textarea
                                </script>
                            </div>
                        </div>

                    </div>
                    <div class="mb-3">
                        <button type="button" onclick="Upload()" id="ThemChuyenMuc" class="btn btn-success">
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
                                <th class="text-center">Giá trị</th>
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
                                    <td class="text-center">';
        echo format_cash($db->get_row(' SELECT SUM(`money`) FROM `accounts` WHERE `sub_id` = \'' . $category['id'] . '\' ')['SUM(`money`)'] ?? 0);
        echo '                                    </td>
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
                                        <a class="btn btn-sm btn-success" href="/cpanel/subcategory/account/list/';
        echo $category['id'];
        echo '">
                                            <i class="fa fa-list"></i>
                                            List tài khoản
                                        </a>
                                        <a class="btn btn-sm btn-primary" href="/cpanel/subcategory/update/';
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
    var room = 0;

    function add_() {
        room++;
        var objTo = document.getElementById(\'gift\');
        var divtest = document.createElement("div");
        divtest.setAttribute("class", "removeclass" + room);
        var rdiv = \'removeclass\' + room;
        divtest.innerHTML = `<div class="col-md-12">
                            <div class="row">
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
                                                <option value="off" selected>Không hiển thị trước khi mua</option>
                                                <option value="on">Hiển thị trước khi mua</option>
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-danger" type="button" onclick="remove(${room});"><i class="fa fa-minus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`;
        objTo.appendChild(divtest);
    }

    // New categories should work immediately for the common account/password
    // schema. Administrators can remove or customize this second row.
    document.addEventListener(\'DOMContentLoaded\', function () {
        add_();
        var types = document.getElementsByName(\'data_type[]\');
        var names = document.getElementsByName(\'data_name[]\');
        var shows = document.getElementsByName(\'data_show[]\');
        var index = types.length - 1;
        if (index >= 0) {
            types[index].value = \'password\';
            names[index].value = \'Mật khẩu\';
            shows[index].value = \'off\';
        }
    });

    function remove(rid) {
        room = rid - 1;
        $(\'.removeclass\' + rid).remove();
    }

    function Upload() {
        $(\'#ThemChuyenMuc\').html(\'<i class="fa fa-spinner"></i> Đang xử lý...\').prop(\'disabled\',
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
                action: \'updateTableSubCategory\',
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
            text: "Xóa danh mục " + id + "? Toàn bộ tài khoản đang còn trong kho của danh mục này cũng sẽ bị xóa. Lịch sử mua của khách vẫn được giữ.",
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
                        action: \'removeSubCategory\',
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
