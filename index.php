<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<!-- <section class="screen">
    <div class="center">
        <div id="container-slider" class="container-slider background-teamplate2">
            <div id="section-slider" class="owl-carousel owl-theme">
                ';
foreach ($db->get_list(' SELECT * FROM `banner` WHERE `status` = 1 ORDER BY `stt` ASC') as $banner) {
    echo '                    <div class="items-slider">
                        <a href="javascript:void(0)">
                            <img src="';
    echo $banner['image'];
    echo '" alt="">
                        </a>
                    </div>
                ';
}
echo '            </div>
        </div>
    </div>
</section> -->
<section class="screen">
    <div class="center">
        <div id="container-slider" class="container-slider background-teamplate2">
            <div id="container-topnap">
                <div class="component-tabs">
                    <ul class="nav nav-nguyennhieu owl-carousel owl-theme owl-list-topnapthe"
                        id="custom-tabs-three-tab-notification" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="tabs-notification" data-toggle="pill" href="#tabs-notification-4"
                                role="tab" aria-controls="tabs-notification-4" aria-selected="true">NẠP THẺ</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="tabs-notification" data-toggle="pill" href="#tabs-notification-0"
                                role="tab" aria-controls="tabs-notification-0" aria-selected="true">TOP NẠP T.';
echo date('m');
echo '</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link " id="tabs-notification" data-toggle="pill" href="#tabs-notification-1"
                                role="tab" aria-controls="tabs-notification-1" aria-selected="true">PHẦN THƯỞNG</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="custom-tabs-three-tabContent-notification">
                        <div class="tab-pane fade show active" id="tabs-notification-4" role="tabpanel" aria-labelledby="tabs-notification">
                            <div class="form-card">
                                <form class="validation-form" novalidate id="submit-napthe2" enctype="multipart/form-data">
                                    <input type="hidden" class="input-text" name="csrf_token" value="';
echo generate_csrf_token();
echo '" required>
                                    <div class="group-input input-selectize mb-3">
                                        <div class="input-element">
                                            <select name="telco" class="select-component" data-max="1" data-search="false"
                                                multiple="multiple" placeholder="Chọn nhà mạng..." required>
                                                <option value="VIETTEL">VIETTEL</option>
                                                <option value="VINAPHONE">VINAPHONE</option>
                                                <option value="MOBIFONE">MOBIFONE</option>
                                                <option value="ZING">ZING</option>
                                                <option value="GARENA">GARENA</option>
                                                <option value="VCOIN">VCOIN</option>
                                                <option value="GATE">GATE</option>
                                            </select>
                                        </div>

                                    </div>
                                    <div class="group-input input-selectize mb-3">
                                        <div class="input-element">
                                            <select name="amount" class="select-component" data-max="1" data-search="false"
                                                multiple="multiple" placeholder="Chọn mệnh giá..." required>
                                                <option value="10000">10.000đ</option>
                                                <option value="20000">20.000đ</option>
                                                <option value="30000">30.000đ</option>
                                                <option value="50000">50.000đ</option>
                                                <option value="100000">100.000đ</option>
                                                <option value="200000">200.000đ</option>
                                                <option value="300000">300.000đ</option>
                                                <option value="500000">500.000đ</option>
                                                <option value="1000000">1.000.000đ</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="group-input input-text mb-3">
                                        <div class="input-element">
                                            <input name="serial" type="text" placeholder="Nhập mã số se ri của bạn" required>
                                        </div>

                                    </div>
                                    <div class="group-input input-text mb-3">
                                        <div class="input-element">
                                            <input name="pin" type="text" placeholder="Nhập mã thẻ của bạn" required>
                                        </div>
                                    </div>
                                    <div class="group-input input-text mb-2">
                                        <div class="input-element d-flex align-items-center gap-2">
                                            <input name="captcha" type="text" placeholder="Nhập mã bảo vệ" required>
                                            <img src="/model/captcha" alt="Captcha" id="captcha-image" style="height: 25px; border: 1px solid #ccc; border-radius: 5px;">
                                            <i class="reload-icon" id="reload-captcha" style="cursor: pointer; font-size: 24px;">🔄</i>
                                        </div>
                                    </div>
                                    <button type="submit">Nạp Ngay</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade show" id="tabs-notification-0" role="tabpanel" aria-labelledby="tabs-notification">
                            <ul class="list-items-topnap">
                                ';
