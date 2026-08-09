<?php
// statically decompiled from transactions.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Lịch sử nạp tiền - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    $sotin1trang = 6;
    if (isset($_GET['page'])) {
        $page = Anti_xss($_GET['page']);
    } else {
        $page = 3;
    }
    $from = ($page - 1) * $sotin1trang;
    $where = ' `id` > 0 AND `user_id` ="' . $data_user['id'] . '"';
    $limit = 6;
    if (isset($_GET['pages'])) {
        $pages = Anti_xss($_GET['pages']);
    } else {
        $pages = 3;
    }
    $froms = ($pages - 1) * $limit;
    $wheres = ' `id` > 0 AND `user_id` ="' . $data_user['id'] . '"';
    $listOrder = $db->get_list('SELECT * FROM `cards` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
    $listBank = $db->get_list('SELECT * FROM `invoices` WHERE ' . $wheres . ' ORDER BY `id` DESC LIMIT ' . $froms . ',' . $limit . ' ');
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
<div class="ws-px-2 ws-mx-auto ws-max-w-7xl" style="min-height:90vh;">
    <div class="ws-mb-[2rem]">
        <div class="ws-px-2 ws-py-4 md:ws-py-12 ws-grid ws-grid-cols-12 ws-gap-2 md:ws-max-w-6xl md:ws-mx-auto">
            ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/profile/menu.php');
    echo '            <div class="ws-col-span-12 md:ws-col-span-9 ws-min-h-[70vh]">
                <div>
                    <div class="el-tabs el-tabs--top demo-tabs ws-px-2 ws--mx-2">
                        <div class="el-tabs__header is-top">
                            <div class="el-tabs__nav-wrap is-top">
                                <div class="el-tabs__nav-scroll">
                                    <div class="el-tabs__nav is-top" role="tablist">
                                        <div class="el-tabs__item is-active animate__animated animate__fadeIn" id="tab-first" aria-controls="pane-first" role="tab" aria-selected="true" tabindex="0">THẺ CÀO ĐÃ NẠP</div>
                                        <div class="el-tabs__item is-top animate__animated animate__fadeIn" id="tab-second" aria-controls="pane-second" role="tab" aria-selected="false" tabindex="-1">ATM / MOMO ĐÃ NẠP</div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="el-tabs__content">
                            <div id="pane-first" class="el-tab-pane" role="tabpanel" aria-hidden="false" aria-labelledby="tab-first" style="">
                                <div>
                                    <div class="ws-bg-white ws-my-1 ws-pb-2 md:ws-py-4 md:ws-px-4 ws-rounded-none md:ws-rounded ws-relative">
                                        ';
    if (count($listOrder) == 0) {
        echo '                                            <div class="empty-state">
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
        echo '                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>THẺ CÀO</th>
                                                            <th>THÔNG TIN</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        ';
        foreach ($listOrder as $info) {
            echo '                                                            <tr class="ws-border-b">
                                                                <td>
                                                                    <div class="cell">
                                                                        <div>
                                                                            <p class="ws-uppercase ws-font-semibold ws-text-red-500">';
            echo $info['telco'];
            echo '</p>
                                                                            <p class="ws-text-xs">';
            echo $info['create_date'];
            echo '</p>
                                                                            <div class="ws-border-b ws-border-dashed ws-w-20 ws-my-2"></div>
                                                                            <div class="ws-text-xs ws-leading-5">
                                                                                <p class="ws-font-medium"> SERI: <span class="ws-text-zinc-900">';
            echo $info['serial'];
            echo '</span></p>
                                                                                <p class="ws-font-medium"> M.THẺ: <span class="ws-text-zinc-900">';
            echo $info['pin'];
            echo '</span></p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <div class="cell">
                                                                        <div>
                                                                            <div>';
            echo display_card($info['status']);
            echo '</div>
                                                                            <div class="ws-border-b ws-border-dashed ws-w-20 ws-my-2"></div>
                                                                            <div class="ws-leading-5">
                                                                                <p class="ws-font-medium ws-text-black"><i class="bx bxs-upvote"></i> Giá gửi: ';
            echo format_cash($info['amount']);
            echo 'đ </p>
                                                                                <p class="ws-font-medium ws-text-red-600"><i class="ws-relative ws-top-[1px] bx bxs-downvote"></i> Nhận: ';
            echo format_cash($info['price']);
            echo 'đ</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        ';
        }
        echo '                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="ws-mt-8 ws--mx-2 ws-flex ws-justify-center">
                                                <div class="el-pagination is-background">
                                                    ';
        $total = $db->num_rows('SELECT * FROM `cards` WHERE ' . $where . ' ORDER BY `id` DESC ');
        if ($sotin1trang < $total) {
            echo pagination_client('/customer/transactions?', $from, $total, $sotin1trang);
        }
        echo '                                                </div>
                                            </div>
                                        ';
    }
    echo '
                                    </div>
                                </div>
                            </div>
                            <div id="pane-second" class="el-tab-pane" role="tabpanel" aria-hidden="true" aria-labelledby="tab-second" style="display: none;">
                                <div class="ws-bg-white ws-my-1 ws-pb-2 md:ws-py-4 md:ws-px-4 ws-rounded-none md:ws-rounded ws-relative">
                                    ';
    if (count($listBank) == 0) {
        echo '                                        <div class="empty-state">
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
        echo '                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>GIAO DỊCH</th>
                                                        <th>THÔNG TIN</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    ';
        foreach ($listBank as $bank) {
            echo '                                                        <tr class="ws-border-b">
                                                            <td>
                                                                <div class="cell">
                                                                    <div>
                                                                        <p class="ws-uppercase ws-font-semibold ws-text-red-500">';
            echo $bank['trans_id'];
            echo '</p>
                                                                        <p class="ws-text-xs">20/05/2024 - 17:25:15</p>
                                                                        <div class="ws-border-b ws-border-dashed ws-w-20 ws-my-2"></div>
                                                                        <div class="ws-text-xs ws-leading-5">
                                                                            <p class="ws-font-medium"> ID: <span class="ws-text-zinc-900">';
            echo $bank['id'];
            echo '</span></p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="cell">
                                                                    <div>
                                                                        <div class="ws-leading-5">
                                                                            <p class="ws-font-medium ws-text-black ws-text-base ws-text-green-600"><span>+</span> ';
            echo format_cash($bank['amount']);
            echo 'đ </p>
                                                                        </div>
                                                                        <div class="ws-border-b ws-border-dashed ws-w-20 ws-my-2"></div>
                                                                        <p class="ws-font-medium ws-text-black ws-text-zinc-700 ws-text-xs"><i>';
            echo $bank['description'];
            echo '</i></p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    ';
        }
        echo '                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="ws-mt-8 ws--mx-2 ws-flex ws-justify-center">
                                            <div class="el-pagination is-background">
                                                ';
        $totals = $db->num_rows('SELECT * FROM `invoices` WHERE ' . $wheres . ' ORDER BY `id` DESC ');
        if ($limit < $totals) {
            echo pagination_client('/customer/transactions?', $froms, $totals, $limit);
        }
        echo '                                            </div>
                                        </div>
                                    ';
    }
    echo '                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener(\'DOMContentLoaded\', function() {
            const tabs = document.querySelectorAll(\'.el-tabs__item\');

            tabs.forEach(tab => {
                tab.addEventListener(\'click\', function() {
                    const tabId = this.getAttribute(\'id\');
                    const contentId = \'pane-\' + tabId.slice(4); // Lấy id của tab và ghép với \'pane-\'

                    // Hiển thị tab tương ứng và ẩn các tab khác
                    document.querySelectorAll(\'.el-tab-pane\').forEach(content => {
                        if (content.getAttribute(\'id\') === contentId) {
                            content.style.display = \'block\';
                        } else {
                            content.style.display = \'none\';
                        }
                    });

                    // Đánh dấu tab đang được chọn
                    document.querySelectorAll(\'.el-tabs__item\').forEach(tab => {
                        if (tab.getAttribute(\'id\') === tabId) {
                            tab.classList.add(\'is-active\');
                        } else {
                            tab.classList.remove(\'is-active\');
                        }
                    });
                });
            });
        });
    </script>

    ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
