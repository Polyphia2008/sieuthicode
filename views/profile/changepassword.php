<?php
// statically decompiled from changepassword.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Đổi Mật Khẩu - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Đổi mật khẩu</span></a></li>
            </ol>
        </div>
    </div>
</div>
<div class="screen">
    <div class="center">
        <div class="page-account">
            <div class="menu-account">
                ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/profile/menu.php');
    echo '            </div>
            <div class="container-account" id="container-account">
                <div class="content-account header-desktop-account">
                    <h2 class="title-account">Đổi mật khẩu</h2>
                    <hr>
                </div>
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="/customer/profile"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Đổi mật khẩu</h2>
                    </div>
                </div>
                <div class="content-account">
                    <form class="form-account validation-form" novalidate id="submit-changepass-profile" enctype="multipart/form-data">
                        <div class="group-input input-password">
                            <div class="input-element">
                                <input type="hidden" class="input-text" name="csrf_token" value="';
    echo generate_csrf_token();
    echo '" required>
                                <input type="password" name="old-password" id="input-password" placeholder="Vui lòng nhập mật khẩu cũ" required>
                                <show class="eye-input hide"></show>
                            </div>
                            <label for="input-password">Mật khẩu cũ</label>
                        </div>
                        <div class="group-input input-password">
                            <div class="input-element">
                                <input type="password" name="new-password" id="input-password" placeholder="Vui lòng nhập mật khẩu mới" required>
                                <show class="eye-input hide"></show>
                            </div>
                            <label for="input-password">Mật khẩu mới</label>
                        </div>
                        <div class="group-input input-password">
                            <div class="input-element">
                                <input type="password" name="new-password-2" id="input-password" placeholder="Vui lòng xác nhận lại mật khẩu mới" required>
                                <show class="eye-input hide"></show>
                            </div>
                            <label for="input-password">Xác nhận</label>
                        </div>
                        <div class="grid grid-2 mt-5 gap-10">
                            <a class="default-button-sub" href="/customer/profile">Trở về</a>
                            <button type="submit" class="default-button">Đổi mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
