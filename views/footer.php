<?php
// statically decompiled from footer.php  [structured; all 1 record(s) structured]

echo '<script>
    document.addEventListener("DOMContentLoaded", function () {
    let menu = document.querySelector(".box-menu");
    let menuList = document.querySelector(".menu-list");

    menu.addEventListener("mouseenter", function () {
        menuList.style.display = "block";
    });

    menu.addEventListener("mouseleave", function () {
        setTimeout(() => {
            if (!menuList.matches(":hover")) {
                menuList.style.display = "none";
            }
        }, 300);
    });

    menuList.addEventListener("mouseleave", function () {
        menuList.style.display = "none";
    });
});

</script>
<section class="screen" id="footer">
    <div class="center">
        <div id="container-footer">
            <div class="footer_sec2_col">
                <h4 class="footer_sec2_title">';
echo strtoupper($_SERVER['SERVER_NAME']);
echo '</h4>
                <ul>
                    <div class="footer_sec2_li" style="line-height: 2;">
                        <p>';
echo $db->site('title');
echo '</p>
                        <p>Chúng tôi luôn lấy uy tín đặt trên hàng đầu đối với khách hàng, hy vọng chúng tôi sẽ được phục vụ các bạn. Cám ơn!</p>
                        <p><strong>Thời Gian Làm Việc: ';
echo $db->site('time_support');
echo '</strong>
                        </p>
                    </div>
                </ul>
            </div>
            <div class="footer_sec2_col">
                <h4 class="footer_sec2_title">THÔNG TIN CHUNG</h4>
                <ul>
                    ';
foreach ($db->get_list('SELECT * FROM `posts` WHERE `footer` = 1 ORDER BY `id` ASC') as $post) {
    echo '                        <li class="footer_sec2_li" style="line-height: 2;"><a href="/tin-tuc/';
    echo $post['slug'];
    echo '">';
    echo $post['title'];
    echo '</a></li>
                    ';
}
echo '                </ul>
            </div>
            <div class="footer_sec2_col">
                <h4 class="footer_sec2_title">SẢN PHẨM</h4>
                <ul>
                    ';
foreach ($db->get_list('SELECT * FROM `subcategory` WHERE `status` = 1 ORDER BY `id` DESC LIMIT 4') as $subcate) {
    $sub_detail = json_decode($subcate['detail'], true);
    echo '                        <li class="footer_sec2_li" style="line-height: 2;"><a href="/tai-khoan/';
    echo $subcate['type_category'];
    echo '">';
    echo $sub_detail['name_product'];
    echo '</a></li>
                    ';
}
echo '                </ul>
            </div>
            <div class="footer_sec2_col">
                <h4 class="footer_sec2_title">THÔNG TIN LIÊN HỆ</h4>
                <ul>
                    <li class="footer_sec2_li">
                        Facebook: <a href="';
echo $db->site('facebook');
echo '">';
echo $db->site('facebook');
echo '</a>
                    </li>
                    <li class="footer_sec2_li">
                        Telegram: <a href="';
echo $db->site('telegram');
echo '">';
echo $db->site('telegram');
echo '</a>
                    </li>
                    <li class="footer_sec2_li">
                        Zalo: <a href="">';
echo $db->site('zalo');
echo '</a>
                    </li>
                    <li class="footer_sec2_li">
                        SĐT/Zalo Hỗ Trợ 24/24: <a href="tel:';
echo $db->site('hotline');
echo '">';
echo $db->site('hotline');
echo '</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="center">
        <div class="footer_bottom_row">
            <div class="copyright-footer">
                <h5 class="footer_bottom_title">@Copyright ';
echo date('Y');
echo ' - Privacy Policy - Terms of Service Operated by <a href="" class="text-secondary-color">';
echo strtoupper($_SERVER['SERVER_NAME']);
echo '</a></h5>
            </div>
            
        </div>
    </div>
</section>

<!-- Background -->
<style type="text/css">
    #footer {
        background-color: #1C1B1B;
    }
</style>
</div>
<div class="background-cart"></div>
<div class="background-mmenu"></div>

<!-- Modal prototype -->
<div class="modal fade toggle-auth" id="form-login-fast" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modals-auth modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="container-auth">
                <div class="banner-auth">
                    <img
                        src="';
