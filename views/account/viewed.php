<?php
// statically decompiled from viewed.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Nick game đã xem | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
$viewed_products = loadViewedProducts();
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="/viewed"><span>Nick game đã xem</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen">
    <div class="center">
        <form class="title-nickgame" method="GET">
            <h4>';
echo countViewedProducts();
echo ' Tài khoản</h4>
        </form>
        ';
if (0 < count($viewed_products)) {
    echo '            <div class="section-container-index">
                ';
    foreach ($viewed_products as $viewed_product_id) {
        $account = $db->get_row('SELECT * FROM `accounts` WHERE `id` = \'' . $viewed_product_id . '\'');
        if ($account) {
            $check = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $account['type_category'] . '\'');
            if ($check) {
                $detail_product = json_decode($check['detail'], true);
                $arr_img = json_decode($account['image'], true);
                $detail = json_decode($account['detail'], true);
                echo '                            ';
                if ($check['type'] == 'ACCOUNT') {
                    echo '                                <a href="/tai-khoan/chi-tiet/';
                    echo $account['id'];
                    echo '" class="items-content-index">
                                    <div class="scale-img">
                                        <img src="';
                    echo DOMAIN . '/' . $arr_img[0];
                    echo '"
                                            class="lazyLoad">
                                    </div>
                                    <h3>';
                    echo $detail_product['name_product'];
                    echo '</h3>
                                    <h4>ID: #';
                    echo $account['id'];
                    echo '</h4>
                                    ';
                    $i = 2;
                    while ($i < count($detail['data'])) {
                        if ($detail['data'][$i]['show'] == 'on') {
                            echo '                                            <p class="p06">';
                            echo $detail['data'][$i]['label'];
                            echo ': ';
                            echo account_field_display($detail['data'][$i]);
                            echo '</p>
                                    ';
                        }
                        ++$i;
                    }
                    echo '                                    <p class="ppr">';
                    echo format_cash($account['money'] - $account['money'] * $account['sale'] / 100);
                    echo 'đ</p>
                                    ';
                    if ($account['sale'] != 0) {
                        echo '                                        <p class="ppo"><span>';
                        echo format_cash($account['money']);
                        echo 'đ</span> <span>';
                        echo $account['sale'];
                        echo '%</span></p>
                                    ';
                    }
                    echo '                                </a>
                            ';
                } else {
                    echo '                                <div class="items-content-index buy-random random-id-';
                    echo $account['id'];
                    echo '" data-id="';
                    echo $account['id'];
                    echo '" data-price="';
                    echo format_cash($account['money'] - $account['money'] * $account['sale'] / 100);
                    echo '">
                                    <div class="scale-img">
                                        <img src="';
                    echo DOMAIN . '/' . $detail_product['thumb'];
                    echo '" alt="';
                    echo $detail_product['name_product'];
                    echo '">
                                    </div>
                                    <h3>';
                    echo $detail_product['name_product'];
                    echo '</h3>
                                    <p class="ppr">';
                    echo format_cash($account['money'] - $account['money'] * $account['sale'] / 100);
                    echo 'đ</p>
                                    <button>Mua ngay</button>
                                </div>
                            ';
                }
                echo '                ';
            }
        }
    }
    echo '            </div>
        ';
} else {
    echo '            <div class="section_no-record">
                <p class="text-danger">Hiện tại không có dữ liệu nào phù hợp với yêu cầu của bạn! Hệ thống cập nhật nick thường xuyên bạn vui lòng theo dõi web trong thời gian tới !</p>
            </div>
        ';
}
echo '    </div>
    
</section>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