$i = 3;
$day = date('m-Y');
foreach ($db->get_list('SELECT SUM(amount) as total,username FROM `top` WHERE DATE_FORMAT(FROM_UNIXTIME(top.created_at), \'%m-%Y\') = \'' . $day . '\' GROUP BY `username` ORDER BY `total` DESC LIMIT 6') as $top) {
    echo '                                    <li class="item-topnap">
                                        <div>
                                            <span><img src="';
    echo rank_recharge($i);
    echo '" alt="img" class="ws-h-6"></span>
                                            <p>';
    echo obfuscateUsername($top['username']);
    echo '</p>
                                        </div>
                                        <p class="text-white">';
    echo format_cash($top['total']);
    echo 'đ</p>
                                    </li>
                                ';
    ++$i;
}
echo '                            </ul>
                        </div>
                        <div class="tab-pane fade show " id="tabs-notification-1" role="tabpanel" aria-labelledby="tabs-notification">
                            ';
echo $db->site('notice_topnap');
echo '                        </div>
                    </div>
                </div>
            </div>
            <div id="section-slider" class="owl-carousel owl-theme">
                ';
foreach ($db->get_list(' SELECT * FROM `banner` WHERE `status` = 1 ORDER BY `stt` ASC') as $banner) {
    echo '                    <div class="items-slider">
                        <a href="javascript:void(0)">
                            <img src="';
    echo $banner['image'];
    echo '" alt="" class="banner-slider">
                        </a>
                    </div>
                ';
}
echo '
            </div>
        </div>
    </div>
</section>
';
$homeNoticeHtml = trim((string) $db->site('notice_home'));
if ($homeNoticeHtml !== '') {
    echo '<section class="screen home-notification-wrap"><div class="center"><div class="notification-section"><div class="notification-section-head"><i class="fas fa-bullhorn"></i><strong>Thông báo quan trọng</strong></div><div class="p-3 home-notification-content">' . $homeNoticeHtml . '</div></div></div></section>';
}
echo '
<section class="screen">
    <div class="center">
        <div id="section-linklienket" class="owl-carousel owl-theme background-teamplate2">
            ';
foreach ($db->get_list('SELECT * FROM `links` WHERE `status` = 1 ORDER BY `stt` ASC') as $link) {
    echo '                <a href="';
    echo $link['link'];
    echo '">
                    <img src="';
    echo $link['image'];
    echo '" alt="';
    echo $link['title'];
    echo '" style="height: 40px;width: 40px;">
                    <h5>';
    echo $link['title'];
    echo '</h5>
                </a>
            ';
}
echo '        </div>
    </div>
</section>
<section class="screen">
    <div class="center">
        <div class="rotation-notify w-100 mb-3 bg-white c-mt-12">
            <div class="w-100">
                <img src="/assets/svg/sound_mobile.svg" class="sound_mobile" alt="">
                <marquee class="rotation-marquee">
                    <div class="rotation-marquee-item">
                        <p>
                            ';
foreach ($db->get_list('SELECT * FROM `history_buy` ORDER BY `id` DESC') as $log) {
    echo '                                <span><img alt="user icon" class="c-mr-5 icon" height="20" src="/assets/images/user.png" width="20"> <span class="username">';
    echo obfuscateUsername($log['username']);
    echo '</span> đã mua acc #';
    echo $log['id_acc'];
    echo ' chỉ với giá ';
    echo format_cash($log['cash']);
    echo 'đ cách đây ';
    echo timeAgo($log['created_at']);
    echo ' </span>
                            ';
}
echo '                        </p>
                    </div>
                </marquee>
            </div>
        </div>
    </div>
</section>
<section class="screen">
    <div class="center">
        <div id="section-flashsale">

        </div>
    </div>
