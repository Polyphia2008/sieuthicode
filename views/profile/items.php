<?php
// statically decompiled from items.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Lịch sử mua vật phẩm - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    $sotin1trang = 6;
    if (isset($_GET['page'])) {
        $page = Anti_xss($_GET['page']);
    } else {
        $page = 3;
    }
    $from = ($page - 1) * $sotin1trang;
    $where = ' `id` > 0 AND `user_id` ="' . $data_user['id'] . '"';
    $keyword = '';
    $listOrder = $db->get_list('SELECT * FROM `order_items` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
    echo '<div class="ws-px-2 ws-mx-auto ws-max-w-7xl" style="min-height:90vh;">
    <div class="ws-mb-[2rem]" id="nick">
        <div class="ws-px-2 ws-py-4 md:ws-py-12 ws-grid ws-grid-cols-12 ws-gap-2 md:ws-max-w-6xl md:ws-mx-auto">
            ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/profile/menu.php');
    echo '            <div class="ws-col-span-12 md:ws-col-span-9 ws-min-h-[70vh]">
                <div>
                    <h3 class="ws-text-xl ws-text-zinc-900 ws-mb-4">Lịch sử mua vật phẩm</h3>
                    <div class="ws-bg-white ws-my-1 ws-pb-2 md:ws-py-4 md:ws-px-4 ws-rounded-none md:ws-rounded ws-relative">
                        ';
    if (count($listOrder) == 0) {
        echo '                            <div class="empty-state">
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
                            </div>
                        ';
    } else {
        echo '                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>GIAO DỊCH</th>
                                            <th>THANH TOÁN</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ';
        foreach ($listOrder as $info) {
            echo '                                            <tr class="ws-border-b">
                                                <td>
                                                    <div>
                                                        <p class="ws-uppercase ws-font-semibold ws-text-red-500">';
            echo $info['value'];
            echo ' ';
            echo $info['unit'];
            echo '</p>
                                                        <p class="ws-text-xs">';
            echo $info['created_at'];
            echo '</p>
                                                        <div class="ws-border-b ws-border-dashed ws-w-20 ws-my-2"></div>
                                                        <div class="ws-text-xs ws-leading-5">
                                                            <p class="ws-font-medium"> Mã đơn: <span class="ws-text-zinc-900">';
            echo $info['code'];
            echo '</span></p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="ws-leading-5">
                                                        <p class="ws-font-medium ws-text-black ws-text-base ws-text-red-600"><span>-</span> ';
            echo format_cash($info['payment']);
            echo 'đ </p>
                                                    </div>
                                                    ';
            echo display_service($info['status']);
            echo '                                                </td>
                                                <td>
                                                    <div><a href="/customer/orders/items/view/';
            echo $info['code'];
            echo '" aria-disabled="false" type="button" class="el-button el-button--primary">Xem chi tiết</a></div>
                                                </td>
                                            </tr>
                                        ';
        }
        echo '                                    </tbody>
                                </table>
                            </div>
                            <div class="ws-mt-8 ws--mx-2 ws-flex ws-justify-center">
                                <div class="el-pagination is-background">
                                    ';
        $total = $db->num_rows('SELECT * FROM `order_items` WHERE ' . $where . ' ORDER BY `id` DESC ');
        if ($sotin1trang < $total) {
            echo pagination_client('/customer/orders/items?', $from, $total, $sotin1trang);
        }
        echo '                                </div>
                            </div>
                        ';
    }
    echo '                    </div>
                </div>
            </div>
        </div>
    </div>
    ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
