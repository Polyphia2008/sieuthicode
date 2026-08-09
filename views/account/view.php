<?php
// statically decompiled from view.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['id'])) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row(' SELECT * FROM `accounts` WHERE `id` = \'' . $id . '\' AND `status` = \'on\' ');
    if (!$row) {
        new Redirect('/');
    }
    if ($row['type'] == 'ACCOUNT') {
        saveViewedProduct($id);
    }
    $detail = json_decode($row['detail'], true);
    $arr_img = json_decode($row['image'], true);
    $check = $db->get_row('SELECT * FROM `subcategory` WHERE `id` = \'' . $row['sub_id'] . '\'');
    $detail_product = json_decode($check['detail'], true);
    $discountPrice = getProductFlashSale($id);
    $getUser = $db->get_row('SELECT id FROM `users` WHERE `username` = \'' . $row['username_post'] . '\' ');
    $getReviews = $db->get_row('SELECT COUNT(*) AS total_reviews, AVG(rating) AS avg_rating FROM reviews WHERE `seller_id` = \'' . $getUser['id'] . '\' ');
    $rating = round($getReviews['avg_rating'] ?? 0, 1);
    $fullStars = floor($rating);
    $halfStar = 0.5 <= $rating - $fullStars;
} else {
    new Redirect('/');
}
$title = 'Tài khoản - ' . $row['id'] . ' | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none"
                            href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item "><a class="text-decoration-none"
                            href="/nick-game"><span>Nick game</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none"
                            href=""><span>';
echo $detail_product['name_product'];
echo '</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen">
    <div class="center">
        <form id="section-nickgame">
            <div class="left-nickgame">
                <div class="swiper-product">
                    ';
if ($row['type'] == 'RANDOM') {
    echo '                        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                            class="swiper mySwiper2 product-detail">
                            <div class="swiper-wrapper" id="lightgallery">
                                <div class="swiper-slide product-detail"
                                    data-url="';
    echo DOMAIN . '/' . $detail_product['thumb'];
    echo '">
                                    <a class="zoom"
                                        href="';
    echo DOMAIN . '/' . $detail_product['thumb'];
    echo '"
                                        title="">
                                        <img src="/assets/images/lazyload.gif" data-src="';
    echo DOMAIN . '/' . $detail_product['thumb'];
    echo '" class="lazyload" alt="" />
                                    </a>
                                </div>
                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <canvas id="canvas" style="display: none;"></canvas>
                        </div>

                    ';
} else {
    echo '                        <div style="--swiper-navigation-color: #fff; --swiper-pagination-color: #fff"
                            class="swiper mySwiper2 product-detail">
                            <div class="swiper-wrapper" id="lightgallery">
                                ';
    foreach ($arr_img as $__key => $img) {
        $key = $__key;
        echo '                                    <div class="swiper-slide product-detail"
                                        data-url="';
        echo DOMAIN . '/' . $img;
        echo '">
                                        <a class="zoom"
                                            href="';
        echo DOMAIN . '/' . $img;
        echo '"
                                            title="">
                                            <img src="/assets/images/lazyload.gif" data-src="';
        echo DOMAIN . '/' . $img;
        echo '" class="lazyload" alt="" />
                                        </a>
                                    </div>
                                ';
    }
    echo '                            </div>
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <canvas id="canvas" style="display: none;"></canvas>
                        </div>
                        <div thumbsSlider="" class="swiper mySwiper product-detail">
                            <div class="swiper-wrapper">
                                ';
    foreach ($arr_img as $__key => $img) {
        $key = $__key;
        echo '                                    <div class="swiper-slide product-detail">
                                        <img src="/assets/images/lazyload.gif" data-src="';
        echo DOMAIN . '/' . $img;
        echo '" class="lazyload" alt="" />
                                    </div>
                                ';
    }
    echo '                            </div>
                        </div>
                    ';
}
echo '                </div>
            </div>
            <div class="right-nickgame">
                ';