</section>
';
foreach ($db->get_list('SELECT * FROM `boostings` WHERE `status` = 1 ORDER BY `stt` ASC') as $boosting) {
    echo '    <section class="screen">
        <div class="center">
            <div id="section-content-index">
                <div class="title-index">
                    <h2><img src="/assets/images/acc-game.png" alt="">';
    echo $boosting['name'];
    echo '</h2>
                    <a href="/section/service/';
    echo $boosting['slug'];
    echo '">Xem thêm <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                </div>
                <div class="section-container-index">
                    ';
    foreach ($db->get_list('SELECT * FROM `subboostings` WHERE `category` = \'' . $boosting['id'] . '\' AND `status` = 1 ORDER BY `stt` ASC') as $suboosting) {
        $detailboosting = json_decode($suboosting['detail'], true);
        echo '                        <a href="/dich-vu/';
        echo $suboosting['type_category'];
        echo '" class="items-content-index">
                            <div class="scale-img">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo DOMAIN . '/' . $detailboosting['thumb'];
        echo '" class="lazyload" alt="';
        echo $detailboosting['name_product'];
        echo '">
                            </div>
                            <h3>';
        echo $detailboosting['name_product'];
        echo '</h3>

                            <p class="p06">Đã giao dịch: ';
        echo format_cash($suboosting['fake'] + ($db->num_rows('SELECT * FROM `orders` WHERE `sub_id`=\'' . $suboosting['id'] . '\'') ?? 0));
        echo '</p>

                            <button>Xem Ngay</button>
                        </a>
                    ';
    }
    echo '
                </div>
                <hr>
            </div>
        </div>
    </section>
';
}
if ($db->site('status_minigame') == 1) {
    echo '    <section class="screen">
        <div class="center">
            <div id="section-content-index">
                <div class="title-index">
                    <h2><img src="/assets/images/icon-vgekrbiahs.gif" alt="">Game Mini Trúng Lớn</h2>
                    <a href="/minigame">Xem thêm <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                </div>
                <div class="section-container-index">
                    ';
    foreach ($db->get_list('SELECT * FROM `spin_quests` WHERE `status` = 1 ORDER BY `stt` ASC') as $spin) {
        echo '                        <a href="/vongquay/';
        echo $spin['id'];
        echo '" class="items-content-index">
                            <div class="scale-img">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo DOMAIN . '/' . $spin['cover'];
        echo '" class="lazyload" alt="';
        echo $spin['name'];
        echo '">
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
            echo '                                <p class="ppo"><span>';
            echo format_cash($spin['price']);
            echo 'đ</span> <span>';
            echo $spin['sale'];
            echo '%</span></p>
                            ';
        }
        echo '                            <button>Chơi Ngay</button>
                        </a>
                    ';
    }
    echo '                </div>
                <hr>
            </div>
        </div>
    </section>
';
}
foreach ($db->get_list('SELECT * FROM `categories` WHERE `status` = 1 ORDER BY `stt` ASC') as $category) {
    echo '    <section class="screen">
        <div class="center">
            <div id="section-content-index">
                <div class="title-index">
                    <h2><img src="';
    echo $category['icon'];
    echo '" alt="';
    echo $category['name'];
    echo '"> ';
    echo $category['name'];
    echo '</h2>
                    <a href="/section/';
    echo $category['slug'];
    echo '">Xem thêm <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                </div>
                <div class="section-container-index">
                    ';
    foreach ($db->get_list('SELECT * FROM `subcategory` WHERE `category` = \'' . $category['id'] . '\' AND `status` = 1 ORDER BY `stt` ASC') as $sub) {
        $detail = json_decode($sub['detail'], true);
        $count_account_groups = $db->get_row('SELECT COUNT(id) FROM `accounts` WHERE `sub_id` = \'' . $sub['id'] . '\' AND `status`=\'on\'')['COUNT(id)'] ?? 0;
        echo '                        <a href="/tai-khoan/';
        echo $sub['type_category'];
        echo '" class="items-content-index">
                            <div class="scale-img">
                                <img src="/assets/images/lazyload.gif" data-src="';
        echo DOMAIN . '/' . $detail['thumb'];
        echo '" class="lazyload" alt="';
        echo $detail['name_product'];
        echo '">';
        echo subcategory_tag_html($detail);
        echo '
                            </div>
                            <h3>';
        echo $detail['name_product'];
        echo '</h3>
                            ';
        if ($sub['type'] == 'RANDOM') {
            echo '                                <p class="p06">Số tài khoản: ';
            echo $count_account_groups;
            echo '</p>

                                <p class="ppr">';
            echo format_cash($detail['cash']);
            echo 'đ</p>
                            ';
        } else {
            echo '                                <p class="p06">Số tài khoản: ';
            echo $count_account_groups;
            echo '</p>
                            ';
        }
        echo '                            <button>Mua Ngay</button>
                        </a>
                    ';
    }
    echo '
                </div>
                <hr>
            </div>
        </div>
    </section>
';
}
echo '<section class="screen">
    <div class="center">
        <section class="section-related-service mt-3">
            <div class="title-index">
                <h2><img src="/assets/images/speaker.png" alt="">IDOL PR ĐẢM BẢO UY TÍN</h2>
            </div>
            <div class="infinite-scroll-container mt-4">
                <div class="infinite-scroll-wrapper">
                    ';
