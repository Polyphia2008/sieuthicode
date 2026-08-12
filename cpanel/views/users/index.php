<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
$google2fa = new PragmaRX\Google2FA\Google2FA();
if (isset($_POST['AddUser']) && $data_user['level'] == 'admin') {
    $usernames = $_POST['username'];
    $passwords = $_POST['password'];
    $emails = $_POST['email'];
    $moneys = $_POST['money'];
    $errors = [];
    foreach ($usernames as $__key => $username) {
        $key = $__key;
        $username = Anti_xss($usernames[$key]);
        $password = Anti_xss($passwords[$key]);
        $email = Anti_xss($emails[$key]);
        $money = Anti_xss($moneys[$key]);
        if (empty($username) || empty($password) || empty($email)) {
            $errors[] = 'Dòng ' . $key + 1 . ': Không được để trống.';
        } else {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Dòng ' . $key + 1 . ': Email không hợp lệ.';
            } else {
                $stmt = $db->get_row('SELECT id FROM users WHERE username = \'' . $username . '\' OR email = \'' . $email . '\'');
                if ($stmt) {
                    $errors[] = 'Dòng ' . $key + 1 . ': Username hoặc Email đã tồn tại.';
                } else {
                    $db->insert('users', ['username' => $username, 'password' => sha1($password), 'money' => $money, 'email' => Anti_xss($email), 'device' => $_SERVER['HTTP_USER_AGENT'], 'ip' => myip(), 'ref_id' => 0, 'token' => md5(random('QWERTYUIOPASDGHJKLZXCVBNMqwertyuiopasdfghjklzxcvbnm0123456789', 6) . time()), 'SecretKey' => $google2fa->generateSecretKey(), 'time_session' => time(), 'create_date' => gettime()]);
                }
            }
        }
    }
    if (!empty($errors)) {
        exit('<script type="text/javascript">if(!alert("' . implode('\\n', $errors) . '")){window.history.back().location.reload();}</script>');
    } else {
        exit('<script type="text/javascript">if(!alert("Thêm thành viên thành công!")){window.history.back().location.reload();}</script>');
    }
} else {
    $sotin1trang = 12;
    if (isset($_GET['page'])) {
        $page = max(1, (int) $_GET['page']);
    } else {
        $page = 1;
    }
    $from = ($page - 1) * $sotin1trang;
    $where = ' `id` > 0 ';
    $order_by = 'ORDER BY id DESC';
    $username = '';
    $email = '';
    $content = '';
    $money = '';
    $limit = '';
    $ip = '';
    $userid = '';
    $device = '';
    $createdate = '';
    $money = '';
    $status = '';
    $ctv = '';
    if (!empty($_GET['user_id'])) {
        $userid = Anti_xss($_GET['user_id']);
        $where .= ' AND `id` = ' . $userid . ' ';
    }
    if (!empty($_GET['username'])) {
        $username = Anti_xss($_GET['username']);
        $dataUser = $db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $username . '\'');
        $where .= ' AND `id` LIKE "' . $dataUser['id'] . '" ';
    }
    if (!empty($_GET['email'])) {
        $email = Anti_xss($_GET['email']);
        $where .= ' AND `email` LIKE "%' . $email . '%" ';
    }
    if (!empty($_GET['ip'])) {
        $ip = Anti_xss($_GET['ip']);
        $where .= ' AND `ip` LIKE "%' . $ip . '%" ';
    }
    if (!empty($_GET['content'])) {
        $content = Anti_xss($_GET['content']);
        $where .= ' AND `action` LIKE "%' . $content . '%" ';
    }
    if (!empty($_GET['device'])) {
        $device = Anti_xss($_GET['device']);
        $where .= ' AND `device` LIKE "%' . $content . '%" ';
    }
    if (!empty($_GET['limit'])) {
        $limit = min(200, max(1, (int) $_GET['limit']));
        $sotin1trang = max(1, (int) $limit);
        $from = ($page - 1) * $sotin1trang;
    }
    if (!empty($_GET['createdate'])) {
        $createdate = Anti_xss($_GET['createdate']);
        $create_date_1 = $createdate;
        $create_date_1 = explode(' to ', $create_date_1);
        if ($create_date_1[0] != $create_date_1[1]) {
            $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
            $where .= ' AND `create_date` >= \'' . $create_date_1[0] . '\' AND `create_date` <= \'' . $create_date_1[1] . '\' ';
        }
    }
    if (!empty($_GET['status'])) {
        $status = Anti_xss($_GET['status']);
        if ($status == 1) {
            $where .= ' AND `banned` = 0 ';
        } else {
            if ($status == 2) {
                $where .= ' AND `banned` = 1 ';
            }
        }
    }
    if (!empty($_GET['ctv'])) {
        $ctv = Anti_xss($_GET['ctv']);
        if ($ctv == 1) {
            $where .= ' AND `ctv` = 0 ';
        } else {
            if ($ctv == 2) {
                $where .= ' AND `ctv` = 1 ';
            }
        }
    }
    if (!empty($_GET['money'])) {
        $money = Anti_xss($_GET['money']);
        if ($money == 1) {
            $order_by = ' ORDER BY `money` ASC ';
        } else {
            if ($money == 2) {
                $order_by = ' ORDER BY `money` DESC ';
            }
        }
    }
    $listUsers = $db->get_list('SELECT * FROM `users` WHERE ' . $where . ' ' . $order_by . ' LIMIT ' . $from . ',' . $sotin1trang . ' ');
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-users"></i> Thành viên</h4>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    DANH SÁCH THÀNH VIÊN
                </div>
                <div class="d-flex">
                    <button data-bs-toggle="modal" data-bs-target="#exampleModalScrollable2" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i class="ri-add-line fw-semibold align-middle"></i> Thêm thành viên</button>
                </div>
            </div>
            <div class="block-content">
                <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                    <div class="row row-cols-lg-auto g-3 mb-3">
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control" type="number" value="';
    echo $userid;
    echo '" name="user_id" placeholder="ID Khách hàng">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control" type="text" value="';
    echo $username;
    echo '" name="username" placeholder="Username">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control" value="';
    echo $email;
    echo '" name="email" placeholder="Email">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control" value="';
    echo $ip;
    echo '" name="ip" placeholder="Địa chỉ IP">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <select name="ctv" class="form-control">
                                <option value="">Cộng tác viên
                                </option>
                                <option ';
    echo $ctv == 2 ? 'selected' : '';
    echo ' value="2">Có
                                </option>
                                <option ';
    echo $ctv == 1 ? 'selected' : '';
    echo ' value="1">Không
                                </option>
                            </select>
                        </div>

                        <div class="col-lg col-md-4 col-6">
                            <select name="status" class="form-control">
                                <option value="">Trạng thái
                                </option>
                                <option ';
    echo $status == 2 ? 'selected' : '';
    echo ' value="2">Banned
                                </option>
                                <option ';
    echo $status == 1 ? 'selected' : '';
    echo ' value="1">Active
                                </option>
                            </select>
                        </div>

                        <div class="col-lg col-md-4 col-6">
                            <select name="money" class="form-control">
                                <option value="">Sắp xếp số dư
                                </option>
                                <option ';
    echo $money == 1 ? 'selected' : '';
    echo ' value="1">Tăng dần
                                </option>
                                <option ';
    echo $money == 2 ? 'selected' : '';
    echo ' value="2">Giảm dần
                                </option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-hero btn-primary btn-sm"><i class="fa fa-search"></i>
                                Lọc </button>
                            <a class="btn btn-hero btn-danger btn-sm" href="/cpanel/users/list"><i class="fa fa-trash"></i>
                                Bỏ lọc </a>
                        </div>
                    </div>
                    <div class="top-filter">
                        <div class="filter-show">
                            <label class="filter-label">Show :</label>
                            <select name="limit" onchange="this.form.submit()" class="form-select filter-select">
                                <option value="5">5</option>
                                <option selected value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                                <option value="500">500</option>
                                <option value="1000">1000</option>
                            </select>
                        </div>
                        <div class="filter-short">
                            <label class="filter-label">Short by Date:</label>
                            <select name="shortByDate" onchange="this.form.submit()" class="form-select filter-select">
                                <option value="">Tất cả</option>
                                <option value="1">Hôm nay </option>
                                <option value="2">Tuần này </option>
                                <option value="3">
                                    Tháng này </option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="table-responsive table-wrapper mb-3">
                    <table class="table text-nowrap table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <div class="form-check form-check-md d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input" name="check_all" id="check_all_checkbox_users" value="option1">
                                    </div>
                                </th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col" class="text-center">Ví</th>
                                <th scope="col" class="text-center">Admin</th>
                                <th scope="col" class="text-center">Trạng thái</th>
                                <th scope="col" class="text-center">Loại TK</th>
                                <th scope="col">Thời gian</th>
                                <th scope="col" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
    $i = 2;
    foreach ($listUsers as $row) {
        echo '                                <tr>
                                    <td class="text-center">
                                        <div class="form-check form-check-md d-flex align-items-center">
                                            <input type="checkbox" class="form-check-input checkbox_users" data-id="';
        echo $row['id'];
        echo '" name="checkbox_users" value="';
        echo $row['id'];
        echo '" />
                                        </div>
                                    </td>
                                    <td><a class="text-primary" href="/cpanel/user/edit/';
        echo $row['id'];
        echo '">';
        echo $row['username'];
        echo ' [ID ';
        echo $row['id'];
        echo ']</a>
                                    </td>
                                    <td>
                                        <i class="fa fa-envelope" aria-hidden="true"></i> ';
        echo $row['email'];
        echo '                                    </td>
                                    <td class="text-right">
                                        <span class="badge bg-primary">';
        echo format_cash($row['money']);
        echo 'đ</span>
                                    </td>
                                    <td class="text-center">';
        echo $row['level'] == 'admin' ? '<span class="badge bg-success">Admin</span>' : '<span class="badge bg-danger">Không</span>';
        echo '</td>
                                    <td class="text-center">';
        echo $row['banned'] == 1 ? '<span class="badge bg-danger">Banned</span>' : '<span class="badge bg-success">Active</span>';
        echo '</td>
                                    <td class="text-center">';
        echo $row['provider'] == 'google' ? 'Google' : 'Tài khoản';
        echo '</td>
                                    <td><span>';
        echo $row['create_date'];
        echo '</span></td>
                                    <td class="text-center fs-base">
                                        <a href="/cpanel/user/edit/';
        echo $row['id'];
        echo '" class="btn btn-sm btn-primary shadow-primary btn-wave" data-bs-toggle="tooltip" title="Edit">
                                            <i class="fa fa-fw fa-edit"></i> Edit
                                        </a>
                                        <a type="button" onclick="confirmAction(';
        echo $row['id'];
        echo ')" class="btn btn-sm btn-danger shadow-danger btn-wave" data-bs-toggle="tooltip" title="Delete">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            ';
    }
    echo '                        </tbody>
                        <tfoot>
                            <td colspan="9">
                                <div class="btn-list">
                                    <button type="button" onclick="confirmDeleteAccount()" class="btn btn-outline-danger shadow-danger btn-wave btn-sm"><i class="fa-solid fa-trash"></i> XÓA THÀNH VIÊN</button>
                                </div>
                            </td>
                        </tfoot>
                    </table>
                </div>
                <div class="row">

                    <div class="col-sm-12 col-md-12 mb-3">
                        <div class="pagination-style-1">
                            <div class="d-flex justify-content-center">
                                ';
    $total = $db->num_rows('SELECT * FROM `users` WHERE ' . $where);
    if ($sotin1trang < $total) {
        echo '<center>' . pagination('/cpanel/users/list?user_id=' . $userid . '&username=' . $username . '&email=' . $email . '&ip=' . $ip . '&status=' . $status . '&ctv=' . $ctv . '&money=' . $money . '&limit=' . $limit . '&shortByDate=&', $from, $total, $sotin1trang) . '</center>';
    }
    echo '                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>