if ($discountPrice['flashSaleStatus'] == 'ongoing') {
    echo '                    <div class="header-flashsale-nickgame">
                        <div class="left-header-falshsale">
                            <div class="title-plashsale">
                                <img src="/assets//images/icon-set.png" alt="">
                                <h2>FLASH SALE</h2>
                            </div>
                            <h4>
                                <img src="/assets/images/icon-timerrr.png" alt="">
                                <span>Kết thúc sau: </span>
                            </h4>
                            <ul class="timer">
                                <li>
                                    <span id="hours">0</span>
                                    <span>Giờ</span>
                                </li>
                                <li>
                                    <span id="minutes">0</span>
                                    <span>Phút</span>
                                </li>
                                <li>
                                    <span id="seconds">0</span>
                                    <span>Giây</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                ';
}
echo '                <h2>';
echo $detail_product['name_product'];
echo '</h2>
                <h3>Mã số: #';
echo $id;
echo '</h3>
                <hr>
                <p>Thông tin acc</p>

                <div class="info-game">
                    ';
$i = 2;
while ($i < count($detail['data'])) {
    if ($detail['data'][$i]['show'] == 'on') {
        echo '                            <p><span>';
        echo $detail['data'][$i]['label'];
        echo '</span> <span>';
        echo decodecryptData($detail['Data'][$i]['value']);
        echo '</span></p>
                    ';
    }
    ++$i;
}
echo '                </div>

                <div class="gia-game">
                    ';
if ($discountPrice['flashSaleStatus'] == 'ongoing') {
    echo '                        <div class="box-gia">
                            <p class="giacu">';
    echo format_cash($row['money'] - $row['money'] * $row['sale'] / 100);
    echo 'đ</p>
                            <p class="giamoi flashsale-color">';
    echo format_cash($row['money'] - $row['money'] * $row['sale'] / 100 - $discountPrice['discountPrice']);
    echo 'đ</p>
                            <p class="giakm ">-';
    echo format_cash($discountPrice['discountPrice']);
    echo 'đ</p>
                        </div>
                    ';
} else {
    echo '                        ';
    if (0 < $row['sale']) {
        echo '                            <div class="box-gia">
                                <p class="giacu">';
        echo format_cash($row['money'] - $row['money'] * $row['sale'] / 100);
        echo 'đ</p>
                                <p class="giamoi flashsale-color">';
        echo format_cash($row['money'] - $row['money'] * $row['sale'] / 100 - $discountPrice['discountPrice']);
        echo 'đ</p>
                                <p class="giakm ">-';
        echo $row['sale'];
        echo '%</p>
                            </div>
                        ';
    } else {
        echo '                            <div class="box-gia">
                                <p class="giamoi flashsale-color">';
        echo format_cash($row['money'] - $row['money'] * $row['sale'] / 100 - $discountPrice['discountPrice']);
        echo 'đ</p>
                            </div>
                        ';
    }
    echo '                    ';
}
echo '                    <p>Rẻ vô đối, giá tốt nhất thị trường</p>
                </div>
                <hr>
                <div class="default-button button-nickgame" data-toggle="modal" data-target="#filter-lsnapthe">
                    Mua ngay </div>
                <div class="justify-content-center-custom mt-3">
                    <div class="rating-container">
                        <div class="stars">
                            ';
$i = 7;
while (1 <= $i) {
    echo '                                <label for="star';
    echo $i;
    echo '" class="';
    echo $i <= $fullStars ? 'filled' : ($i == $fullStars + 1 && $halfStar ? 'half-filled' : '');
    echo '">★</label>
                            ';
    --$i;
}
echo '                        </div>
                        <span class="rating-text">';
echo number_format($rating, 1);
echo '/5 (';
echo $getReviews['total_reviews'];
echo ' Đánh giá)</span>
                        <button type="button" class="review-button" onclick="location.href=\'/reviews?id=';
