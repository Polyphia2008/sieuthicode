<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['type'])) {
    $type = Anti_xss($_GET['type']);
    $row = $db->get_row(' SELECT * FROM `subcategory` WHERE `type_category` = \'' . $type . '\'  ');
    if (!$row) {
        new Redirect('/');
        exit;
    }
    $id = $row['id'];
    $detail_query = json_decode($row['detail'], true);
    if (!is_array($detail_query)) {
        new Redirect('/');
        exit;
    }
    $data_detail = isset($detail_query['data']) && is_array($detail_query['data'])
        ? $detail_query['data']
        : [];
} else {
    new Redirect('/');
    exit;
}
$title = $detail_query['name_product'] . ' | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
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
        <form class="title-nickgame">
            <h3>';
echo $detail_query['name_product'];
echo '</h3>
            <hr>
            <div id="section-noidunglist">
                <div class="noidung-text container-base-up3s">
                    ';
echo $detail_query['thele'];
echo '                </div>
                <a class="toogle-noidung"><span class="toogle-noidung-text" data-text="Ẩn bớt">Xem thêm nội dung</span> <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
            </div>
            <h4>Chọn tài khoản game bạn muốn mua
                <div class="bo-loc" data-toggle="modal" data-target="#filter-lsnapthe">
                    <img src="/assets/images/filter-account.svg" alt="">
                    <p>Bộ lọc</p>
                </div>
            </h4>
            <hr>
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
                                    <p>Tìm kiếm giao dịch nhanh chóng các yêu cầu chi tiết.</p>
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
                                        <input class="input-text" type="text" name="id" value="" id="input-id" placeholder="Nhập mã số">
                                    </div>
                                    <label for="input-id" class="font-weight-bold">Mã số</label>
                                </div>
                                <div class="group-input input-selectize">
                                    <div class="input-element">
                                        <select id="select-id_money" name="id_money" class="select-component" data-max="1" multiple="multiple" placeholder="Chọn khoảng tiền" data-search="false">
                                            <option value="1">Dưới 50k</option>
                                            <option value="2">Từ 50k đến 200k</option>
                                            <option value="3">Từ 200k đến 500k</option>
                                            <option value="4">Từ 500k đến 1 triệu</option>
                                            <option value="5">Từ 1 triệu đến 2 triệu</option>
                                            <option value="6">Từ 2 triệu đến 5 triệu</option>
                                            <option value="7">Trên 5 triệu</option>
                                        </select>
                                    </div>
                                    <label for="select-id_status" class="font-weight-bold">Tiền</label>
                                </div>



                                ';
$i = 2;
while ($i < count($data_detail)) {
    if ($data_detail[$i]['show'] == 'on') {
        echo '                                        ';
        if ($data_detail[$i]['type'] == 'input') {
            echo '                                            <div class="group-input input-selectize">
                                                <div class="input-element">
                                                    <input class="input-text" type="text" name="';
            echo $data_detail[$i]['name'];
            echo '" id="';
            echo $data_detail[$i]['name'];
            echo '" placeholder="';
            echo $data_detail[$i]['label'];
            echo '">
                                                </div>
                                                <label for="" class="font-weight-bold">';
            echo $data_detail[$i]['label'];
            echo '</label>
                                            </div>
                                        ';
        } else {
            if ($data_detail[$i]['type'] == 'number') {
                echo '                                            <div class="group-input input-selectize">
                                                <div class="input-element">
                                                    <input class="input-text" type="number" name="';
                echo $data_detail[$i]['name'];
                echo '" id="';
                echo $data_detail[$i]['name'];
                echo '" placeholder="';
                echo $data_detail[$i]['label'];
                echo '">
                                                </div>
                                                <label for="" class="font-weight-bold">';
                echo $data_detail[$i]['label'];
                echo '</label>
                                            </div>
                                        ';
            } else {
                if ($data_detail[$i]['type'] == 'select') {
                    echo '                                            <div class="group-input input-selectize">
                                                <div class="input-element">
                                                    <select class="select-component" data-max="1" data-search="false" id="';
                    echo $data_detail[$i]['name'];
                    echo '" placeholder="';
                    echo $data_detail[$i]['label'];
                    echo '" size="large">
                                                        ';
                    $explode = explode('|', $data_detail[$i]['value']);
                    $a = 2;
                    while ($a < count($explode)) {
                        echo '                                                            <option value="';
                        echo $explode[$a];
                        echo '">';
                        echo $explode[$a];
                        echo '</option>
                                                        ';
                        ++$a;
                    }
                    echo '                                                    </select>
                                                </div>
                                                <label for="" class="font-weight-bold">';
                    echo $data_detail[$i]['label'];
                    echo '</label>
                                            </div>
                                        ';
                }
            }
        }
        echo '                                ';
    }
    ++$i;
}
echo '                            </div>
                        </div>
                        <div class="footer-popup-confirm">
                            <a class="cancel-confirm-nguyennhieu" onclick="load_account()">Huỷ bỏ</a>
                            <button type="button" onclick="fitler()" class="access-confirm-sieuthicode">Xem kết quả</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div id="list_account">

        </div>


        <div id="section-content-index">
            <div class="title-index">
                <h3>Mua thêm nick game khác</h3>
                <a href="/section/';
echo getRowRealtime('categories', $row['category'], 'slug');
echo '">Xem thêm <img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
            </div>
            <div class="section-container-index">
                ';
