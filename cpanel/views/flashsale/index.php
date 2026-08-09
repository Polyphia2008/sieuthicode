<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddCategory']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        if ($db->get_row('SELECT * FROM `categories` WHERE `name` = \'' . Anti_xss($_POST['name']) . '\' ')) {
            exit('<script type="text/javascript">if(!alert("Chuyên mục này đã tồn tại trong hệ thống")){window.history.back().location.reload();}</script>');
        } else {
            $isInsert = $db->insert('flash_sales', ['name' => Anti_xss($_POST['name']), 'start_time' => Anti_xss($_POST['start_time']), 'end_time' => Anti_xss($_POST['end_time'])]);
            if ($isInsert) {
                insetLog($data_user['id'], 'Thêm chiến dịch flash sale (' . Anti_xss($_POST['name']) . ') vào hệ thống.');
                exit('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
            } else {
                exit('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
            }
        }
    }
} else {
    if (isset($_GET['limit'])) {
        $limit = Anti_xss($_GET['limit']);
    } else {
        $limit = 12;
    }
    if (isset($_GET['page'])) {
        $page = Anti_xss($_GET['page']);
    } else {
        $page = 3;
    }
    $from = ($page - 1) * $limit;
    $where = ' `id` > 0 ';
    $listDatatable = $db->get_list(' SELECT * FROM `flash_sales` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $limit . ' ');
    $totalDatatable = $db->num_rows(' SELECT * FROM `flash_sales` WHERE ' . $where . ' ORDER BY id DESC ');
    $urlDatatable = pagination('/cpanel/flashsale&', $from, $totalDatatable, $limit);
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h4 class="mb-1">
                    Chiến dịch Flash Sale
                </h4>
            </div>
            <div class="mt-4 mt-md-0">
                <a class="btn btn-sm btn-success" href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#modal-block-popout">
                    <i class="fa fa-plus"></i> Thêm mới
                </a>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">DANH SÁCH CHIẾN DỊCH (';
    echo $totalDatatable;
    echo ')</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 100px;">ID</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">Bắt đầu</th>
                                <th class="text-center">Kết thúc</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
    foreach ($listDatatable as $category) {
        echo '                                <tr>
                                    <td class="text-center fs-sm">
                                        <a class="fw-semibold" href="be_pages_ecom_product_edit.html">
                                            ';
        echo $category['id'];
        echo '</a>
                                    </td>
                                    <td class="d-sm-table-cell text-center fs-sm">';
        echo $category['name'];
        echo '</td>
                                    <td class="d-sm-table-cell text-center fs-sm">';
        echo $category['start_time'];
        echo '</td>
                                    <td class="d-sm-table-cell text-center fs-sm">';
        echo $category['end_time'];
        echo '</td>
                                    
                                    <td class="d-sm-table-cell text-center fs-sm">';
        echo getFlashSaleStatus($category['start_time'], $category['end_time'])['msg'];
        echo '</td>
                                    <td class="text-center fs-sm">
                                        <a class="btn btn-sm btn-success" href="/cpanel/flashsale/account/list/';
        echo $category['id'];
        echo '">
                                            <i class="fa fa-list"></i>
                                            List tài khoản
                                        </a>
                                        <a class="btn btn-sm btn-primary" href="/cpanel/flashsale/update/';
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
<div class="modal fade" id="modal-block-popout" tabindex="-1" role="dialog" aria-labelledby="modal-block-popout" aria-hidden="true">
    <div class="modal-dialog modal-dialog-popout" role="document">
        <div class="modal-content">
            <div class="block block-rounded block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark">
                    <h3 class="block-title">Chiến dịch mới</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="block-content">
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Tên chiến dịch Flash Sale</label>
                            <input type="text" class="form-control" name="name" placeholder="Tên chiến dịch" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Thời gian bắt đầu</label>
                            <input type="text" class="js-flatpickr form-control" id="example-flatpickr-datetime-24" name="start_time" data-enable-time="true" data-time_24hr="true" placeholder="Chọn thời gian">
                        </div>
                        <div class="mb-4">
                            <label class="form-label" for="example-text-input">Thời gian kết thúc</label>
                            <input type="text" class="js-flatpickr form-control" id="example-flatpickr-datetime-24" name="end_time" data-enable-time="true" data-time_24hr="true" placeholder="Chọn thời gian">
                        </div>
                    </div>
                    <div class="block-content block-content-full text-end bg-body">
                        <button class="btn btn-sm btn-success" type="submit" name="AddCategory">Thêm Ngay</button>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Đóng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa Flash Sale " + id,
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
                        action: \'removeFlashSale\',
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
