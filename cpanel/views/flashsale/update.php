<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `flash_sales` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/flashsale');
    }
} else {
    new Redirect('/cpanel/flashsale');
}
if (isset($_POST['UpdateCategory']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $isInsert = $db->update('flash_sales', ['name' => Anti_xss($_POST['name']), 'start_time' => Anti_xss($_POST['start_time']), 'end_time' => Anti_xss($_POST['end_time'])], ' `id` = \'' . $row['id'] . '\' ');
        if ($isInsert) {
            insetLog($data_user['id'], 'Chỉnh sửa Flash Sale (' . $row['name'] . ' ID ' . $row['id'] . ').');
            exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
        } else {
            exit('<script type="text/javascript">if(!alert("Lưu thất bại !")){window.history.back().location.reload();}</script>');
        }
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Cập nhật Flash Sale [';
    echo $row['name'];
    echo ']
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="row mb-2">
                                <div class="col-lg-12">
                                    <div class="form-group mb-2">
                                        <label class="form-label">
                                            Tên chiến dịch
                                        </label>
                                        <input type="text" name="name" value="';
    echo $row['name'];
    echo '" class="form-control" placeholder="Tên chiến dịch" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Thời gian bắt đầu</label>
                                        <input type="text" class="js-flatpickr form-control" id="example-flatpickr-datetime-24" name="start_time" data-enable-time="true" data-time_24hr="true" placeholder="Chọn thời gian" value="';
    echo convertToDateOnly($row['start_time']);
    echo '">
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label" for="example-text-input">Thời gian kết thúc</label>
                                        <input type="text" class="js-flatpickr form-control" id="example-flatpickr-datetime-24" name="end_time" data-enable-time="true" data-time_24hr="true" placeholder="Chọn thời gian" value="';
    echo convertToDateOnly($row['end_time']);
    echo '">
                                    </div>
                                 
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2">
                                <a type="button" class="btn btn-danger" href="/cpanel/flashsale">Quay Lại</a>
                                <button type="submit" name="UpdateCategory" class="btn btn-success">
                                    Lưu Ngay
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
