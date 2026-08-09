<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Thông tin tài khoản - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Thông tin tài khoản</span></a></li>
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
                    <h2 class="title-account">Thông tin tài khoản</h2>
                    <hr>
                </div>
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="/customer/profile"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Thông tin tài khoản</h2>
                    </div>
                </div>
                <div class="content-account">
                    <div class="text-h3-account">
                        <h3>ID của bạn</h3>
                        <h4>';
    echo $data_user['id'];
    echo '</h4>
                    </div>
                    <div class="text-h3-account">
                        <h3>Username</h3>
                        <h4>';
    echo $data_user['username'];
    echo '</h4>
                    </div>
                    <div class="text-h3-account">
                        <h3>Tên hiển thị</h3>
                        <h4>';
    echo $data_user['provider'] == 'google' ? $data_user['name'] : $data_user['username'];
    echo '</h4>
                    </div>
                    <div class="text-h3-account">
                        <h3>Email</h3>
                        <h4 class="">';
    echo $data_user['email'];
    echo '</h4>
                    </div>
                    <div class="text-h3-account">
                        <h3>Điện thoại</h3>
                        <h4 class="Warring">';
    echo empty($data_user['phone']) ? 'Chưa cập nhật' : $data_user['phone'];
    echo '</h4>
                    </div>
                    <div class="text-h3-account">
                        <h3>Số dư</h3>
                        <h4 class="blue">';
    echo format_cash($data_user['money']);
    echo 'đ</h4>
                    </div>
                    <div class="text-h3-account">
                        <h3>Số dư coin</h3>
                        <h4 class="blue">';
    echo format_cash($data_user['cost']);
    echo 'đ</h4>
                    </div>

                    <div class="text-canhbao-account">
                        <h5>*Để bảo mật vui lòng cập nhật đầy đủ thông tin tài khoản <a href="/customer/profile/update">Tại đây</a></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