foreach ($db->get_list('SELECT * FROM `advertisement` WHERE `status` = 1 ORDER BY `stt` ASC') as $adv) {
    echo '                        <div class="infinite-scroll-slide">
                            <a href="';
    echo $adv['link'];
    echo '" target="_blank">
                                <img src="/assets/images/lazyload.gif" data-src="';
    echo $adv['image'];
    echo '" class="lazyload" alt="IDOL PR ĐẢM BẢO UY TÍN" width="200" height="120">
                            </a>
                        </div>
                    ';
}
echo '                    ';
foreach ($db->get_list('SELECT * FROM `advertisement` WHERE `status` = 1 ORDER BY `stt` ASC') as $adv) {
    echo '                        <div class="infinite-scroll-slide">
                            <a href="';
    echo $adv['link'];
    echo '" target="_blank">
                                <img src="/assets/images/lazyload.gif" data-src="';
    echo $adv['image'];
    echo '" class="lazyload" alt="IDOL PR ĐẢM BẢO UY TÍN" width="200" height="120">
                            </a>
                        </div>
                    ';
}
echo '                </div>
            </div>
        </section>
    </div>
</section>
<section class="screen">
    <div class="center">
        <div id="section-content-index">
            <section class="news mt-3">
                <div class="title-index">
                    <h2><img src="/assets/images/speaker.png" alt="">Tin tức</h2>
                    <a href="/tin-tuc">Xem thêm <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                </div>

                <div class="swiper js-swiper-news card-list d-none d-lg-block mt-4">
                    <div class="swiper-wrapper">
                        ';
foreach ($db->get_list('SELECT * FROM `posts` WHERE `status` = 1 ORDER BY `stt` ASC') as $blog) {
    echo '                            <div class="swiper-slide">
                                <div class="card card-service">
                                    <a href="/tin-tuc/';
    echo $blog['slug'];
    echo '" class="news-link scale-thumb">
                                        <div class="card-body">
                                            <div class="news-thumb">
                                                <img src="/assets/images/lazyload.gif" data-src="';
    echo $blog['image'];
    echo '" alt="img-news" width="200" height="120" class="news-thumb-image lazyload">
                                            </div>
                                            <div>
                                                <div class="news-title c-mt-12 c-mb-4 text-limit limit-2 c_max-header-tin-tuc">';
    echo $blog['title'];
    echo '</div>
                                                <div class="datetime">
                                                    ';
    echo $blog['created_at'];
    echo '                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        ';
}
echo '                    </div>
                    <div class="navigation slider-next"></div>
                    <div class="navigation slider-prev"></div>
                </div>

                <ul class="news-list d-block d-lg-none">
                    ';
foreach ($db->get_list('SELECT * FROM `posts` WHERE `status` = 1 ORDER BY `stt` ASC LIMIT 4') as $blog) {
    echo '                        <li class="news-item">
                            <a href="/tin-tuc/';
    echo $blog['slug'];
    echo '" class="news-link">
                                <div class="news-article">
                                    <div class="article-thumb-wrap">
                                        <div class="news-thumb">
                                            <img src="/assets/images/lazyload.gif" data-src="';
    echo $blog['image'];
    echo '" alt="img-news" class="news-thumb-image lazyload">
                                        </div>
                                    </div>
                                    <div class="news-info">
                                        <div class="news-title text-limit limit-3 fz-lg-13 lh-lg-20 c-mb-4">';
    echo $blog['title'];
    echo '                                        </div>
                                        <div class="datetime">';
    echo $blog['created_at'];
    echo '</div>
                                    </div>
                                </div>
                            </a>
                        </li>
                    ';
}
echo '                    <a href="/tin-tuc" class="c-mt-16 fz-14 text-center d-block">Xem tất cả</a>
                </ul>

            </section>
        </div>
    </div>