<div class="modal fade" id="exampleModalScrollable2" tabindex="-1" aria-labelledby="exampleModalScrollable2" data-bs-keyboard="false" aria-hidden="true">
    <!-- Scrollable modal -->
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel2">Thêm thành viên mới</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div id="userFields">
                        <div class="user-entry row mb-2 align-items-center">
                            <div class="col-md-3">
                                <label>Tài khoản</label>
                                <input type="text" class="form-control" name="username[]" placeholder="Tài khoản" required>
                            </div>
                            <div class="col-md-2">
                                <label>Mật khẩu</label>
                                <input type="text" class="form-control" name="password[]" value="123456" placeholder="Mật khẩu" required>
                            </div>
                            <div class="col-md-3">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email[]" value="';
    echo time();
    echo '@gmail.com" required>
                            </div>
                            <div class="col-md-2">
                                <label>Số dư</label>
                                <input type="text" class="form-control" name="money[]" value="0" placeholder="Số dư" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger btn-sm removeUser mt-4">Xóa</button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm mt-2" id="addUser">Thêm dòng</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" name="AddUser" class="btn btn-primary btn-sm">
                        <i class="fa fa-fw fa-plus me-1"></i> Thêm ngay
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function generateRandomEmail() {
            const chars = \'abcdefghijklmnopqrstuvwxyz0123456789\';
            let email = \'\';
            for (let i = 0; i < 8; i++) {
                email += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            return email + \'@example.com\';
        }

        document.getElementById(\'addUser\').addEventListener(\'click\', function() {
            let userFields = document.getElementById(\'userFields\');
            let newEntry = document.createElement(\'div\');
            newEntry.classList.add(\'user-entry\', \'row\', \'mb-2\', \'align-items-center\');
            newEntry.innerHTML = `
            <div class="col-md-3">
                <label>Tài khoản</label>
                <input type="text" class="form-control" name="username[]" placeholder="Tài khoản" required>
            </div>
            <div class="col-md-2">
                <label>Mật khẩu</label>
                <input type="text" class="form-control" name="password[]" value="123456" placeholder="Mật khẩu" required>
            </div>
            <div class="col-md-3">
                <label>Email</label>
                <input type="email" class="form-control" name="email[]" value="${generateRandomEmail()}" required>
            </div>
            <div class="col-md-2">
                <label>Số dư</label>
                <input type="text" class="form-control" name="money[]" value="0" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm removeUser mt-4">Xóa</button>
            </div>
        `;
            userFields.appendChild(newEntry);
        });

        document.addEventListener(\'click\', function(e) {
            if (e.target.classList.contains(\'removeUser\')) {
                e.target.closest(\'.user-entry\').remove();
            }
        });
        document.querySelector("form").addEventListener("submit", function(e) {
            let isValid = true;
            let errorMessages = [];

            document.querySelectorAll(\'.user-entry\').forEach((entry, index) => {
                let username = entry.querySelector(\'input[name="username[]"]\').value.trim();
                let password = entry.querySelector(\'input[name="password[]"]\').value.trim();
                let email = entry.querySelector(\'input[name="email[]"]\').value.trim();

                if (username === "") {
                    isValid = false;
                    errorMessages.push(`Dòng ${index + 1}: Username không được để trống.`);
                }
                if (password.length < 6) {
                    isValid = false;
                    errorMessages.push(`Dòng ${index + 1}: Mật khẩu phải có ít nhất 6 ký tự.`);
                }
                if (!validateEmail(email)) {
                    isValid = false;
                    errorMessages.push(`Dòng ${index + 1}: Email không hợp lệ.`);
                }
            });

            if (!isValid) {
                alert(errorMessages.join("\\n"));
                e.preventDefault(); // Ngăn form submit
            }
        });

        // Hàm kiểm tra định dạng email
        function validateEmail(email) {
            let re = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
            return re.test(email);
        }
    </script>

</div>
<script type="text/javascript">
    function logoutALL() {
        cuteAlert({
            type: "question",
            title: "WARNING",
            message: "The system will exit the login of all accounts, except for the Admin account, do you agree?",
            confirmText: "Agree",
            cancelText: "Close"
        }).then((e) => {
            if (e) {
                $(\'#logoutALL\').html(\'<i class="fa fa-spinner fa-spin"></i>\').prop(\'disabled\', true);
                $.ajax({
                    url: "",
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        action: "logoutALL"
                    },
                    success: function(respone) {
                        if (respone.status == \'success\') {
                            showMessage(\'error\', respone.msg);
                            $(\'#logoutALL\').html(
                                \'<i class="fas fa-right-from-bracket mr-1"></i>THOÁT TẤT CẢ\').prop(
                                \'disabled\', false);
                        } else {
                            showMessage(\'error\', respone.msg);
                        }
                    },
                    error: function() {
                        alert(html(response));
                        location.reload();
                    }
                });
            }
        })
    }
    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa thành viên " + id,
            icon: \'warning\',
            showCancelButton: true,
            confirmButtonColor: \'#3085d6\',
            cancelButtonColor: \'#d33\',
            confirmButtonText: \'Đồng ý\',
            cancelButtonText: \'Hủy\'
        }).then(async (confirm) => {
            if (confirm.isConfirmed) {
                await Item(id);
            }
        });
    }

    const Item = async (id) => {
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
            url: \'/model/admin/delete\',
            method: "POST",
            dataType: "JSON",
            data: {
                action: \'removeUser\',
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


    function postRemoveAccount(id) {
        $.ajax({
            url: "/model/admin/delete",
            type: \'POST\',
            dataType: "JSON",
            data: {
                action: \'removeUser\',
                id: id
            },
            success: function(response) {
                if (response.status == \'success\') {
                    showMessage(\'success\', \'Mục đã được xóa thành công \' + id);
                } else {
                    showMessage(\'error\', \'Đã xảy ra lỗi khi xóa mục \' + id);
                }
            }
        });
    }

    function confirmDeleteAccount() {
        var checkbox = document.getElementsByName(\'checkbox_users\');
        var isAnyCheckboxChecked = false;
        for (var i = 0; i < checkbox.length; i++) {
            if (checkbox[i].checked === true) {
                isAnyCheckboxChecked = true;
                break;
            }
        }
        if (!isAnyCheckboxChecked) {
            alert(\'Lỗi: Vui lòng chọn ít nhất một bản ghi.\');
            return;
        }
        var result = confirm(\'Bạn có đồng ý xóa các bản ghi đã chọn không?\');
        if (result) {
            function postUpdatesSequentially(index) {
                if (index < checkbox.length) {
                    if (checkbox[index].checked === true) {
                        postRemoveAccount(checkbox[index].value);
                    }
                    setTimeout(function() {
                        postUpdatesSequentially(index + 1);
                    }, 100);
                } else {
                    setTimeout(function() {
                        location.reload();
                    }, 1000);
                }
            }
            postUpdatesSequentially(0);
        }
    }

    $(function() {
        $(\'#check_all_checkbox_users\').on(\'click\', function() {
            $(\'.checkbox_users\').prop(\'checked\', this.checked);
        });
        $(\'.checkbox_users\').on(\'click\', function() {
            $(\'#check_all_checkbox_users\').prop(\'checked\', $(\'.checkbox_users:checked\')
                .length === $(\'.checkbox_users\').length);
        });
    });
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
