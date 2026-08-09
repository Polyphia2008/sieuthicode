<?php
// statically decompiled from section.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['slug'])) {
    $slug = Anti_xss($_GET['slug']);
    $boosting = $db->get_row(' SELECT * FROM `boostings` WHERE `slug` = \'' . $slug . '\'  ');
    if (!$boosting) {
        new Redirect('/');
    }
} else {
    new Redirect('/');
}
$title = $boosting['name'] . ' | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="/section/service/';
echo $slug;
echo '"><span>Section</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen">
    <div class="center">
        <div class="title-nickgame">
            <h3>Danh sách mục Section</h3>
            <hr>
            <h4>Chọn dịch vụ bạn muốn mua</h4>
        </div>
        <div class="section-container-index">
            ';
foreach ($db->get_list('SELECT * FROM `subboostings` WHERE `category` = \'' . $boosting['id'] . '\' AND `status` = 1 ORDER BY `stt` ASC') as $suboosting) {
    $detailboosting = json_decode($suboosting['detail'], true);
    echo '                <a href="/dich-vu/';
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

                </a>
            ';
}
echo '        </div>
    </div>
</section>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
