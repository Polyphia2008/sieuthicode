<?php
// statically decompiled from viewcard.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    if (isset($_GET['transid'])) {
        $code = Anti_xss($_GET['transid']);
        $info = $db->get_row(' SELECT * FROM `cards` WHERE `trans_id` = \'' . $code . '\' AND `user_id` = \'' . $data_user['id'] . '\' ');
        if (!$info) {
            new Redirect('/customer/history/card');
        }
    } else {
        new Redirect('/customer/history/card');
    }
    $title = 'Chi tiết nạp thẻ cào - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Chi tiết nạp thẻ</span></a></li>
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
                    <h2 class="title-account">Chi tiết nạp thẻ</h2>
                    <hr>
                </div>
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="account/lich-su-nap-the"><img src="assets/images/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Chi tiết nạp thẻ</h2>
                    </div>
                </div>
                <div class="content-account">
                    <h2 class="title-sub-account">Nạp thẻ ';
    echo $info['telco'];
    echo '</h2>
                    <hr>
                    <h4 class="mt-3">Thông tin giao dịch</h4>
                    <div class="container-content-account mt-3">
                        <div class="items-content-account">
                            <div>
                                <p>Mã GD</p>
                                <P>#';
    echo $info['trans_id'];
    echo ' <span class="ml-3 copy copyButton" data-text="';
    echo $info['trans_id'];
    echo '"><i class="far fa-copy"></i></span></P>
                            </div>
                            <div>
                                <p>Nhà mạng</p>
                                <P>';
    echo $info['telco'];
    echo '</P>
                            </div>
                            <div>
                                <p>Kiểu nạp</p>
                                <P>Nạp tự động</P>
                            </div>
                        </div>
                        <div class="items-content-account">
                            <div>
                                <p>Mệnh giá thẻ</p>
                                <P>';
    echo format_cash($info['amount']);
    echo 'đ</P>
                            </div>
                            <div>
                                <p>Thực nhận</p>
                                <P>';
    echo format_cash($info['price']);
    echo 'đ</P>
                            </div>
                            <div>
                                <p>Ngày giao dịch</p>
                                <P>';
    echo $info['create_date'];
    echo '</P>
                            </div>
                            <div>
                                <p>Trạng thái</p>
                                <P>';
    echo display_card($info['status']);
    echo '</P>
                            </div>
                        </div>
                        <div class="items-content-account">
                            <div>
                                <p>Thông tin thẻ nạp</p>
                                <P></P>
                            </div>
                            <div>
                                <p>Mã thẻ</p>
                                <P>';
    echo $info['pin'];
    echo ' <span class="ml-3 copy copyButton" data-text="';
    echo $info['pin'];
    echo '"><i class="far fa-copy"></i></span></P>
                            </div>
                            <div>
                                <p>Số seri</p>
                                <P>';
    echo $info['serial'];
    echo ' <span class="ml-3 copy copyButton" data-text="';
    echo $info['serial'];
    echo '"><i class="far fa-copy"></i></span></P>
                            </div>
                        </div>
                        <a href="/customer/history/card" class="button-trove-content">Trở về</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
