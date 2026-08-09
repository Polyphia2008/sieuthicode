<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['id'])) {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row(' SELECT * FROM `spin_quests` WHERE `id` = \'' . $id . '\'  ');
    if (!$row) {
        new Redirect('/');
    }
    $unit = $db->site('unit') ?? null;
    $items = json_decode($row['prizes'], true);
    $prizes = [];
    foreach ($items as $__key => $item) {
        $index = $__key;
        $prizes[] = ['location' => $index + 1, 'ten' => 'Code ' . $item['value'] . ' ' . $unit, 'noidung' => 'Chúc mừng bạn đã quay trúng ' . $item['value'] . ' ' . $unit, 'percentpage' => 0];
    }
    $query_top_day = 'SELECT 
    user_id,
    username,
        COUNT(*) AS total_spins
    FROM spin_quest_logs
    GROUP BY user_id, username
    ORDER BY total_spins DESC
    LIMIT 10;
    ';
    $query_top_week = 'SELECT 
    user_id,
    username,
        COUNT(*) AS total_spins
    FROM spin_quest_logs
    WHERE created_at >= NOW() - INTERVAL 7 DAY
    GROUP BY user_id, username
    ORDER BY total_spins DESC
    LIMIT 10;
    ';
} else {
    new Redirect('/');
}
$title = $row['name'] . ' | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item "><a class="text-decoration-none" href="/minigame"><span>Minigame</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>';
echo $row['name'];
echo '</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen">
    <div class="center">
        <div id="form-minigame">
            <div class="right-minigame">
                <div class="content-wheel">
                    <div class="header-wheel">
                        <h3>';
echo $row['name'];
echo '</h3>
                        <h5>
                            <img src="/assets/images/security-user1.svg" alt="">
                            ';
echo rand(0, 999);
echo ' người đang chơi
                        </h5>
                    </div>

                    <div id="section-wheel">
                        <img src="/assets/images/bg-default.png" alt="" id="background-wheel">
                        <div class="history-luckywheel">
                            <img src="/assets/images/sound.svg" alt="">
                            <marquee class="rotation-marquee">
                                <div class="rotation-marquee-item">
                                    <h5>Danh sách trúng thưởng:</h5>
                                    ';
foreach ($db->get_list('SELECT * FROM `spin_quest_logs` ORDER BY `id` DESC LIMIT 20') as $log) {
    echo '                                        <div class="span-marquee">
                                            <span style="color: #28a745"><i class="menu-icon fas fa-user"></i>
                                                ';
    echo mb_substr($log['username'], 0, 5) . '*****';
    echo '</span>
                                            - ';
    echo $log['content'];
    echo '                                        </div>
                                    ';
}
echo '                                </div>
                            </marquee>
                        </div>
                        <section id="luckywheel" data-id="273" class="hc-luckywheel">
                            <div class="hc-luckywheel-container active">
                                <canvas class="hc-luckywheel-canvas" width="500px" height="500px">';
echo $row['name'];
echo '</canvas>
                                <img src="/assets/images/lazyload.gif" data-src="';
echo $row['image'];
echo '" alt="" style="transform: rotate(0deg);" class="img-vongquay lazyload" id="spin">
                            </div>
                            <button class="hc-luckywheel-quay" id="start">
                                <img src="/assets/images/lazyload.gif" data-src="';
echo DOMAIN . $row['play'];
echo '" class="lazyload" alt="quay">
                            </button>
                            <button class="hc-luckywheel-btn d-none"></button>
                        </section>
                    </div>
                    <div class="footer-wheel">
                        <div class="bonus50">
                            ';
