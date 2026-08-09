<?php
// statically decompiled from view.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['slug'])) {
    $slug = Anti_xss($_GET['slug']);
    $row = $db->get_row(' SELECT * FROM `categories` WHERE `slug` = \'' . $slug . '\'  ');
    if (!$row) {
        new Redirect('/');
    }
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
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="/section/';
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
            <h4>Chọn game bạn muốn mua</h4>
        </div>
        <div class="section-container-index">
            ';
foreach ($db->get_list('SELECT * FROM `subcategory` WHERE `category` = \'' . $row['id'] . '\' AND `status` = 1 ORDER BY `stt` ASC') as $sub) {
    $detail = json_decode($sub['detail'], true);
    $count_account_groups = $db->get_row('SELECT COUNT(id) FROM `accounts` WHERE `sub_id` = \'' . $sub['id'] . '\' AND `status`=\'on\'')['COUNT(id)'] ?? 0;
    echo '                <a href="/tai-khoan/';
    echo $sub['type_category'];
    echo '" class="items-content-index">
                    <div class="scale-img">
                        <img src="';
    echo DOMAIN . '/' . $detail['thumb'];
    echo '" alt="';
    echo $detail['name_product'];
    echo '">
                    </div>
                    <h3>';
    echo $detail['name_product'];
    echo '</h3>
                    ';
    if ($sub['type'] == 'RANDOM') {
        echo '                        <p class="p06">Số tài khoản: ';
        echo $count_account_groups;
        echo '</p>

                        <p class="ppr">';
        echo format_cash($detail['cash']);
        echo 'đ</p>
                    ';
    } else {
        echo '                        <p class="p06">Số tài khoản: ';
        echo $count_account_groups;
        echo '</p>
                    ';
    }
    echo '                    <button>Mua Ngay</button>
                </a>
            ';
}
echo '        </div>
    </div>
</section>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
