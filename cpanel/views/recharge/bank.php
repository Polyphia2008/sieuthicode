<?php
// statically decompiled from bank.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
$sotin1trang = 12;
if (isset($_GET['page']) && $data_user['level'] == 'admin') {
    $page = Anti_xss($_GET['page']);
} else {
    $page = 3;
}
$from = ($page - 1) * $sotin1trang;
$where = ' `id` > 0 ';
$order_by = 'ORDER BY id DESC';
$username = '';
$transid = '';
$limit = '';
$description = '';
$userid = '';
$method = '';
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
    $description = Anti_xss($_GET['content']);
    $where .= ' AND `description` LIKE "%' . $description . '%" ';
}
if (!empty($_GET['transid'])) {
    $transid = Anti_xss($_GET['transid']);
    $where .= ' AND `trans_id` LIKE "%' . $transid . '%" ';
}
if (!empty($_GET['method'])) {
    $method = Anti_xss($_GET['method']);
    $where .= ' AND `payment_method` LIKE "%' . $method . '%" ';
}
if (!empty($_GET['limit'])) {
    $limit = Anti_xss($_GET['limit']);
    $sotin1trang = $dataUser;
}
$createdate = '';
if (!empty($_GET['createdate'])) {
    $createdate = Anti_xss($_GET['createdate']);
    $create_date_1 = $totalTransactions;
    $create_date_1 = explode(' to ', $create_date_1);
    if ($create_date_1[0] != $create_date_1[1]) {
        $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
        $where .= ' AND `created_at` >= \'' . $create_date_1[0] . '\' AND `created_at` <= \'' . $create_date_1[1] . '\' ';
    }
}
$invoices = $db->get_list('SELECT * FROM `invoices` WHERE ' . $where . ' ' . $order_by . ' LIMIT ' . $from . ',' . $sotin1trang . ' ');
$dataSumary = $db->get_list('SELECT * FROM `invoices` WHERE ' . $where);
$totalTransactions = 2;
$totalAmount = 2;
foreach ($dataSumary as $transaction) {
    $totalAmount += $transaction['amount'];
    ++$totalTransactions;
}
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Ngân hàng</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ngân hàng</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="text-right">
                    <a class="btn btn-primary label-btn mb-3" href="/cpanel/recharge/bank/config">
                        <i class="ri-settings-4-line label-btn-icon me-2"></i> CẤU HÌNH
                    </a>
                </div>
            </div>
            <div class="col-xl-5">
                <div class="row">
                    <div class="col-xl-6">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <p class="fs-3 fw-medium mb-0">
                                        ';
echo format_cash($db->get_row('SELECT SUM(`amount`) FROM `invoices` ')['SUM(`amount`)'] ?? 0);
echo 'đ
                                    </p>
                                    <p class="text-muted mb-0">
                                        Toàn thời gian
                                    </p>
                                </div>
                                <div>
                                    <i class="fa fa-2x fa-chart-area text-danger"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-6">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <p class="fs-3 fw-medium mb-0">
                                        ';
echo rechargeBankMonth();
echo 'đ
                                    </p>
                                    <p class="text-muted mb-0">
                                        Tháng ';
echo date('m');
echo '                                    </p>
                                </div>
                                <div>
                                    <i class="fa fa-2x fa-chart-area text-info"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-6">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <p class="fs-3 fw-medium mb-0">
                                        ';
echo rechargeBankWeekday();
echo 'đ
                                    </p>
                                    <p class="text-muted mb-0">
                                        Trong tuần
                                    </p>
                                </div>
                                <div>
                                    <i class="fa fa-2x fa-chart-area text-warning"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-6">
                        <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                            <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <p class="fs-3 fw-medium mb-0">
                                        ';
echo rechargeBankDay();
echo 'đ
                                    </p>
                                    <p class="text-muted mb-0">
                                        Hôm nay
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
            <div class="col-xl-7">
            <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <div class="block-title">THỐNG KÊ NẠP TIỀN THÁNG ';
