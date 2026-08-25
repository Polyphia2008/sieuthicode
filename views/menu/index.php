<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Danh mục | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen" style="max-width: 375px;">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="';
echo DOMAIN;
echo '"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="';
echo DOMAIN;
echo '/menu"><span>Danh mục</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen" style="max-width: 375px;">
    <div class="center">
        <form class="title-nickgame" method="GET">
            <h4>Danh mục</h4>
        </form>
    </div>
</section>
<section class="screen" style="max-width: 375px;">
    <div class="center p-2">
        <div class="menu-category">
            <ul class="row px-0 menu-category_fixm ">
                <li class="col-4 c-px-8 c-pt-8 c-pb-8">
                    <a href="/">
                        <div class="c-pt-10 c-pb-10 brs-8 menu-category-item justify-content-center">
                            <img src="/assets/images/home.png" alt="trang-chu">
                            <p class="fw-400 mb-0 text-primary-color">Trang chủ</p>
                        </div>
                    </a>
                </li>
                <li class="col-4 c-px-8 c-pt-8 c-pb-8">
                    <a href="/nick-game">
                        <div class="c-pt-10 c-pb-10 brs-8 menu-category-item justify-content-center">
                            <img src="/assets/images/nick.png" alt="mua-acc">
                            <p class="fw-400 mb-0 text-primary-color">Mua Acc</p>
                        </div>
                    </a>
                </li>
                <li class="col-4 c-px-8 c-pt-8 c-pb-8">
                    <a href="/customer/deposit">
                        <div class="c-pt-10 c-pb-10 brs-8 menu-category-item justify-content-center">
                            <img src="/assets/images/bank.png" alt="mua-acc">
                            <p class="fw-400 mb-0 text-primary-color">Nạp tiền</p>
                        </div>
                    </a>
                </li>
                <li class="col-4 c-px-8 c-pt-8 c-pb-8">
                    <a href="/kiem-tien-online">
                        <div class="c-pt-10 c-pb-10 brs-8 menu-category-item justify-content-center">
                            <i class="fas fa-coins" style="font-size:30px;color:#2767df;margin-bottom:6px"></i>
                            <p class="fw-400 mb-0 text-primary-color">Kiếm tiền</p>
                        </div>
                    </a>
                </li>
                <li class="col-4 c-px-8 c-pt-8 c-pb-8">
                    <a href="/reviews">
                        <div class="c-pt-10 c-pb-10 brs-8 menu-category-item justify-content-center">
                            <img src="/assets/images/tintuc.jpg" alt="tin-tuc">
                            <p class="fw-400 mb-0 text-primary-color">Đánh giá</p>
                        </div>
                    </a>
                </li>
                <li class="col-4 c-px-8 c-pt-8 c-pb-8">
                    <a href="/tin-tuc">
                        <div class="c-pt-10 c-pb-10 brs-8 menu-category-item justify-content-center">
                            <img src="/assets/images/tintuc.jpg" alt="tin-tuc">
                            <p class="fw-400 mb-0 text-primary-color">Tin tức</p>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