</section>
<section class="screen">
    <div class="center">
        <div class="background-teamplate2 mt-4">
            <div id="section-gioithieu">
                <div class="noidung-gioithieu content-desc hide">
                    ';
echo $db->site('notice_home');
echo '                </div>
                <a href="/introducing">Xem thêm nội dung <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
            </div>
        </div>
    </div>
</section>
<section class="screen">
    <div class="center">
        <div class="background-teamplate2 mt-4">
            <div id="section-camket" class="owl-carousel owl-theme">
                <a href="javascript:void(0)">
                    <img src="/assets/images/intro4-6322.svg" alt=" Giá cả ưu đãi, siêu rẻ trên thị trường">
                    <h5> Giá cả ưu đãi, siêu rẻ trên thị trường</h5>
                    <h6>Cung cấp những sản phẩm với giá cả tốt nhất cùng với đó là những ưu đãi vô cùng hấp dẫn.</h6>
                </a>
                <a href="javascript:void(0)">
                    <img src="/assets/images/intro3-6468.svg" alt="Trung tâm trợ giúp hỗ trợ tư vấn 24/24">
                    <h5>Trung tâm trợ giúp hỗ trợ tư vấn 24/24</h5>
                    <h6>Đội ngũ chăm sóc khách hàng luôn tư vấn và hỗ trợ nhiệt tình khi gặp sự cố trong quá<br />
                        trình trải nghiệm sản phẩm.</h6>
                </a>
                <a href="javascript:void(0)">
                    <img src="/assets/images/intro2-7479.svg" alt=" Hàng nghìn khách hàng tin tưởng">
                    <h5> Hàng nghìn khách hàng tin tưởng</h5>
                    <h6>Hơn 260.000 giao dịch thành công mỗi ngày. Chúng tôi luôn đặt uy tín, chất lượng dịch<br />
                        vụ lên hàng đầu.</h6>
                </a>
                <a href="javascript:void(0)">
                    <img src="/assets/images/intro1-4143.svg" alt="Sản phẩm, dịch vụ đa dạng, cập nhật thường xuyên">
                    <h5>Sản phẩm, dịch vụ đa dạng, cập nhật thường xuyên</h5>
                    <h6>Hệ thống luôn cung cấp, cập nhật những sản phẩm mới/hot nhất của các dòng game trên thị trường.</h6>
                </a>
            </div>
        </div>
    </div>
</section>


<div class="modal hide no-scrollbar" id="notification-categories" tabindex="-1" role="dialog" aria-labelledby="notification-categories" aria-hidden="true">
    <div class="modal-dialog popup-nguyennhieu" role="document">
        <div class="content-popup-nguyennhieu w-500">
            <div class="main-popup-notification npmg container-base-up3s">
                ';
echo $db->site('popup_home');
echo '            </div>
            <div class="footer-popup-confirm">
                <button class="cancel-confirm-nguyennhieu" data-dismiss="modal">Đóng</button>
                <button class="access-confirm-sieuthicode" id="close-hours">Đóng trong 1h</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        var modal = document.getElementById(\'notification-categories\');
        var dontShowAgainBtn = document.getElementById(\'close-hours\');
        var modalInstance = new bootstrap.Modal(modal);

        var modalClosedTime = localStorage.getItem(\'modalClosedTime\');
        if (!modalClosedTime || (Date.now() - parseInt(modalClosedTime) > 60 * 60 * 1000)) {
            modalInstance.show();
        }

        dontShowAgainBtn.addEventListener(\'click\', function() {
            localStorage.setItem(\'modalClosedTime\', Date.now());
            modalInstance.hide();
        });
    });
</script>
<script>
    document.getElementById(\'reload-captcha\').addEventListener(\'click\', function() {
        document.getElementById(\'captcha-image\').src = \'/model/captcha?\' + Date.now();
    });
</script>
';
if ($db->site('status_event') == 1) {
    echo '    <div class="content-gif-left">
        <div class="close-gif-left">
            <img src="/assets/images/closer-red.png" alt="">
        </div>
        <a href="/event"><img src="/assets/images/lazyload.gif" data-src="';
    echo $db->site('img_event');
    echo '" class="lazyload" alt=""></a>
    </div>
';
}
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