if (0 < $row['sale']) {
    echo '                                <span class="gia">';
    echo format_cash($row['price']);
    echo 'đ</span>
                                <p class="giamoi">';
    echo format_cash($row['price'] - $row['price'] * $row['sale'] / 100);
    echo 'đ</p>
                                <img src="/assets/images/bonus50.png" alt="">
                                <span class="giakm">Giảm ';
    echo $row['sale'];
    echo '%</span>
                            ';
} else {
    echo '                                <p class="giamoi">';
    echo format_cash($row['price'] - $row['price'] * $row['sale'] / 100);
    echo 'đ</p>
                            ';
}
echo '                        </div>
                        <div class="button-choi">
                            <button class="default-button-sub quay-thu-wheel">Chơi thử</button>
                            <button class="default-button quay-ngay">Quay ngay</button>
                        </div>
                    </div>
                    <div class="modal hide no-scrollbar" id="filter-congratulations" tabindex="-1" role="dialog" aria-labelledby="filter-congratulations" aria-hidden="true">
                        <div class="modal-dialog popup-nguyennhieu" role="document">
                            <div class="content-popup-nguyennhieu w-400">
                                <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                                    <div class="modal-congratulations-wheel">
                                        <div id="img-congratulations-wheel"> <img src="/assets/images/success.png" class="m-auto" alt=""></div>
                                        <h3 id="text-cung">Danh sách giải thưởng: </h3>
                                        <div id="container-congratulations-wheel">
                                            <h3 id="congratulations-wheel"></h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-popup-confirm grid1">
                                    <a class="access-confirm-sieuthicode" data-dismiss="modal" aria-label="Close">Đóng</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal hide no-scrollbar" id="filter-comfirm-xacnhan" tabindex="-1" role="dialog" aria-labelledby="filter-comfirm-xacnhan" aria-hidden="true">
                        <div class="modal-dialog popup-nguyennhieu" role="document">
                            <div class="content-popup-nguyennhieu w-400">
                                <div class="header-popup-confirm">
                                    <div class="title-popup-nguyennhieu">
                                        <div class="icon-popup-nguyennhieu-2" style="--color: #344054">
                                            <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDExSDE3LjhDMTguOTIwMSAxMSAxOS40ODAyIDExIDE5LjkwOCAxMS4yMThDMjAuMjg0MyAxMS40MDk3IDIwLjU5MDMgMTEuNzE1NyAyMC43ODIgMTIuMDkyQzIxIDEyLjUxOTggMjEgMTMuMDc5OSAyMSAxNC4yVjIxTTEzIDIxVjYuMkMxMyA1LjA3OTkgMTMgNC41MTk4NCAxMi43ODIgNC4wOTIwMkMxMi41OTAzIDMuNzE1NjkgMTIuMjg0MyAzLjQwOTczIDExLjkwOCAzLjIxNzk5QzExLjQ4MDIgMyAxMC45MjAxIDMgOS44IDNINi4yQzUuMDc5OSAzIDQuNTE5ODQgMyA0LjA5MjAyIDMuMjE3OTlDMy43MTU2OSAzLjQwOTczIDMuNDA5NzMgMy43MTU2OSAzLjIxNzk5IDQuMDkyMDJDMyA0LjUxOTg0IDMgNS4wNzk5IDMgNi4yVjIxTTIyIDIxSDJNNi41IDdIOS41TTYuNSAxMUg5LjVNNi41IDE1SDkuNSIgc3Ryb2tlPSIjMzQ0MDU0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" />
                                        </div>
                                        <div class="content-title-popup-nguyennhieu">
                                            <h3>Xác nhận thanh toán</h3>
                                            <p>Kiểm tra kỹ trước khi xác nhận thanh toán.</p>
                                        </div>
                                    </div>
                                    <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal" aria-label="Close">
                                        &times;
                                    </button>
                                </div>
                                <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                                    <div id="xacnhan_thongtin">
                                        <div class="line-thongtin">
                                            <p>Danh mục</p>
                                            <p>';
echo $row['name'];
echo '</p>
                                        </div>
                                        <div class="line-thongtin">
                                            <p>Giá mỗi lượt</p>
                                            <p>';
echo format_cash($row['price'] - $row['price'] * $row['sale'] / 100);
echo 'đ</p>
                                        </div>

                                        <div class="line-thongtin">
                                            <p>Phí thanh toán</p>
                                            <p>Miễn phí</p>
                                        </div>
                                        <div class="line-thongtin">
                                            <p>Tổng thanh toán</p>
                                            <p class="money-display">';
echo format_cash($row['price'] - $row['price'] * $row['sale'] / 100);
echo 'đ</p>
                                        </div>
                                        <div class="line-chonnguontien">
                                            <p>Phương thức thanh toán:</p>
                                            <div class="phuongthucthanhtoan-radio">
                                                <div class="container-radio-payments">
                                                    <input class="radio-member-payments" type="radio" data-money="3000" data-giamoi="9000" name="radio-payments" id="radio-payments1" value="1" checked>
                                                    <label class="payments-label" for="radio-payments1">
                                                        <p>Số dư tài khoản</p>
                                                        <p class="display_money_member">';
echo $user ? format_cash($data_user['money']) : 0;
echo 'đ</p>
                                                    </label>
                                                </div>
                                                <div class="container-radio-payments">
                                                    <input class="radio-member-payments" type="radio" data-money="0" data-giamoi="9000" name="radio-payments" id="radio-payments2" value="2">
                                                    <label class="payments-label" for="radio-payments2">
                                                        <p>Tài khoản coin</p>
                                                        <p class="display_coin_member">';
echo $user ? format_cash($data_user['cost']) : 0;
echo 'đ</p>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="footer-popup-confirm grid1">
                                    <button id="access-wheel" class="access-confirm-sieuthicode confirm-dichvugame">Xác nhận</button>
                                    <!-- <button class="access-confirm-sieuthicode open-modals-naptien  naptien-dichvugame">Nạp tiền ngay</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var id = \'';