echo $getUser['id'];
echo '\';">Xem đánh giá</button>
                    </div>
                </div>
            </div>
            <div class="modal hide no-scrollbar" id="filter-lsnapthe" tabindex="-1" role="dialog"
                aria-labelledby="filter-lsnapthe" aria-hidden="true">
                <div class="modal-dialog popup-nguyennhieu" role="document">
                    <div class="content-popup-nguyennhieu w-500">
                        <div class="header-popup-confirm">
                            <div class="title-popup-nguyennhieu">
                                <div class="icon-popup-nguyennhieu-2" style="--color: #344054">
                                    <img
                                        src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDExSDE3LjhDMTguOTIwMSAxMSAxOS40ODAyIDExIDE5LjkwOCAxMS4yMThDMjAuMjg0MyAxMS40MDk3IDIwLjU5MDMgMTEuNzE1NyAyMC43ODIgMTIuMDkyQzIxIDEyLjUxOTggMjEgMTMuMDc5OSAyMSAxNC4yVjIxTTEzIDIxVjYuMkMxMyA1LjA3OTkgMTMgNC41MTk4NCAxMi43ODIgNC4wOTIwMkMxMi41OTAzIDMuNzE1NjkgMTIuMjg0MyAzLjQwOTczIDExLjkwOCAzLjIxNzk5QzExLjQ4MDIgMyAxMC45MjAxIDMgOS44IDNINi4yQzUuMDc5OSAzIDQuNTE5ODQgMyA0LjA5MjAyIDMuMjE3OTlDMy43MTU2OSAzLjQwOTczIDMuNDA5NzMgMy43MTU2OSAzLjIxNzk5IDQuMDkyMDJDMyA0LjUxOTg0IDMgNS4wNzk5IDMgNi4yVjIxTTIyIDIxSDJNNi41IDdIOS41TTYuNSAxMUg5LjVNNi41IDE1SDkuNSIgc3Ryb2tlPSIjMzQ0MDU0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" />
                                </div>
                                <div class="content-title-popup-nguyennhieu">
                                    <h3>Xác nhận thanh toán</h3>
                                    <p>Kiểm tra kỹ trước khi xác nhận thanh toán.</p>
                                </div>
                            </div>
                            <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal"
                                aria-label="Close">
                                &times;
                            </button>
                        </div>
                        <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                            <div id="xacnhan_thongtin">
                                <h3>Thông tin mua acc</h3>
                                <div class="line-thongtin">
                                    <p>Danh mục</p>
                                    <p>';
echo $detail_product['name_product'];
echo '</p>
                                </div>

                                <div class="line-thongtin">
                                    <p>Giá tiền</p>
                                    <p>';
echo format_cash($row['money'] - $row['money'] * $row['sale'] / 100 - $discountPrice['discountPrice']);
echo 'đ</p>
                                </div>
                                <br>
                                ';
$i = 2;
while ($i < count($detail['data'])) {
    if ($detail['data'][$i]['show'] == 'on') {
        echo '                                        <div class="line-thongtin">
                                            <p>';
        echo $detail['data'][$i]['label'];
        echo '</p>
                                            <p>';
        echo decodecryptData($detail['Data'][$i]['value']);
        echo '</p>
                                        </div>
                                ';
    }
    ++$i;
}
echo '                                <br>
                                <div class="line-thongtin">
                                    <p>Phí thanh toán</p>
                                    <p>Miễn phí</p>
                                </div>
                                <div class="line-thongtin">
                                    <p>Tổng thanh toán</p>
                                    <p>';
echo format_cash($row['money'] - $row['money'] * $row['sale'] / 100 - $discountPrice['discountPrice']);
echo 'đ</p>
                                </div>
                                ';
if ($user) {
    echo '                                    <div class="line-chonnguontien">
                                        <p>Phương thức thanh toán:</p>
                                        <div class="phuongthucthanhtoan-radio">
                                            <div class="container-radio-payments">
                                                <input class="radio-member-payments" type="radio" data-money="3000" data-giamoi="199000" name="radio-payments" id="radio-payments1" value="1" checked="">
                                                <label class="payments-label" for="radio-payments1">
                                                    <p>Số dư tài khoản</p>
                                                    <p>';
    echo format_cash($data_user['money']);
    echo 'đ</p>
                                                </label>
                                            </div>
                                            <div class="container-radio-payments">
                                                <input class="radio-member-payments" type="radio" data-money="0" data-giamoi="199000" name="radio-payments" disabled="" id="radio-payments2" value="2">
                                                <label class="payments-label" for="radio-payments2">
                                                    <p>Tài khoản coin</p>
                                                    <p>';
    echo format_cash($data_user['cost']);
    echo 'đ</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                ';
}
echo '                            </div>
                        </div>
                        <div class="footer-popup-confirm grid1">
                            ';
if ($user) {
    echo '                                <button type="button" class="access-confirm-sieuthicode" onclick="payment_account(`';
    echo $id;
    echo '`)">Thanh toán</button>
                            ';
} else {
    echo '                                <button class="access-confirm-sieuthicode open-modals-auth" data-class="login">Đăng
                                    nhập</button>
                            ';
}
echo '                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="section-content-index">
            <div class="title-index">
                <h3>Tài khoản game liên quan</h3>
                <a href="/tai-khoan/';
