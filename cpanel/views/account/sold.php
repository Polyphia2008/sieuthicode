<?php
// statically decompiled from sold.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
$sotin1trang = 12;
if (isset($_GET['page']) && is_admin_account($data_user)) {
    $page = max(1, (int) $_GET['page']);
} else {
    $page = 1;
}
$from = ($page - 1) * $sotin1trang;
$where = ' `id` > 0 ';
$idacc = '';
$create_date = '';
$username = '';
$username_post = '';
$type_category = '';
if (!empty($_GET['idacc'])) {
    $idacc = Anti_xss($_GET['idacc']);
    $where .= ' AND `id_acc` LIKE "%' . $idacc . '%" ';
}
if (!empty($_GET['username'])) {
    $username = Anti_xss($_GET['username']);
    $where .= ' AND `username` LIKE "%' . $username . '%" ';
}
if (!empty($_GET['username_post'])) {
    $username_post = Anti_xss($_GET['username_post']);
    $where .= ' AND `username_post` LIKE "%' . $username_post . '%" ';
}
if (!empty($_GET['type_category'])) {
    $type_category = Anti_xss($_GET['type_category']);
    $where .= ' AND `type_category` LIKE "%' . $type_category . '%" ';
}
if (!empty($_GET['create_date'])) {
    $create_date = Anti_xss($_GET['create_date']);
    $create_date_1 = $create_date;
    $create_date_1 = explode(' to ', $create_date_1);
    if ($create_date_1[0] != $create_date_1[1]) {
        $start_timestamp = strtotime($create_date_1[0] . ' 00:00:00');
        $end_timestamp = strtotime($create_date_1[1] . ' 23:59:59');
        $where .= ' AND `created_at` >= \'' . $start_timestamp . '\' AND `created_at` <= \'' . $end_timestamp . '\' ';
    }
}
$listOrder = $db->get_list(' SELECT * FROM `history_buy` WHERE ' . $where . ' ORDER BY id DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
$dataSumary = $db->get_list('SELECT * FROM `history_buy` WHERE ' . $where);
$totalTransactions = 0;
$totalAmount = 0;
$totalAmountReal = 0;
foreach ($dataSumary as $transaction) {
    $totalAmount += $transaction['cash'];
    $totalAmountReal += $transaction['cost'];
    ++$totalTransactions;
}
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><i class="fa fa-shopping-cart"></i> Tài khoản đã bán</h4>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    DANH SÁCH TÀI KHOẢN ĐÃ BÁN
                </div>
            </div>
            <div class="block-content">
                <div class="row mb-2">
                    <div class="col-sm-12 mb-3">
                        <form action="" name="formSearch" method="GET">
                            <div class="row">
                                <div class="mb-3 col-12 col-sm-6 col-lg-3">

                                    <input type="text" class="form-control" value="';
echo $idacc;
echo '" name="idacc" placeholder="Mã số acc">
                                </div>
                                <div class="mb-3 col-12 col-sm-6 col-lg-3">

                                    <input type="text" class="form-control" value="';
echo $username;
echo '" name="username" placeholder="Tên người mua">
                                </div>
                                <div class="mb-3 col-12 col-sm-6 col-lg-3">

                                    <input type="text" class="form-control" value="';
echo $username_post;
echo '" name="username_post" placeholder="Tên người bán">
                                </div>

                                <div class="form-group col-12 col-sm-6 col-lg-3 mb-2">
                                    <div class="input-group">
                                        <input type="text" name="create_date" class="form-control js-flatpickr" id="example-flatpickr-range" value="';
echo $create_date;
echo '" placeholder="Chọn thời gian" data-mode="range">

                                    </div>
                                </div>
                                <div class="mb-3 col-12 col-sm-6 col-lg-3">

                                    <select id="status" class="form-control datatable-input select2bs4" name="type_category">
                                        <option value="" selected>-- Tất cả danh mục --
                                        </option>
                                        ';
foreach ($db->get_list('SELECT * FROM `subcategory` WHERE `status`= \'1\'') as $groups) {
    $groups_detail = json_decode($groups['detail'], true);
    echo '                                            <option value="';
    echo $groups['type_category'];
    echo '" ';
    echo $type_category == $groups['type_category'] ? 'selected' : '';
    echo '>';
    echo $groups_detail['name_product'];
    echo '</option>
                                        ';
}
echo '                                    </select>
                                </div>
                                <div class="col-sm-4 mb-2">
                                    <button type="submit" name="submit" value="filter" class="btn btn-info"><i class="fa fa-search"></i>
                                        Tìm kiếm
                                    </button>
                                    <a class="btn btn-danger" href="/cpanel/account/sold"><i class="fa fa-trash"></i>
                                        Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-12 m--margin-bottom-10-tablet-and-mobile" style="font-size: 14px ">
                        Tổng số nick đã bán: <b id="total_record">';
echo format_cash($totalTransactions);
echo '</b> -
                        Doanh thu (Giá gốc): <b id="total_price">';
echo format_cash($totalAmount);
echo '</b> - Doanh
                        thu của CTV (Được hưởng): <b id="total_price">';
echo format_cash($totalAmountReal);
echo '</b>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th width="5px;"><input type="checkbox" class="form-check-input" name="check_all" id="check_all"
                                        value="option1"></th>
                                <th>ID</th>
                                <th>ID Tài Khoản</th>
                                <th>Loại Tài Khoản</th>
                                <th>Tài khoản</th>
                                <th>Người Mua</th>
                                <th>Người Bán</th>
                                <th>Giá</th>
                                <th>Thực nhận</th>
                                <th>Thời Gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
$i = 2;
foreach ($listOrder as $row) {
    $detail = json_decode($row['detail'], true);
    $arr_detail = $detail['data'];
    echo '                                <tr>
                                    <td><input type="checkbox" data-id="';
    echo $row['id'];
    echo '" name="checkbox_accounts"
                                            class="form-check-input checkbox_accounts" value="';
    echo $row['id'];
    echo '" /></td>
                                    <td>';
    echo $row['id'];
    echo '</td>
                                    <td>#';
    echo $row['id_acc'];
    echo '</td>

                                    <td>';
    echo $detail['name_product'];
    echo '</td>
                                    <td>
                                        <ul>
                                            ';
    foreach ($arr_detail as $item) {
        echo '                                                <li class="font-bold text-black">';
        echo $item['label'];
        echo ' : ';
        echo decodecryptData($item['value']);
        echo ' </li>
                                            ';
    }
    echo '                                        </ul>
                                    </td>
                                    <td><b style="color:green">';
    echo $row['username'];
    echo '</b></td>
                                    <td><b style="color:blue">';
    echo $row['username_post'];
    echo '</b></td>

                                    <td><b style="color:red">';
    echo format_cash($row['cash']);
    echo '</b></td>
                                    <td><b style="color:blueviolet">';
    echo format_cash($row['cost']);
    echo '</b></td>
                                    <td>';
    echo date('H:i d-m-Y', $row['created_at']);
    echo '</td>
                                </tr>
                            ';
}
echo '                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5 mb-2">
                        <button class="btn btn-danger btn-sm" type="button" onclick="confirmDeleteAccount()"
                            name="btn_delete"><i class="fas fa-trash mr-1"></i>Delete</button>
                    </div>
                    <div class="col-sm-12 col-md-7 mb-2">
                        ';
$total = $db->num_rows(' SELECT * FROM `history_buy` WHERE ' . $where . ' ORDER BY id DESC ');
if ($sotin1trang < $total) {
    echo '<center>' . pagination('/cpanel/account/sold?idacc=' . $idacc . '&username=' . $username . '&username_post=' . $username_post . '&type_category=' . $type_category . '&create_date=' . $create_date . '&', $from, $total, $sotin1trang) . '</center>';
}
echo '                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
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

    function postRemoveAccount(id) {
        $.ajax({
            url: "/model/admin/account",
            type: \'POST\',
            dataType: "JSON",
            data: {
                action: \'deleteSold\',
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


    function confirmDeleteAccount() {
        var checkbox = document.getElementsByName(\'checkbox_accounts\');
        var isAnyCheckboxChecked = false;
        for (var i = 0; i < checkbox.length; i++) {
            if (checkbox[i].checked === true) {
                isAnyCheckboxChecked = true;
                break;
            }
        }
        if (!isAnyCheckboxChecked) {
            showMessage(\'Vui lòng chọn ít nhất một tài khoản\', \'error\');
            return;
        }
        var result = confirm(\'Bạn có đồng ý xóa các tài khoản đã chọn không?\');
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
        $(\'#check_all\').on(\'click\', function() {
            $(\'.checkbox_accounts\').prop(\'checked\', this.checked);
        });
        $(\'.checkbox_accounts\').on(\'click\', function() {
            $(\'#check_all\').prop(\'checked\', $(\'.checkbox_accounts:checked\')
                .length === $(\'.checkbox_accounts\').length);
        });
    });
    
</script>
';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