echo $db->site('background_login');
echo '"
                        alt="" />
                    <div class="content-banner-auth">
                        <h3></h3>
                        <div class="noidung-auth">
                        </div>
                    </div>
                </div>
                <div>
                    <div class="form-auth register">
                        <div class="title-auth">
                            <h3>Đăng ký</h3>
                            <h4>Bạn đã có tài khoản <span class="toogle-form-auth no-select"
                                    data-action="login">Đăng nhập</span></h4>
                        </div>
                        <div class="login-google-facebook">
                            <h4>Đăng nhập hoặc đăng ký (miễn phí)</h4>
                            <div class="box-social-login">
                                <a href="/login/google"><img width="30" height="30"
                                        src="/assets/images/gg.webp" alt="Login with google"></a>

                            </div>
                            <div class="or-login">
                                <span></span>
                                <span>Hoặc</span>
                                <span></span>
                            </div>
                        </div>
                        <form class="validation-form" id="submit-register" novalidate
                            enctype="multipart/form-data">
                            <div class="form-reg-email">
                                <div class="group-input input-text">
                                    <div class="input-element">
                                        <input class="input-text" name="email-register" type="email"
                                            id="email-register-modals" max="100" placeholder="Nhập email"
                                            required>
                                        <max class="count-input"></max>
                                    </div>
                                </div>
                                <div class="group-input input-text">
                                    <div class="input-element">
                                        <input class="input-text" name="username-register" type="text"
                                            id="username-register-modals" max="25" placeholder="Nhập tài khoản"
                                            required>
                                        <input type="hidden" class="input-text" name="csrf_token" value="';
echo generate_csrf_token();
echo '" required>
                                        <max class="count-input"></max>
                                    </div>
                                </div>
                                <div class="group-input input-password">
                                    <div class="input-element">
                                        <input type="password" name="password-register" id="password-register-modals"
                                            placeholder="Nhập mật khẩu" required>
                                        <show class="eye-input hide"></show>
                                    </div>
                                </div>
                                <div class="group-input input-password">
                                    <div class="input-element">
                                        <input type="password" name="passwordcf-register" id="passwordcf-register-modals"
                                            placeholder="Xác nhận lại mật khẩu" required>
                                        <show class="eye-input hide"></show>
                                    </div>
                                </div>
                                <button type="submit" class="default-button" name="register">Đăng ký</button>
                                <div class="input-group mt-3 group-sub-login">
                                    <h4 class="toogle-form-auth no-select" data-action="login">Đăng nhập</h4>
                                    <h4 class="toogle-form-auth no-select" data-action="forgotpass">
                                        Quên mật khẩu</h4>
                                </div>
                            </div>
                        </form>
                        <div class="noidung-auth">
                            <p>Sau khi đăng ký, đồng nghĩa bạn chấp nhận&nbsp;<a href="" target="_blank" rel="noopener">
                                    <font color="#185BF3">Điều Khoản Dịch Vụ</font>&nbsp;
                                </a>của chúng tôi.&nbsp;<span class="Y2IQFc" lang="vi">Vui lòng đọc&nbsp;</span>
                                <font color="#185BF3"><a class="oz+h+rw" href="" target="_blank" rel="noopener">Chính Sách Quyền Riêng Tư&nbsp;</a></font>
                            </p>
                        </div>
                    </div>
                    <div class="form-auth login active">
                        <div class="title-auth">
                            <h3>Đăng nhập</h3>
                            <h4>Bạn chưa có tài khoản <span class="toogle-form-auth no-select"
                                    data-action="register">Đăng ký ngay</span></h4>
                        </div>
                        <div class="login-google-facebook">
                            <h4>Đăng nhập hoặc đăng ký (miễn phí)</h4>
                            <div class="box-social-login">
                                <a href="/login/google"><img width="30" height="30"
                                        src="/assets/images/gg.webp" alt="Login with google"></a>
                            </div>
                            <div class="or-login">
                                <span></span>
                                <span>Hoặc</span>
                                <span></span>
                            </div>
                        </div>
                        <form class="validation-form" id="submit-login" novalidate
                            enctype="multipart/form-data">
                            <div class="form-reg-email mt-3">
                                <div class="group-input input-text">
                                    <div class="input-element">
                                        <input type="text" class="input-text" name="account-login" id="account-login-modals"
                                            max="25" placeholder="Nhập tài khoản" required>
                                        <input type="hidden" class="input-text" name="csrf_token" value="';
