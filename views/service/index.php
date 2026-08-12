<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['type'])) {
    $type = Anti_xss($_GET['type']);
    $row = $db->get_row(' SELECT * FROM `subboostings` WHERE `type_category` = \'' . $type . '\'  ');
    if (!$row) {
        new Redirect('/');
    }
    $detail_query = json_decode($row['detail'], true);
} else {
    new Redirect('/');
}
$title = $detail_query['name_product'] . ' | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
$sotin1trang = 32;
if (isset($_GET['page'])) {
    $page = max(1, (int) $_GET['page']);
} else {
    $page = 1;
}
$from = ($page - 1) * $sotin1trang;
$where = ' `id` > 0 AND `sub_id` = "' . $row['id'] . '"';
$keyword = '';
if (!empty($_GET['keyword'])) {
    $keyword = Anti_xss($_GET['keyword']);
    $where .= ' AND `name` LIKE \'%' . $keyword . '%\'';
}
$id_pack = '';
if (!empty($_GET['id_pack'])) {
    $id_pack = Anti_xss($_GET['id_pack']);
    $where .= ' AND `id` = \'' . $id_pack . '\'';
}
$listPackage = $db->get_list('SELECT * FROM `package_boostings` WHERE ' . $where . ' ORDER BY `stt` ASC LIMIT ' . $from . ',' . $sotin1trang . ' ');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item "><a class="text-decoration-none" href="/minigame"><span>Dịch vụ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>';
echo $detail_query['name_product'];
echo '</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="screen">
    <div class="center">
        ';
