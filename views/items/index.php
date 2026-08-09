<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Mua items | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div class="ws-px-2 ws-mx-auto ws-max-w-7xl" style="min-height:90vh;">
    <div class="ws-mb-[2rem] ws-bg-white" style="margin-top:20px" id="service">
        <div class="ws-max-w-6xl ws-mx-auto ws-py-10 ws-px-2">
            <div class="ws-rounded ws-pt-2 ws-mb-4"><label class="ws-text-base"><small>DỊCH VỤ</small></label>
                <h2 class="ws-block ws-font-bold ws-text-lg ws-text-red-500">';
echo $db->site('title_shop_items');
echo '</h2>
            </div>
            <div class="ws-grid ws-grid-cols-12 ws-gap-4">
                <div class="ws-col-span-12 ws-border ws-border-red-600 ws-border-dashed ws-rounded ws-py-4 ws-px-3">
                    <div class="ws-mb-4">
                        <div>
                            
                            <div class="ws-more-hidd ws-more ws-leading-7 ws-mb-4 ws-bg-white ws-rounded ws-transition-all ws-duration-200 custom-height show-more--content active">
                                ';
echo $db->site('notice_items');
echo '                            </div>
                           
                        </div>
                    </div>
                    <button id="showMoreButton" type="button" class="el-button el-button--primary el-button--small ws-relative ws-top-[-15px] ws-flex ws-justify-center">Xem thêm</button>
                    <button id="showLessButton" type="button" class="el-button el-button--primary el-button--small ws-relative ws-top-[-15px] ws-flex ws-justify-center" style="display: none;">Thu gọn</button>
                </div>
                
                <div class="ws-col-span-12 md:ws-col-span-4"><img src="';
echo $db->site('thumb_items');
echo '" class="ws-w-full ws-rounded"></div>
                <div class="ws-col-span-12 md:ws-col-span-4">
                    <form class="el-form el-form--default el-form--label-top">
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Chọn thể loại</label>
                            <el-select v-model="selectedPackage" placeholder="Chọn loại" size="large" @change="updateFactor">
                                ';
foreach ($db->get_list('SELECT * FROM `category_items` WHERE `status` = 1 ORDER BY `id` ASC') as $package) {
    echo '                                    <el-option :data-factor="';
    echo $package['factor'];
    echo '" :data-price="';
    echo $package['factor'];
    echo '" label="';
    echo $package['name'];
    echo '" value="';
    echo $package['id'];
    echo '"></el-option>
                                ';
}
echo '                            </el-select>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Hệ số</label>
                            <el-input v-model="factor" type="text" id="factor" size="large" readonly></el-input>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Nhập số tiền để mua</label>
                            <el-input v-model.number="price" type="text" size="large" placeholder="Nhập số tiền"></el-input>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Đơn vị</label>
                            <el-input :value="unit" type="text" size="large" readonly></el-input>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Thực nhận</label>
                            <el-input :value="formattedTotal" type="text" size="large" readonly></el-input>
                        </div>

                    </form>
                </div>
                <div class="ws-col-span-12 md:ws-col-span-4">
                    <form class="el-form el-form--default el-form--label-top">
                        <h2 class="ws-block ws-mb-3">THÔNG TIN NGƯỜI DÙNG</h2>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Tên tài khoản</label>
                            <el-input v-model="input_user" type="text" size="large" placeholder="Nhập tài khoản"></el-input>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Mật khẩu</label>
                            <el-input v-model="input_pass" type="text" size="large" placeholder="Nhập mật khẩu"></el-input>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">2FA / Mã dự phòng</label>
                            <el-input v-model="input_extra" type="text" size="large" placeholder="Nhập 2fa/mã dự phòng Roblox"></el-input>
                            <i><a class="ws-text-red-600" target="_blank" href="';
echo $row['link'];
echo '">Xem hướng dẫn</a></i>
                        </div>
                        <div class="el-form-item is-required asterisk-left">
                            <label class="el-form-item__label">Ghi Chú</label>
                            <el-input v-model="order_note" type="textarea" :rows="4" size="large" placeholder="Nhập ghi chú cho admin nếu có..."></el-input>
                        </div>
                    </form>
                    <button aria-disabled="false" id="btnOrder" @click="order" type="button" class="el-button el-button--primary el-button--large ws-font-extrabold"><span class="">MUA NGAY</span></button>
                </div>



            </div>

        </div>
    </div>
</div>
<script>
    const {} = ElementPlus;

    const service = Vue.createApp({
        data() {
            return {
                selectedPackage: \'\',
                factor: 0,
                price: 0,
                unit: \'\',
                input_pass: \'\',
                input_user: \'\',
                order_note: \'\',
                input_extra: \'\',
                packages: [
                    ';
foreach ($db->get_list('SELECT * FROM `category_items` WHERE `status` = 1 ORDER BY `id` ASC') as $package) {
    echo ' {
                            id: ';
    echo $package['id'];
    echo ',
                            factor: ';
    echo $package['factor'];
    echo ',
                            unit: "';
    echo addslashes($package['unit']);
    echo '"
                        },
                    ';
}
echo '                ]
            };
        },
        computed: {
            updateFactor() {
                const selected = this.packages.find(pkg => pkg.id === Number(this.selectedPackage));
                this.factor = selected ? selected.factor : 0;
                this.unit = selected ? selected.unit : 0;
                return this.factor;
            },

            formattedTotal() {
                return this.price * this.updateFactor;
            }
        },
        methods: {
            order() {
                $(\'#btnOrder\').html(\'<i class="bx bx-loader ws-mr-1 ws-mt-1"></i> Đang xử lý...\').prop(\'disabled\',
                    true);
                $.ajax({
                    url: "/model/item",
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        csrf_token: csrf_token,
                        package: this.selectedPackage,
                        price: this.price,
                        input_user: this.input_user,
                        input_pass: this.input_pass,
                        input_extra: this.input_extra,
                        order_note: this.order_note,
                        total: 0,
                    },
                    success: function(respone) {
                        if (respone.status == \'success\') {
                            showMessage(respone.msg, \'success\');
                            setTimeout("location.href = \'/customer/orders/items\';", 1000);
                        } else {
                            showMessage(respone.msg, \'error\');
                        }
                        $(\'#btnOrder\').html(\'MUA NGAY\').prop(\'disabled\', false);
                    },
                    error: function() {
                        showMessage(\'Không thể xử lý\', \'error\');
                        $(\'#btnOrder\').html(\'MUA NGAY\').prop(\'disabled\', false);
                    }

                });
            },


        },
        watch: {
            // Watch for changes in selectedPackage and price to update factor and total
            selectedPackage() {
                this.factor = this.updateFactor;
            },
            price() {
                // Trigger recalculation of formattedTotal when price changes
                this.formattedTotal;
            }
        },
        mounted() {
            // Initialize the factor when the component is mounted
            this.factor = this.updateFactor;
        }
    });

    service.use(ElementPlus);
    service.mount(\'#service\');
</script>
<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        var showMoreButton = document.getElementById(\'showMoreButton\');
        var showLessButton = document.getElementById(\'showLessButton\');
        var content = document.querySelector(\'.show-more--content\');

        showMoreButton.addEventListener(\'click\', function() {
            content.classList.remove(\'custom-height\', \'active\');
            showMoreButton.style.display = \'none\';
            showLessButton.style.display = \'inline-block\';
        });

        showLessButton.addEventListener(\'click\', function() {
            content.classList.add(\'custom-height\', \'active\');
            showLessButton.style.display = \'none\';
            showMoreButton.style.display = \'inline-block\';
        });
    });
</script>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
