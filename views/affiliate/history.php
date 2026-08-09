<?php
// statically decompiled from history.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Lịch sử nhận hoa hồng - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    $sotin1trang = 12;
    if (isset($_GET['page'])) {
        $page = Anti_xss($_GET['page']);
    } else {
        $page = 3;
    }
    $from = ($page - 1) * $sotin1trang;
    $where = ' `id` > 0 AND `user_id` = "' . $data_user['id'] . '"';
    $order_by = 'ORDER BY id DESC';
    $tid = '';
    $content = '';
    if (!empty($_GET['content'])) {
        $content = Anti_xss($_GET['content']);
        $where .= ' AND `reason` LIKE "%' . $content . '%" ';
    }
    $createdate = '';
    if (!empty($_GET['time'])) {
        $createdate = Anti_xss($_GET['time']);
        $create_date_1 = $total;
        $create_date_1 = explode(' to ', $create_date_1);
        if ($create_date_1[0] != $create_date_1[1]) {
            $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
            $where .= ' AND `created_at` >= \'' . $create_date_1[0] . '\' AND `created_at` <= \'' . $create_date_1[1] . '\' ';
        }
    }
    $listAff = $db->get_list('SELECT * FROM `log_ref` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
    echo '<div class="ws-px-2 ws-mx-auto ws-max-w-7xl" style="min-height:90vh;">
<div class="ws-mb-[2rem]" id="aff">
    <div class="ws-my-4 ws--mx-2 md:ws--mx-0 md:ws-rounded">
        <div class="ws-p-3 ws-bg-white">
            <div class="overflow-auto">
                <form action="" method="GET">
                    <div class="ws-mb-2 ws-flex ws-justify-between">
                    <label class="ws-font-semibold ws-block ws-text-black ws-mb-2 ws-hidden  md:ws-block">Lịch sử hoa hồng</label>
                        <div class="ltr:ml-auto rtl:mr-auto ws-flex gap-x-2">
                            <input placeholder="Lý do" type="text" name="content" value="';
    echo $content;
    echo '" class="ant-input css-eq3tly ws--mx-2">
                            <button type="submit" class="el-button el-button--primary ws-ml-2"><i class="bx bx-search"></i></button>
                            <a href="/affiliate/history" class="el-button el-button--info ws-ml-2" role="button"><i class="bx bx-x"></i>
                            </a>
                        </div>
                    </div>
                </form>
                <div class="ant-table-wrapper font-medium whitespace-nowrap css-eq3tly">
                    <div class="ant-spin-nested-loading css-eq3tly">
                        <!---->
                        <div class="ant-spin-container">
                            <!---->
                            <div class="ant-table ant-table-small">
                                <!---->
                                <div class="ant-table-container">
                                    <div class="ant-table-content">
                                        <table style="table-layout: auto;">

                                            <thead class="ant-table-thead">

                                                <th class="ant-table-cell" colstart="1" colend="1">
                                                    ID
                                                </th>
                                                <th class="ant-table-cell" colstart="2" colend="2">
                                                    Hoa hồng ban đầu
                                                </th>
                                                <th class="ant-table-cell" colstart="3" colend="3">
                                                    Hoa hồng thay đổi
                                                </th>
                                                <th class="ant-table-cell" colstart="4" colend="4">
                                                    Hoa hồng hiện tại
                                                </th>
                                                <th class="ant-table-cell" colstart="5" colend="5">
                                                    Thời gian
                                                </th>

                                                <th class="ant-table-cell" colstart="7" colend="7">
                                                    Lý do
                                                </th>
                                                </tr>
                                            </thead>
                                            <tbody class="ant-table-tbody">
                                                ';
    if (count($listAff) == 0) {
        echo '                                                    <tr class="ant-table-placeholder">
                                                        <td colspan="11" class="ant-table-cell">
                                                            <!---->
                                                            <div class="ant-empty css-eq3tly ant-empty-normal">
                                                                <div class="ant-empty-image"><svg width="64" height="41" viewBox="0 0 64 41" xmlns="http://www.w3.org/2000/svg">
                                                                        <g transform="translate(0 1)" fill="none" fill-rule="evenodd">
                                                                            <ellipse fill="#f5f5f5" cx="32" cy="33" rx="32" ry="7"></ellipse>
                                                                            <g fill-rule="nonzero" stroke="#d9d9d9">
                                                                                <path d="M55 12.76L44.854 1.258C44.367.474 43.656 0 42.907 0H21.093c-.749 0-1.46.474-1.947 1.257L9 12.761V22h46v-9.24z">
                                                                                </path>
                                                                                <path d="M41.613 15.931c0-1.605.994-2.93 2.227-2.931H55v18.137C55 33.26 53.68 35 52.05 35h-40.1C10.32 35 9 33.259 9 31.137V13h11.16c1.233 0 2.227 1.323 2.227 2.928v.022c0 1.605 1.005 2.901 2.237 2.901h14.752c1.232 0 2.237-1.308 2.237-2.913v-.007z" fill="#fafafa"></path>
                                                                            </g>
                                                                        </g>
                                                                    </svg></div>
                                                                <p class="ant-empty-description">No data</p>
                                                                <!---->
                                                            </div>
                                                            <!---->
                                                        </td>
                                                    </tr>
                                                ';
    } else {
        echo '                                                    ';
        foreach ($listAff as $aff) {
            echo '                                                        <tr class="ant-table-row ant-table-row-level-0">
                                                            <td class="ant-table-cell">
                                                                ';
            echo $aff['id'];
            echo '                                                            </td>
                                                            <td class="ant-table-cell">
                                                                ';
            echo format_cash($aff['sotientruoc']);
            echo ' ₫
                                                            </td>
                                                            <td class="ant-table-cell">
                                                                <span class="text-green-600">';
            echo format_cash($aff['sotienthaydoi']);
            echo '                                                                    ₫</span>
                                                            </td>
                                                            <td class="ant-table-cell">
                                                                ';
            echo format_cash($aff['sotienhientai']);
            echo ' ₫
                                                            </td>

                                                            <td class="ant-table-cell">
                                                                ';
            echo $aff['created_at'];
            echo '                                                            </td>
                                                            <td class="ant-table-cell">
                                                                ';
            echo $aff['reason'];
            echo '                                                            </td>


                                                        </tr>
                                                    ';
        }
        echo '                                                ';
    }
    echo '
                                            </tbody>
                                            <!---->
                                        </table>
                                    </div>
                                </div>
                                <!---->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="el-pagination is-background ws-flex ws-justify-end ws-mt-6">
                ';
    $total = $db->num_rows('SELECT * FROM `log_ref` WHERE ' . $where . ' ORDER BY `id` DESC ');
    if ($sotin1trang < $total) {
        echo pagination_client('/affiliate/history?content=' . $content . '&time=' . $createdate . '&', $from, $total, $sotin1trang);
    }
    echo '            </div>
        </div>
    </div>
</div>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
