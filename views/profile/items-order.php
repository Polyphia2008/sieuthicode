<?php
// statically decompiled from items-order.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    if (isset($_GET['code'])) {
        $code = Anti_xss($_GET['code']);
        $row = $db->get_row(' SELECT * FROM `order_items` WHERE `code` = \'' . $code . ('\' AND `user_id` = \'' . $data_user['id'] . '\' '));
        if (!$row) {
            new Redirect('/customer/orders/items');
        }
    } else {
        new Redirect('/customer/orders/items');
    }
    $title = 'Thông tin đơn hàng - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="ws-px-2 ws-mx-auto ws-max-w-7xl" style="min-height:90vh;">
    <div class="ws-mb-[2rem]" id="order">
        <div class="ws-px-2 ws-py-4 md:ws-py-12 ws-grid ws-grid-cols-12 ws-gap-2 md:ws-max-w-6xl md:ws-mx-auto">
            ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/profile/menu.php');
    echo '            <div class="ws-col-span-12 md:ws-col-span-9 ws-min-h-[70vh]">
                <div>
                    <h3 class="ws-text-xl ws-text-zinc-900 ws-mb-4">Thông tin đơn hàng</h3>
                    <div class="ws-bg-white ws-my-1 ws-pb-2 md:ws-py-4 md:ws-px-4 ws-rounded-none md:ws-rounded ws-relative">
                        <div class="ws-grid ws-grid-cols-12">
                            <div class="ws-col-span-12 md:ws-col-span-6 ws-my-2 ws-p-2">
                                <el-card class="card">
                                    <div class="px-6 pb-6">
                                        <el-form class="space-y-3">
                                            <div class="grid grid-cols-2 gap-3">
                                                <el-form-item label="Vật phẩm">
                                                    <el-input value="';
    echo $row['value'];
    echo ' ';
    echo $row['unit'];
    echo '" readonly></el-input>
                                                </el-form-item>
                                                <el-form-item label="Thanh Toán">
                                                    <el-input value="';
    echo format_cash($row['payment']);
    echo 'đ" v></el-input>
                                                </el-form-item>
                                            </div>
                                            
                                            <div class="grid grid-cols-2 gap-3">
                                                <el-form-item label="Ngày Mua">
                                                    <el-input value="';
    echo $row['created_at'];
    echo '" readonly></el-input>
                                                </el-form-item>
                                                <el-form-item label="Ngày Cập Nhật">
                                                    <el-input value="';
    echo $row['updated_at'];
    echo '" readonly></el-input>
                                                </el-form-item>
                                            </div>
                                            <div class="text-center">
                                                Trạng thái: ';
    echo display_service($row['status']);
    echo '                                            </div>
                                        </el-form>
                                    </div>
                                </el-card>
                            </div>
                            <div class="ws-col-span-12 md:ws-col-span-6 ws-my-2 ws-p-2">
                                <el-card class="card">
                                    <div class="px-6 pb-6">
                                        <el-form class="space-y-3">
                                            <div class="grid grid-cols-2 gap-3">
                                                <el-form-item label="Tài khoản">
                                                    <el-input value="';
    echo decodecryptData($row['input_user']);
    echo '" readonly>
                                                        <template #append>
                                                            <i @click="copyToClipboard(\'';
    echo decodecryptData($row['input_user']);
    echo '\')" class="bx bx-copy" role="button"></i>
                                                        </template>
                                                    </el-input>
                                                </el-form-item>

                                                <el-form-item label="Mật Khẩu">
                                                    <el-input value="';
    echo decodecryptData($row['input_pass']);
    echo '" readonly>
                                                        <template #append>
                                                            <i @click="copyToClipboard(\'';
    echo decodecryptData($row['input_pass']);
    echo '\')" class="bx bx-copy" role="button"></i>
                                                        </template>
                                                    </el-input>
                                                </el-form-item>
                                                <el-form-item label="Thông tin khác">
                                                    <el-input value="';
    echo $row['input_extra'];
    echo '" readonly>

                                                    </el-input>
                                                </el-form-item>
                                            </div>
                                            <el-form-item label="Ghi Chú">
                                                <el-input type="textarea" value="';
    echo $row['order_note'];
    echo '" readonly></el-input>
                                            </el-form-item>
                                        </el-form>
                                    </div>
                                </el-card>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const orderservice = Vue.createApp({
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

        orderservice.use(ElementPlus);
        orderservice.mount(\'#order\');
    </script>
    ';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
