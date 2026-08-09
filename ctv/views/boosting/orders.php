<?php
// statically decompiled from orders.php  [structured; all 1 record(s) structured]

$title = 'Đơn cày thuê';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/ctv/views/header.php';
if ($data_user['ctv'] != 1 && $data_user['ctv_boosting'] != 1) {
    new Redirect('/ctv/home');
}
$sotin1trang = 12;
if (isset($_GET['page'])) {
    $page = Anti_xss($_GET['page']);
} else {
    $page = 3;
}
$from = ($page - 1) * $sotin1trang;
$where = ' `id` > 0 AND `status` = "pending" AND `receiver` IS NULL';
$order_by = 'ORDER BY id DESC';
$group = '';
$limit = '';
$trans_id = '';
$service = '';
$status = '';
if (!empty($_GET['group'])) {
    $group = Anti_xss($_GET['group']);
    $where .= ' AND `name_sub` LIKE "%' . $group . '%" ';
}
if (!empty($_GET['limit'])) {
    $limit = Anti_xss($_GET['limit']);
    $sotin1trang = $createdate;
}
if (!empty($_GET['trans_id'])) {
    $trans_id = Anti_xss($_GET['trans_id']);
    $where .= ' AND `trans_id` LIKE "%' . $trans_id . '%" ';
}
if (!empty($_GET['service'])) {
    $service = Anti_xss($_GET['service']);
    $where .= ' AND `name` LIKE "%' . $service . '%" ';
}
$createdate = '';
if (!empty($_GET['createdate'])) {
    $createdate = Anti_xss($_GET['createdate']);
    $create_date_1 = $row;
    $create_date_1 = explode(' to ', $create_date_1);
    if ($create_date_1[0] != $create_date_1[1]) {
        $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
        $where .= ' AND `created_at` >= \'' . $create_date_1[0] . '\' AND `created_at` <= \'' . $create_date_1[1] . '\' ';
    }
}
$listLogs = $db->get_list('SELECT * FROM `orders` WHERE ' . $where . ' ' . $order_by . ' LIMIT ' . $from . ',' . $sotin1trang . ' ');
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Đơn hàng cày thuê</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Cày thuê</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Đơn hàng cày thuê</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
   
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    ĐƠN CÀY THUÊ
                </div>
            </div>
            <div class="block-content">
                <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                    <div class="row row-cols-lg-auto g-3 mb-3">
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $trans_id;
echo '" name="trans_id" placeholder="Mã giao dịch">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $service;
echo '" name="service" placeholder="Dịch vụ">
                        </div>
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control form-control-sm" value="';
echo $group;
echo '" name="group" placeholder="Nhóm game">
                        </div>
                      
                        <div class="col-lg col-md-4 col-6">
                            <input type="text" name="createdate" class="form-control form-control-sm js-flatpickr" id="example-flatpickr-range" value="';
echo $createdate;
echo '" placeholder="Chọn thời gian" data-mode="range">
                        </div>
                        <div class="col-12">
                            <button class="btn btn-hero btn-sm btn-primary"><i class="fa fa-search"></i>
                                Lọc </button>
                            <a class="btn btn-hero btn-sm btn-danger" href="/ctv/boosting/orders"><i class="fa fa-trash"></i>
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
                                <th>Thao tác</th>
                                <th>Mã giao dịch</th>
                                <th>Khách hàng</th>
                                <th>Người nhận đơn</th>
                                <th>Dịch vụ</th>
                                <th>Nhóm</th>
                                <th>Thanh toán</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                                <th>Thời Gian</th>

                            </tr>
                        </thead>
                        <tbody>
                            ';
foreach ($listLogs as $row) {
    echo '                                <tr>
                                    <td><button class="btn btn-info" onclick="confirmAction(';
    echo $row['id'];
    echo ')"><i class="fa fa-download"></i></button>
                                    </td>
                                    <td>';
    echo $row['code'];
    echo '</td>
                                    <td>';
    echo getRowRealTime('users', $row['user_id'], 'username');
    echo '</td>
                                    <td>';
    echo $row['receiver'] ?? 'Chưa có người nhận';
    echo '</td>
                                    <td>';
    echo $row['name'];
    echo '</td>
                                    <td>';
    echo $row['name_sub'];
    echo '</td>
                                    <td><b style="color:red">';
    echo format_cash($row['payment']);
    echo '</b></td>
                                    <td>';
    echo display_service_admin($row['status']);
    echo '</td>
                                    <td><textarea class="form-control">';
    echo $row['admin_note'];
    echo '</textarea></td>
                                    <td>';
    echo $row['created_at'];
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
$total = $db->num_rows('SELECT * FROM `orders` WHERE ' . $where);
if ($sotin1trang < $total) {
    echo '<center>' . pagination('/ctv/boosting/orders?service=' . $service . '&group=' . $group . '&createdate=' . $createdate . '&limit=' . $limit . '&shortByDate=&', $from, $total, $sotin1trang) . '</center>';
}
echo '                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="modal fade" id="ModalDialogViewOrder" tabindex="-1" aria-labelledby="modal-block-popout" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel2">ĐƠN HÀNG CÀY THUÊ [<b id="idOrder">0</b>]
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalView"></div>
            </div>
        </div>
    </div>
</div>
<script>
  const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý nhận đơn này không",
            icon: \'warning\',
            showCancelButton: true,
            confirmButtonColor: \'#3085d6\',
            cancelButtonColor: \'#d33\',
            confirmButtonText: \'Đồng ý\',
            cancelButtonText: \'Hủy\'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: \'/model/ctv/receive\',
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        action: \'receive\',
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
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/ctv/views/footer.php';