echo generate_csrf_token();
echo '" required>
                                        <max class="count-input"></max>
                                    </div>
                                </div>
                                <div class="group-input input-password">
                                    <div class="input-element">
                                        <input type="password" name="password-login" id="password-login-modals"
                                            placeholder="Nhập mật khẩu" required>
                                        <show class="eye-input hide"></show>
                                    </div>
                                </div>
                                <div class="my-checkbox-dichvugame">
                                    <label for="show-checkbox-remember" class="my-dichvugame-label no-select">Lưu đăng nhập</label>
                                    <input type="checkbox" class="checkbox-dichvugame" name="remember-user" id="show-checkbox-remember">
                                </div>
                                <button type="submit" class="default-button" name="login">Đăng nhập</button>
                            </div>
                            <div class="input-group mt-3 group-sub-login">
                                <h4 class="toogle-form-auth no-select" data-action="register">Tạo tài khoản mới</h4>
                                <h4 class="toogle-form-auth no-select" data-action="forgotpass">Quên mật khẩu </h4>
                            </div>
                        </form>
                        <div class="noidung-auth">
                            <p>Sau khi đăng ký, đồng nghĩa bạn chấp nhận&nbsp;<a href="" target="_blank" rel="noopener">
                                    <font color="#185BF3">Điều Khoản Dịch Vụ</font>&nbsp;
                                </a>của chúng tôi.&nbsp;<span class="Y2IQFc" lang="vi">Vui lòng đọc&nbsp;</span>
                                <font color="#185BF3"><a class="oz+h+rw" href="" target="_blank" rel="noopener">Chính Sách Quyền Riêng Tư&nbsp;</a></font>
                            </p>
                        </div>

                    </div>
                    <div class="form-auth forgotpass">
                        <form class="validation-form" id="submit-forgotpass" novalidate method="post" action="account/quen-mat-khau"
                            enctype="multipart/form-data">
                            <div class="title-auth">
                                <h4 class="toogle-form-auth no-select" data-action="login"><i
                                        class="fas fa-long-arrow-alt-left mr-3"></i>Trở về</h4>
                                <h3>Quên mật khẩu với email</h3>
                            </div>
                            <div class="form-reg-email mt-3">
                                <div class="group-input input-text">
                                    <div class="input-element">
                                        <input class="input-text" name="email-register" type="text" id="email-register-modals"
                                            max="150" placeholder="Nhập email tài khoản của bạn" required>
                                        <max class="count-input"></max>
                                    </div>
                                </div>
                                <button type="submit" class="default-button" name="quenmatkhau">Lấy lại mật khẩu</button>
                            </div>
                        </form>
                        <div class="noidung-auth">
                            <p>Sau khi đăng ký, đồng nghĩa bạn chấp nhận&nbsp;<a href="" target="_blank" rel="noopener">
                                    <font color="#185BF3">Điều Khoản Dịch Vụ</font>&nbsp;
                                </a>của chúng tôi.&nbsp;<span class="Y2IQFc" lang="vi">Vui lòng đọc&nbsp;</span>
                                <font color="#185BF3"><a class="oz+h+rw" href="" target="_blank" rel="noopener">Chính Sách Quyền Riêng Tư&nbsp;</a></font>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal hide no-scrollbar" id="filter-yeucaudangnhap" tabindex="-1" role="dialog" aria-labelledby="filter-yeucaudangnhap" aria-hidden="true">
    <div class="modal-dialog popup-nguyennhieu" role="document">
        <div class="content-popup-nguyennhieu w-400">
            <div class="header-popup-confirm">
                <div class="title-popup-nguyennhieu">
                    <img src="/assets/images/thatbai.png" class="m-auto" alt="">
                </div>
                <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal" aria-label="Close">
                    &times;
                </button>
            </div>
            <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                <div class="modal-login-wheel">
                    <h3 class="text-danger">Vui lòng đăng nhập để tiếp tục</h3>
                </div>
            </div>
            <div class="footer-popup-confirm grid1">
                <button class="access-confirm-sieuthicode open-modals-auth" data-class="login">Đăng nhập</button>
            </div>
        </div>
    </div>
</div>
<div class="container-support" id="container-support">
    <a class="btn-zalo btn-frame text-decoration-none" target="_blank" href="';
