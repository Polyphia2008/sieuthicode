<?php
// statically decompiled from loadflashsale.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$flashsale_id = isset($_POST['id']) ? Anti_xss($_POST['id']) : 0;
$where = ' `id` > 0';
if (!empty($flashsale_id) && is_numeric($flashsale_id)) {
    $where .= ' AND `id` = \'' . $flashsale_id . '\'';
} else {
    $current_time = date('Y-m-d H:i:s');
    $active_flashsale = $db->get_row('SELECT * FROM `flash_sales` WHERE `start_time` <= \'' . $current_time . '\' AND `end_time` >= \'' . $current_time . '\' ORDER BY `start_time` ASC LIMIT 1');
    if ($active_flashsale) {
        $flashsale_id = $active_flashsale['id'];
    } else {
        $upcoming_flashsale = $db->get_row('SELECT * FROM `flash_sales` WHERE `start_time` > \'' . $current_time . '\' ORDER BY `start_time` ASC LIMIT 1');
        if ($upcoming_flashsale) {
            $flashsale_id = $upcoming_flashsale['id'];
        }
    }
}
$current_time_flashsale = time();
$flashsale = $db->get_row('SELECT * FROM `flash_sales` WHERE `end_time` >= NOW()  ORDER BY `start_time` ASC LIMIT 1');
if ($flashsale) {
    $start_timestamp = !empty($flashsale['start_time']) ? strtotime($flashsale['start_time']) * 1000 : 0;
    $end_timestamp = !empty($flashsale['end_time']) ? strtotime($flashsale['end_time']) * 1000 : 0;
    if ($current_time_flashsale < strtotime($flashsale['start_time'])) {
        $status = 'Bắt đầu sau:';
    } else {
        if (strtotime($flashsale['start_time']) <= $current_time_flashsale && $current_time_flashsale < strtotime($flashsale['end_time'])) {
            $status = 'Kết thúc sau:';
        } else {
            $status = 'Đã kết thúc';
        }
    }
} else {
    $start_timestamp = 2;
    $end_timestamp = 2;
    $status = 'Đã kết thúc';
}
echo '<div class="header-flashsale">
    <div class="left-header-falshsale">
        <div class="title-plashsale">
            <img src="/assets/images/icon-set.png" alt="">
            <h2>FLASH SALE</h2>
        </div>
        <h4>
            <img src="/assets/images/icon-timerrr.png" alt="">
            <span id="flashsale-status">';
echo $status;
echo '</span>
        </h4>
        <ul class="timer" id="countDowns"
            data-start="';
echo $start_timestamp;
echo '"
            data-end="';
echo $end_timestamp;
echo '"
            data-status="';
echo $status;
echo '">
            <li>
                <span id="days">00</span>
                <span>NGÀY</span>
            </li>
            <li>
                <span id="hours">00</span>
                <span>GIỜ</span>
            </li>
            <li>
                <span id="min">00</span>
                <span>PHÚT</span>
            </li>
            <li>
                <span id="sec">00</span>
                <span>GIÂY</span>
            </li>
        </ul>
    </div>
    <div class="right-header-flashsale">
        <a href="/flashsale">Xem tất cả <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
    </div>
</div>
<div class="main-flashsale component-tabs">
    <ul class="nav nav-nguyennhieu list-flashsale" id="custom-tabs-flashsale" role="tablist">
        ';
foreach ($db->get_list('SELECT * FROM `flash_sales` WHERE ' . $where . ' ORDER BY `start_time` ASC') as $flash) {
    echo '            <li class="nav-item">
                <a class="nav-link ';
    echo $flashsale_id == $flash['id'] ? 'active show' : '';
    echo '"
                    data-id="';
    echo $flash['id'];
    echo '"
                    data-toggle="pill"
                    href="#tabs-flashsale-';
    echo $flash['id'];
    echo '"
                    role="tab"
                    aria-controls="tabs-flashsale-';
    echo $flash['id'];
    echo '"
                    aria-selected="';
    echo $flashsale_id == $flash['id'] ? 'true' : 'false';
    echo '">
                    <span>';
    echo getHourAndMinute($flash['start_time']);
    echo ' - ';
    echo getHourAndMinute($flash['end_time']);
    echo '</span>
                    <span>';
    echo getFlashSaleStatus($flash['start_time'], $flash['end_time'])['msg'];
    echo '</span>
                </a>
            </li>
        ';
}
echo '    </ul>
    <div class="tab-content" id="custom-tabs-flashsale-tabContent">

        ';