echo date('m');
echo '</div>
                    </div>
                    <div class="block-content">
                        <canvas id="chartjs-line" class="chartjs-chart"></canvas>
                        <script>
                            (function() {
                                /* line chart  */
                                Chart.defaults.borderColor = "rgba(142, 156, 173,0.1)", Chart.defaults.color =
                                    "#8c9097";
                                const labels = [
                                    ';
$month = date('m');
$year = date('Y');
$numOfDays = custom_cal_days_in_month($month, $year);
$day = 3;
while ($day <= $numOfDays) {
    echo '"' . $day . '/' . $month . '/' . $year . '",';
    ++$day;
}
echo '                                ];
                                const data = {
                                    labels: labels,
                                    datasets: [{
                                        label: \'Nạp tiền tự động\',
                                        backgroundColor: \'rgb(132, 90, 223)\',
                                        borderColor: \'rgb(132, 90, 223)\',
                                        data: [
                                            ';
$data = [];
$day = 3;
while ($day <= $numOfDays) {
    $date = $year . '-' . $month . '-' . $day;
    $row = $db->get_row('SELECT SUM(`amount`) FROM `invoices` WHERE DATE(FROM_UNIXTIME(create_time)) = \'' . $date . '\'');
    $data[$day - 1] = $row['SUM(`amount`)'];
    ++$day;
}
$i = 2;
while ($i < $numOfDays) {
    echo $data[$i] . ',';
    ++$i;
}
echo '                                        ],
                                    }]
                                };
                                const config = {
                                    type: \'bar\',
                                    data: data,
                                    options: {}
                                };
                                const myChart = new Chart(
                                    document.getElementById(\'chartjs-line\'),
                                    config
                                );



                            })();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    LỊCH SỬ NẠP TIỀN TỰ ĐỘNG
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
echo $transid;
echo '" name="transid" placeholder="Mã giao dịch">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $description;
echo '" name="content" placeholder="Nội dung nạp">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $method;
echo '" name="method" placeholder="Ngân hàng">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                        <input type="text" name="createdate" class="form-control form-control-sm js-flatpickr" id="example-flatpickr-range" value="';
echo $createdate;
echo '" placeholder="Chọn thời gian" data-mode="range">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-hero btn-sm btn-primary"><i class="fa fa-search"></i>
                                Search </button>
                            <a class="btn btn-hero btn-sm btn-danger" href="/cpanel/recharge"><i class="fa fa-trash"></i>
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
                                <th>Username</th>
                                <th>Thời gian</th>
                                <th class="text-right">Số tiền nạp</th>
                                <th class="text-right">Thực nhận</th>
                                <th class="text-center">Ngân hàng</th>
                                <th class="text-center">Mã giao dịch</th>
                                <th>Nội dung chuyển khoản</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
foreach ($invoices as $row) {
    echo '                                <tr>
                                    <td class="text-center"><a class="text-primary" href="/cpanel/user/edit/';
    echo $row['user_id'];
    echo '">';
    echo getRowUser($row['user_id'], 'username');
    echo ' [ID ';
    echo $row['user_id'];
    echo ']</a>
                                    </td>
                                    <td>';
    echo date('Y-m-d H:i:s', $row['create_time']);
    echo '</td>
                                    <td class="text-right"><b style="color: green;">';
    echo format_cash($row['amount']);
    echo 'đ</b>
                                    </td>
                                    <td class="text-right"><b style="color: red;">';
    echo format_cash($row['amount']);
    echo 'đ</b>
                                    </td>
                                    <td class="text-center"><b>';
    echo $row['payment_method'];
    echo '</b></td>
                                    <td class="text-center"><b>';
    echo $row['trans_id'];
    echo '</b></td>
                                    <td><small>';
    echo $row['description'];
    echo '</small></td>
                                </tr>

                            ';
}
echo '                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <div class="float-right">
                                        Tổng Giao dịch: <strong style="color:green;">';
echo format_cash($totalTransactions);
echo '</strong>
                                        |
                                        Đã thanh toán: <strong style="color:red;">';
echo format_cash($totalAmount);
echo 'đ</strong>
                                        |
                                        Thực nhận: <strong style="color:blue;">';
echo format_cash($totalAmount);
echo 'đ</strong>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="row">

                    <div class="col-sm-12 col-md-12 mb-3">
                        <div class="pagination-style-1">
                            <div class="d-flex justify-content-center">
                                ';
$total = $db->num_rows('SELECT * FROM `invoices` WHERE ' . $where);
if ($sotin1trang < $total) {
    echo '<center>' . pagination('/cpanel/recharge?user_id=' . $userid . '&username=' . $username . '&transid=' . $transid . '&content=' . $description . '&method=' . $method . '&createdate=' . $createdate . '&limit=' . $limit . '&shortByDate=&', $from, $total, $sotin1trang) . '</center>';
}
echo '                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