echo $db->site('facebook');
echo '">
        <div class="animated infinite zoomIn kenit-alo-circle"></div>
        <div class="animated infinite pulse kenit-alo-circle-fill"></div>
        <i><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                data-v-c758b3fe="">
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M20 0C8.73239 0 0 8.25363 0 19.4007C0 25.2317 2.39034 30.27 6.28169 33.7509C6.60765 34.0447 6.80483 34.4511 6.82093 34.8898L6.92958 38.4472C6.9658 39.582 8.13682 40.3184 9.17505 39.8637L13.1429 38.1132C13.4809 37.9643 13.8551 37.9361 14.2093 38.0327C16.0322 38.5357 17.9759 38.8013 20 38.8013C31.2676 38.8013 40 30.5477 40 19.4007C40 8.25363 31.2676 0 20 0Z"
                    fill="url(#paint0_linear_586_455)" data-v-c758b3fe=""></path>
                <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M7.99199 25.0748L13.8672 15.7547C14.8008 14.2738 16.8049 13.9036 18.2053 14.9539L22.8773 18.459C23.3079 18.7809 23.8954 18.7769 24.322 18.455L30.6318 13.6662C31.4729 13.0263 32.5755 14.0364 32.0081 14.9298L26.1369 24.2458C25.2033 25.7267 23.1992 26.0969 21.7988 25.0466L17.1268 21.5415C16.6962 21.2196 16.1087 21.2236 15.6821 21.5455L9.36825 26.3384C8.5272 26.9782 7.42459 25.9681 7.99199 25.0748Z"
                    fill="white" data-v-c758b3fe=""></path>
                <defs data-v-c758b3fe="">
                    <linearGradient id="paint0_linear_586_455" x1="20" y1="0" x2="20" y2="40.0005"
                        gradientUnits="userSpaceOnUse" data-v-c758b3fe="">
                        <stop stop-color="#00B2FF" data-v-c758b3fe=""></stop>
                        <stop offset="1" stop-color="#006AFF" data-v-c758b3fe=""></stop>
                    </linearGradient>
                </defs>
            </svg></i>
    </a>
</div>
<div class="plugbar">
    <ul>
        <li class="menu-item-footer">
            <a href="/">
                <i class="fas fa-home"></i>
                <p>Trang chủ</p>
            </a>
        </li>
        <li class="menu-item-footer">
            <a href="/menu">
                <i class="fas fa-bars"></i>
                <p>Danh mục</p>
            </a>
        </li>
        <li class="menu-item-footer">
            <a href="/customer/deposit">
                <i class="far fa-credit-card"></i>
                <p>Nạp tiền</p>
            </a>
        </li>
        <li class="menu-item-footer">
            <a href="/nick-game">
                <i class="fas fa-gamepad"></i>
                <p>Mua nick</p>
            </a>
        </li>
        <li class="menu-item-footer">
            <a href="';
echo $user ? '/customer/profile' : '/login';
echo '">
                <i class="fas fa-user"></i>
                <p>Tài khoản</p>
            </a>
        </li>
    </ul>
</div>

<script type="text/javascript">
    var SIEUTHICODE = SIEUTHICODE || {};
    var CONFIG_BASE = \'/\';
    var WEBSITE_NAME = \'';
echo $db->site('title');
echo '\';
    var TIMENOW = \'03/02/2025\';
    var SHIP_CART = false;
    var GOTOP = \'assets/images/top.png\';
    var LANG = {
        \'no_keywords\': \'Vui lòng nhập đầy đủ từ khoá\',
        \'back\': \'Trở về\',
        \'warning\': \'Cảnh báo\',
        \'areyouwanttodelete\': \'Bạn chắc chắn muốn thực hiện thao tác này\',
        \'yesiscontinue\': \'Chon yes để tiếp tục thực hiện\',
        \'couponsisrequired\': \'Mã khuyến mãi không được để trống\',
        \'productsolderror\': \'Sản phẩm không đủ để thực hiện thao tác\',
        \'noquantitypctd\': \'Không đủ số lượng sản phẩm để thực hiện\',
        \'checkallattributes\': \'Vui lòng chọn đầy đủ thuộc tính\',
        \'placeseach\': \'Tìm kiếm\',
        \'select\': \'Select\',
        \'updatesuccess\': \'Cập nhật thành công\',
        \'areyousure\': \'Bạn chắc chắn thực hiện?\',
        \'tingting\': \'Thông báo\',
        \'norestore\': \'Không thể khôi phục\',
        \'viewmore\': \'Xem thêm\',
        \'rutgon\': \'Rút gọn\',
        \'dacopynoidung\': \'Đã sao chép nội dung được chọn\',
        \'vuilongdiendayduthongtin\': \'Vui lòng điền đầy đủ thông tin\',
        \'bancomuonluucapnhathienthi\': \'Bạn có muốn lưu cập nhật này\',
        \'chonyesdeluuthaydoi\': \'Chọn yes để thực hiện thay đổi\',
    };
</script>

<script src="/assets/js/highlight.min.js"></script>
<script src="/assets/lenis/gsap.js"></script>
<script src="/assets/js/hc-canvas-luckwheel.js"></script>

<script type="text/javascript" src="/assets/js/cached.js?v=';
echo (string) (@filemtime(APP_ROOT . '/assets/js/cached.js') ?: 1);
echo '"></script>
<script src="/assets/js/swiper-slider-conf.js"></script>
<script src="/assets/js/custom.js"></script>
</body>

</html>';
