<?php
// statically decompiled from list.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['limit']) && $data_user['level'] == 'admin') {
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
$category = '';
$create_gettime = '';
$title = '';
$shortByDate = '';
if (!empty($_GET['title'])) {
    $title = Anti_xss($_GET['title']);
    $where .= ' AND `title` LIKE "%' . $title . '%" ';
}
$create_date = '';
if (!empty($_GET['create_gettime'])) {
    $create_date = Anti_xss($_GET['create_gettime']);
    $create_gettime = $currentMonth;
    $create_date_1 = str_replace('-', '/', $create_date);
    $create_date_1 = explode(' to ', $create_date_1);
    if ($create_date_1[0] != $create_date_1[1]) {
        $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
        $where .= ' AND `created_at` >= \'' . $create_date_1[0] . '\' AND `created_at` <= \'' . $create_date_1[1] . '\' ';
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
        $where .= ' AND `created_at` LIKE \'%' . $currentDate . '%\' ';
    }
    if ($shortByDate == 2) {
        $where .= ' AND YEAR(created_at) = ' . $currentYear . ' AND WEEK(created_at, 1) = ' . $currentWeek . ' ';
    }
    if ($shortByDate == 3) {
        $where .= ' AND MONTH(created_at) = \'' . $currentMonth . '\' AND YEAR(created_at) = \'' . $currentYear . '\' ';
    }
}
$listDatatable = $db->get_list(' SELECT * FROM `links` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $limit . ' ');
$totalDatatable = $db->num_rows(' SELECT * FROM `links` WHERE ' . $where . ' ORDER BY id DESC ');
$urlDatatable = pagination('?limit=' . $limit . '&shortByDate=' . $shortByDate . '&title=' . $title . '&category=' . $category . '&create_gettime=' . $create_gettime . '&', $from, $totalDatatable, $limit);
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-users"></i> Liên kết</h4>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    DANH SÁCH LIÊN KẾT
                </div>
                <div class="d-flex">
                    <a href="/cpanel/link/add" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i class="fa fa-plus"></i> Thêm mới</a>
                </div>
            </div>
            <div class="block-content">
                <form action="" class="align-items-center mb-3" name="formSearch" method="GET">
                    <div class="row row-cols-lg-auto g-3 mb-3">
                        <div class="col-lg col-md-4 col-6">
                            <input class="form-control" value="';
echo $title;
echo '" name="title" placeholder="Title">
                        </div>

                        <div class="col-lg col-md-4 col-6">
                            <div class="input-group">
                                <input type="text" name="create_date" class="form-control js-flatpickr" id="example-flatpickr-range" value="';
echo $create_date;
echo '" placeholder="Chọn thời gian" data-mode="range">

                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-hero btn-sm btn-primary"><i class="fa fa-search"></i> Tìm kiếm </button>
                            <a class="btn btn-hero btn-sm btn-danger" href="/cpanel/link/list"><i class="fa fa-trash"></i>
                                Loại bỏ </a>
                        </div>
                    </div>
                    <div class="top-filter">
                        <div class="filter-show"> <label class="filter-label">Show :</label> <select name="limit" onchange="this.form.submit()" class="form-select filter-select">
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
echo ' value="1000">1000</option>
                            </select> </div>
                        <div class="filter-short">
                            <label class="filter-label">ShortbyDate:</label> <select name="shortByDate" onchange="this.form.submit()" class="form-select filter-select">
                                <option value="">Tấtcả</option>
                                <option ';
echo $shortByDate == 1 ? 'selected' : '';
echo ' value="1">Hôm nay
                                </option>
                                <option ';
echo $shortByDate == 2 ? 'selected' : '';
echo ' value="2">Tuầ này
                                </option>
                                <option ';
echo $shortByDate == 3 ? 'selected' : '';
echo ' value="3"> Tháng này
                                </option>
                            </select>
                        </div>
                    </div>
                </form>
                <div class="table-responsive mb-3">
                    <table class="table text-nowrap table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Vị trí</th>
                                <th>Tiêu đề bài viết</th>
                                <th>Ảnh</th>
                                <th>Liên kết</th>
                                <th class="text-center">Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
foreach ($listDatatable as $row) {
    echo '                                <tr onchange="updateForm(\'';
    echo $row['id'];
    echo '\')">
                                    <td class="text-center" width="10%"><input id="stt';
    echo $row['id'];
    echo '" class="form-control" type="number" value="';
    echo $row['stt'];
    echo '"></td>
                                    <td>';
    echo $row['title'];
    echo ' </td>
                                    <td><img src="';
    echo $row['image'];
    echo '" width="100px"> </td>
                                    <td>';
    echo $row['link'];
    echo ' </td>
                                    <td class="text-center">
                                        <form action="" method="post">
                                            <div class="form-check form-switch form-check-inline">
                                                <input class="form-check-input" type="checkbox" id="status';
    echo $row['id'];
    echo '" value="1" ';
    echo $row['status'] == 1 ? 'checked=""' : '';
    echo '>
                                            </div>
                                        </form>
                                    </td>
                                    <td>
                                        <a type="button" target="_blank" href="';
    echo $row['link'];
    echo '" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Xem"> <i class="fa fa-eye"></i> </a>
                                        <a type="button" href="/cpanel/link/list/update/';
    echo $row['id'];
    echo '" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Chỉnh sửa"> <i class="fa fa-pencil-alt"></i> </a>
                                        <a type="button" onclick="confirmAction(\'';
    echo $row['id'];
    echo '\')" class="btn btn-sm btn-light" data-bs-toggle="tooltip" title="Xoá"> <i class="fas fa-trash"></i> </a>
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
echo ' Results
                        </p>
                    </div>
                    <div class="col-sm-12 col-md-7 mb-3"> ';
echo $limit < $totalDatatable ? $urlDatatable : '';
echo ' </div>
                </div>
            </div>
        </div>

    </div>
</main>

<script type="text/javascript">
    function updateForm(id) {
        $.ajax({
            url: "/model/admin/update",
            method: "POST",
            dataType: "JSON",
            data: {
                action: \'updateTableLink\',
                id: id,
                stt: $(\'#stt\' + id).val(),
                status: $(\'#status\' + id + \':checked\').val()
            },
            success: function(result) {
                if (result.status == \'success\') {
                    showMessage(result.msg, result.status);
                } else {
                    showMessage(result.msg, result.status);
                }
            },
            error: function() {
                alert(html(result));
                location.reload();
            }
        });
    }

    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa bài viết " + id,
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
                action: \'removeLink\',
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
</script>
';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
