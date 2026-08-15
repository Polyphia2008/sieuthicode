<?php
// statically decompiled from config.php  [structured; all 1 record(s) structured]

$title = 'Cấu hình Affiliate';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['btnSaveOption']) && is_admin_account($data_user)) {
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
            <h4 class="page-title fw-semibold fs-18 mb-0">Cấu hình</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Tiếp thị liên kết</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cấu hình</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">CẤU HÌNH</hê>
            </div>
            <div class="block-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12 col-xl-6">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Trạng thái</label>
                                <div class="col-sm-6">
                                    <select class="form-control" name="status_ref" required>
                                        <option value="1" ';
    echo $db->site('status_ref') == 1 ? 'selected' : '';
    echo '>Bật
                                        </option>
                                        <option value="0" ';
    echo $db->site('status_ref') == 0 ? 'selected' : '';
    echo '>Tắt
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Hoa hồng nạp tiền</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="';
    echo $db->site('ck_ref');
    echo '" name="ck_ref" placeholder="VD 10 = 10%">
                                        <span class="input-group-text">
                                            %
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Số tiền rút tối thiểu</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="';
    echo $db->site('minrut_ref');
    echo '" name="minrut_ref" placeholder="VD 100000 = 100.000đ">
                                        <span class="input-group-text">
                                            VNĐ </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Phương thức rút tiền</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <textarea class="form-control" rows="4" placeholder="Mỗi dòng 1 ngân hàng" name="listbank_ref">';
    echo $db->site('listbank_ref');
    echo '</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                       
                        <div class="col-lg-12 col-xl-12">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Lưu ý</label>
                                <div class="col-sm-12">
                                    <textarea id="notice_ref" name="notice_ref">';
    echo $db->site('notice_ref');
    echo '</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">

                        </div>
                    </div>
                    <a type="button" class="btn btn-danger" href=""><i class="fa fa-fw fa-undo me-1"></i>
                        Reload</a>
                    <button type="submit" name="btnSaveOption" class="btn btn-primary">
                        <i class="fa fa-fw fa-save me-1"></i> Lưu Ngay </button>
                </form>
            </div>
        </div>

    </div>

</main>
<script>
    CKEDITOR.replace("notice_ref");
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
