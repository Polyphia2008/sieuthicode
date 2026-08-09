<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Cập nhật thông tin tài khoản - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Cập nhật thông tin tài khoản</span></a></li>
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
                    <h2 class="title-account">Cập nhật thông tin tài khoản</h2>
                    <hr>
                </div>
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="/customer/profile"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Cập nhật thông tin tài khoản</h2>
                    </div>
                </div>
                <div class="content-account">
                    <form class="form-account validation-form" novalidate id="submit-update-profile"
                    enctype="multipart/form-data">
                        <div class="group-input input-text">
                            <div class="input-element">
                            <input type="hidden" class="input-text" name="csrf_token" value="';
    echo generate_csrf_token();
    echo '" required>
                                <input class="input-text" name="name" value="';
    echo $data_user['name'];
    echo '" type="text" max="155" placeholder="Nhập tên hiển thị" required>
                                <max class="count-input"></max>
                            </div>
                            <label for="name">Tên hiển thị</label>
                        </div>
                        <div class="group-input input-text">
                            <div class="input-element">
                                <input class="input-text" name="email" value="';
    echo $data_user['email'];
    echo '" type="email" max="155" placeholder="Nhập email" required>
                                <max class="count-input"></max>
                            </div>
                            <label for="email">Email</label>
                        </div>
                        <div class="group-input input-text">
                            <div class="input-element">
                                <input class="input-text" name="phone" value="';
    echo $data_user['phone'];
    echo '" type="text" max="155" placeholder="Nhập số điện thoại" required>
                                <max class="count-input"></max>
                            </div>
                            <label for="phone">Điện thoại</label>
                        </div>
                        <!-- <div class="control-file my-4">
                            <label for="input-avatar-account">Ảnh đại diện</label>
                            <input type="file" name="file" id="input-avatar-account">
                            <div class="zone-file" for="input-avatar-account">
                                <div class="button-upload avatar-account">
                                    <img src="/assets/images/upload.svg" />
                                </div>
                                <div class="drop-zone" id="zone-avatar-account">
                                    <p><b>Click to upload</b> or drag and drop</p>
                                    <p>SVG, PNG, JPG or GIF (Standard size: 200x200)</p>
                                </div>
                            </div>
                        </div> -->
                        <div class="grid grid-2 mt-5 gap-10">
                            <a class="default-button-sub" href="/customer/profile">Trở về</a>
                            <button class="default-button">Cập nhật thông tin</button>
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