echo $row['type_category'];
echo '">Xem thêm <img src="/assets/images/sidebar_arrow_right.svg"
                        alt=""></a>
            </div>
            <div class="section-container-index">

                ';
$sql_show_1 = 'SELECT * FROM `accounts` WHERE `type_category` = \'' . $row['type_category'] . '\' AND `status` = \'on\' AND `id` NOT IN (' . $id . ') ORDER BY RAND() LIMIT 10';
foreach ($db->get_list($sql_show_1) as $info_1) {
    $check_1 = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $info_1['type_category'] . '\' ');
    $detail_product_1 = json_decode($check_1['detail'], true);
    $arr_img_1 = json_decode($info_1['image'], true);
    $detail_1 = json_decode($info_1['detail'], true);
    echo '                    ';
    if ($check_1['type'] == 'ACCOUNT') {
        echo '                        <a href="/tai-khoan/chi-tiet/';
        echo $info_1['id'];
        echo '" class="items-content-index">
                            <div class="scale-img">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo DOMAIN . '/' . $arr_img_1[0];
        echo '"
                                    class="lazyload">
                            </div>
                            <h3>';
        echo $detail_product_1['name_product'];
        echo '</h3>
                            <h4>ID: #';
        echo $info_1['id'];
        echo '</h4>
                            ';
        $i = 2;
        while ($i < count($detail_1['data'])) {
            if ($detail_1['data'][$i]['show'] == 'on') {
                echo '                                    <p class="p06">';
                echo $detail_1['data'][$i]['label'];
                echo ': ';
                echo decodecryptData($detail_1['Data'][$i]['value']);
                echo '</p>
                            ';
            }
            ++$i;
        }
        echo '                            <p class="ppr">';
        echo format_cash($info_1['money'] - $info_1['money'] * $info_1['sale'] / 100);
        echo 'đ</p>
                            ';
        if ($info_1['sale'] != 0) {
            echo '                                <p class="ppo"><span>';
            echo format_cash($info_1['money']);
            echo 'đ</span> <span>';
            echo $info_1['sale'];
            echo '%</span></p>
                            ';
        }
        echo '                        </a>
                    ';
    } else {
        echo '                        <div class="items-content-index buy-random random-id-';
        echo $info_1['id'];
        echo '" data-id="';
        echo $info_1['id'];
        echo '" data-price="';
        echo format_cash($info_1['money'] - $info_1['money'] * $info_1['sale'] / 100);
        echo '">
                            <div class="scale-img">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo DOMAIN . '/' . $detail_product_1['thumb'];
        echo '" class="lazyload" alt="';
        echo $detail_product_1['name_product'];
        echo '">
                            </div>
                            <h3>';
        echo $detail_product_1['name_product'];
        echo '</h3>
                            <p class="ppr">';
        echo format_cash($info_1['money'] - $info_1['money'] * $info_1['sale'] / 100);
        echo 'đ</p>
                            <button>Mua ngay</button>
                        </div>
                    ';
    }
    echo '
                ';
}
echo '            </div>
            <hr>
        </div>

    </div>
</section>
';
if ($discountPrice['flashSaleStatus'] == 'ongoing') {
    echo '    <script>
        const flashSaleStartTime = new Date(\'';
    echo date('Y-m-d\\TH:i:s', strtotime($discountPrice['start_time']));
    echo '\')
            .getTime();
        const flashSaleEndTime = new Date(\'';
    echo date('Y-m-d\\TH:i:s', strtotime($discountPrice['end_time']));
    echo '\').getTime();

        function updateCountdown() {
            const now = new Date().getTime();

            if (now < flashSaleStartTime) {
                document.getElementById(\'countdown-timer\').innerHTML = "Chưa bắt đầu";
                return;
            }

            const timeLeft = flashSaleEndTime - now;

            if (timeLeft <= 0) {
                showMessage(\'Chương trình Flash Sale đã kết thúc\', \'error\');
                clearInterval(countdownInterval);
                return;
            }

            const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

            document.getElementById(\'hours\').textContent = hours < 10 ? \'0\' + hours : hours;
            document.getElementById(\'minutes\').textContent = minutes < 10 ? \'0\' + minutes : minutes;
            document.getElementById(\'seconds\').textContent = seconds < 10 ? \'0\' + seconds : seconds;
        }
        const countdownInterval = setInterval(updateCountdown, 1000);
        updateCountdown();
    </script>
';
}
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
