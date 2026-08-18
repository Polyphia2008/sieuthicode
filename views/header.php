<?php
// statically decompiled from header.php  [structured; all 1 record(s) structured]

$rateLimitWindow = 10;
$maxRequests = 60;
$userIP = myip();
$currentTime = time();

if (!isset($_SESSION['rate_limit'][$userIP])
    || $currentTime - (int) $_SESSION['rate_limit'][$userIP]['start_time'] >= $rateLimitWindow) {
    $_SESSION['rate_limit'][$userIP] = ['start_time' => $currentTime, 'request_count' => 1];
} else {
    ++$_SESSION['rate_limit'][$userIP]['request_count'];
}

if ($_SESSION['rate_limit'][$userIP]['request_count'] > $maxRequests) {
    http_response_code(429);
    exit('Bạn đã gửi quá nhiều yêu cầu. Vui lòng thử lại sau.');
}

$cachedCssVersion = (string) (@filemtime(APP_ROOT . '/assets/css/cached.css') ?: 1);
$stylesCssVersion = (string) (@filemtime(APP_ROOT . '/assets/css/styles.css') ?: 1);

echo '<!DOCTYPE html>
<html lang="vi">

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>';
    echo $title;
    echo '</title>
    <meta name="title" content="';
    echo $db->site('title');
    echo '">
    <meta name="keywords" content="';
    echo $db->site('keywords');
    echo '">
    <meta name="description" content="';
    echo $db->site('description');
    echo '">

    <meta property="og:type" content="website">
    <meta property="og:url" content="';
    echo DOMAIN;
    echo '">
    <meta property="og:title" content="';
    echo $db->site('title');
    echo '">
    <meta property="og:description" content="';
    echo $db->site('description');
    echo '">
    <meta property="og:image" content="';
    echo DOMAIN . $db->site('anhbia');
    echo '">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="';
    echo DOMAIN;
    echo '">
    <meta property="twitter:title" content="';
    echo $db->site('title');
    echo '">
    <meta property="twitter:description" content="';
    echo $db->site('description');
    echo '">
    <meta property="twitter:image" content="';
    echo DOMAIN . $db->site('anhbia');
    echo '">

    <meta name="author" content="';
    echo $db->site('author');
    echo '">
    <link rel="icon" href="';
    echo DOMAIN . $db->site('favicon');
    echo '" type="image/x-icon" />

    <!-- Css Files -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Signika:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Play:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <!-- Background -->
    <style type="text/css">
        * {
            font-family: \'Signika\', sans-serif;
            letter-spacing: 0.5px;
        }

        body {
            background-color: #f5f5f5 !important
        }

        @media (min-width:1024px) {
            .banner-slider {
                height: 27rem !important;
            }
        }

        #container-topnap {
            box-shadow: 0 1px 3px rgba(214, 221, 237, .5), 0 1px 2px rgba(214, 221, 237, .5);
        }
    </style><!-- Color default -->
    <style type="text/css">
        :root {
            --color-default: 244, 115, 185;
            --primary-color: ';
    echo $db->site('theme_color');
    echo ';
        }
    </style><!-- Color default sub -->
    <style type="text/css">
        :root {
            --color-default-sub: ';
    echo $db->site('theme_color1');
    echo ';
        }
    </style><!-- Color text -->
    <style type="text/css">
        :root {
            --color-text: 28, 28, 28;
        }
    </style><!-- Color text default -->
    <style type="text/css">
        :root {
            --color-text-default: 255, 255, 255;
        }
    </style>
    <link href="/assets/css/cached.css?v=';
    echo $cachedCssVersion;
    echo '" rel="stylesheet">
    <link href="/assets/css/styles.css?v=';
    echo $stylesCssVersion;
    echo '" rel="stylesheet">
    <script src="/assets/js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/lazysizes/5.3.2/lazysizes.min.js" async></script>
    <script>
        var csrf_token = "';
    echo generate_csrf_token();
    echo '"
    </script>
</head>

