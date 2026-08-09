<?php
// statically decompiled from config.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['SaveSettings']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        foreach ($_POST as $__key => $value) {
            $key = $__key;
            $db->update('options', ['value' => $value], ' `key` = \'' . $key . '\' ');
        }
        exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><i class="fa fa-shopping-cart"></i> Cấu hình rút tiền CTV</h4>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    CẤU HÌNH
                </div>
            </div>
            <div class="block-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group mb-3">
                            <label>Trạng thái</label>
                            <select class="form-control select2bs4" name="status_withdraw_ctv">
                                <option ';
    echo $db->site('status_withdraw_ctv') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                </option>
                                <option ';
    echo $db->site('status_withdraw_ctv') == 0 ? 'selected' : '';
    echo ' value="0">
                                    OFF
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label>Ngân hàng được phép rút tiền</label>
                            <textarea class="form-control" rows="5" placeholder="Nhập tên ngân hàng chi phép rút tiền, mỗi dòng 1 ngân hàng" name="listbank_ctv">';
    echo $db->site('listbank_ctv');
    echo '</textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Số tiền rút tối thiểu</label>
                            <input type="number" class="form-control" name="minrut_ctv" value="';
    echo $db->site('minrut_ctv');
    echo '">
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Lưu ý</label>
                            <textarea id="notice_withdraw_ctv" name="notice_withdraw_ctv">';
    echo $db->site('notice_withdraw_ctv');
    echo '</textarea>
                        </div>
                    </div>
                    <div class="card-footer clearfix mb-3">
                        <button name="SaveSettings" class="btn btn-info btn-icon-left m-b-10" type="submit"><i class="fas fa-save mr-1"></i>Lưu Ngay</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</main>

<script>
CKEDITOR.replace("notice_withdraw_ctv");
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
