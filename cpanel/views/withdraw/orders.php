<?php
// statically decompiled from orders.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
$sotin1trang = 12;
if (isset($_GET['page']) && $data_user['level'] == 'admin') {
    $page = max(1, (int) $_GET['page']);
} else {
    $page = 1;
}
$from = ($page - 1) * $sotin1trang;
$where = ' `id` > 0 ';
$create_date = '';
$stk = '';
$username = '';
$banks = '';
if (!empty($_GET['username'])) {
    $username = Anti_xss($_GET['username']);
    $dataUser = $db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $username . '\'');
    $where .= ' AND `user_id` = "' . $dataUser['id'] . '" ';
}
if (!empty($_GET['stk'])) {
    $stk = Anti_xss($_GET['stk']);
    $where .= ' AND `stk` LIKE "%' . $stk . '%" ';
}
if (!empty($_GET['bank'])) {
    $banks = Anti_xss($_GET['bank']);
    $where .= ' AND `bank` LIKE "%' . $banks . '%" ';
}
if (!empty($_GET['create_date'])) {
    $create_date = Anti_xss($_GET['create_date']);
    $create_date_1 = $create_date;
    $create_date_1 = explode(' to ', $create_date_1);
    if ($create_date_1[0] != $create_date_1[1]) {
        $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
        $where .= ' AND `create_gettime` >= \'' . $create_date_1[0] . '\' AND `create_gettime` <= \'' . $create_date_1[1] . '\' ';
    }
}
$listOrder = $db->get_list(' SELECT * FROM `withdraw_ctv` WHERE ' . $where . ' ORDER BY id DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
$dataSumary = $db->get_list('SELECT * FROM `withdraw_ctv` WHERE ' . $where);
$totalTransactions = 0;
$totalAmount = 0;
$status0Transactions = 0;
$status2Transactions = 0;
$status3Transactions = 0;
foreach ($dataSumary as $transaction) {
    $totalAmount += $transaction['amount'];
    ++$totalTransactions;
    if ($transaction['status'] == '0') {
        ++$status0Transactions;
    }
    if ($transaction['status'] == '2') {
        ++$status2Transactions;
    }
    if ($transaction['status'] == '1') {
        ++$status3Transactions;
    }
}
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><i class="fa fa-shopping-cart"></i> ĐƠN RÚT TIỀN</h4>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    ĐƠN RÚT TIỀN
                </div>
            </div>
            <div class="block-content">
                <div class="row mb-2">
                    <div class="col-sm-12 mb-3">
                        <form action="" name="formSearch" method="GET">
                            <div class="row">
                                <div class="mb-3 col-12 col-sm-6 col-lg-3">
                                    <input type="text" class="form-control" value="';
echo $username;
echo '" name="username" placeholder="Tài khoản CTV">
                                </div>
                                <div class="mb-3 col-12 col-sm-6 col-lg-3">
                                    <input type="text" class="form-control" value="';
echo $stk;
echo '" name="stk" placeholder="Số tài khoản">
                                </div>
                                <div class="mb-3 col-12 col-sm-6 col-lg-3">
                                    <input type="text" class="form-control" value="';
echo $banks;
echo '" name="bank" placeholder="Ngân hàng">
                                </div>
                                <div class="col-12 col-sm-6 col-lg-3 mb-2">
                                    <input type="text" name="create_date" class="form-control js-flatpickr" id="example-flatpickr-range" value="';
echo $create_date;
echo '" placeholder="Chọn thời gian" data-mode="range">
                                </div>

                                <div class="col-sm-4 mb-2">
                                    <button type="submit" name="submit" value="filter" class="btn btn-info"><i class="fa fa-search"></i>
                                        Tìm kiếm
                                    </button>
                                    <a class="btn btn-danger" href="/cpanel/withdraw/orders"><i class="fa fa-trash"></i>
                                        Reset
                                    </a>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-lg-12 m--margin-bottom-10-tablet-and-mobile" style="font-size: 14px ">
                        Tổng số đơn đã rút: <b id="total_record">';
echo format_cash($totalTransactions);
echo '</b> -
                        Đang xử lý: <b>';
echo format_cash($status0Transactions ?? 0);
echo '</b> - Đã hủy:
                        <b>';
echo format_cash($status1Transactions ?? 0);
echo '</b> - Đã thanh toán:
                        <b>';
echo format_cash($status3Transactions ?? 0);
echo '</b>
                    </div>
                </div>
                <div class="table-responsive">
                    <table id="datatable1" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Mã giao dịch</th>
                                <th>CTV</th>
                                <th>Thông tin</th>
                                <th>Số tiền</th>
                                <th>Nội dung</th>
                                <th>Trạng thái</th>
                                <th>Thời Gian</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
$i = 2;
foreach ($listOrder as $row) {
    echo '                                <tr>
                                    <td>';
    echo $row['trans_id'];
    echo '</td>
                                    <td>';
    echo getRowRealTime('users', $row['user_id'], 'username');
    echo '</td>

                                    <td>
                                        <ul>
                                            <li>Ngân hàng: ';
    echo $row['bank'];
    echo '</li>
                                            <li>Số tài khoản: ';
    echo $row['stk'];
    echo '</li>
                                            <li>Chủ tài khoản: ';
    echo $row['name'];
    echo '</li>
                                        </ul>
                                    </td>
                                    <td><b style="color:red">';
    echo format_cash($row['amount']);
    echo '</b></td>
                                    <td><textarea class="form-control">';
    echo $row['reason'];
    echo '</textarea></td>
                                    <td>';
    echo status_withdraw($row['status']);
    echo '</td>
                                    <td>';
    echo $row['update_gettime'];
    echo '</td>
                                    <td><button class="btn btn-info" onclick="show(';
    echo $row['id'];
    echo ',';
    echo $row['status'];
    echo ',`';
    echo $row['reason'];
    echo '`)"><i class="fa fa-eye"></i></button></td>
                                </tr>
                            ';
}
echo '                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">

                    </div>
                    <div class="col-sm-12 col-md-7">
                        ';
$total = $db->num_rows(' SELECT * FROM `withdraw_ctv` WHERE ' . $where . ' ORDER BY id DESC ');
if ($sotin1trang < $total) {
    echo '<center>' . pagination('/cpanel/withdraw/orders?&username=' . $username . '&stk=' . $stk . '&create_date=' . $create_date . '&bank=' . $banks . '&', $from, $total, $sotin1trang) . '</center>';
}
echo '                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
<div class="modal fade" id="modal-diamond">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Thông tin</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="inputEmail3" class="col-form-label">Ghi chú</label>
                    <textarea class="form-control" id="note"></textarea>
                </div>
                <div class="form-group">
                    <label for="inputEmail3" class="col-form-label">Trạng thái</label>
                    <input type="hidden" id="iddiamond">
                    <select class="form-control" id="status">
                        <option value="0">Chờ duyệt</option>
                        <option value="2">Đã thanh toán</option>
                        <option value="1">Đã hủy</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="change" onclick="change()">Lưu ngay</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>

            </div>
        </div>

    </div>
</div>
<script>
    function show(id, status, note) {
        $(\'#iddiamond\').val(id); // Make sure the element with ID \'iddiamond\' exists
        $(\'#note\').val(note); // Make sure the element with ID \'note\' exists
        var selectElement = document.getElementById("status"); // Ensure the element with ID \'status\' exists
        var options = selectElement.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value == status) {
                options[i].selected = true;
            }
        }
        $(\'#modal-diamond\').modal(\'show\'); // Make sure the modal with ID \'modal-diamond\' exists
    }


    function change() {
        $(\'#change\').html(\'Đang xử lý...\').prop(\'disabled\',
            true);
        $.ajax({
            url: "/model/admin/update",
            method: "POST",
            dataType: "JSON",
            data: {
                action:\'updateWithdrawCTV\',
                id: $("#iddiamond").val(),
                note: $("#note").val(),
                status: $("#status").val()
            },
            success: function(response) {
                if (response.status == \'success\') {
                    showMessage(response.msg, response.status);
                    setTimeout(function() {
                        window.location = \'\';
                    }, 1000);
                } else {
                    showMessage(response.msg, response.status);
                }
                $(\'#change\').html(
                        \'Lưu ngay\')
                    .prop(\'disabled\', false);
            }
        });
    }
</script>
';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
