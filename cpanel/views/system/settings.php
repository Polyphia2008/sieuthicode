<?php
// statically decompiled from settings.php  [structured; all 1 record(s) structured]

$title = 'Cấu hình Affiliate';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
if (isset($_POST['SaveSettings']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $oldEventNotice = (string) $db->site('notice_event');
        foreach ($_POST as $__key => $value) {
            $key = $__key;
            $db->update('options', ['value' => $value], ' `key` = \'' . $key . '\' ');
        }
        uploadAndSaveOption('img_event', 'img_event');
        $newEventNotice = trim((string) ($_POST['notice_event'] ?? ''));
        if ($newEventNotice !== '' && $newEventNotice !== trim($oldEventNotice)) {
            create_user_notification(
                0,
                'event',
                'Thông báo sự kiện mới',
                trim(strip_tags($newEventNotice)),
                '/event'
            );
        }
        exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0"><i class="fa-solid fa-gear"></i> Cài đặt</h4>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <ul class="nav nav-tabs nav-tabs-alt" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="btabs-alt-static-home-tab" data-bs-toggle="tab" data-bs-target="#btabs-alt-static-home" role="tab" aria-controls="btabs-alt-static-home" aria-selected="true">Cài đặt chung</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-alt-static-profile-tab" data-bs-toggle="tab" data-bs-target="#btabs-alt-static-profile" role="tab" aria-controls="btabs-alt-static-profile" aria-selected="false">Kết nối</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="btabs-alt-static-rutthuong-tab" data-bs-toggle="tab" data-bs-target="#btabs-alt-static-rutthuong" role="tab" aria-controls="btabs-alt-static-rutthuong" aria-selected="false">Event</button>
                </li>
            </ul>
            <div class="block-content tab-content">
                <div class="tab-pane active" id="btabs-alt-static-home" role="tabpanel" aria-labelledby="btabs-alt-static-home-tab" tabindex="0">
                    <form action="" method="POST">
                        <div class="row push mb-3">
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Title</td>
                                            <td>
                                                <input type="text" name="title" value="';
    echo $db->site('title');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Description</td>
                                            <td>
                                                <textarea name="description" class="form-control">';
    echo $db->site('description');
    echo '</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Keywords</td>
                                            <td>
                                                <textarea name="keywords" class="form-control">';
    echo $db->site('keywords');
    echo '</textarea>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Author</td>
                                            <td>
                                                <input type="text" name="author" value="';
    echo $db->site('author');
    echo '" class="form-control">
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Email</td>
                                            <td>
                                                <input type="text" name="email" value="';
    echo $db->site('email');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Hotline</td>
                                            <td>
                                                <input type="text" name="hotline" value="';
    echo $db->site('hotline');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Facebook</td>
                                            <td>
                                                <input type="text" name="facebook" value="';
    echo $db->site('facebook');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Telegram</td>
                                            <td>
                                                <input type="text" name="telegram" value="';
    echo $db->site('telegram');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Zalo</td>
                                            <td>
                                                <input type="text" name="zalo" value="';
    echo $db->site('zalo');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ID YouTube</td>
                                            <td>
                                                <input type="text" name="youtube" value="';
    echo $db->site('youtube');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Thời gian hỗ trợ</td>
                                            <td>
                                                <input type="text" name="time_support" value="';
    echo $db->site('time_support');
    echo '" class="form-control">
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <tbody>
                                        <tr>
                                            <td>Cập nhật phiên bản tự động</td>
                                            <td>
                                                <select class="form-control" name="status_update">
                                                    <option selected value="1">ON
                                                    </option>
                                                    <option value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                                <small>Hệ thống sẽ tự động cập nhật khi có phiên bản
                                                    mới nếu bạn chọn ON.</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ON/OFF Tính năng tự động đăng xuất Admin khi thay đổi địa chỉ IP</td>
                                            <td>
                                                <select class="form-control" name="status_only_ip_login_admin">
                                                    <option ';
    echo $db->site('status_only_ip_login_admin') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                                    </option>
                                                    <option ';
    echo $db->site('status_only_ip_login_admin') == 0 ? 'selected' : '';
    echo ' value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ON/OFF Tính năng xác minh IP khi truy cập Admin nếu bạn chọn ON</td>
                                            <td>
                                                <select class="form-control" name="status_security">
                                                    <option ';
    echo $db->site('status_security') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                                    </option>
                                                    <option ';
    echo $db->site('status_security') == 0 ? 'selected' : '';
    echo ' value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Trạng thái Banner</td>
                                            <td>
                                                <select class="form-control" name="status_banner">
                                                    <option ';
    echo $db->site('status_banner') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                                    </option>
                                                    <option ';
    echo $db->site('status_banner') == 0 ? 'selected' : '';
    echo ' value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Chức năng Flash Sale</td>
                                            <td>
                                                <select class="form-control" name="status_flash_sale">
                                                    <option ';
    echo $db->site('status_flash_sale') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                                    </option>
                                                    <option ';
    echo $db->site('status_flash_sale') == 0 ? 'selected' : '';
    echo ' value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Màu chủ đạo</td>
                                            <td>
                                                <input type="color" class="form-control form-control-color border-0" id="exampleColorInput" name="theme_color" value="';
    echo $db->site('theme_color');
    echo '" title="Choose your color">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Màu phụ</td>
                                            <td>
                                                <input type="color" class="form-control form-control-color border-0" id="exampleColorInput" name="theme_color1" value="';
    echo $db->site('theme_color1');
    echo '" title="Choose your color">

                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label class="col-form-label">Thông báo ngoài trang chủ</label>
                                        <textarea name="notice_home" id="notice_home" class="form-control" rows="5">';
    echo $db->site('notice_home');
    echo '</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="col-form-label">Thông báo nổi</label>
                                        <textarea name="popup_home" id="popup_home" class="form-control" rows="5">';
    echo $db->site('popup_home');
    echo '</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="col-form-label">Thông báo mua hàng</label>
                                        <textarea name="notice_purchasing" id="notice_purchasing" class="form-control" rows="5">';
    echo $db->site('notice_purchasing');
    echo '</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="col-form-label">Thông báo rút vật phẩm</label>
                                        <textarea name="notice_withdraw" id="notice_withdraw" class="form-control" rows="5">';
    echo $db->site('notice_withdraw');
    echo '</textarea>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="col-form-label">Thông báo cộng tác viên</label>
                                        <textarea name="notice_ctv" id="notice_ctv" class="form-control" rows="5">';
    echo $db->site('notice_ctv');
    echo '</textarea>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <button type="submit" name="SaveSettings" class="btn btn-primary w-100 mb-3">
                            <i class="fa fa-fw fa-save me-1"></i> Save </button>
                    </form>
                </div>
                <div class="tab-pane" id="btabs-alt-static-profile" role="tabpanel" aria-labelledby="btabs-alt-static-profile-tab" tabindex="0">
                    <form action="" method="POST">
                        <div class="row push mb-3">
                            <div class="col-md-6">
                                <table class="mb-3 table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th colspan="2" class="text-center">
                                                <img src="/assets/images/icon-smtp.png" width="20px"> SMTP
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>SMTP Mail</td>
                                            <td>
                                                <select class="form-control" name="smtp_status">
                                                    <option value="1" ';
    echo $db->site('smtp_status') == 1 ? 'selected' : '';
    echo '>ON
                                                    </option>
                                                    <option ';
    echo $db->site('smtp_status') == 0 ? 'selected' : '';
    echo ' value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SMTP Host</td>
                                            <td>
                                                <input type="text" name="smtp_host" value="smtp.gmail.com" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SMTP Encryption</td>
                                            <td>
                                                <input type="text" name="smtp_encryption" value="tls" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SMTP Port</td>
                                            <td>
                                                <input type="text" name="smtp_port" value="587" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SMTP Email</td>
                                            <td>
                                                <input type="text" name="email_smtp" value="';
    echo $db->site('email_smtp');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SMTP Password</td>
                                            <td>
                                                <input type="text" name="pass_email_smtp" value="';
    echo $db->site('pass_email_smtp');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="mb-3 table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th colspan="2" class="text-center">
                                                <img src="/assets/images/google.png" width="20px"> LOGIN GOOGLE
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Status Login Google</td>
                                            <td>
                                                <select class="form-control" name="status_login_google">
                                                    <option value="1" ';
    echo $db->site('status_login_google') == 1 ? 'selected' : '';
    echo '>ON
                                                    </option>
                                                    <option ';
    echo $db->site('status_login_google') == 0 ? 'selected' : '';
    echo ' value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Google App ID</td>
                                            <td>
                                                <input type="text" name="google_app_id" value="';
    echo $db->site('google_app_id');
    echo '" class="form-control">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Google App Secret</td>
                                            <td>
                                                <input type="text" name="google_app_secret" value="';
    echo $db->site('google_app_secret');
    echo '" class="form-control">
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>

                            <div class="col-md-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th colspan="2" class="text-center">
                                                <img src="/assets/images/google_recaptcha.png" width="20px"> reCAPTCHA
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>reCAPTCHA</td>
                                            <td>
                                                <select class="form-control" name="status_captcha">
                                                    <option ';
    echo $db->site('status_captcha') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                                    </option>
                                                    <option ';
    echo $db->site('status_captcha') == 0 ? 'selected' : '';
    echo ' value="0">
                                                        OFF
                                                    </option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>reCAPTCHA Site Key</td>
                                            <td>
                                                <input type="text" name="site_key" value="';
    echo $db->site('site_key');
    echo '" class="form-control">
                                                <small><a href="" target="_blank">Xem hướng dẫn</a></small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>reCAPTCHA Secret Key</td>
                                            <td>
                                                <input type="text" name="secret_key" value="';
    echo $db->site('secret_key');
    echo '" class="form-control">
                                                <small><a href="" target="_blank">Xem hướng dẫn</a></small>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <button type="submit" name="SaveSettings" class="btn btn-primary w-100 mb-3">
                            <i class="fa fa-fw fa-save me-1"></i> Save </button>
                    </form>
                </div>
                

                <div class="tab-pane" id="btabs-alt-static-rutthuong" role="tabpanel" aria-labelledby="btabs-alt-static-rutthuong-tab" tabindex="0">
                    <form action="" method="POST" class="default-form" enctype="multipart/form-data">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="status_event" class="form-label">Trạng Thái</label>
                                <select name="status_event" id="status_event" class="form-control">
                                    <option ';
    echo $db->site('status_event') == 0 ? 'selected' : '';
    echo ' value="0">OFF
                                    </option>
                                    <option ';
    echo $db->site('status_event') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="balance" class="form-label">Yêu Cầu Số Dư</label>
                                <input type="number" class="form-control" id="balance" name="balance" value="';
    echo $db->site('balance');
    echo '" required="">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="min" class="form-label">Tối Thiểu</label>
                                <input type="number" class="form-control" id="min" name="min" value="';
    echo $db->site('min');
    echo '" required="">
                            </div>
                            <div class="col-md-6">
                                <label for="max" class="form-label">Tối Đa</label>
                                <input type="number" class="form-control" id="max" name="max" value="';
    echo $db->site('max');
    echo '" required="">
                            </div>
                            <div class="col-md-6">
                                <label for="max" class="form-label">Đơn vị</label>
                                <input type="text" class="form-control" id="unit" name="unit" value="';
    echo $db->site('unit');
    echo '" required="">
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="up_image" class="form-label">Hình Ảnh</label>
                            <input type="file" class="form-control" id="img_event" name="img_event">

                            <div class="mb-2 mt-2 text-center">
                                <img src="';
    echo $db->site('img_event');
    echo '" alt="Gift" class="img-fluid" style="max-height: 100px;">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="col-form-label">Nội dung</label>
                            <textarea name="notice_event" id="notice_event" class="form-control" rows="5">';
    echo $db->site('notice_event');
    echo '</textarea>
                        </div>
                        <button type="submit" name="SaveSettings" class="btn btn-primary w-100 mb-3">
                            <i class="fa fa-fw fa-save me-1"></i> Save </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
<script>
    CKEDITOR.replace("page_policy");
    CKEDITOR.replace("notice_ctv");
    CKEDITOR.replace("faq");
    CKEDITOR.replace("popup_home");
    CKEDITOR.replace("notice_home");
    CKEDITOR.replace("notice_purchasing");
    CKEDITOR.replace("notice_event");
    CKEDITOR.replace("notice_withdraw");
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