<body id="body" class="index">
    <ul class="h-card hidden">
        <li class="h-fn fn">';
    echo $db->site('title');
    echo '</li>
        <li class="h-org org">';
    echo $db->site('title');
    echo '</li>
        <li class="h-tel tel">';
    echo $db->site('hotline');
    echo '</li>
        <li><a class="u-url ul" href="';
    echo DOMAIN;
    echo '">';
    echo DOMAIN;
    echo '</a></li>
    </ul>
    <h1 class="hidden-seoh">';
    echo $db->site('title');
    echo '</h1>
    <div class="mm-page ">
        <section id="menu" class=" background-teamplate2">
            <div class="screen">
                <div class="center container-menu1">
                    <div class="logo-menu">
                        <a href="/">
                            <img width="140" height="42" src="';
    echo $db->site('logo');
    echo '" alt="';
    echo $db->site('title');
    echo '" />
                        </a>
                    </div>
                    <div class="categories-menu1">
                        <a href="/menu" id="hover-menudm" class="box-menu">
                            <span>
                                <img src="/assets/images/menu.svg" alt="">
                            </span>
                            <p>Danh mục</p>
                        </a>
                        <a href="/viewed">
                            <span>
                                <img src="/assets/images/viewed.png" alt="">
                            </span>
                            <p>Đã xem</p>
                        </a>
                    </div>
                    <div class="search-menu1">
                        <div class="group-input input-search">
                            <div class="group-search">
                                <div class="input-element custom-search-menu1">
                                    <input type="text" id="input-search" placeholder="Tìm kiếm" required>
                                    <custom-seach>
                                        <icon class="icon-search search"></icon>
                                    </custom-seach>
                                </div>
                                <div class="search-result d-none">
                                    <ul>
                                        <li>
                                            <a href="">Search result total <b>search</b></a>
                                        </li>
                                        <li>
                                            <a href="">Search result total <b>search</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="button-menu1">
                        <a href="/customer/deposit" class="btn-naptien1">Nạp Tiền</a>
                        <a class="notification-menu1 chat-header-link" href="/chat-box" style="position: relative;" title="Chat hỗ trợ">
                            <span class="span-menu">
                                <i class="far fa-comment-dots" style="font-size: 20px; margin-top: 10px;"></i>
                                <span class="chat-badge-unread" data-chat-badge style="display:none">0</span>
                            </span>
                        </a>
                        <div class="notification-menu1">
                            <span class="span-menu open-notification1">
                                <img src="/assets/images/ring.svg" alt="">
                            </span>
                            <div class="lists-notification1">
                                <div class="header-notification1">
                                    <h3>Thông báo</h3>
                                    <span><span class="close-notification1" aria-hidden="true">&times;</span></span>
                                </div>
                                <div class="content-notification1">
                                    <div class="component-tabs">
                                        <ul class="nav nav-nguyennhieu nav-menu-layout1 owl-carousel owl-theme owl-list-notification" id="custom-tabs-three-tab-notification" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="tabs-notification" data-toggle="pill" href="#tabs-notification-69"
                                                    role="tab" aria-controls="tabs-notification-69" aria-selected="true">Hệ thống/ Event</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link " id="tabs-notification" data-toggle="pill" href="#tabs-notification-70"
                                                    role="tab" aria-controls="tabs-notification-70" aria-selected="true">Giao dịch</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content content-tabs-menu1" id="custom-tabs-three-tabContent-notification">
                                            <div class="tab-pane fade show active" id="tabs-notification-69" role="tabpanel" aria-labelledby="tabs-notification">
                                                <ul class="list-items-notify-menu1">
                                                    ';
    foreach (get_user_notifications($user ? (int) $data_user['id'] : 0, ['system', 'event']) as $noti) {
        echo notification_item_html($noti);
    }
    foreach ($db->get_list('SELECT * FROM `posts` WHERE `status`=1 AND `noti`=1 AND `id`<>24 ORDER BY `id` DESC LIMIT 20') as $postNoti) {
        echo notification_item_html([
            'title' => $postNoti['title'], 'message' => '', 'link' => $postNoti['link'] ?: '/',
            'created_at' => $postNoti['created_at'],
        ]);
    }
    echo '                                                </ul>
                                            </div>
                                            <div class="tab-pane fade show active" id="tabs-notification-70" role="tabpanel" aria-labelledby="tabs-notification">
                                                <ul class="list-items-notify-menu1">
                                                    ';
    if ($user) {
        foreach (get_user_notifications((int) $data_user['id'], ['transaction']) as $noti) {
            echo notification_item_html($noti);
        }
    }
    echo '                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
    if ($user) {
        echo '                            <div class="user-button-active no-select">
                                <div class="info-profile open-profile">
                                    <h3>';
        echo $data_user['provider'] == 'google' ? $data_user['name'] : $data_user['username'];
        echo '</h3>
                                    <p>Số dư: <span class="display_money_member">';
        echo format_cash($data_user['money']);
        echo 'đ</span></p>
                                </div>
                                <img class="open-profile" src="/assets/images/anhdaidien.svg" alt="';
        echo $data_user['provider'] == 'google' ? $data_user['name'] : $data_user['username'];
        echo '">
                                <div class="lists-notification1">
                                    <div class="header-notification1">
                                        <h3>Tài khoản</h3>
                                        <span><span class="close-profile" aria-hidden="true">×</span></span>
                                    </div>
                                    <div class="content-notification1">
                                        <div class="content-info-login">
                                            <div class="sidebar-user-top">
                                                <img src="/assets/images/anhdaidien.svg" alt="';
        echo $data_user['provider'] == 'google' ? $data_user['name'] : $data_user['username'];
        echo '">
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
            echo '                                                    <a class="item-link" target="_blank" href="/cpanel/home">
                                                        <span><img src="/assets/images/control-system.png" width="25px" alt=""></span>
                                                        <h3>Thông quản trị</h3>
                                                        <img src="/assets/images/sidebar_arrow_right.svg" alt="">
                                                    </a>
                                                ';
        }
        echo '                                                ';
        if ($data_user['ctv'] == 1) {
            echo '                                                    <a class="item-link" target="_blank" href="/ctv/home">
                                                        <span><img src="/assets/images/control-system.png" width="25px" alt=""></span>
                                                        <h3>Trang cộng tác viên</h3>
                                                        <img src="/assets/images/sidebar_arrow_right.svg" alt="">
                                                    </a>
                                                ';
        }
        echo '                                                <a class="item-link" href="/customer/profile">
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
                                                    <h3>Dịch vụ đã thuê</h3>
                                                    <img src="/assets/images/sidebar_arrow_right.svg" alt="">
                                                </a>
                                                <a class="item-link " href="/customer/history/card">
                                                    <span><img src="/assets/images/lichsunapthe.svg" alt=""></span>
                                                    <h3>Lịch sử nạp thẻ</h3>
                                                    <img src="/assets/images/sidebar_arrow_right.svg" alt="">
                                                </a>
                                                <a class="item-link " href="/customer/history/bank">
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
                                </div>
                            </div>
                        ';
    } else {
        echo '                            <a class="user-button" href="/login">
                                <span class="span-menu">
                                    <img src="/assets/images/profile.svg" alt="">
                                </span>
                            </a>
                        ';
    }
    echo '
                    </div>
                </div>

                <div class="menu-list">
                    <div class="menu-category ">
                        <div class="container-menu-category">
                            <ul class="d-flex justify-content-between px-0">
                                <li class="w-100 c-px-4">
                                    <a href="/">
                                        <div class="c-p-8 brs-8 d-flex justify-content-center" style="white-space: nowrap;">
                                            <div>
                                                <img src="/assets/images/home.png" alt="Trang chủ" class="c-pr-4">
                                            </div>
                                            <span class="fw-500 fz-15 lh-24 ">Trang chủ</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="w-100 c-px-4">
                                    <a href="/customer/deposit">
                                        <div class="c-p-8 brs-8 d-flex justify-content-center" style="white-space: nowrap;">
                                            <div>
                                                <img src="/assets/images/bank.png" alt="Nạp thẻ" class="c-pr-4">
                                            </div>

                                            <span class="fw-500 fz-15 lh-24 ">Nạp thẻ</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="w-100 c-px-4">
                                    <a href="/customer/deposit">
                                        <div class="c-p-8 brs-8 d-flex justify-content-center" style="white-space: nowrap;">
                                            <div>
                                                <img src="/assets/images/bank.png" alt="Nạp ATM" class="c-pr-4">
                                            </div>

                                            <span class="fw-500 fz-15 lh-24 ">Nạp ATM</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="w-100 c-px-4">
                                    <a href="/nick-game">
                                        <div class="c-p-8 brs-8 d-flex justify-content-center" style="white-space: nowrap;">
                                            <div>
                                                <img src="/assets/images/nick.png" alt="Mua Acc" class="c-pr-4">
                                            </div>
                                            <span class="fw-500 fz-15 lh-24 ">Mua Acc</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="w-100 c-px-4">
                                    <a href="/reviews">
                                        <div class="c-p-8 brs-8 d-flex justify-content-center" style="white-space: nowrap;">
                                            <div>
                                                <img src="/assets/images/tintuc.jpg" alt="Đánh giá" class="c-pr-4">
                                            </div>
                                            <span class="fw-500 fz-15 lh-24 ">Đánh giá</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="w-100 c-px-4">
                                    <a href="/tin-tuc">
                                        <div class="c-p-8 brs-8 d-flex justify-content-center" style="white-space: nowrap;">
                                            <div>
                                                <img src="/assets/images/tintuc.jpg" alt="Tin tức" class="c-pr-4">
                                            </div>
                                            <span class="fw-500 fz-15 lh-24 ">Tin tức</span>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <div id="menu-mobile" class="">
            <div class="screen">
                <div class="center">
                    <div class="container-mmenu">
                        <div class="logo-mmenu">
                            <a href="/">
                                <img width="140" height="40" src="';
    echo $db->site('logo');
    echo '" alt="';
    echo $db->site('title');
    echo '" />
                            </a>
                        </div>
                        <div class="mmenu-end mmenu-bar">
                            <div class="button-menu1">
                                <div class="group-input input-search search-mobile-icon">
                                    <div class="group-search">
                                        <div class="input-element custom-search-menu2">
                                            <custom-seach>
                                                <icon class="icon-search search"></icon>
                                            </custom-seach>
                                        </div>
                                    </div>
                                </div>
                                <a class="notification-menu1" href="/viewed">
                                    <span class="span-menu">
                                        <img src="/assets/images/viewermobile.png" alt="">
                                    </span>
                                </a>
                                <a class="notification-menu1 chat-header-link" href="/chat-box" style="position: relative;" title="Chat hỗ trợ">
                                    <span class="span-menu">
                                        <i class="far fa-comment-dots" style="font-size: 20px; margin-top: 10px;"></i>
                                        <span class="chat-badge-unread" data-chat-badge style="display:none">0</span>
                                    </span>
                                </a>
                                <div class="notification-menu1">
                                    <span class="span-menu open-notification1">
                                        <img src="/assets/images/ring.svg" alt="">
                                    </span>
                                    <div class="lists-notification1">
                                        <div class="header-notification1">
                                            <h3>Thông báo</h3>
                                            <span><span class="close-notification1" aria-hidden="true">&times;</span></span>
                                        </div>
                                        <div class="content-notification1">
                                            <div class="component-tabs">
                                                <ul class="nav nav-nguyennhieu nav-menu-layout1 owl-carousel owl-theme owl-list-notification"
                                                    id="custom-tabs-three-tab-notification" role="tablist">
                                                    <li class="nav-item">
                                                        <a class="nav-link active"
                                                            id="tabs-notification" data-toggle="pill"
                                                            href="#tabs-notification-69" role="tab"
                                                            aria-controls="tabs-notification-69"
                                                            aria-selected="true">Hệ thống/ Event</a>
                                                    </li>
                                                    <li class="nav-item">
                                                        <a class="nav-link "
                                                            id="tabs-notification" data-toggle="pill"
                                                            href="#tabs-notification-70" role="tab"
                                                            aria-controls="tabs-notification-70"
                                                            aria-selected="true">Giao dịch</a>
                                                    </li>
                                                </ul>
                                                <div class="tab-content content-tabs-menu1"
                                                    id="custom-tabs-three-tabContent-notification">
                                                    <div class="tab-pane fade show active"
                                                        id="tabs-notification-69" role="tabpanel"
                                                        aria-labelledby="tabs-notification">
                                                        <ul class="list-items-notify-menu1">
                                                        ';
    foreach (get_user_notifications($user ? (int) $data_user['id'] : 0, ['system', 'event']) as $noti) {
        echo notification_item_html($noti);
    }
    foreach ($db->get_list('SELECT * FROM `posts` WHERE `status`=1 AND `noti`=1 AND `id`<>24 ORDER BY `id` DESC LIMIT 20') as $postNoti) {
        echo notification_item_html([
            'title' => $postNoti['title'], 'message' => '', 'link' => $postNoti['link'] ?: '/',
            'created_at' => $postNoti['created_at'],
        ]);
    }
    echo '                                                        </ul>
                                                    </div>
                                                    <div class="tab-pane fade show active"
                                                        id="tabs-notification-70" role="tabpanel"
                                                        aria-labelledby="tabs-notification">
                                                        <ul class="list-items-notify-menu1">
                                                            ';
    if ($user) {
        foreach (get_user_notifications((int) $data_user['id'], ['transaction']) as $noti) {
            echo notification_item_html($noti);
        }
    }
    echo '                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="notification-menu1">
                                    <a href="/customer/deposit" class="btn-naptien1">Nạp Tiền</a>
                                </div> -->
                                ';
    if (!$user) {
        echo '                                    <a class="user-button" href="/login">
                                        <span class="span-menu">
                                            <img src="/assets/images/profile.svg" alt="">
                                        </span>
                                    </a>
                                ';
    }
    echo '
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="search-mobile-hidden">
            <div class="screen">
                <div class="center">
                    <div class="search-bar-hidden">
                        <div class="group-input input-search">
                            <div class="group-search">
                                <div class="input-element custom-search-menu1">
                                    <input type="text" id="input-search1" placeholder="Tìm kiếm" required>
                                    <custom-seach>
                                        <icon class="icon-search search"></icon>
                                    </custom-seach>
                                </div>
                                <div class="search-result d-none">
                                    <ul>
                                        <li>
                                            <a href="">Search result total <b>search</b></a>
                                        </li>
                                        <li>
                                            <a href="">Search result total <b>search</b></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>';
