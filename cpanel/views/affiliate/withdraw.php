<?php
// statically decompiled from withdraw.php  [structured; all 1 record(s) structured]

$title = 'Nhật ký hoa hồng';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
$sotin1trang = 12;
if (isset($_GET['page']) && $data_user['level'] == 'admin') {
    $page = max(1, (int) $_GET['page']);
} else {
    $page = 1;
}
$from = ($page - 1) * $sotin1trang;
$where = ' `id` > 0 ';
$order_by = 'ORDER BY id DESC';
$username = '';
$content = '';
$limit = '';
$userid = '';
$trans_id = '';
$stk = '';
if (!empty($_GET['user_id'])) {
    $userid = Anti_xss($_GET['user_id']);
    $where .= ' AND `user_id` = ' . $userid . ' ';
}
if (!empty($_GET['username'])) {
    $username = Anti_xss($_GET['username']);
    $dataUser = $db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $username . '\'');
    $where .= ' AND `user_id` LIKE "' . $dataUser['id'] . '" ';
}
if (!empty($_GET['content'])) {
    $content = Anti_xss($_GET['content']);
    $where .= ' AND `reason` LIKE "%' . $content . '%" ';
}
if (!empty($_GET['limit'])) {
    $limit = min(200, max(1, (int) $_GET['limit']));
    $sotin1trang = max(1, (int) $limit);
        $from = ($page - 1) * $sotin1trang;
}
if (!empty($_GET['trans_id'])) {
    $trans_id = Anti_xss($_GET['trans_id']);
    $where .= ' AND `trans_id` LIKE "%' . $trans_id . '%" ';
}
if (!empty($_GET['stk'])) {
    $stk = Anti_xss($_GET['stk']);
    $where .= ' AND `stk` LIKE "%' . $stk . '%" ';
}
$createdate = '';
if (!empty($_GET['createdate'])) {
    $createdate = Anti_xss($_GET['createdate']);
    $create_date_1 = $createdate;
    $create_date_1 = explode(' to ', $create_date_1);
    if ($create_date_1[0] != $create_date_1[1]) {
        $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
        $where .= ' AND `created_at` >= \'' . $create_date_1[0] . '\' AND `created_at` <= \'' . $create_date_1[1] . '\' ';
    }
}
$listLogs = $db->get_list('SELECT * FROM `withdraw_ref` WHERE ' . $where . ' ' . $order_by . ' LIMIT ' . $from . ',' . $sotin1trang . ' ');
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Lịch sử rút tiền</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Tiếp thị liên kết</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Lịch sử rút tiền</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo withdrawTotal();
echo 'đ
                            </p>
                            <p class="text-muted mb-0">
                                Tổng số tiền đã rút
                            </p>
                        </div>
                        <div>
                            <i class="fa fa-2x fa-chart-area text-danger"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo withdrawMonth();
echo 'đ
                            </p>
                            <p class="text-muted mb-0">
                                Tiền rút trong tháng ';
echo date('m');
echo '                            </p>
                        </div>
                        <div>
                            <i class="fa fa-2x fa-chart-area text-info"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo withdrawWeekDay();
echo 'đ
                            </p>
                            <p class="text-muted mb-0">
                                Tiền rút trong tuần
                            </p>
                        </div>
                        <div>
                            <i class="fa fa-2x fa-chart-area text-warning"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo withdrawDay();
echo 'đ
                            </p>
                            <p class="text-muted mb-0">
                                Tiền rút hôm nay
                            </p>
                        </div>
                        <div>
                            <i class="fa fa-2x fa-chart-area text-primary"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    ĐƠN RÚT TIỀN HOA HỒNG
                </div>
            </div>
            <div class="block-content">
                <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                    <div class="row row-cols-lg-auto g-3 mb-3">
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $userid;
echo '" name="user_id" placeholder="ID User">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $username;
echo '" name="username" placeholder="Username">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $trans_id;
echo '" name="trans_id" placeholder="Mã giao dịch">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $stk;
echo '" name="stk" placeholder="Số tài khoản">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $content;
echo '" name="content" placeholder="Lý do">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                        <input type="text" name="createdate" class="form-control form-control-sm js-flatpickr" id="example-flatpickr-range" value="';
echo $createdate;
echo '" placeholder="Chọn thời gian" data-mode="range">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-hero btn-sm btn-primary"><i class="fa fa-search"></i>
                                Search </button>
                            <a class="btn btn-hero btn-sm btn-danger" href="/cpanel/affiliate/withdraw"><i class="fa fa-trash"></i>
                                Clear filter </a>
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
                                <option value="1000">1.000</option>
                                <option value="5000">5.000</option>
                                <option value="10000">10.000</option>
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
                                <th>Mã giao dịch</th>
                                <th>Khách hàng</th>
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
foreach ($listLogs as $row) {
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
    echo status_withdraw_orders_admin($row['status']);
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
    echo '`)"><i class="fa fa-eye"></i></button>
                                    </td>
                                </tr>
                            ';
}
echo '                        </tbody>
                    </table>
                </div>
                <div class="row">

                    <div class="col-sm-12 col-md-12 mb-3">
                        <div class="pagination-style-1">
                            <div class="d-flex justify-content-center">
                                ';
$total = $db->num_rows('SELECT * FROM `withdraw_ref` WHERE ' . $where);
if ($sotin1trang < $total) {
    echo '<center>' . pagination('/cpanel/affiliate/withdraw?user_id=' . $userid . '&username=' . $username . '&content=' . $content . '&createdate=' . $createdate . '&limit=' . $limit . '&shortByDate=&', $from, $total, $sotin1trang) . '</center>';
}
echo '                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div id="modal-diamond" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Thông tin</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
            </div>
            <div class="modal-body">
                <div class="mb-2">
                    <label class="form-label">Trạng thái</label>
                    <input type="hidden" id="iddiamond">
                    <select class="form-select mb-3" id="statuss">
                        <option value="0">Chờ duyệt</option>
                        <option value="2">Đã thanh toán</option>
                        <option value="1">Đã hủy</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label">Ghi chú</label>
                    <textarea class="form-control" id="note"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="change" onclick="change()">Lưu ngay</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script>
    function show(id, status, note) {
        $(\'#iddiamond\').val(id);
        $(\'#note\').val(note);
        var selectElement = document.getElementById("statuss");
        var options = selectElement.options;
        for (var i = 0; i < options.length; i++) {
            if (options[i].value == status) {
                options[i].selected = true;
            }
        }
        $(\'#modal-diamond\').modal(\'show\');
    }

    function change() {
        $(\'#change\').html(\'Đang xử lý...\').prop(\'disabled\',
            true);
        $.ajax({
            url: "/model/admin/withdraw",
            method: "POST",
            dataType: "JSON",
            data: {
                id: $("#iddiamond").val(),
                note: $("#note").val(),
                status: $("#statuss").val()
            },
            success: function(response) {
                if (response.status == \'success\') {
                    Swal.fire({
                        icon: \'success\',
                        title: \'Thành công\',
                        text: response.msg
                    })
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                } else {
                    Swal.fire({
                        icon: \'error\',
                        title: \'Có lỗi\',
                        text: response.msg
                    })
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
