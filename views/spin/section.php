<?php
// statically decompiled from section.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Minigame | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="/minigame"><span>Minigame</span></a></li>
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
            <h4>Chọn minigame bạn muốn chơi</h4>
        </div>
        <div class="section-container-index">
            ';
foreach ($db->get_list('SELECT * FROM `spin_quests` WHERE `status` = 1 ORDER BY `stt` ASC') as $spin) {
    echo '                <a href="/vongquay/';
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
        echo '                        <p class="ppo"><span>';
        echo format_cash($spin['price']);
        echo 'đ</span> <span>';
        echo $spin['sale'];
        echo '%</span></p>
                    ';
    }
    echo '                </a>
            ';
}
echo '        </div>
    </div>
</section>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
