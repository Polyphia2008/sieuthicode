<?php
// statically decompiled from viewwithdraw.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    if (isset($_GET['transid'])) {
        $code = Anti_xss($_GET['transid']);
        $info = $db->get_row(' SELECT * FROM `withdraw_logs` WHERE `trans_id` = \'' . $code . '\' AND `user_id` = \'' . $data_user['id'] . '\' ');
        if (!$info) {
            new Redirect('/customer/history/withdraw');
        }
        $detail = json_decode($info['detail'], true);
    } else {
        new Redirect('/customer/history/withdraw');
    }
    $title = 'Chi tiết vật phẩm đã rút - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Chi tiết vật phẩm đã rút</span></a></li>
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
                    <h2 class="title-account">Chi tiết vật phẩm đã rút</h2>
                    <hr>
                </div>
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="account/lich-su-nap-the"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Chi tiết vật phẩm đã rút</h2>
                    </div>
                </div>
                <div class="content-account">
                    <h2 class="title-sub-account">Gói ';
    echo $detail['name_package'];
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
                                <p>Loại vật phẩm</p>
                                <P>';
    echo $detail['name_product'];
    echo '</P>
                            </div>
                            <div>
                                <p>Gói</p>
                                <P>';
    echo $detail['name_package'];
    echo '</P>
                            </div>
                            <div>
                                <p>Trạng thái</p>
                                <P>';
    echo display_service($info['status']);
    echo '</P>
                            </div>
                        </div>
                        <div class="items-content-account">
                            
                            <div>
                                <p>Ngày giao dịch</p>
                                <P>';
    echo $info['created_at'];
    echo '</P>
                            </div>
                           
                        </div>
                        <div class="items-content-account">
                            <div>
                                <p>Thông tin người dùng</p>
                                <P></P>
                            </div>
                            ';
    foreach ($detail['data'] as $item) {
        echo '                                <div>
                                    <p>';
        echo $item['label'];
        echo '</p>
                                    <P>';
        echo $item['value'];
        echo ' <span class="ml-3 copy copyButton" data-text="';
        echo $item['value'];
        echo '"><i class="far fa-copy"></i></span></P>
                                </div>
                            ';
    }
    echo '                            
                        </div>
                        <a href="/customer/history/withdraw" class="button-trove-content">Trở về</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
