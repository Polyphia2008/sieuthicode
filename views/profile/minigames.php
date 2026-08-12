<?php
// statically decompiled from minigames.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Lịch sử minigame - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    $sotin1trang = 8;
    if (isset($_GET['page'])) {
        $page = max(1, (int) $_GET['page']);
    } else {
        $page = 1;
    }
    $from = ($page - 1) * $sotin1trang;
    $where = ' `id` > 0 AND `user_id` ="' . $data_user['id'] . '"';
    $keyword = '';
    if (!empty($_GET['keyword'])) {
        $keyword = Anti_xss($_GET['keyword']);
        $where .= ' AND `trans_id` LIKE "%' . $keyword . '%" ';
    }
    $id_game = '';
    if (!empty($_GET['id_game'])) {
        $id_game = Anti_xss($_GET['id_game']);
        $where .= ' AND `spin_id` = ' . $id_game . ' ';
    }
    $from_date = '';
    $to_date = '';
    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        $from_date = Anti_xss($_GET['from_date']);
        $to_date = Anti_xss($_GET['to_date']);
        $start_timestamp = urldecode($from_date) . ' 00:00:00';
        $end_timestamp = urldecode($to_date) . ' 23:59:59';
        $where .= ' AND `created_at` >= \'' . $start_timestamp . '\' AND `created_at` <= \'' . $end_timestamp . '\' ';
    }
    $listOrder = $db->get_list('SELECT * FROM `spin_quest_logs` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Lịch sử minigame</span></a></li>
            </ol>
        </div>
    </div>
</div>
<div class="screen">
    <div class="center">
        <div class="page-account">
            <div class="menu-account">
                ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/profile/menu.php');
    echo '            </div>
            <div class="container-account" id="container-account">
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="/customer/profile"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Lịch sử chơi minigame</h2>
                    </div>
                </div>
                <div class="content-account header-desktop-account">
                    <h2 class="title-account">Lịch sử chơi minigame</h2>
                    <hr>
                    <form class="form-filter mt-3" method="GET" action="">
                        <div class="search-menu1">
                            <div class="group-input input-search">
                                <div class="group-search">
                                    <div class="input-element custom-search-menu1">
                                        <input type="text" name="keyword" value="" id="input-search-lsnapthe" placeholder="Tìm kiếm mã giao dịch">
                                        <button><custom-seach>
                                                <icon class="icon-search search"></icon>
                                            </custom-seach></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bo-loc" data-toggle="modal" data-target="#filter-lsnapthe">
                            <img src="/assets/images/filter-account.svg" alt="">
                            <p>Bộ lọc</p>
                        </div>
                        <div class="modal hide" id="filter-lsnapthe" tabindex="-1" role="dialog" aria-labelledby="filter-lsnapthe" aria-hidden="true">
                            <div class="modal-dialog popup-nguyennhieu" role="document">
                                <div class="content-popup-nguyennhieu w-500">
                                    <div class="header-popup-confirm">
                                        <div class="title-popup-nguyennhieu">
                                            <div class="icon-popup-nguyennhieu-2" style="--color: #344054">
                                                <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDExSDE3LjhDMTguOTIwMSAxMSAxOS40ODAyIDExIDE5LjkwOCAxMS4yMThDMjAuMjg0MyAxMS40MDk3IDIwLjU5MDMgMTEuNzE1NyAyMC43ODIgMTIuMDkyQzIxIDEyLjUxOTggMjEgMTMuMDc5OSAyMSAxNC4yVjIxTTEzIDIxVjYuMkMxMyA1LjA3OTkgMTMgNC41MTk4NCAxMi43ODIgNC4wOTIwMkMxMi41OTAzIDMuNzE1NjkgMTIuMjg0MyAzLjQwOTczIDExLjkwOCAzLjIxNzk5QzExLjQ4MDIgMyAxMC45MjAxIDMgOS44IDNINi4yQzUuMDc5OSAzIDQuNTE5ODQgMyA0LjA5MjAyIDMuMjE3OTlDMy43MTU2OSAzLjQwOTczIDMuNDA5NzMgMy43MTU2OSAzLjIxNzk5IDQuMDkyMDJDMyA0LjUxOTg0IDMgNS4wNzk5IDMgNi4yVjIxTTIyIDIxSDJNNi41IDdIOS41TTYuNSAxMUg5LjVNNi41IDE1SDkuNSIgc3Ryb2tlPSIjMzQ0MDU0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" />
                                            </div>
                                            <div class="content-title-popup-nguyennhieu">
                                                <h3>Bộ lọc</h3>
                                                <p>Tìm kiếm giao dịch nhanh chóng các yêu cầu chi tiết.</p>
                                            </div>
                                        </div>
                                        <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal" aria-label="Close">
                                            &times;
                                        </button>
                                    </div>
                                    <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                                        <div class="filter-form">
                                            <div class="group-input input-selectize">
                                                <div class="input-element">
                                                    <select id="select-id_game" name="id_game" class="select-component" data-max="1" data-search="false"
                                                        multiple="multiple" placeholder="Chọn danh mục minigame">
                                                        ';
    foreach ($db->get_list('SELECT * FROM `spin_quests` WHERE `status` = 1 ORDER BY `stt` ASC') as $spin) {
        echo '                                                            <option value="';
        echo $spin['id'];
        echo '" ';
        echo $spin['id'] == $id_game ? 'selected' : '';
        echo '>';
        echo $spin['name'];
        echo '</option>
                                                        ';
    }
    echo '                                                    </select>
                                                </div>
                                                <label for="select-id_game" class="font-weight-bold">Danh mục minigame</label>
                                            </div>
                                            <div class="grid grid-2 gap-10">
                                                <div class="group-input input-date">
                                                    <div class="input-element">
                                                        <input type="text" value="';
    echo $from_date;
    echo '" id="from_date" name="from_date" class="input-date-singe" data-drops="up" placeholder="Từ ngày">
                                                        <icon class="icon-time"></icon>
                                                    </div>
                                                    <label for="from_date">Từ ngày</label>
                                                </div>
                                                <div class="group-input input-date">
                                                    <div class="input-element">
                                                        <input type="text" value="';
    echo $to_date;
    echo '" id="to_date" name="to_date" class="input-date-singe" data-drops="up" placeholder="Đến ngày">
                                                        <icon class="icon-time"></icon>
                                                    </div>
                                                    <label for="to_date">Đến ngày</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="footer-popup-confirm">
                                        <a class="cancel-confirm-nguyennhieu" href="/customer/history/minigame">Huỷ bỏ</a>
                                        <button class="access-confirm-sieuthicode">Xem kết quả</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="content-account">
                    <div class="container-table">
                        ';
    if (count($listOrder) == 0) {
        echo '                            <div class="items-content-account">
                                <p class="text-danger font-weight-bold no-records">Không có dữ liệu!</p>
                            </div>
                        ';
    }
    echo '                        ';
    foreach ($listOrder as $info) {
        echo '                            <a class="row-gd" href="/customer/history/minigame/detail/';
        echo $info['trans_id'];
        echo '">
                                <div>
                                    <p class="ten_row">';
        echo $info['name_spin'];
        echo ' (#';
        echo $info['trans_id'];
        echo ')</p>
                                    <p class="date_row">';
        echo $info['created_at'];
        echo '</p>
                                </div>
                                <div>
                                    <p class="gia_row">';
        echo format_cash($info['price']);
        echo 'đ</p>
                                    <p class="status_row">Nhận được: ';
        echo $info['content'];
        echo '</p>
                                </div>
                            </a>
                        ';
    }
    echo '                    </div>
                    <div class="paging-account mt-3">
                        <div class="default-paginate">
                            ';
    $total = $db->num_rows('SELECT * FROM `spin_quest_logs` WHERE ' . $where . ' ORDER BY `id` DESC ');
    if ($sotin1trang < $total) {
        echo pagination_client('/customer/history/minigame?keyword=' . $keyword . '&id_game=' . $id_game . '&from_date=' . $from_date . '&to_date=' . $to_date . '&', $from, $total, $sotin1trang);
    }
    echo '                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
