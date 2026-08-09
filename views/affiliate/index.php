<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Thống kê hoa hồng - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
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
<div class="ws-mb-[2rem]" id="aff">
    <div class="ws-my-4 ws--mx-2 md:ws--mx-0 md:ws-rounded">
        <div class="ws-grid ws-grid-cols-12 ws-gap-x-4 ws-gap-y-0 ws-p-3">
            <div class="ws-col-span-12 md:ws-col-span-6 ws-bg-white ws-p-3">

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
                        <p>Tỷ lệ hoa hồng</p>
                        <h5>';
    echo $db->site('ck_ref');
    echo '%</h5>
                    </div>
                    <div class="wallet-card">
                        <p>Tổng số tiền hoa hồng đã nhận</p>
                        <h5>';
    echo format_cash($data_user['ref_total_money']);
    echo 'đ</h5>
                    </div>
                    <div class="wallet-card">
                        <p>Thành viên đã giới thiệu</p>
                        <h5>';
    echo format_cash($db->get_row(' SELECT COUNT(id) FROM `users` WHERE `ref_id` = \'' . $data_user['id'] . '\' ')['COUNT(id)']);
    echo '</h5>
                    </div>
                </div>
                <div class="ws--mt-3 ws-flex ws-justify-between">
                    <a href="/affiliate/history" class="ws-text-red-600 hover:text-red-700">Xem lịch sử hoa hồng</a>
                    <a href="/affiliate/withdraw" class="ws-text-red-600 hover:text-red-700">Rút hoa hồng về ngân hàng</a>
                </div>

            </div>

            <div class="ws-col-span-12 md:ws-col-span-6 ws-bg-white ws-p-3">
                <label class="ws-font-semibold ws-block ws-text-black ws-mb-2">Liên kết giới thiệu của bạn</label>
                <el-form-item>
                    <el-input value="';
    echo DOMAIN;
    echo '/reffer/';
    echo $data_user['id'];
    echo '" readonly>
                        <template #append>
                            <i @click="copyToClipboard(\'';
    echo DOMAIN;
    echo '/reffer/';
    echo $data_user['id'];
    echo '\')" class="bx bx-copy" role="button"></i>
                        </template>
                    </el-input>
                </el-form-item>

                <div class="ws-flex ws-justify-center ws-mt-3">
                    <ul class="footer-social ws-flex space-x-4">
                        <li><a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=';
    echo DOMAIN;
    echo '/reffer/';
    echo $data_user['id'];
    echo '" class="text-blue-600"><i class="bx bxl-facebook"></i></a></li>
                        <li><a target="_blank" href="https://twitter.com/intent/tweet?url=';
    echo DOMAIN;
    echo '/reffer/';
    echo $data_user['id'];
    echo '" class="text-blue-400"><i class="bx bxl-twitter"></i></a></li>
                        <li><a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url=';
    echo DOMAIN;
    echo '/reffer/';
    echo $data_user['id'];
    echo '" class="text-blue-700"><i class="bx bxl-linkedin"></i></a></li>
                        <li><a target="_blank" href="https://www.instagram.com/?url=';
    echo DOMAIN;
    echo '/reffer/';
    echo $data_user['id'];
    echo '" class="text-pink-600"><i class="bx bxl-instagram"></i></a></li>
                    </ul>
                </div>
                <div class="mt-3">
                    ';
    echo $db->site('notice_ref');
    echo '                </div>

            </div>
        </div>
    </div>
</div>
<script>
    const aff = Vue.createApp({
        setup() {
            const copyToClipboard = (text) => {
                navigator.clipboard.writeText(text).then(() => {
                    showMessage(\'Đã sao chép thành công: \' + text, \'success\');
                });
            };
            return {
                copyToClipboard
            };
        },
    });

    aff.use(ElementPlus);
    aff.mount(\'#aff\');
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
