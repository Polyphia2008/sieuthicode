<?php
// statically decompiled from card.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['limit']) && is_admin_account($data_user)) {
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
$order_by = 'ORDER BY id DESC';
$username = '';
$transid = '';
$userid = '';
$pin = '';
$createdate = '';
$serial = '';
$status = '';
$shortByDate = '';
if (!empty($_GET['user_id'])) {
    $userid = Anti_xss($_GET['user_id']);
    $where .= ' AND `user_id` = ' . $userid . ' ';
}
if (!empty($_GET['username'])) {
    $username = Anti_xss($_GET['username']);
    $dataUser = $db->get_row('SELECT * FROM `users` WHERE `username` = \'' . $username . '\'');
    $where .= ' AND `user_id` LIKE "' . $dataUser['id'] . '" ';
}
if (!empty($_GET['status'])) {
    $status = Anti_xss($_GET['status']);
    $where .= ' AND `status` = "' . $status . '" ';
}
if (!empty($_GET['pin'])) {
    $pin = Anti_xss($_GET['pin']);
    $where .= ' AND `pin` LIKE "%' . $pin . '%" ';
}
if (!empty($_GET['serial'])) {
    $serial = Anti_xss($_GET['serial']);
    $where .= ' AND `serial` LIKE "%' . $serial . '%" ';
}
if (!empty($_GET['create_date'])) {
    $create_date = Anti_xss($_GET['create_date']);
    $createdate = $create_date;
    $create_date_1 = str_replace('-', '/', $create_date);
    $create_date_1 = explode(' to ', $create_date_1);
    if ($create_date_1[0] != $create_date_1[1]) {
        $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
        $where .= ' AND `create_date` >= \'' . $create_date_1[0] . '\' AND `create_date` <= \'' . $create_date_1[1] . '\' ';
    }
}
if (isset($_GET['shortByDate'])) {
    $shortByDate = Anti_xss($_GET['shortByDate']);
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    $currentWeek = date('W');
    $currentMonth = date('m');
    $currentYear = date('Y');
    $currentDate = date('Y-m-d');
    if ($shortByDate == 1) {
        $where .= ' AND `create_date` LIKE \'%' . $currentDate . '%\' ';
    }
    if ($shortByDate == 2) {
        $where .= ' AND YEAR(create_date) = ' . $currentYear . ' AND WEEK(create_date, 1) = ' . $currentWeek . ' ';
    }
    if ($shortByDate == 3) {
        $where .= ' AND MONTH(create_date) = \'' . $currentMonth . '\' AND YEAR(create_date) = \'' . $currentYear . '\' ';
    }
}
$invoices = $db->get_list('SELECT * FROM `cards` WHERE ' . $where . ' ' . $order_by . ' LIMIT ' . $from . ',' . $limit . ' ');
$dataSumary = $db->get_list('SELECT * FROM `cards` WHERE ' . $where);
$totalTransactions = 0;
$totalAmount = 0;
$totalPrice = 0;
foreach ($dataSumary as $transaction) {
    $totalAmount += $transaction['amount'];
    $totalPrice += $transaction['price'];
    ++$totalTransactions;
}
$yesterday = date('Y-m-d', strtotime('-1 day'));
$currentWeek = date('W');
$currentMonth = date('m');
$currentYear = date('Y');
$currentDate = date('Y-m-d');
$total_all_time = $db->get_row('SELECT SUM(amount) FROM cards WHERE  `status` = \'completed\' ')['SUM(amount)'] ?? 0;
$total_today = $db->get_row('SELECT SUM(amount) FROM cards WHERE  `status` = \'completed\' AND `create_date` LIKE \'%' . $currentDate . '%\' ')['SUM(amount)'] ?? 0;
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title mb-0">Nạp tiền bằng thẻ Điện Thoại, thẻ Game</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Card</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-12">
                <div class="text-right">
                    <?php if (is_superadmin_account($data_user)) { ?>
                    <a class="btn btn-primary label-btn mb-3" href="/cpanel/recharge/card/config">
                        <i class="ri-settings-4-line label-btn-icon me-2"></i> CẤU HÌNH
                    </a>
                    <?php } ?>
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
echo format_cash($total_all_time);
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
echo format_cash($db->get_row('SELECT SUM(amount) FROM cards WHERE `status`=\'completed\' AND MONTH(create_date)=\'' . $currentMonth . '\'AND YEAR(create_date)=\'' . $currentYear . '\'')['SUM(amount)'] ?? 0);
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
echo format_cash($db->get_row('SELECT SUM(amount) FROM cards WHERE `status`=\'completed\' AND YEAR(create_date)=' . $currentYear . ' AND WEEK(create_date,1)=' . $currentWeek . '')['SUM(amount)'] ?? 0);
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
echo format_cash($total_today);
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
    $row = $db->get_row('SELECT SUM(amount) FROM cards WHERE DATE(create_date) = \'' . $date . '\' AND `status` = \'completed\'');
    $data[$day - 1] = $row['SUM(amount)'] ?? 0;
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
                    LỊCH SỬ NẠP THẺ CÀO
                </div>
            </div>
            <div class="block-content">
                <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                    <div class="row row-cols-lg-auto g-3 mb-3">
                        <div class="col-md-3 col-6">
                            <input class="form-control form-control-sm" value="';
