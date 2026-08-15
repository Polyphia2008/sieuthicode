<?php
// statically decompiled from menu.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    echo '<div class="content-notification1">
    <div class="content-info-login">
        <div class="sidebar-user-top">
            <img src="/assets/images/anhdaidien.svg" alt="dev trum">
            <div class="sidebar-user-top-text">
                <h3>';
    echo $data_user['provider'] == 'google' ? $data_user['name'] : $data_user['username'];
    echo '</h3>
                <h4>Số dư: <span class="display_money_member">';
    echo format_cash($data_user['money']);
    echo 'đ</span></h4>
                <h4>Số dư coin: <span class="display_coin_member">';
    echo format_cash($data_user['cost']);
    echo 'đ</span></h4>
                <h5>ID: <span>';
    echo $data_user['id'];
    echo '</span></h5>
            </div>
        </div>
        <div class="sidebar-user-info">
            ';
    if (is_admin_account($data_user)) {
        echo '                <a class="item-link" href="/cpanel/home">
                    <span><img src="/assets/images/control-system.png" width="25px" alt=""></span>
                    <h3>Thông quản trị</h3>
                    <img src="/assets/images/sidebar_arrow_right.svg" alt="">
                </a>
            ';
    }
    echo '            ';
    if ($data_user['ctv'] == 1) {
        echo '                <a class="item-link" target="_blank" href="/ctv/home">
                    <span><img src="/assets/images/control-system.png" width="25px" alt=""></span>
                    <h3>Trang cộng tác viên</h3>
                    <img src="/assets/images/sidebar_arrow_right.svg" alt="">
                </a>
            ';
    }
    echo '            <a class="item-link" target="_blank" href="/customer/profile">
                <span><img src="/assets/images/thongtintaikhoan.svg" alt=""></span>
                <h3>Thông tin tài khoản</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
            <a class="item-link " href="/customer/change/password">
                <span><img src="/assets/images/doimatkhau.svg" alt=""></span>
                <h3>Đổi mật khẩu</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
        </div>
        <div class="sidebar-user-giaodich">
            <h4>Quản lý giao dịch</h4>
            <div class="content-giaodich">
                <a class="item-link " href="/customer/deposit">
                    <span><img src="/assets/images/naptien.svg" alt=""></span>
                    <h3>Nạp tiền</h3>
                </a>
                <a class="item-link " href="/customer/withdraw">
                    <span><img src="/assets/images/rutvatpham.svg" alt=""></span>
                    <h3>Rút vật phẩm</h3>
                </a>
            </div>
        </div>
        <div class="sidebar-user-info">
            <a class="item-link " href="/customer/history/balance">
                <span><img src="/assets/images/biendongsodu.svg" alt=""></span>
                <h3>Biến động số dư</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
            <a class="item-link " href="/customer/history/withdraw">
                <span><img src="/assets/images/lichsuquaythuong.svg" alt=""></span>
                <h3>Lịch sử rút vật phẩm</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
            <a class="item-link " href="/customer/history/account">
                <span><img src="/assets/images/taikhoandamua.svg" alt=""></span>
                <h3>Tài khoản đã mua</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
            <a class="item-link " href="/customer/history/minigame">
                <span><img src="/assets/images/lichsuquaythuong.svg" alt=""></span>
                <h3>Lịch sử chơi minigame</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
            <a class="item-link " href="/customer/history/service">
                <span><img src="/assets/images/dichvudamua.svg" alt=""></span>
                <h3>Dịch vụ đã mua</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
            <a class="item-link " href="/customer/history/card">
                <span><img src="/assets/images/lichsunapthe.svg" alt=""></span>
                <h3>Lịch sử nạp thẻ</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
            <a class="item-link "
                href="/customer/history/bank">
                <span><img src="/assets/images/napatmtudong.svg" alt=""></span>
                <h3>Lịch sử nạp atm tự động</h3>
                <img src="/assets/images/sidebar_arrow_right.svg" alt="">
            </a>
        </div>
        <div class="sidebar-user-info">
            <a class="item-link" href="/logout">
                <span><img src="/assets/images/log-out.svg" alt=""></span>
                <h3>Đăng xuất</h3>
            </a>
        </div>
    </div>
</div>
';
}