echo $row['id'];
echo '\';
                    var currentUrl = \'vong-quay-ca-chep\';
                    var prizes = JSON.parse(\'';
echo json_encode($prizes);
echo '\');
                    var isPercentage = true;
                    var check_user = "';
echo $user ? '1' : '';
echo '";
                </script>

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
            </div>
            <div class="left-minigame">
                <div class="sticky-minigame">
                    <div class="some-button">
                        <a href="/customer/history/minigame" class="default-button-sub">Lịch sử quay</a>
                        <a href="/customer/withdraw" class="default-button">Rút quà</a>
                    </div>
                    <hr>
                    <div class="top-quaythuong">
                        <h3>
                            <img src="/assets/images/top-leaderboard.svg" alt="">
                            Top quay thưởng
                        </h3>
                        <div class="content-quaythuong">
                            <div class="component-tabs">
                                <ul class="nav nav-nguyennhieu nav-topquaythuong" id="custom-tabs-three-tab-lang" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-today"
                                            role="tab" aria-controls="tabs-today" aria-selected="true">Hôm nay</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tabs-lang" data-toggle="pill" href="#tabs-7day" role="tab"
                                            aria-controls="tabs-7day" aria-selected="true">7 ngày</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tabs-lang" data-toggle="pill" href="#tabs-quaduatop" role="tab"
                                            aria-controls="tabs-quaduatop" aria-selected="true">Thể lệ</a>
                                    </li>
                                </ul>
                                <div class="tab-content mt-3" id="custom-tabs-three-tabContent-lang">
                                    <div class="tab-pane fade show active tab-topduathuong" id="tabs-today" role="tabpanel"
                                        aria-labelledby="tabs-lang">
                                        ';
foreach ($db->get_list($query_top_day) as $__key => $top_day) {
    $key = $__key;
    echo '                                            <div class="items-records">
                                                <span>';
    echo $key + 1;
    echo '</span>
                                                <img src="/assets/images/user_avatar.png" alt="';
    echo mb_substr($top_day['username'], 0, 5) . '*****';
    echo '">
                                                <div class="text-records">
                                                    <h5>';
    echo mb_substr($top_day['username'], 0, 5) . '*****';
    echo '</h5>
                                                    <p>';
    echo $top_day['total_spins'];
    echo ' lượt</p>
                                                </div>
                                            </div>
                                        ';
}
echo '                                    </div>
                                    <div class="tab-pane fade show tab-topduathuong" id="tabs-7day" role="tabpanel"
                                        aria-labelledby="tabs-lang">
                                        ';
foreach ($db->get_list($query_top_week) as $__key => $top_week) {
    $key = $__key;
    echo '                                            <div class="items-records">
                                                <span>';
    echo $key + 1;
    echo '</span>
                                                <img src="/assets/images/user_avatar.png" alt="';
    echo mb_substr($top_week['username'], 0, 5) . '*****';
    echo '">
                                                <div class="text-records">
                                                    <h5>';
    echo mb_substr($top_week['username'], 0, 5) . '*****';
    echo '</h5>
                                                    <p>';
    echo $top_week['total_spins'];
    echo ' lượt</p>
                                                </div>
                                            </div>
                                        ';
}
echo '                                    </div>
                                    <div class="tab-pane fade show tab-topduathuong" id="tabs-quaduatop" role="tabpanel"
                                        aria-labelledby="tabs-lang">
                                        ';
echo $row['descr'];
echo '                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="section-content-index" class="mt-4">
            <div class="title-index">
                <h3>Dịch vụ liên quan</h3>
                <a href="/minigame">Xem thêm <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
            </div>
            <div class="section-container-index">
                ';
foreach ($db->get_list('SELECT * FROM `spin_quests` WHERE `status` = 1 AND `id` NOT IN (' . $row['id'] . ') ORDER BY `stt` ASC') as $spin) {
    echo '                    <a href="/vongquay/';
    echo $spin['id'];
    echo '" class="items-content-index">
                        <div class="scale-img">
                            <img src="/assets/images/lazyload.gif" data-src="';
    echo DOMAIN . '/' . $spin['cover'];
    echo '" alt="';
    echo $spin['name'];
    echo '" class="lazyload">
                        </div>
                        <h3>';
    echo $spin['name'];
    echo '</h3>
                        <p class="p06">Đã chơi: ';
    echo $spin['played'];
    echo '</p>
                        <p class="ppr">';
    echo format_cash($spin['price'] - $spin['price'] * $spin['sale'] / 100);
    echo 'đ</p>
                        ';
    if (0 < $spin['sale']) {
        echo '                            <p class="ppo"><span>';
        echo format_cash($spin['price']);
        echo 'đ</span> <span>';
        echo $spin['sale'];
        echo '%</span></p>
                        ';
    }
    echo '                        <button>Chơi Ngay</button>
                    </a>
                ';
}
echo '            </div>
        </div>
    </div>
</section>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