echo $userid;
echo '" name="user_id" placeholder="Search ID User">
                        </div>
                        <div class="col-md-3 col-6">
                            <input class="form-control form-control-sm" value="';
echo $username;
echo '" name="username" placeholder="Search Username">
                        </div>
                        <div class="col-md-3 col-6">
                            <input class="form-control form-control-sm" value="';
echo $pin;
echo '" name="pins" placeholder="Search Pin">
                        </div>
                        <div class="col-md-3 col-6">
                            <input class="form-control form-control-sm" value="';
echo $serial;
echo '" name="sesrial" placeholder="Search Serial">
                        </div>
                        <div class="col-md-3 col-6">
                            <select class="form-control form-control-sm mb-1" name="status">
                                <option value="">Status </option>
                                <option ';
echo $status == 'pending' ? 'selected' : '';
echo ' value="pending"> Đang chờ xử lý
                                </option>
                                <option ';
echo $status == 'error' ? 'selected' : '';
echo ' value="error"> Thẻ lỗi </option>
                                <option ';
echo $status == 'completed' ? 'selected' : '';
echo ' value="completed"> Thành công
                                </option>
                            </select>
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input type="text" name="create_date" class="form-control form-control-sm js-flatpickr" id="example-flatpickr-range" value="';
echo $createdate;
echo '" placeholder="Chọn thời gian" data-mode="range">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Search
                            </button> <a class="btn btn-sm btn-danger" href="/cpanel/recharge/card"><i class="fa fa-trash"></i>
                                Clearfilter </a>
                        </div>
                    </div>
                    <div class="top-filter">
                        <div class="filter-show"> <label class="filter-label">Giới hạn :</label> <select name="limit" onchange="this.form.submit()" class="form-select filter-select">
                                <option ';
echo $limit == 5 ? 'selected' : '';
echo ' value="5">5</option>
                                <option ';
echo $limit == 10 ? 'selected' : '';
echo ' value="10">10</option>
                                <option ';
echo $limit == 20 ? 'selected' : '';
echo ' value="20">20</option>
                                <option ';
echo $limit == 50 ? 'selected' : '';
echo ' value="50">50</option>
                                <option ';
echo $limit == 100 ? 'selected' : '';
echo ' value="100">100</option>
                                <option ';
echo $limit == 500 ? 'selected' : '';
echo ' value="500">500</option>
                                <option ';
echo $limit == 1000 ? 'selected' : '';
echo ' value="1000">1000 </option>
                            </select> </div>
                        <div class="filter-short"> <label class="filter-label">Short by Date: </label> <select name="shortByDate" onchange="this.form.submit()" class="form-select filter-select">
                                <option value="">Tất cả </option>
                                <option ';
echo $shortByDate == 1 ? 'selected' : '';
echo ' value="1"> Hôm nay </option>
                                <option ';
echo $shortByDate == 2 ? 'selected' : '';
echo ' value="2"> Tuần này </option>
                                <option ';
echo $shortByDate == 3 ? 'selected' : '';
echo ' value="3"> Tháng này </option>
                            </select> </div>
                    </div>
                </form>
                <div class="table-responsive table-wrapper mb-3">
                    <table class="table text-nowrap table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th class="text-center">Telco</th>
                                <th class="text-center">Serial</th>
                                <th class="text-center">Pin</th>
                                <th class="text-center">Mệnhgiá</th>
                                <th class="text-center">Thựcnhận</th>
                                <th class="text-center">Trạngthái</th>
                                <th class="text-center">Createdate</th>
                                <th class="text-center">Updatedate</th>
                                <th class="text-center">Lýdo</th>
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
    echo '                                            [ID ';
    echo $row['user_id'];
    echo ']</a> </td>
                                    <td class="text-center">';
    echo $row['telco'];
    echo ' </td>
                                    <td class="text-center">';
    echo $row['serial'];
    echo ' </td>
                                    <td class="text-center">';
    echo $row['pin'];
    echo ' </td>
                                    <td class="text-right"><b style="color: red;">';
    echo format_cash($row['amount']);
    echo ' </b>
                                    </td>
                                    <td class="text-right"><b style="color: green;">';
    echo format_cash($row['price']);
    echo ' </b>
                                    </td>
                                    <td class="text-center">';
    echo display_service($row['status']);
    echo ' </td>
                                    <td>';
    echo $row['create_date'];
    echo ' </td>
                                    <td>';
    echo $row['update_date'];
    echo ' </td>
                                    <td>';
    echo $row['reason'];
    echo ' </td>
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
echo format_cash($totalPrice);
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
$total = $db->num_rows('SELECT * FROM `cards` WHERE ' . $where);
if ($limit < $total) {
    echo '<center>' . pagination('/cpanel/recharge/card?user_id=' . $userid . '&username=' . $username . '&pin=' . $pin . '&serial=' . $serial . '&status=' . $status . '&create_date=' . $createdate . '&limit=' . $limit . '&shortByDate=' . $shortByDate . '&', $from, $total, $limit) . '</center>';
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
