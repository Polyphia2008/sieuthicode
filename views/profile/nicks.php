<?php
// statically decompiled from nicks.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Lịch sử mua nick - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    $sotin1trang = 8;
    if (isset($_GET['page'])) {
        $page = max(1, (int) $_GET['page']);
    } else {
        $page = 1;
    }
    $from = ($page - 1) * $sotin1trang;
    $where = ' `id` > 0 AND `username` ="' . $data_user['username'] . '"';
    $keyword = '';
    if (!empty($_GET['keyword'])) {
        $keyword = Anti_xss($_GET['keyword']);
        $where .= ' AND `trans_id` LIKE "%' . $keyword . '%" ';
    }
    $id_category = '';
    if (!empty($_GET['category'])) {
        $id_category = Anti_xss($_GET['category']);
        $where .= ' AND `category_id` = ' . $id_category . ' ';
    }
    $from_date = '';
    $to_date = '';
    if (!empty($_GET['from_date']) && !empty($_GET['to_date'])) {
        $from_date = Anti_xss($_GET['from_date']);
        $to_date = Anti_xss($_GET['to_date']);
        $start_timestamp = strtotime(urldecode($from_date) . ' 00:00:00');
        $end_timestamp = strtotime(urldecode($to_date) . ' 23:59:59');
        $where .= ' AND `created_at` >= \'' . $start_timestamp . '\' AND `created_at` <= \'' . $end_timestamp . '\' ';
    }
    $id_money = '';
    if (!empty($_GET['id_money'])) {
        $id_money = Anti_xss($_GET['id_money']);
        if ($id_money == 1) {
            $where .= 'AND `cash` < 50000 ';
        } else {
            if ($id_money == 2) {
                $where .= 'AND `cash` BETWEEN 50000 AND 200000 ';
            } else {
                if ($id_money == 3) {
                    $where .= 'AND `cash` BETWEEN 200000 AND 500000 ';
                } else {
                    if ($id_money == 4) {
                        $where .= 'AND `cash` BETWEEN 500000 AND 1000000 ';
                    } else {
                        if ($id_money == 5) {
                            $where .= 'AND `cash` BETWEEN 1000000 AND 2000000 ';
                        } else {
                            if ($id_money == 6) {
                                $where .= 'AND `cash` BETWEEN 2000000 AND 5000000 ';
                            } else {
                                if ($id_money == 7) {
                                    $where .= 'AND `cash` >= 10000000 ';
                                } else {
                                    $where .= '';
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    $listOrder = $db->get_list('SELECT * FROM `history_buy` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Tài khoản đã mua</span></a></li>
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
                        <h2 class="title-account">Tài khoản đã mua</h2>
                    </div>
                </div>
                <div class="content-account header-desktop-account">
                    <h2 class="title-account">Tài khoản đã mua</h2>
                    <hr>
                    <form class="form-filter mt-3" method="GET" action="">
                        <div class="search-menu1">
                            <div class="group-input input-search">
                                <div class="group-search">
                                    <div class="input-element custom-search-menu1">
                                        <input type="text" name="keyword" value="';
    echo $keyword;
    echo '" id="input-search-lsnapthe" placeholder="Tìm kiếm mã giao dịch">
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
                                                    <select id="category" name="category" class="select-component" data-max="1"
                                                        multiple="multiple" placeholder="Chọn danh mục nick game" data-search="false">
                                                        ';
    foreach ($db->get_list('SELECT * FROM `subcategory` WHERE `status` = 1 ORDER BY `stt` ASC') as $category) {
        $detail = json_decode($category['detail'], true);
        echo '                                                            <option value="';
        echo $category['id'];
        echo '" ';
        echo $id_category == $category['id'] ? 'selected' : '';
        echo '>';
        echo $detail['name_product'];
        echo '</option>
                                                        ';
    }
    echo '                                                    </select>
                                                </div>
                                                <label for="select-id_game" class="font-weight-bold">Danh mục game</label>
                                            </div>
                                            <div class="group-input input-selectize">
                                                <div class="input-element">
                                                    <select id="select-id_money" name="id_money" class="select-component" data-max="1" multiple="multiple" placeholder="Chọn khoảng tiền" data-search="false">
                                                        <option value="1" ';
    echo $id_money == 1 ? 'selected' : '';
    echo '>Dưới 50k</option>
                                                        <option value="2" ';
    echo $id_money == 2 ? 'selected' : '';
    echo '>Từ 50k đến 200k</option>
                                                        <option value="3" ';
    echo $id_money == 3 ? 'selected' : '';
    echo '>Từ 200k đến 500k</option>
                                                        <option value="4" ';
    echo $id_money == 4 ? 'selected' : '';
    echo '>Từ 500k đến 1 triệu</option>
                                                        <option value="5" ';
    echo $id_money == 5 ? 'selected' : '';
    echo '>Từ 1 triệu đến 2 triệu</option>
                                                        <option value="6" ';
    echo $id_money == 6 ? 'selected' : '';
    echo '>Từ 2 triệu đến 5 triệu</option>
                                                        <option value="7" ';
    echo $id_money == 7 ? 'selected' : '';
    echo '>Trên 5 triệu</option>
                                                    </select>
                                                </div>
                                                <label for="select-id_status" class="font-weight-bold">Tiền</label>
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
                                        <a class="cancel-confirm-nguyennhieu" href="/customer/history/account">Huỷ bỏ</a>
                                        <button class="access-confirm-sieuthicode">Xem kết quả</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="content-account">
                    ';
    echo $db->site('notice_purchasing');
    echo '                </div>
                <div class="content-account">
                    ';
    if (count($listOrder) == 0) {
        echo '                        <div class="items-content-account">
                            <p class="text-danger font-weight-bold no-records">Không có dữ liệu!</p>
                        </div>
                    ';
    }
    echo '                    <div class="container-table">
                        ';
    foreach ($listOrder as $info) {
        $query_product = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $info['type_category'] . '\'');
        $detail_product = json_decode($query_product['detail'], true);
        $detail = json_decode($info['detail'], true);
        $arr_detail = $detail['data'];
        echo '                            <a class="row-gd">
                                <div>
                                    <p class="ten_row">';
        echo $detail_product['name_product'];
        echo ' (#';
        echo $info['trans_id'];
        echo ')</p>
                                    <p class="date_row">';
        echo date('Y-m-d H:i:s', $info['created_at']);
        echo '</p>
                                </div>
                                <div>
                                    <p class="gia_row">';
        echo format_cash($info['cash']);
        echo 'đ</p>
                                    <p class="status_row"><span class="text-success">Thành công</span></p>

                                </div>
                                <div class="action-buttons">
                                    <button class="evaluate review_button" data-product-id="';
        echo $info['id'];
        echo '"><img src="/assets/images/rate.png" width="30" /></button>
                                    <button class="detail" onclick="location.href=\'/customer/history/account/detail/';
        echo $info['trans_id'];
        echo '\';"><img src="/assets/images/research.png" width="30" /></button>
                                </div>

                            </a>
                        ';
    }
    echo '                    </div>
                    <div class="paging-account mt-3">
                        <div class="default-paginate">
                            ';
    $total = $db->num_rows('SELECT * FROM `history_buy` WHERE ' . $where . ' ORDER BY `id` DESC ');
    if ($sotin1trang < $total) {
        echo pagination_client('/customer/history/account?keyword=' . $keyword . '&category=' . $id_category . '&id_money=' . $id_money . '&from_date=' . $from_date . '&to_date=' . $to_date . '&', $from, $total, $sotin1trang);
    }
    echo '                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal hide no-scrollbar" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModal" aria-hidden="true">
    <div class="modal-dialog popup-nguyennhieu" role="document">
        <div class="content-popup-nguyennhieu w-500">
            <form id="ratingForm">
                <div class="main-popup-notification npmg container-base-up3s">
                    <label class="form-label me-2" for="rating">Đánh giá của bạn</label>
                    <div class="stars">
                        <input type="radio" name="star" class="star" id="star5" value="5">
                        <label for="star5">★</label>
                        <input type="radio" name="star" class="star" id="star4" value="4">
                        <label for="star4">★</label>
                        <input type="radio" name="star" class="star" id="star3" value="3">
                        <label for="star3">★</label>
                        <input type="radio" name="star" class="star" id="star2" value="2">
                        <label for="star2">★</label>
                        <input type="radio" name="star" class="star" id="star1" value="1">
                        <label for="star1">★</label>
                    </div>
                    <div class="sample-container">
                        <p class="form-label">Chọn nội dung mẫu:</p>
                        <div class="sample-buttons">
                            <button type="button" class="btn-sample" data-content="Dịch vụ rất tốt!">Dịch vụ rất tốt!</button>
                            <button type="button" class="btn-sample" data-content="Sản phẩm chất lượng.">Sản phẩm chất lượng.</button>
                            <button type="button" class="btn-sample" data-content="Hỗ trợ nhanh chóng.">Hỗ trợ nhanh chóng.</button>
                        </div>
                    </div>
                    <div>
                        <p class="form-label">Nội dung</p>
                        <textarea id="reviews" class="form-control input-text" rows="3"></textarea>
                    </div>
                    

                </div>
                <div class="footer-popup-confirm">
                    <button class="cancel-confirm-nguyennhieu" data-dismiss="modal">Đóng</button>
                    <button class="access-confirm-sieuthicode">Đánh giá</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let productId;
    document.querySelectorAll(\'.review_button\').forEach(button => {
        button.addEventListener(\'click\', function() {
            productId = this.getAttribute(\'data-product-id\');
            $(\'#reviewModal\').modal(\'show\');
        });
    });
    $(document).on("click", ".btn-sample", function() {
        const content = $(this).data("content");
        $("#reviews").val(content);
    });
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
