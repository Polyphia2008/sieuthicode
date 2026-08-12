<?php
// statically decompiled from account.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
error_reporting(0);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
        exit('Invalid CSRF Protection Token');
    } else {
        $type = Anti_xss($_POST['type']);
        $arr_data = [];
        $current_page = max(1, (int) ($_POST['page'] ?? 1));
        $price = isset($_POST['price']) ? Anti_xss($_POST['price']) : '';
        $sort = isset($_POST['sort']) ? Anti_xss($_POST['sort']) : '';
        $id = isset($_POST['id']) ? Anti_xss($_POST['id']) : '';
        $sql_query = 'SELECT * FROM `subcategory` WHERE `type_category` = \'' . $type . '\' AND `status` = 1 LIMIT 1';
        $query = $db->get_row($sql_query);
        if ($query) {
            $detail_query = json_decode($query['detail'], true);
            $arr_query = $detail_query['data'];
            $sql = [];
            $a = 2;
            while ($a < count($arr_query)) {
                if ($arr_query[$a]['show'] == 'on') {
                    $name = Anti_xss($_POST[$arr_query[$a]['name']]);
                    $arr_name = $arr_query[$a]['name'];
                    if (!empty($name)) {
                        $sql[] = 'AND LOWER(JSON_UNQUOTE(JSON_EXTRACT(JSON_EXTRACT(`detail`, \'$.data[' . $a . ']\'), \'$.' . $arr_name . '\'))) LIKE LOWER(\'%' . $name . '%\')';
                    }
                }
                ++$a;
            }
            $implode = implode('', $sql);
            $sql_id = '';
            if ($id) {
                $sql_id = ' AND `id` = \'' . $id . '\'';
            }
            $sql_price = '';
            if ($price == 'duoi-50k') {
                $sql_price = 'AND `money` < 50000 ';
            } else {
                if ($price == 'tu-50k-200k') {
                    $sql_price = 'AND `money` BETWEEN 50000 AND 200000 ';
                } else {
                    if ($price == 'tu-200k-500k') {
                        $sql_price = 'AND `money` BETWEEN 200000 AND 500000 ';
                    } else {
                        if ($price == 'tu-500k-1-trieu') {
                            $sql_price = 'AND `money` BETWEEN 500000 AND 1000000 ';
                        } else {
                            if ($price == 'tren-1-trieu') {
                                $sql_price = 'AND `money` >= 1000000 ';
                            } else {
                                if ($price == 'tren-5-trieu') {
                                    $sql_price = 'AND `money` >= 5000000 ';
                                } else {
                                    if ($price == 'tren-10-trieu') {
                                        $sql_price = 'AND `money` >= 10000000 ';
                                    } else {
                                        $sql_price = '';
                                    }
                                }
                            }
                        }
                    }
                }
            }
            $sql_sort = 'ORDER BY `id` DESC';
            if ($sort == 'asc' || $sort == 'ASC') {
                $sql_sort = 'ORDER BY `money` ASC';
            } else {
                if ($sort == 'desc' || $sort == 'DESC') {
                    $sql_sort = 'ORDER BY `money` DESC';
                }
            }
            $sql_acc = 'SELECT * FROM `accounts` WHERE `type_category` = \'' . $type . '\' AND `status` = \'on\' ' . $sql_id . ' ' . $sql_price . ' ' . $implode . ' ' . $sql_sort;
            $total_records = $db->num_rows($sql_acc);
            $limit = 22;
            $total_page = (int) ceil($total_records / $limit);
            if ($total_page > 0) {
                $current_page = min($current_page, $total_page);
            } else {
                $current_page = 1;
            }
            $start = ($current_page - 1) * $limit;
            $sql_show = 'SELECT * FROM `accounts` WHERE `type_category` = \'' . $type . '\' AND `status` = \'on\' ' . $sql_id . ' ' . $sql_price . ' ' . $implode . ' ' . $sql_sort . ' LIMIT ' . $start . ', ' . $limit;
            echo '    <div class="section-container-index">
        ';
            if (0 < $total_records) {
                foreach ($db->get_list($sql_show) as $info) {
                    $check = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $info['type_category'] . '\'');
                    $detail_product = json_decode($check['detail'], true);
                    $arr_img = json_decode($info['image'], true);
                    $detail = json_decode($info['detail'], true);
                    echo '                ';
                    if ($check['type'] == 'ACCOUNT') {
                        echo '                    <a href="/tai-khoan/chi-tiet/';
                        echo $info['id'];
                        echo '" class="items-content-index">
                        <div class="scale-img">
                            <img src="/assets/images/lazyload.gif" data-src="';
                        echo DOMAIN . '/' . $arr_img[0];
                        echo '"
                                class="lazyload">
                        </div>
                        <h3>';
                        echo $detail_product['name_product'];
                        echo '</h3>
                        <h4>ID: #';
                        echo $info['id'];
                        echo '</h4>
                        ';
                        $i = 2;
                        while ($i < count($detail['data'])) {
                            if ($detail['data'][$i]['show'] == 'on') {
                                echo '                                <p class="p06">';
                                echo $detail['data'][$i]['label'];
                                echo ': ';
                                echo account_field_display($detail['data'][$i]);
                                echo '</p>
                        ';
                            }
                            ++$i;
                        }
                        echo '                        <p class="ppr">';
                        echo format_cash($info['money'] - $info['money'] * $info['sale'] / 100);
                        echo 'đ</p>
                        ';
                        if ($info['sale'] != 0) {
                            echo '                            <p class="ppo"><span>';
                            echo format_cash($info['money']);
                            echo 'đ</span> <span>';
                            echo $info['sale'];
                            echo '%</span></p>
                        ';
                        }
                        echo '                    </a>
                ';
                    } else {
                        echo '                    <div class="items-content-index buy-random random-id-';
                        echo $info['id'];
                        echo '" data-id="';
                        echo $info['id'];
                        echo '" data-price="';
                        echo format_cash($info['money'] - $info['money'] * $info['sale'] / 100);
                        echo '" onclick="showrandom(';
                        echo $info['id'];
                        echo ',`';
                        echo format_cash($info['money'] - $info['money'] * $info['sale'] / 100);
                        echo '`)">
                        <div class="scale-img">
                            <img src="/assets/images/lazyload.gif" data-src="';
                        echo DOMAIN . '/' . $detail_product['thumb'];
                        echo '" class="lazyload" alt="';
                        echo $detail_product['name_product'];
                        echo '">
                        </div>
                        <h3>';
                        echo $detail_product['name_product'];
                        echo '</h3>
                        <p class="ppr">';
                        echo format_cash($info['money'] - $info['money'] * $info['sale'] / 100);
                        echo 'đ</p>
                        <button>Mua ngay</button>
                    </div>
                ';
                    }
                    echo '            ';
                }
            } else {
                echo '            <div class="empty-state">
                <svg width="184" height="152" viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" fill-rule="evenodd">
                        <g transform="translate(24 31.67)">
                            <ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668">
                            </ellipse>
                            <path
                                d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z"
                                fill="#AEB8C2"></path>
                            <path
                                d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z"
                                fill="url(#linearGradient-1)" transform="translate(13.56)"></path>
                            <path
                                d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z"
                                fill="#F5F5F7"></path>
                            <path
                                d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z"
                                fill="#DCE0E6"></path>
                        </g>
                        <path
                            d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z"
                            fill="#DCE0E6"></path>
                        <g transform="translate(149.65 15.383)" fill="#FFF">
                            <ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse>
                            <path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path>
                        </g>
                    </g>
                </svg>
                <p>Không có dữ liệu</p>
            </div>
        ';
            }
            echo '    </div>
    <!-- qua trang -->
    <div class="paging-account mt-3">
        <div class="default-paginate">
            ';
            $tong = $db->num_rows('SELECT * FROM `accounts` WHERE `type_category` = \'' . $type . '\' AND `status` = \'on\' ' . $sql_id . ' ' . $sql_price . ' ' . $implode . '  ORDER BY `id` ASC');
            if ($limit < $tong) {
                echo '<center>' . pagination_account('', $start, $tong, $limit) . '</center>';
            }
            echo '        </div>
    </div>
<script>
    function showrandom(id,price){
		if (!check_user) {
			$("#filter-yeucaudangnhap").modal("show");
		} else {
			$("#access-random").attr("data-id", id);
			$("#access-random-price").html(price);
			$("#filter-comfirm-xacnhan").modal("show");
		}
	}
</script>
';
        } else {
            exit('<div class="empty-state">
        <svg width="184" height="152" viewBox="0 0 184 152" xmlns="http://www.w3.org/2000/svg">
            <g fill="none" fill-rule="evenodd">
                <g transform="translate(24 31.67)">
                    <ellipse fill-opacity=".8" fill="#F5F5F7" cx="67.797" cy="106.89" rx="67.797" ry="12.668"></ellipse>
                    <path d="M122.034 69.674L98.109 40.229c-1.148-1.386-2.826-2.225-4.593-2.225h-51.44c-1.766 0-3.444.839-4.592 2.225L13.56 69.674v15.383h108.475V69.674z" fill="#AEB8C2"></path>
                    <path d="M101.537 86.214L80.63 61.102c-1.001-1.207-2.507-1.867-4.048-1.867H31.724c-1.54 0-3.047.66-4.048 1.867L6.769 86.214v13.792h94.768V86.214z" fill="url(#linearGradient-1)" transform="translate(13.56)"></path>
                    <path d="M33.83 0h67.933a4 4 0 0 1 4 4v93.344a4 4 0 0 1-4 4H33.83a4 4 0 0 1-4-4V4a4 4 0 0 1 4-4z" fill="#F5F5F7"></path>
                    <path d="M42.678 9.953h50.237a2 2 0 0 1 2 2V36.91a2 2 0 0 1-2 2H42.678a2 2 0 0 1-2-2V11.953a2 2 0 0 1 2-2zM42.94 49.767h49.713a2.262 2.262 0 1 1 0 4.524H42.94a2.262 2.262 0 0 1 0-4.524zM42.94 61.53h49.713a2.262 2.262 0 1 1 0 4.525H42.94a2.262 2.262 0 0 1 0-4.525zM121.813 105.032c-.775 3.071-3.497 5.36-6.735 5.36H20.515c-3.238 0-5.96-2.29-6.734-5.36a7.309 7.309 0 0 1-.222-1.79V69.675h26.318c2.907 0 5.25 2.448 5.25 5.42v.04c0 2.971 2.37 5.37 5.277 5.37h34.785c2.907 0 5.277-2.421 5.277-5.393V75.1c0-2.972 2.343-5.426 5.25-5.426h26.318v33.569c0 .617-.077 1.216-.221 1.789z" fill="#DCE0E6"></path>
                </g>
                <path d="M149.121 33.292l-6.83 2.65a1 1 0 0 1-1.317-1.23l1.937-6.207c-2.589-2.944-4.109-6.534-4.109-10.408C138.802 8.102 148.92 0 161.402 0 173.881 0 184 8.102 184 18.097c0 9.995-10.118 18.097-22.599 18.097-4.528 0-8.744-1.066-12.28-2.902z" fill="#DCE0E6"></path>
                <g transform="translate(149.65 15.383)" fill="#FFF">
                    <ellipse cx="20.654" cy="3.167" rx="2.849" ry="2.815"></ellipse>
                    <path d="M5.698 5.63H0L2.898.704zM9.259.704h4.985V5.63H9.259z"></path>
                </g>
            </g>
        </svg>
        <p>Không có dữ liệu</p>
    </div>');
        }
    }
} else {
    exit(json_encode(['message' => 'The requested resource does not support http method \'GET\'.']));
}