foreach ($db->get_list('SELECT * FROM `subcategory` WHERE `category` = \'' . $row['category'] . '\' AND `id` NOT IN (' . $id . ') AND `status` = 1 ORDER BY `stt` ASC') as $sub) {
    $detail = json_decode($sub['detail'], true);
    $count_account_groups = $db->get_row('SELECT COUNT(id) FROM `accounts` WHERE `sub_id` = \'' . $sub['id'] . '\' AND `status`=\'on\'')['COUNT(id)'] ?? 0;
    echo '                    <a href="/tai-khoan/';
    echo $sub['type_category'];
    echo '" class="items-content-index">
                        <div class="scale-img">
                            <img src="/assets/images/lazyload.gif" data-src="';
    echo DOMAIN . '/' . $detail['thumb'];
    echo '" class="lazyload" alt="';
    echo $detail['name_product'];
    echo '">
                        </div>
                        <h3>';
    echo $detail['name_product'];
    echo '</h3>
                        ';
    if ($sub['type'] == 'RANDOM') {
        echo '                            <p class="p06">Số tài khoản: ';
        echo $count_account_groups;
        echo '</p>

                            <p class="ppr">';
        echo format_cash($detail['cash']);
        echo 'đ</p>
                        ';
    } else {
        echo '                            <p class="p06">Số tài khoản: ';
        echo $count_account_groups;
        echo '</p>
                        ';
    }
    echo '                        <button>Mua Ngay</button>
                    </a>
                ';
}
echo '
            </div>
            <hr>
        </div>


    </div>
    ';
if ($row['type'] == 'RANDOM') {
    echo '        <script>
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
        <div class="modal hide no-scrollbar" id="filter-congratulations" tabindex="-1" role="dialog" aria-labelledby="filter-congratulations" aria-hidden="true">
            <div class="modal-dialog popup-nguyennhieu" role="document">
                <div class="content-popup-nguyennhieu w-400">
                    <div class="header-popup-confirm">
                        <div class="title-popup-nguyennhieu">
                            <img src="/assets/images/success.png" class="m-auto" alt="">
                        </div>
                        <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal" aria-label="Close">
                            &times;
                        </button>
                    </div>
                    <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                        <div class="modal-congratulations-wheel">
                            <h3 id="congratulations-wheel">Thanh toán thành công. Truy cập lịch sử mua tài khoản <a href="/customer/history/account">Tại đây</a> để nhận thông tin nick game.</h3>
                        </div>
                    </div>
                    <div class="footer-popup-confirm">
                        <a class="access-confirm-sieuthicode" data-dismiss="modal" aria-label="Close">Đóng</a>
                        <a href="/customer/history/account" class="access-confirm-sieuthicode">Xem thông tin</a>
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
                                <h3>Xác nhận</h3>
                                <p>Bạn đang mua ';
    echo $detail_query['name_product'];
    echo ' với giá <span id="access-random-price">0</span>đ.</p>
                            </div>
                        </div>
                        <button type="button" class="close-popup-nguyennhieu" data-dismiss="modal" aria-label="Close">
                            &times;
                        </button>
                    </div>
                    <div class="main-popup-confirm npmg  line-heaer-popup-nguyennhieu">
                        <div id="xacnhan_thongtin">
                            <div class="line-chonnguontien">
                                <p>Phương thức thanh toán:</p>
                                <div class="phuongthucthanhtoan-radio">
                                    <div class="container-radio-payments">
                                        <input class="radio-member-payments" type="radio" data-money="3000" data-giamoi="50000" name="radio-payments" id="radio-payments1" value="1" checked>
                                        <label class="payments-label" for="radio-payments1">
                                            <p>Số dư tài khoản</p>
                                            <p class="display_money_member">';
    echo $user ? format_cash($data_user['money']) : 0;
    echo 'đ</p>
                                        </label>
                                    </div>
                                    <div class="container-radio-payments">
                                        <input class="radio-member-payments" type="radio" data-money="0" data-giamoi="50000" name="radio-payments" disabled id="radio-payments2" value="2">
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
                        <button id="access-random" class="access-confirm-sieuthicode confirm-dichvugame">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
    ';
}
echo '</section>
<script>
    page = 1,
        id = \'\',
        price = \'\',
        sort = \'\',
        type = "';
echo $row['type_category'];
echo '";
    ';
$i = 2;
while ($i < count($data_detail)) {
    if ($data_detail[$i]['show'] == 'on') {
        echo '            ';
        echo $data_detail[$i]['name'];
        echo ' = "";
    ';
    }
    ++$i;
}
echo '
    function load_account() {
        $("#list_account").hide();
        $.ajax({
            type: \'POST\',
            url: \'/model/account/list\',
            data: {
                csrf_token: "';
echo generate_csrf_token();
echo '",
                page: page,
                id: id,
                price: price,
                sort: sort,
                ';
$i = 2;
while ($i < count($data_detail)) {
    if ($data_detail[$i]['show'] == 'on') {
        echo '                        ';
        echo $data_detail[$i]['name'];
        echo ': ';
        echo $data_detail[$i]['name'];
        echo ',
                ';
    }
    ++$i;
}
echo '                type: type

            },
            success: function(response) {
                $("#list_account").html(\'\');
                $(\'#list_account\').empty().append(response);
                $("#list_account").show();
            }
        });
    }

    function fitler() {
        id = $("#id").val();
        price = $("#price").val();
        ';
$i = 2;
while ($i < count($data_detail)) {
    if ($data_detail[$i]['show'] == 'on') {
        echo '                ';
        echo $data_detail[$i]['name'];
        echo ' = $("#';
        echo $data_detail[$i]['name'];
        echo '").val();
        ';
    }
    ++$i;
}
echo '        load_account();
    }
    load_account();
</script>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
