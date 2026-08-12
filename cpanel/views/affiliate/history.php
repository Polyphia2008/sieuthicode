<?php
// statically decompiled from history.php  [structured; all 1 record(s) structured]

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
$listLogs = $db->get_list('SELECT * FROM `log_ref` WHERE ' . $where . ' ' . $order_by . ' LIMIT ' . $from . ',' . $sotin1trang . ' ');
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Nhật ký hoa hồng</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Tiếp thị liên kết</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Nhật ký hoa hồng</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">NHẬT KÝ HOA HỒNG</h3>
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
                            <a class="btn btn-hero btn-sm btn-danger" href="/cpanel/affiliate/history"><i class="fa fa-trash"></i>
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
                <div class="table-responsive">
                    <table class="table text-nowrap table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Hoa hồng ban đầu</th>
                                <th>Hoa hồng thay đổi</th>
                                <th>Hoa hồng hiện tại</th>
                                <th>Thời gian</th>
                                <th>Lý do</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
foreach ($listLogs as $row) {
    echo '                                <tr>
                                    <td><a class="text-primary" href="/cpanel/user/edit/';
    echo $row['user_id'];
    echo '">';
    echo getRowUser($row['user_id'], 'username');
    echo ' [ID ';
    echo $row['user_id'];
    echo ']</a>
                                    </td>
                                    <td class="text-right">
                                        <span class="badge bg-success">';
    echo format_cash($row['sotientruoc']);
    echo 'đ</span>
                                    </td>
                                    <td class="text-right"><span class="badge bg-danger">';
    echo format_cash($row['sotienthaydoi']);
    echo 'đ</span></td>
                                    <td class="text-right"><span class="badge bg-primary">';
    echo format_cash($row['sotienhientai']);
    echo 'đ</span>
                                    </td>
                                    <td><span class="badge bg-light text-dark">';
    echo $row['created_at'];
    echo '</span></td>
                                    <td>';
    echo $row['reason'];
    echo '</td>
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
$total = $db->num_rows('SELECT * FROM `log_ref` WHERE ' . $where);
if ($sotin1trang < $total) {
    echo '<center>' . pagination('/cpanel/affiliate/history?user_id=' . $userid . '&username=' . $username . '&trans_id=' . $trans_id . '&stk=' . $stk . '&content=' . $content . '&createdate=' . $createdate . '&limit=' . $limit . '&shortByDate=&', $from, $total, $sotin1trang) . '</center>';
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
