<?php
// statically decompiled from theme.php  [structured; all 1 record(s) structured]

$title = 'Giao diện';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_POST['btnSaveOption']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        uploadAndSaveOption('logo', 'logo');
        uploadAndSaveOption('favicon', 'favicon');
        uploadAndSaveOption('anhbia', 'anhbia');
        uploadAndSaveOption('banner_active', 'banner_active');
        uploadAndSaveOption('banner_1', 'banner_1');
        uploadAndSaveOption('background', 'background');
        uploadAndSaveOption('background_login', 'background_login');
        exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Cấu hình giao diện</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Hệ thống</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cấu hình giao diện</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    THAY ĐỔI GIAO DIỆN WEBSITE
                </div>
            </div>
            <div class="block-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group">
                                <label for="formFile" class="form-label">Logo</label>
                                <input type="file" class="form-control" name="logo">
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <img width="300px" src="';
    echo $db->site('logo');
    echo '" />
                            <hr>
                        </div>

                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group">
                                <label for="formFile" class="form-label">Favicon</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="form-control" name="favicon">

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <img width="100px" src="';
    echo $db->site('favicon');
    echo '" />
                            <hr>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group">
                                <label for="formFile" class="form-label">Image</label>
                                <input type="file" class="form-control" name="anhbia">
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <img width="400px" src="';
    echo $db->site('anhbia');
    echo '" />
                            <hr>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group">
                                <label for="formFile" class="form-label">Background</label>
                                <input type="file" class="form-control" name="background">
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <img width="400px" src="';
    echo $db->site('background');
    echo '" />
                            <hr>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="form-group">
                                <label for="formFile" class="form-label">Background Đăng Nhập</label>
                                <input type="file" class="form-control" name="background_login">
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <img width="400px" src="';
    echo $db->site('background_login');
    echo '" />
                            <hr>
                        </div>


                    </div>
                    <button name="btnSaveOption" class="btn btn-primary" type="submit"><i class="fas fa-save"></i> Lưu Ngay</button>
                </form>
            </div>
        </div>
    </div>
</main>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