if ($row['type'] == 'caythue') {
    echo '            <h3>';
    echo $detail_query['name_product'];
    echo '</h3>
            <hr>
            <form class="validation-form" novalidate id="submit-service">
                <input type="hidden" class="input-text" name="csrf_token" value="';
    echo generate_csrf_token();
    echo '" required>
                <div class="row">
                    <div class="col-12 col-lg-8 c-pr-lg-16">
                        <p class="text-title fw-700 title-color-lg c-py-16  c-py-lg-20">
                            Thông tin người dùng
                        </p>
                        <div class="card-service mt-2 mb-3">
                            <div class="card-body">
                                <div class="row">
                                    ';
    if (!empty($detail_query['data'])) {
        echo '                                        ';
        foreach ($detail_query['data'] as $field) {
            echo '                                            ';
            if ($field['type'] == 'select') {
                echo '                                                <div class="col-md-6 mb-2">
                                                    <div class="group-input c-px-8 align-content-end">
                                                        <div class="form-label">
                                                            ';
                echo htmlspecialchars($field['label']);
                echo '                                                        </div>
                                                        <div class="input-element">
                                                            <select name="fields_';
                echo $field['id'];
                echo '" id="field-';
                echo $field['id'];
                echo '" class="select-component" data-max="1" data-search="false"
                                                                multiple="multiple" placeholder="Chọn ';
                echo htmlspecialchars($field['label']);
                echo '">
                                                                ';
                $options = explode(',', $field['option']);
                foreach ($options as $option) {
                    echo '                                                                    <option value="';
                    echo htmlspecialchars($option);
                    echo '">';
                    echo htmlspecialchars($option);
                    echo '</option>
                                                                ';
                }
                echo '                                                            </select>
                                                        </div>
                                                        ';
                if (!empty($field['content'])) {
                    echo '                                                            <div class="input-element border-danger">
                                                                <p>';
                    echo $field['content'];
                    echo '</p>
                                                            </div>
                                                        ';
                }
                echo '                                                    </div>
                                                </div>
                                            ';
            } else {
                echo '                                                <div class="col-md-6 mb-2">
                                                    <div class="group-input c-px-8 align-content-end">
                                                        <div class="form-label">
                                                            ';
                echo $field['label'];
                echo '                                                        </div>
                                                        <div class="input-element">
                                                            <input class="input-text" type="';
                echo $field['type'];
                echo '" name="fields_';
                echo $field['id'];
                echo '" id="field-';
                echo $field['id'];
                echo '" placeholder="';
                echo $field['label'];
                echo '" required>

                                                        </div>
                                                    </div>
                                                </div>
                                            ';
            }
            echo '
                                        ';
        }
        echo '                                    ';
    }
    echo '                                </div>
                            </div>
                        </div>

                        <div class="c-mb-16">
                            <h2 class="text-title-bold d-block c-mt-8 d-lg-none c-mb-8">Chi tiết dịch vụ</h2>
                            <div class="card-service mt-3 mb-3 overflow-hidden detailViewBlock">
                                <div class="card-body c-px-16">
                                    <h2 class="text-title-bold d-none d-lg-block c-mb-24 detailViewBlockTitle">Chi
                                        tiết
                                        dịch vụ</h2>
                                    <div
                                        class="content-desc detailViewBlockContent ">
                                        <div class="data-content">
                                            ';
    echo $detail_query['thele'];
    echo '                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 mobile-first">
                        <div class="js_sticky" data-top="140">
                            <h2 class="text-title fw-700 title-color-lg c-py-16">
                                Gói
                            </h2>
                            <div class="card-service mt-2 mb-3 p-2">
                                <div class="card-body scrollbar py-0">
                                    ';
    foreach ($db->get_list('SELECT * FROM `package_boostings` WHERE `sub_id` = \'' . $row['id'] . '\' ORDER BY `stt` ASC') as $package) {
        echo '                                        <label data-name="';
        echo $package['name'];
        echo '" for="ratio_';
        echo $package['id'];
        echo '" class="input-checkbox mt-2 input-checkbox-ratio">
                                            <input id="ratio_';
        echo $package['id'];
        echo '" type="radio"
                                                name="package" value="';
        echo $package['id'];
        echo '"
                                                data-price="';
        echo format_cash($package['price']);
        echo 'đ"
                                                data-content="';
        echo base64_decode($package['thele']);
        echo '"
                                                data-name="';
        echo $package['name'];
        echo '">
                                            <span class="checkmark"></span>
                                            <span class="text-label text">';
        echo $package['name'];
        echo ' (';
        echo format_cash($package['price']);
        echo 'đ)</span>
                                        </label>
                                    ';
    }
    echo '                                    <div class="border-danger border-1 d-none" id="content-display">

                                    </div>
                                </div>
                            </div>
                            <div class="t-sub-3 invalid-color error-selected"></div>
                            <div class="card-service section-pay mt-3 d-none d-lg-block">
                                <div class="card-body c-p-16">
                                    <div class="text-title-bold">Báo giá: <span
                                            class="text-title secondary total__price c-ml-8">0 đ</span></div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="javascript:void(0)"
                                                class="btn primary buy-service">Thanh toán</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-mobile c-p-20">
                    <div class="price-container">
                        <p class="fw-lg-500">Báo giá:</p>
                        <p class="text-title secondary total__price">0 đ</p>
                    </div>
                    <div class="d-flex">
                        <button style="top: 54px !important;" type="button"
                            class="btn primary buy-service">Giao dịch ngay</button>
                    </div>
                </div>
                <div class="modal hide no-scrollbar" id="filter-comfirm-xacnhan" tabindex="-1" role="dialog"
                    aria-labelledby="filter-comfirm-xacnhan" aria-hidden="true">
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
                                    <h3>Thông tin yêu cầu</h3>
                                    <div class="line-thongtin">
                                        <p>Dịch vụ</p>
                                        <p>';
    echo $detail_query['name_product'];
    echo '</p>
                                    </div>
                                    <div class="line-thongtin">
                                        <p>Gói</p>
                                        <p><b class="show-pack">Không có</b></p>
                                    </div>
                                    <div class="line-thongtin">
                                        <p>Giá tiền</p>
                                        <p class="show-price">đ</p>
                                    </div>

                                    ';
    if ($user) {
        echo '                                        <div class="line-chonnguontien">
                                            <p>Phương thức thanh toán:</p>
                                            <div class="phuongthucthanhtoan-radio">
                                                <div class="container-radio-payments">
                                                    <label class="payments-label" for="radio-payments1">
                                                        <p>Số dư tài khoản</p>
                                                        <p>';
        echo format_cash($data_user['money']);
        echo 'đ</p>
                                                    </label>
                                                </div>
                                                <div class="container-radio-payments">

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
    echo '                                </div>
                            </div>
                            <div class="footer-popup-confirm grid1">
                                ';
    if ($user) {
        echo '                                    <button type="submit" class="access-confirm-sieuthicode">Thanh toán</button>
                                ';
    } else {
        echo '                                    <button class="access-confirm-sieuthicode open-modals-auth" data-class="login">Đăng
                                        nhập</button>
                                ';
    }
    echo '                            </div>
                        </div>
                    </div>
                </div>
            </form>
        ';
} else {
    if ($row['type'] == 'item') {
        echo '            <form class="title-nickgame">
                <h4>';
        echo $detail_query['name_product'];
        echo '                    <div class="bo-loc" data-toggle="modal" data-target="#filter-lsnapthe">
                        <img src="/assets/images/filter-account.svg" alt="">
                        <p>Bộ lọc</p>
                    </div>
                </h4>
                <div class="modal hide no-scrollbar" id="filter-lsnapthe" tabindex="-1" role="dialog" aria-labelledby="filter-lsnapthe" aria-hidden="true">
                    <div class="modal-dialog popup-nguyennhieu" role="document">
                        <div class="content-popup-nguyennhieu w-500">
                            <div class="header-popup-confirm">
                                <div class="title-popup-nguyennhieu">
                                    <div class="icon-popup-nguyennhieu-2" style="--color: #344054">
                                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDExSDE3LjhDMTguOTIwMSAxMSAxOS40ODAyIDExIDE5LjkwOCAxMS4yMThDMjAuMjg0MyAxMS40MDk3IDIwLjU5MDMgMTEuNzE1NyAyMC43ODIgMTIuMDkyQzIxIDEyLjUxOTggMjEgMTMuMDc5OSAyMSAxNC4yVjIxTTEzIDIxVjYuMkMxMyA1LjA3OTkgMTMgNC41MTk4NCAxMi43ODIgNC4wOTIwMkMxMi41OTAzIDMuNzE1NjkgMTIuMjg0MyAzLjQwOTczIDExLjkwOCAzLjIxNzk5QzExLjQ4MDIgMyAxMC45MjAxIDMgOS44IDNINi4yQzUuMDc5OSAzIDQuNTE5ODQgMyA0LjA5MjAyIDMuMjE3OTlDMy43MTU2OSAzLjQwOTczIDMuNDA5NzMgMy43MTU2OSAzLjIxNzk5IDQuMDkyMDJDMyA0LjUxOTg0IDMgNS4wNzk5IDMgNi4yVjIxTTIyIDIxSDJNNi41IDdIOS41TTYuNSAxMUg5LjVNNi41IDE1SDkuNSIgc3Ryb2tlPSIjMzQ0MDU0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" />
                                    </div>
                                    <div class="content-title-popup-nguyennhieu">
                                        <h3>Bộ lọc</h3>
                                        <p>Tìm kiếm dịch vụ nhanh chóng các yêu cầu chi tiết.</p>
                                    </div>
                                </div>
                                <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal" aria-label="Close">
                                    &times;
                                </button>
                            </div>
                            <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                                <div class="filter-form">
                                    <div class="group-input input-id">
                                        <div class="input-element">
                                            <input class="input-text" type="text" name="keyword" value="';
        echo $keyword;
        echo '" placeholder="Nhập từ khóa tìm kiếm">
                                        </div>
                                        <label for="input-id" class="font-weight-bold">Tìm kiếm</label>
                                    </div>
                                    <div class="group-input input-selectize">
                                        <div class="input-element">
                                            <select id="id_pack" name="id_pack" class="select-component" data-max="1" multiple="multiple" placeholder="Chọn dịch vụ" data-search="false">
                                                ';
        foreach ($db->get_list('SELECT * FROM `package_boostings` WHERE `sub_id` = \'' . $row['id'] . '\' ORDER BY `stt` ASC') as $packages) {
            echo '                                                    <option value="';
            echo $packages['id'];
            echo '" ';
            echo $id_pack == $packages['id'] ? 'selected' : '';
            echo '>';
            echo $packages['name'];
            echo '</option>
                                                ';
        }
        echo '                                            </select>
                                        </div>
                                        <label for="select-id_status" class="font-weight-bold">Chọn dịch vụ</label>
                                    </div>
                                </div>
                            </div>
                            <div class="footer-popup-confirm">
                                <a href="/dich-vu/';
        echo $type;
        echo '" class="cancel-confirm-nguyennhieu">Huỷ bỏ</a>
                                <button type="submit" class="access-confirm-sieuthicode">Xem kết quả</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <hr>
            <div class="c-mb-16">
                <h2 class="text-title-bold d-block c-mt-8 d-lg-none c-mb-8">Chi tiết dịch vụ</h2>
                <div class="card card-service mt-3 mb-3 overflow-hidden detailViewBlock">
                    <div class="card-body c-px-16">
                        <h2 class="text-title-bold d-none d-lg-block c-mb-24 detailViewBlockTitle">Chi
                            tiết
                            dịch vụ</h2>
                        <div
                            class="content-desc detailViewBlockContent ">
                            <div class="data-content">
                                ';
        echo $detail_query['thele'];
        echo '                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="list-service c-py-16 c-py-lg-8 list-service-all">
                ';
        foreach ($listPackage as $package) {
            echo '                    <div class="item-service js-service">
                        <div class="card card-hover card-item" style="position: relative">
                            <a href="javascript:void(0)" data-id="';
            echo $package['id'];
            echo '" data-selected="0" data-pack="';
            echo $package['name'];
            echo '" data-price="';
            echo format_cash($package['price']);
            echo 'đ" class="card-body scale-thumb c-p-16 c-p-lg-12 show-confirm">
                                <div class="account-thumb c-mb-8">
                                    <img src="/assets/images/lazyload.gif" data-src="';
            echo DOMAIN . $package['image'];
            echo '" alt="';
            echo $package['name'];
            echo '" class="account-thumb-image lazyload">
                                </div>
                                <div class="account-title">
                                    <div class="text-title fw-700 text-limit limit-2 h-title-image">
                                        ';
            echo $package['name'];
            echo '                                    </div>
                                </div>
                                <div class="price">
                                    <div class="price-current w-100">
                                        ';
            echo format_cash($package['price']);
            echo 'đ
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                ';
        }
        echo '            </div>
            <div class="paging-account mt-3">
                <div class="default-paginate">
                    ';
        $total = $db->num_rows('SELECT * FROM `package_boostings` WHERE ' . $where);
        if ($sotin1trang < $total) {
            echo pagination_client('/dich-vu/' . $type . '?keyword=' . $keyword . '&id_pack=' . $id_pack . '&', $from, $total, $sotin1trang);
        }
        echo '                </div>
            </div>
           
            <div class="modal hide no-scrollbar" id="filter-comfirm-xacnhan" tabindex="-1" role="dialog" aria-labelledby="filter-comfirm-xacnhan" aria-hidden="true">
                <div class="modal-dialog popup-nguyennhieu" role="document">
                    <div class="content-popup-nguyennhieu w-500">
                        <form class="validation-form" novalidate id="submit-service-item">
                            <div class="header-popup-confirm">
                                <div class="title-popup-nguyennhieu">
                                    <div class="icon-popup-nguyennhieu-2" style="--color: #344054">
                                        <img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEzIDExSDE3LjhDMTguOTIwMSAxMSAxOS40ODAyIDExIDE5LjkwOCAxMS4yMThDMjAuMjg0MyAxMS40MDk3IDIwLjU5MDMgMTEuNzE1NyAyMC43ODIgMTIuMDkyQzIxIDEyLjUxOTggMjEgMTMuMDc5OSAyMSAxNC4yVjIxTTEzIDIxVjYuMkMxMyA1LjA3OTkgMTMgNC41MTk4NCAxMi43ODIgNC4wOTIwMkMxMi41OTAzIDMuNzE1NjkgMTIuMjg0MyAzLjQwOTczIDExLjkwOCAzLjIxNzk5QzExLjQ4MDIgMyAxMC45MjAxIDMgOS44IDNINi4yQzUuMDc5OSAzIDQuNTE5ODQgMyA0LjA5MjAyIDMuMjE3OTlDMy43MTU2OSAzLjQwOTczIDMuNDA5NzMgMy43MTU2OSAzLjIxNzk5IDQuMDkyMDJDMyA0LjUxOTg0IDMgNS4wNzk5IDMgNi4yVjIxTTIyIDIxSDJNNi41IDdIOS41TTYuNSAxMUg5LjVNNi41IDE1SDkuNSIgc3Ryb2tlPSIjMzQ0MDU0IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K" />
                                    </div>
                                    <div class="content-title-popup-nguyennhieu">
                                        <h3>Xác nhận thanh toán</h3>

                                    </div>
                                </div>
                                <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal" aria-label="Close">
                                    &times;
                                </button>
                            </div>
                            <div class="main-popup-confirm npmg line-heaer-popup-nguyennhieu p-3">
                                <div id="xacnhan_thongtin">
                                    <h3>Thông tin yêu cầu</h3>
                                    <div class="line-thongtin">
                                        <p>Gói</p>
                                        <p id="access-service-item-pack"></p>
                                        <input type="hidden" id="access-service-item-id" name="package" />
                                        <input type="hidden" class="input-text" name="csrf_token" value="';
        echo generate_csrf_token();
        echo '" required>
                                    </div>

                                    <div class="line-thongtin">
                                        <p>Tổng thanh toán</p>
                                        <p id="access-service-item-price">0đ</p>
                                    </div>
                                    <h3>Thông tin người dùng</h3>
                                    <div class="border line-info p-3">
                                        <div class="row">
                                            ';
        if (!empty($detail_query['data'])) {
            echo '                                                ';
            foreach ($detail_query['data'] as $field) {
                echo '                                                    ';
                if ($field['type'] == 'select') {
                    echo '                                                        <div class="col-md-12 mb-2">
                                                            <div class="group-input c-px-8 align-content-end">
                                                                <div class="form-label">
                                                                    ';
                    echo htmlspecialchars($field['label']);
                    echo '                                                                </div>
                                                                <div class="input-element">
                                                                    <select name="fields_';
                    echo $field['id'];
                    echo '" id="field-';
                    echo $field['id'];
                    echo '" class="select-component" data-max="1" data-search="false"
                                                                        multiple="multiple" placeholder="Chọn ';
                    echo htmlspecialchars($field['label']);
                    echo '">
                                                                        ';
                    $options = explode(',', $field['option']);
                    foreach ($options as $option) {
                        echo '                                                                            <option value="';
                        echo htmlspecialchars($option);
                        echo '">';
                        echo htmlspecialchars($option);
                        echo '</option>
                                                                        ';
                    }
                    echo '                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    ';
                } else {
                    echo '                                                        <div class="col-md-12 mb-2">
                                                            <div class="group-input c-px-8 align-content-end">
                                                                <div class="form-label">
                                                                    ';
                    echo $field['label'];
                    echo '                                                                </div>
                                                                <div class="input-element">
                                                                    <input class="input-text" type="';
                    echo $field['type'];
                    echo '" name="fields_';
                    echo $field['id'];
                    echo '" id="field-';
                    echo $field['id'];
                    echo '" placeholder="';
                    echo $field['label'];
                    echo '" required>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    ';
                }
                echo '
                                                ';
            }
            echo '                                            ';
        }
        echo '                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="footer-popup-confirm grid1">
                                <button type="submit" class="access-confirm-sieuthicode confirm-dichvugame">Xác nhận</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        ';
    } else {
        if ($row['type'] == 'robux') {
            echo '            <h3>';
            echo $detail_query['name_product'];
            echo '</h3>
            <hr>
            <form class="validation-form" novalidate id="submit-service-robux">
                <input type="hidden" class="input-text" name="csrf_token" value="';
            echo generate_csrf_token();
            echo '" required>
                <div class="row">
                    <div class="col-12 col-lg-8 c-pr-lg-16">
                        <div class="text-title fw-700 title-color-lg c-py-16 c-py-lg-20">
                            Vui lòng chọn thông tin
                        </div>
                        <div class="card-service">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <div class="group-input c-px-8 align-content-end">
                                            <div class="form-label">
                                                Nhập số tiền cần mua:
                                            </div>
                                            <div class="input-element">
                                                <input type="hidden" name="service" value="';
            echo $row['id'];
            echo '" />
                                                <input class="input-text" type="text" id="selectedAmount" name="amount" placeholder="Số tiền" required>
                                            </div>
                                        </div>
                                        <span>Số tiền thanh toán phải từ
                                            <span class="t-sub-3">';
            echo format_cash($detail_query['min']);
            echo 'đ</span>
                                            đến
                                            <span class="t-sub-3">';
            echo format_cash($detail_query['max']);
            echo 'đ</span>
                                        </span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <div class="group-input c-px-8 align-content-end">
                                            <div class="form-label">
                                                Hệ số:
                                            </div>
                                            <div class="input-element">
                                                <input class="input-text" type="text" value="';
            echo $detail_query['coefficient'];
            echo '" id="coefficient" name="coefficient" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <p class="text-title fw-700 mt-3">
                            Thông tin người dùng
                        </p>
                        <div class="card-service mt-2 mb-3">
                            <div class="card-body">
                                <div class="row">
                                    ';
            if (!empty($detail_query['data'])) {
                echo '                                        ';
                foreach ($detail_query['data'] as $field) {
                    echo '                                            ';
                    if ($field['type'] == 'select') {
                        echo '                                                <div class="col-md-6 mb-2">
                                                    <div class="group-input c-px-8 align-content-end">
                                                        <div class="form-label">
                                                            ';
                        echo htmlspecialchars($field['label']);
                        echo '                                                        </div>
                                                        <div class="input-element">
                                                            <select name="fields_';
                        echo $field['id'];
                        echo '" id="field-';
                        echo $field['id'];
                        echo '" class="select-component" data-max="1" data-search="false"
                                                                multiple="multiple" placeholder="Chọn ';
                        echo htmlspecialchars($field['label']);
                        echo '">
                                                                ';
                        $options = explode(',', $field['option']);
                        foreach ($options as $option) {
                            echo '                                                                    <option value="';
                            echo htmlspecialchars($option);
                            echo '">';
                            echo htmlspecialchars($option);
                            echo '</option>
                                                                ';
                        }
                        echo '                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            ';
                    } else {
                        echo '                                                <div class="col-md-6 mb-2">
                                                    <div class="group-input c-px-8 align-content-end">
                                                        <div class="form-label">
                                                            ';
                        echo $field['label'];
                        echo '                                                        </div>
                                                        <div class="input-element">
                                                            <input class="input-text" type="';
                        echo $field['type'];
                        echo '" name="fields_';
                        echo $field['id'];
                        echo '" id="field-';
                        echo $field['id'];
                        echo '" placeholder="';
                        echo $field['label'];
                        echo '" required>

                                                        </div>
                                                    </div>
                                                </div>
                                            ';
                    }
                    echo '
                                        ';
                }
                echo '                                    ';
            }
            echo '                                </div>
                            </div>
                        </div>

                        <div class="c-mb-16">
                            <h2 class="text-title-bold d-block c-mt-8 d-lg-none c-mb-8">Chi tiết dịch vụ</h2>
                            <div class="card-service mt-3 mb-3 overflow-hidden detailViewBlock">
                                <div class="card-body c-px-16">
                                    <h2 class="text-title-bold d-none d-lg-block c-mb-24 detailViewBlockTitle">Chi
                                        tiết
                                        dịch vụ</h2>
                                    <div
                                        class="content-desc detailViewBlockContent ">
                                        <div class="data-content">
                                            ';
            echo $detail_query['thele'];
            echo '                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 c-pl-8 mobile-first">
                        <div class="js_sticky" data-top="140">

                            <div class="t-sub-3 invalid-color error-selected"></div>
                            <div class="card-service section-pay mt-3 d-none d-lg-block">
                                <div class="card-body c-p-16">
                                    <div class="text-title-bold">Báo giá: <span
                                            class="text-title secondary total__price c-ml-8 mr-1">0 </span class="text-title secondary">';
            echo $detail_query['unit'];
            echo '</div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-6">
                                            <a href="javascript:void(0)"
                                                class="btn primary buy-service">Thanh toán</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="footer-mobile c-p-20">
                    <div class="price-container">
                        <p class="fw-lg-500">Báo giá:</p>
                        <p class="text-title secondary total__price">0 </p><span>';
            echo $detail_query['unit'];
            echo '</span>
                    </div>
                    <div class="d-flex">
                        <button style="top: 54px !important;" type="button"
                            class="btn primary buy-service">Giao dịch ngay</button>
                    </div>
                </div>
                <div class="modal hide no-scrollbar" id="filter-comfirm-xacnhan" tabindex="-1" role="dialog"
                    aria-labelledby="filter-comfirm-xacnhan" aria-hidden="true">
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
                                    <h3>Thông tin yêu cầu</h3>
                                    <div class="line-thongtin">
                                        <p>Dịch vụ</p>
                                        <p>';
            echo $detail_query['name_product'];
            echo '</p>
                                    </div>
                                    <div class="line-thongtin">
                                        <p>Gói</p>
                                        <p><b class="show-pack">Không có</b> ';
            echo $detail_query['unit'];
            echo '</p>
                                    </div>
                                    <div class="line-thongtin">
                                        <p>Giá tiền</p>
                                        <p class="show-price">đ</p>
                                    </div>

                                    ';
            if ($user) {
                echo '                                        <div class="line-chonnguontien">
                                            <p>Phương thức thanh toán:</p>
                                            <div class="phuongthucthanhtoan-radio">
                                                <div class="container-radio-payments">
                                                    <label class="payments-label" for="radio-payments1">
                                                        <p>Số dư tài khoản</p>
                                                        <p>';
                echo format_cash($data_user['money']);
                echo 'đ</p>
                                                    </label>
                                                </div>
                                                <div class="container-radio-payments">

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
            echo '                                </div>
                            </div>
                            <div class="footer-popup-confirm grid1">
                                ';
            if ($user) {
                echo '                                    <button type="submit" class="access-confirm-sieuthicode">Thanh toán</button>
                                ';
            } else {
                echo '                                    <button class="access-confirm-sieuthicode open-modals-auth" data-class="login">Đăng
                                        nhập</button>
                                ';
            }
            echo '                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const amountInput = document.getElementById("selectedAmount");
                    const coefficientInput = document.getElementById("coefficient");
                    const totalPriceElements = document.querySelectorAll(".total__price");
                    const showPrice = document.querySelector(".show-price");
                    const showPack = document.querySelector(".show-pack");

                    function formatCurrency(value) {
                        return new Intl.NumberFormat(\'vi-VN\', {
                            style: \'currency\',
                            currency: \'VND\'
                        }).format(value);
                    }

                    function calculateTotal() {
                        let amount = parseFloat(amountInput.value) || 0;
                        let coefficient = parseFloat(coefficientInput.value) || 0;
                        let total = (amount / 1000) * coefficient;

                        let formattedAmount = formatCurrency(amount);
                        let formattedTotal = total;
                        showPrice.textContent = formattedAmount;
                        showPack.textContent = formattedTotal;
                        totalPriceElements.forEach(el => {
                            el.textContent = formattedTotal;
                        });
                    }

                    amountInput.addEventListener("input", calculateTotal);
                });
            </script>
        ';
        }
    }
}
echo '    </div>
</section>
<script>
    var check_user = "';
echo $user ? '1' : '';
echo '";
</script>
<script>
    document.querySelectorAll(\'input[name="package"]\').forEach((radio) => {
    radio.addEventListener(\'change\', function() {
        const contentDisplay = document.getElementById(\'content-display\');
        const dataContent = this.getAttribute(\'data-content\');
        if (dataContent !== null && dataContent !== \'\') {
            contentDisplay.innerHTML = dataContent;
            contentDisplay.classList.remove(\'d-none\');
        } else {
            contentDisplay.classList.add(\'d-none\');
        }
    });
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function updatePrice() {

            const selected = document.querySelector(\'input[name="package"]:checked\');

            let price = selected ? selected.getAttribute("data-price") : "0 đ";
            let pack = selected ? selected.getAttribute("data-name") : "Không có";


            document.querySelectorAll(".total__price").forEach(el => el.textContent = price);
            document.querySelector(".show-pack").textContent = pack;
            document.querySelector(".show-price").textContent = price;
        }

        document.querySelectorAll(\'input[name="package"]\').forEach(radio => {
            radio.addEventListener("change", updatePrice);
        });
    });
</script>

';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
