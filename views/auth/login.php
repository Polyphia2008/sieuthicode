<?php
// statically decompiled from login.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Đăng nhập | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
if ($user) {
    new Redirect('/');
}
echo '<div class="screen">
    <div class="center py-4">
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
                    <form class="validation-form" novalidate id="submit-register"
                        enctype="multipart/form-data">
                        <div class="form-reg-email">
                            <div class="group-input input-text">
                                <div class="input-element">
                                    <input class="input-text input-email-register" name="email-register" type="email" id="email-register" max="100" placeholder="Nhập email" required>
                                    <input type="hidden" class="input-text" name="csrf_token" value="';
echo generate_csrf_token();
echo '" required>
                                    <max class="count-input"></max>
                                </div>
                            </div>
                            <div class="group-input input-text">
                                <div class="input-element">
                                    <input class="input-text input-username-register" name="username-register" type="text" id="username-register" max="25" placeholder="Nhập tài khoản" required>
                                    <max class="count-input"></max>
                                </div>
                            </div>
                            <div class="group-input input-password">
                                <div class="input-element">
                                    <input type="password" name="password-register" id="password-register" placeholder="Nhập mật khẩu" required>
                                    <show class="eye-input hide"></show>
                                </div>
                            </div>
                            <div class="group-input input-password">
                                <div class="input-element">
                                    <input type="password" name="passwordcf-register" id="passwordcf-register" placeholder="Xác nhận lại mật khẩu" required>
                                    <show class="eye-input hide"></show>
                                </div>
                            </div>
                            <button type="submit" class="default-button" name="register">Đăng ký</button>
                            <div class="input-group mt-3 group-sub-login">
                                <h4 class="toogle-form-auth no-select" data-action="login">Đăng nhập</h4>
                                <h4 class="toogle-form-auth no-select" data-action="forgotpass">Quên mật khẩu</h4>
                            </div>
                        </div>
                    </form>
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
                    <form class="validation-form" novalidate id="submit-login"
                        enctype="multipart/form-data">
                        <div class="form-reg-email mt-3">
                            <div class="group-input input-text">
                                <div class="input-element">
                                    <input type="text" class="input-text" name="account-login" id="account-login" max="25" placeholder="Nhập tài khoản" required>
                                    <input type="hidden" class="input-text" name="csrf_token" value="';
echo generate_csrf_token();
echo '" required>
                                    <max class="count-input"></max>
                                </div>
                            </div>
                            <div class="group-input input-password">
                                <div class="input-element">
                                    <input type="password" name="password-login" id="password-login" placeholder="Nhập mật khẩu" required>
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
                            <h4 class="toogle-form-auth no-select" data-action="forgotpass">Quên mật khẩu</h4>
                        </div>
                    </form>
                </div>
                <div class="form-auth forgotpass">
                    <form class="validation-form" novalidate id="submit-forgotpass"
                        enctype="multipart/form-data">
                        <div class="title-auth">
                            <h4 class="toogle-form-auth no-select" data-action="login"><i
                                    class="fas fa-long-arrow-alt-left mr-3"></i>Trở về</h4>
                            <h3>Quên mật khẩu với email</h3>
                        </div>
                        <div class="form-reg-email mt-3">
                            <div class="group-input input-text">
                                <div class="input-element">
                                    <input type="hidden" class="input-text" name="csrf_token" value="';
echo generate_csrf_token();
echo '" required>
                                    <input class="input-text" name="email" type="text" id="email-register" max="150" placeholder="Nhập email tài khoản của bạn" required>
                                    <max class="count-input"></max>
                                </div>
                            </div>
                            <button type="submit" class="default-button" name="quenmatkhau">Lấy lại mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
