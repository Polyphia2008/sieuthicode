<?php
// statically decompiled from withdraw.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Rút tiền - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    $sotin1trang = 7;
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
    if (!empty($_GET['tid'])) {
        $tid = Anti_xss($_GET['tid']);
        $where .= ' AND `trans_id` LIKE "%' . $tid . '%" ';
    }
    $createdate = '';
    if (!empty($_GET['time'])) {
        $createdate = Anti_xss($_GET['time']);
        $create_date_1 = $row;
        $create_date_1 = explode(' to ', $create_date_1);
        if ($create_date_1[0] != $create_date_1[1]) {
            $create_date_1 = [$create_date_1[0] . ' 00:00:00', $create_date_1[1] . ' 23:59:59'];
            $where .= ' AND `create_gettime` >= \'' . $create_date_1[0] . '\' AND `create_gettime` <= \'' . $create_date_1[1] . '\' ';
        }
    }
    $listOrder = $db->get_list('SELECT * FROM `withdraw_ref` WHERE ' . $where . ' ORDER BY `id` DESC LIMIT ' . $from . ',' . $sotin1trang . ' ');
    echo '<style>
    .wallet-card-group {
        display: -ms-grid;
        display: grid;
        grid-gap: 20px;
        -ms-grid-columns: (minmax(200px, 1fr)) [ auto-fit];
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        -ms-grid-rows: auto;
        grid-template-rows: auto;
    }

    .wallet-card {
        padding: 25px;
        border-radius: 8px;
        background: #f5f5f5;
        transition: all linear .3s;
        -webkit-transition: all linear .3s;
        -moz-transition: all linear .3s;
        -ms-transition: all linear .3s;
        -o-transition: all linear .3s;
    }

    .footer-social li {
        display: inline-block;
        margin-right: 10px;
    }

    .footer-social li a {
        display: block;
        width: 40px;
        height: 40px;
        line-height: 40px;
        text-align: center;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .footer-social li a i {
        color: #007ea8;
        font-size: 20px;
        line-height: 40px;
    }
</style>
<div class="ws-px-2 ws-mx-auto ws-max-w-7xl" style="min-height:90vh;">
<div class="ws-mb-[2rem]" id="withdraw">
    <div class="ws-my-4 ws--mx-2 md:ws--mx-0 md:ws-rounded">
        <div class="ws-grid ws-grid-cols-12 ws-gap-x-4 ws-gap-y-0 ws-p-3">
            <div class="ws-col-span-12 md:ws-col-span-6 ws-bg-white ws-p-3 ws-mb-3">

                <label class="ws-font-semibold ws-block ws-text-black ws-mb-2">Rút số dư hoa hồng</label>
                <form class="el-form el-form--default el-form--label-top">
                    <div class="el-form-item is-required asterisk-left">
                        <label class="el-form-item__label">Ngân hàng</label>
                        <el-select v-model="bank" placeholder="Chọn ngân hàng" size="large">
                            ';
    foreach (explode(PHP_EOL, $db->site('listbank_ref')) as $bank) {
        echo '                                <el-option label="';
        echo $bank;
        echo '" value="';
        echo $bank;
        echo '"></el-option>
                            ';
    }
    echo '                        </el-select>
                    </div>
                    <div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Số tài khoản</label>
                            <el-input v-model="input_stk" type="text" size="large" placeholder="Nhập số tài khoản"></el-input>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Tên chủ tài khoản</label>
                            <el-input v-model="input_name" type="text" size="large" placeholder="Nhập tên chủ tài khoản"></el-input>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Số tiền cần rút</label>
                            <el-input v-model="input_amount" type="number" size="large" placeholder="Nhập số tiền cần rút"></el-input>
                        </div>

                    </div>
                    <div class="el-form-item asterisk-left">
                        <label class="ws-font-semibold ws-block ws-text-black ws-mb-2">Số tiền rút tối thiểu là : <b class="ws-text-red-500 ws-font-extrabold">';
    echo format_cash($db->site('minrut_ref'));
    echo 'đ</b></label>
                    </div>
                    <button aria-disabled="false" id="btnWithdraw" @click="withdraw" type="button" class="el-button el-button--primary el-button--large ws-font-extrabold">XÁC NHẬN</button>
                </form>

            </div>

            <div class="ws-col-span-12 md:ws-col-span-6 ws-bg-white ws-p-3 ws-mb-3">
                <label class="ws-font-semibold ws-block ws-text-black ws-mb-2">Thống kê của bạn</label>
                <label class="ws-font-semibold ws-block ws-text-black ws-mb-2">Hoa hồng khả dụng : <b class="ws-text-red-500 ws-font-extrabold">';
    echo format_cash($data_user['ref_money']);
    echo 'đ</b></label>
                <div class="wallet-card-group">
                    <div class="wallet-card">
                        <p>Số lần nhấp vào liên kết</p>
                        <h5>';
    echo format_cash($data_user['ref_click']);
    echo '</h5>
                    </div>
                    <div class="wallet-card">
                        <p>Tổng số tiền hoa hồng đã nhận</p>
                        <h5>';
    echo format_cash($data_user['ref_total_money']);
    echo 'đ</h5>
                    </div>
                </div>

            </div>
            <div class="ws-col-span-12 md:ws-col-span-12 ws-bg-white ws-p-3">
                <div class="overflow-auto">
                    <form action="" method="GET">
                        <div class="mb-5 ws-flex ws-justify-between ws-mb-2">
                            <label class="ws-font-semibold ws-block ws-text-black ws-mb-2 ws-hidden  md:ws-block">Lịch sử rút tiền</label>
                            <div class="ltr:ml-auto rtl:mr-auto ws-flex gap-x-2">
                            <input placeholder="Mã GD" type="text" name="tid" value="';
    echo $tid;
    echo '" class="ant-input css-eq3tly" style="margin-right:7px">
                                <input placeholder="Lý do" type="text" name="content" value="';
    echo $content;
    echo '" class="ant-input css-eq3tly ws--mx-2">
                                <button type="submit" class="el-button el-button--primary ws-ml-2"><i class="bx bx-search"></i></button>
                                <a href="/affiliate/withdraw" class="el-button el-button--info ws-ml-2" role="button"><i class="bx bx-x"></i>
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
                                                    <tr>
                                                        <th class="ant-table-cell">Mã giao dịch</th>
                                                        <th class="ant-table-cell">Thông tin</th>
                                                        <th class="ant-table-cell">Số tiền</th>
                                                        <th class="ant-table-cell">Nội dung</th>
                                                        <th class="ant-table-cell">Trạng thái</th>
                                                        <th class="ant-table-cell">Thời Gian</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="ant-table-tbody">
                                                    ';
    if (count($listOrder) == 0) {
        echo '                                                        <tr class="ant-table-placeholder">
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
        echo '                                                        ';
        foreach ($listOrder as $row) {
            echo '                                                            <tr class="ant-table-row ant-table-row-level-0">
                                                            <tr>
                                                                <td>';
            echo $row['trans_id'];
            echo '</td>
                                                                <td>
                                                                    <ul>
                                                                        <li>Ngân hàng: ';
            echo $row['bank'];
            echo '</li>
                                                                        <li>Số tài khoản: ';
            echo $row['stk'];
            echo '</li>
                                                                        <li>Chủ tài khoản: ';
            echo $row['name'];
            echo '</li>
                                                                    </ul>
                                                                </td>
                                                                <td><b style="color:red">';
            echo format_cash($row['amount']);
            echo '</b></td>
                                                                <td>
                                                                    <div class="el-textarea el-input--large">
                                                                        <textarea class="el-textarea__inner" rows="2" tabindex="0" autocomplete="off">';
            echo $row['reason'];
            echo '</textarea>
                                                                    </div>
                                                                </td>
                                                                <td>';
            echo status_withdraw_orders($row['status']);
            echo '</td>
                                                                <td>';
            echo $row['update_gettime'];
            echo '</td>
                                                            </tr>


                                                            </tr>
                                                        ';
        }
        echo '                                                    ';
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
    $total = $db->num_rows('SELECT * FROM `withdraw_ref` WHERE ' . $where . ' ORDER BY `id` DESC ');
    if ($sotin1trang < $total) {
        echo pagination_client('/affiliate/withdraw?tid=' . $tid . '&time=' . $createdate . '&', $from, $total, $sotin1trang);
    }
    echo '                </div>
            </div>
        </div>
    </div>
</div>
<script>
    const {


    } = ElementPlus;

    const withdraw = Vue.createApp({
        data() {
            return {
                bank: \'\',
                input_stk: \'\',
                input_name: \'\',
                input_amount: \'\',
                input_extra: \'\'
            };
        },
        methods: {
            withdraw() {
                $(\'#btnWithdraw\').html(\'<i class="bx bx-loader ws-mr-1 ws-mt-1"></i> Đang xử lý...\').prop(\'disabled\',
                    true);
                $.ajax({
                    url: "/model/withdraw",
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        csrf_token: csrf_token,
                        stk: this.input_stk,
                        name: this.input_name,
                        amount: this.input_amount,
                        bank: this.bank
                    },
                    success: function(respone) {
                        if (respone.status == \'success\') {
                            showMessage(respone.msg, \'success\');
                            setTimeout("location.href = \'\';", 1000);
                        } else {
                            showMessage(respone.msg, \'error\');
                        }
                        $(\'#btnWithdraw\').html(\'XÁC NHẬN\').prop(\'disabled\', false);
                    },
                    error: function() {
                        showMessage(\'Không thể xử lý\', \'error\');
                        $(\'#btnWithdraw\').html(\'XÁC NHẬN\').prop(\'disabled\', false);
                    }

                });
            },
        },
        mounted() {

        }
    });

    withdraw.use(ElementPlus);
    withdraw.mount(\'#withdraw\');
</script>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
