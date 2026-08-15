<?php
// statically decompiled from card-config.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
// Cấu hình nạp thẻ cào là chức năng nhạy cảm: chỉ superadmin (cả GET lẫn POST).
// Gọi require_superadmin TRƯỚC khi render header để không xuất HTML trước status 403.
require_superadmin(false);
// CSRF gate phải chạy TRƯỚC mọi output (header.php) để HTTP 419 có hiệu lực.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SaveSettings'])) {
    verify_csrf_token();
}
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['SaveSettings']) && is_superadmin_account($data_user)) {
    foreach ($_POST as $__key => $value) {
        $key = $__key;
        $db->update('options', ['value' => $value], ' `key` = \'' . $key . '\' ');
    }
    exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Cấu hình nạp thẻ cào</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item"><a href="/cpanel/recharge">Card</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cấu hình</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    CẤU HÌNH
                </div>
            </div>
            <div class="block-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="';
    echo generate_csrf_token();
    echo '">
                    <div class="row mb-3">
                        <div class="col-lg-12 col-xl-6">
                            <div class="row mb-4">
                                <label class="col-sm-4 col-form-label" for="example-hf-email">Trạng
                                    thái</label>
                                <div class="col-sm-8">
                                    <select class="form-control form-control-sm" name="card_status">
                                        <option ';
    echo $db->site('card_status') == 0 ? 'selected' : '';
    echo ' value="0">OFF
                                        </option>
                                        <option ';
    echo $db->site('card_status') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-4 col-form-label" for="example-hf-email">Partner ID</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control form-control-sm" value="';
    echo $db->site('card_partner_id');
    echo '" name="card_partner_id">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="row mb-4">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Phí nạp thẻ</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <input type="text" class="form-control form-control-sm" value="';
    echo $db->site('card_ck');
    echo '" name="card_ck">
                                        <span class="input-group-text">
                                            % </span>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Partner Key</label>
                                <div class="col-sm-6">
                                <input type="text" class="form-control form-control-sm" value="';
    echo $db->site('card_partner_key');
    echo '" name="card_partner_key">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-12">
                            <div class="row mb-4">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">URL API CARD</label>
                                <div class="col-sm-12">
                                    <input type="text" name="card_url_api" class="form-control form-control-sm" value="';
    echo $db->site('card_url_api');
    echo '">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-12">
                            <div class="row mb-4">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">URL CALLBACK CARD</label>
                                <div class="col-sm-12">
                                    <input type="text" name="card_url_api" class="form-control form-control-sm" value="';
    echo DOMAIN;
    echo '/api/deposit/card" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-12">
                            <div class="row mb-4">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Lưu ý nạp thẻ</label>
                                <div class="col-sm-12">
                                    <textarea id="card_notice" name="card_notice">';
    echo $db->site('card_notice');
    echo '</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a type="button" class="btn btn-danger" href=""><i class="fa fa-fw fa-undo me-1"></i>
                        Reload</a>
                    <button type="submit" name="SaveSettings" class="btn btn-success">
                        <i class="fa fa-fw fa-save me-1"></i> Save </button>
                </form>
            </div>
        </div>
    </div>
</main>


';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
    echo '<script>
    CKEDITOR.replace("card_notice");
</script>';
}