foreach ($db->get_list('SELECT * FROM `flash_sales` WHERE ' . $where) as $flash) {
    echo '            <div class="tab-pane fade show';
    echo $flashsale_id == $flash['id'] ? ' active' : '';
    echo '"
                id="tabs-flashsale-';
    echo $flash['id'];
    echo '"
                role="tabpanel"
                aria-labelledby="tabs-flashsale">
                <div class="container-product-sale owl-carousel owl-theme">
                    ';
    foreach ($db->get_list('SELECT * FROM `flash_sale_products` WHERE `flash_sale_id` = \'' . $flash['id'] . '\'') as $product) {
        $account = $db->get_row('SELECT * FROM `accounts` WHERE `id` = \'' . $product['product_id'] . '\'');
        if ($account) {
            $check = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $account['type_category'] . '\'');
            if ($check) {
                $detail_product = json_decode($check['detail'], true);
                $arr_img = json_decode($account['image'], true);
                $detail = json_decode($account['detail'], true);
                $discountPrice = getProductFlashSale($account['id']);
                echo '
                                <div class="items-content-flashsale ';
                echo $account['status'] == 'off' ? 'sold-out' : '';
                echo '">

                                    <a href="';
                echo getFlashSaleStatus($flash['start_time'], $flash['end_time'])['status'] == 0 ? 'javascript:' : '/tai-khoan/chi-tiet/' . $product['product_id'] . '';
                echo '" class="scale-img">
                                        ';
                if ($check['type'] == 'ACCOUNT') {
                    echo '                                            <img  src="/assets/images/lazyload.gif" data-src="';
                    echo DOMAIN . '/' . $arr_img[0];
                    echo '" class="lazyload" alt="">
                                        ';
                } else {
                    echo '                                            <img  src="/assets/images/lazyload.gif" data-src="';
                    echo DOMAIN . '/' . $detail_product['thumb'];
                    echo '" class="lazyload" alt="">
                                        ';
                }
                echo '
                                    </a>
                                    <h3><a href="';
                echo getFlashSaleStatus($flash['start_time'], $flash['end_time'])['status'] == 0 ? 'javascript:' : '/tai-khoan/chi-tiet/' . $product['product_id'] . '';
                echo '">Giảm Giá Giờ Vàng</a></h3>
                                    <h4><a href="';
                echo getFlashSaleStatus($flash['start_time'], $flash['end_time'])['status'] == 0 ? 'javascript:' : '/tai-khoan/chi-tiet/' . $product['product_id'] . '';
                echo '">ID: #';
                echo $account['id'];
                echo '</a></h4>
                                    ';
                $i = 2;
                while ($i < count($detail['data'])) {
                    if ($detail['data'][$i]['show'] == 'on') {
                        echo '                                            <p class="p06">';
                        echo $detail['data'][$i]['label'];
                        echo ': <span>';
                        echo decodecryptData($detail['Data'][$i]['value']);
                        echo '</span></p>
                                    ';
                    }
                    ++$i;
                }
                echo '
                                    <p class="ppr">';
                echo format_cash($account['money'] - $account['money'] * $account['sale'] / 100 - $product['discount_price']);
                echo 'đ</p>
                                    <p class="ppo"><span>';
                echo format_cash($account['money'] - $account['money'] * $account['sale'] / 100);
                echo 'đ</span><span><i class="fas fa-bolt"></i> -';
                echo calculateDiscountFromAmount($account['money'] - $account['money'] * $account['sale'] / 100, $product['discount_price']);
                echo '%</span></p>
                                    <a href="';
                echo $user ? '/tai-khoan/chi-tiet/' . $product['product_id'] : '/login';
                echo '" class="button-buy-flashsale"><span>Mua ngay</span><span>KẾT THÚC FLASHSALE</span></a>
                                </div>
                    ';
            }
        }
    }
    echo '                </div>
            </div>
        ';
}
echo '    </div>
</div>

<script>
    const countDownElement = document.getElementById("countDowns");
    const statusElement = document.getElementById("flashsale-status");

    let startTime = parseInt(countDownElement.getAttribute("data-start"));
    let endTime = parseInt(countDownElement.getAttribute("data-end"));
    let status = countDownElement.getAttribute("data-status");

    function updateCountdown() {
        const now = new Date().getTime();

        if (now >= endTime) {
            statusElement.innerText = "Đã kết thúc";
            document.getElementById("days").innerText = "00";
            document.getElementById("hours").innerText = "00";
            document.getElementById("min").innerText = "00";
            document.getElementById("sec").innerText = "00";
            clearInterval(countdownInterval);
            return;
        }

        let targetTime;
        if (now < startTime) {
            targetTime = startTime;
            statusElement.innerText = "Bắt đầu sau:";
        } else {
            targetTime = endTime;
            statusElement.innerText = "Kết thúc sau:";
        }

        const distance = targetTime - now;
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = days.toString().padStart(2, \'0\');
        document.getElementById("hours").innerText = hours.toString().padStart(2, \'0\');
        document.getElementById("min").innerText = minutes.toString().padStart(2, \'0\');
        document.getElementById("sec").innerText = seconds.toString().padStart(2, \'0\');
    }


    let countdownInterval = setInterval(updateCountdown, 1000);
    updateCountdown();
</script>';
