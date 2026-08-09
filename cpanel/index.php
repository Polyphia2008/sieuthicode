<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
echo '<main id="main-container">
    <div class="content">
        <h3 class="mb-1">Chào mừng quản trị viên</h3>
        <p>Theo dõi phân tích và thống kê kinh doanh của bạn.</p>
        <div class="row">

            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">

                        <div class="ms-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo usersTotal();
echo '                            </p>
                            <p class="text-muted mb-0">
                                Thành viên
                            </p>
                        </div>
                        <div>
                            <i class="far fa-2x fa-user-circle text-success"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo revenueTotal();
echo 'đ
                            </p>
                            <p class="text-muted mb-0">
                                Tổng nạp
                            </p>
                        </div>
                        <div>
                            <i class="fa fa-2x fa-chart-area text-danger"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">

                        <div class="ms-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo boostingCompletedTotal();
echo '                            </p>
                            <p class="text-muted mb-0">
                                Tổng đơn cày game
                            </p>
                        </div>
                        <div>
                            <i class="fa fa-2x fa-gamepad text-primary"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-pop" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div class="me-3">
                            <p class="fs-3 fw-medium mb-0">
                                ';
echo accountSoldTotal();
echo '                            </p>
                            <p class="text-muted mb-0">
                                Tài khoản đã bán
                            </p>
                        </div>
                        <div>
                            <i class="fa fa-2x fa-box text-warning"></i>
                        </div>
                    </div>
                </a>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-7">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <div class="block-title">THỐNG KÊ NẠP THÁNG ';
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
                                            label: \'Nạp ngân hàng\',
                                            backgroundColor: \'rgb(132, 90, 223)\',
                                            borderColor: \'rgb(132, 90, 223)\',
                                            data: [
                                                ';
$data = [];
$day = 3;
while ($day <= $numOfDays) {
    $date = $year . '-' . $month . '-' . $day;
    $row = $db->get_row('SELECT SUM(`amount`) FROM `invoices` WHERE DATE(FROM_UNIXTIME(create_time)) = \'' . $date . '\'');
    $rowTotal = $row['SUM(`amount`)'];
    $data[$day - 1] = $rowTotal;
    ++$day;
}
$i = 2;
while ($i < $numOfDays) {
    echo $data[$i] . ',';
    ++$i;
}
echo '                                            ],
                                        },
                                        {
                                            label: \'Nạp thẻ cào\',
                                            backgroundColor: \'rgb(73,182,245)\',
                                            borderColor: \'rgb(73,182,245)\',
                                            data: [
                                                ';
$data = [];
$day = 3;
while ($day <= $numOfDays) {
    $date = $year . '-' . $month . '-' . $day;
    $card = $db->get_row('SELECT SUM(amount) FROM cards WHERE DATE(create_date) = \'' . $date . '\' AND `status` = \'completed\'');
    $cardTotal = $card['SUM(amount)'];
    $data[$day - 1] = $cardTotal;
    ++$day;
}
$i = 2;
while ($i < $numOfDays) {
    echo $data[$i] . ',';
    ++$i;
}
echo '                                            ],
                                        },
                                    ]
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
            <div class="col-xl-5">
                <div class="block block-rounded">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">ĐƠN HÀNG GẦN ĐÂY</h3>
                    </div>
                    <div class="block-content">
                        <ul class="timeline timeline-alt" style="height:500px;overflow-x:hidden;overflow-y:auto;">

                            ';
foreach ($db->get_list('SELECT * FROM `history_buy` ORDER BY `updated_at` DESC LIMIT 10') as $history) {
    echo '                                <li class="timeline-event">
                                    <div class="timeline-event-icon bg-danger">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <div class="timeline-event-block block block-rounded">
                                        <div class="block-header block-header-default">
                                            <div class="block-options">
                                                <div class="timeline-event-time block-options-item fs-sm fw-semibold">
                                                    ';
    echo timeAgo($history['updated_at']);
    echo '                                                </div>
                                            </div>
                                        </div>
                                        <div class="block-content">
                                            <div class="d-flex fs-sm">
                                                <a class="flex-shrink-0 img-link me-2" href="javascript:void(0)">
                                                    <img class="img-avatar img-avatar48 img-avatar-thumb" src="/assets/images/avt.png" alt="">
                                                </a>
                                                <div class="flex-grow-1">
                                                    <p>
                                                        <a class="fw-semibold" href="javascript:void(0)">';
    echo $history['username'];
    echo '</a>
                                                        đã
                                                        mua tài khoản #';
    echo $history['id_acc'];
    echo ' giá
                                                        ';
    echo format_cash($history['cash']);
    echo 'đ
                                                        </a>
                                                    </p>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            ';
}
echo '                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>
';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
