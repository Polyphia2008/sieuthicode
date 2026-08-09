<?php
// statically decompiled from viewnick.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    if (isset($_GET['transid'])) {
        $code = Anti_xss($_GET['transid']);
        $info = $db->get_row(' SELECT * FROM `history_buy` WHERE `trans_id` = \'' . $code . '\' AND `username` = \'' . $data_user['username'] . '\' ');
        if (!$info) {
            new Redirect('/customer/history/account');
        }
        $query_product = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $info['type_category'] . '\'');
        $detail_product = json_decode($query_product['detail'], true);
        $detail = json_decode($info['detail'], true);
        $arr_detail = $detail['data'];
    } else {
        new Redirect('/customer/history/account');
    }
    $title = 'Thông tin đơn hàng - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Chi tiết tài khoản đã mua</span></a></li>
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
                    <h2 class="title-account">Chi tiết tài khoản đã mua</h2>
                    <hr>
                </div>
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="account/lich-su-nap-the"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Chi tiết tài khoản đã mua</h2>
                    </div>
                </div>
                <div class="content-account">

                    <h4 class="mt-3">Thông tin giao dịch</h4>
                    <div class="container-content-account mt-3">
                        <div class="items-content-account">
                            <div>
                                <p>Mã GD</p>
                                <P>#';
    echo $info['trans_id'];
    echo '</P>
                            </div>
                            <div>
                                <p>Danh mục</p>
                                <P>';
    echo $detail_product['name_product'];
    echo '</P>
                            </div>
                        </div>
                        <div class="items-content-account">
                            <div>
                                <p>Thanh toán</p>
                                <P>';
    echo format_cash($info['cash']);
    echo 'đ</P>
                            </div>
                            <div>
                                <p>Ngày giao dịch</p>
                                <P>';
    echo date('Y-m-d H:i:s', $info['created_at']);
    echo '</P>
                            </div>
                            <div>
                                <p>Trạng thái</p>
                                <P><span class=\'text-success\'>Thành công</span></P>
                            </div>
                        </div>
                        <div class="items-content-account">
                            <div>
                                <p>Thông tin tài khoản</p>
                                <P></P>
                            </div>
                            ';
    foreach ($arr_detail as $item) {
        echo '                                <div>
                                    <p>';
        echo $item['label'];
        echo '</p>
                                    <P>';
        echo decodecryptData($item['value']);
        echo ' <span class="ml-3 copy copyButton" data-text="';
        echo decodecryptData($item['value']);
        echo '"><i class="far fa-copy"></i></span></P>
                                </div>
                            ';
    }
    echo '

                        </div>

                        <a href="/customer/history/account" class="button-trove-content">Trở về</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
