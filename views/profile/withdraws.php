<?php
// statically decompiled from withdraws.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Rút vật phẩm - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Rút vật phẩm</span></a></li>
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
                <div class="content-account header-desktop-account">
                    <h2 class="title-account">Rút vật phẩm</h2>
                    <hr>
                </div>
                
                <div class="content-account header-mobile-account">
                    <div class="div-header">
                        <a href="/customer/profile"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Rút vật phẩm</h2>
                    </div>
                </div>
                <div class="content-account">
                    ';
    echo $db->site('notice_withdraw');
    echo '                </div>
                <div class="content-account">
      
                    <form class="validation-form mt-3" id="form-rutvatpham" novalidate enctype="multipart/form-data">
                        <div class="group-input input-selectize">
                            <div class="input-element">
                                <input type="hidden" class="input-text" name="csrf_token" value="';
    echo generate_csrf_token();
    echo '" required>
                                <select id="select-vatpham" name="vatpham" class="select-component" data-search="false" data-max="1" data-search="false"
                                    placeholder="Chọn vật phẩm muốn rút" required>
                                    <option value="0">Chọn vật phẩm rút</option>
                                    ';
    foreach ($db->get_list('SELECT * FROM `units` WHERE `status` =  1 ORDER BY `stt` ASC') as $unit) {
        $detail = json_decode($unit['detail'], true);
        echo '                                        <option value="';
        echo $unit['id'];
        echo '">';
        echo $detail['name_product'];
        echo '</option>
                                    ';
    }
    echo '                                </select>
                            </div>
                            <label for="select-vatpham" class="text-dark font-weight-bold">Chọn vật phẩm muốn rút: </label>
                        </div>
                        <div class="group-input input-text mt-3">
                            <div class="input-element">
                                <p class="vatphamhienco">Số vật phẩm hiện có: <span>';
    echo format_cash($data_user['coin']);
    echo '</span></p>
                            </div>
                        </div>

                        <div id="package-container" style="display: none;">

                        </div>

                        <div id="dynamic-fields"></div>

                        <div class="grid grid-2 mt-5 gap-10">
                            <a class="default-button-sub" href="/customer/profile">Trở về</a>
                            <button class="default-button" type="submit">Rút vật phẩm</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $(\'#select-vatpham\').change(function() {
            let selectedItem = $(this).val();
            if (!selectedItem) {
                $(\'#package-container\').hide();
                $(\'#dynamic-fields\').html(\'\');
                $(\'#submit-btn\').hide();
                return;
            }

            $.ajax({
                url: \'/model/get_item_details\',
                type: \'GET\',
                data: {
                    type: selectedItem
                },
                dataType: \'json\',
                beforeSend: function() {
                    $(\'#select-goimuonrut\').html(\'<option>Đang tải...</option>\');
                    $(\'#dynamic-fields\').html(\'\');
                    $(\'#package-container\').hide();
                    $(\'#submit-btn\').hide();
                },
                success: function(response) {
                    if (response.status === "success") {
                        // Xử lý danh sách package
                        if (response.packages.length > 0) {
                            let packageSelect = `
                                    <div class="group-input input-selectize c-px-8 align-content-end mb-2 mt-2">
                                        <div class="form-label">Chọn gói</div>
                                        <div class="input-element">
                                            <select id="select-goimuonrut" name="select-goimuonrut" class="select-component" ata-search="false" data-max="1" data-search="false">
                                                ${response.packages.map(pkg => `<option value="${pkg.id}">${pkg.name}</option>`).join(\'\')}
                                            </select>
                                        </div>
                                    </div>
                                `;
                            $(\'#package-container\').html(packageSelect).show();
                            $(\'#select-goimuonrut\').selectize({
                                maxItems: 1,
                                searchField: false
                            });
                        }

                        // Xử lý dynamic fields
                        let dynamicFields = \'\';
                        response.fields.forEach(function(field) {
                            if (field.type === \'select\') {
                                let options = field.options.map(opt => `<option value="${opt}">${opt}</option>`).join(\'\');
                                dynamicFields += `
                                <div class="group-input c-px-8 align-content-end mb-2">
                                    <div class="form-label">${field.label}</div>
                                    <div class="input-element">
                                        <select name="fields_${field.id}" class="select-component" ata-search="false" data-max="1" data-search="false">
                                            ${options}
                                        </select>
                                    </div>
                                </div>
                            `;
                            } else {
                                dynamicFields += `
                                <div class="group-input c-px-8 align-content-end mb-2">
                                    <div class="form-label">${field.label}</div>
                                    <div class="input-element">
                                        <input type="text" name="fields_${field.id}" placeholder="${field.label}" class="input-text" required>
                                    </div>
                                </div>
                            `;
                            }
                        });
                        $(\'#dynamic-fields\').html(dynamicFields);
                        $(\'#dynamic-fields select\').selectize({
                            maxItems: 1,
                            searchField: false
                        });
                        $(\'#submit-btn\').show();
                    } else {
                        // Nếu có lỗi, ẩn dữ liệu và hiển thị thông báo
                        $(\'#package-container\').hide();
                        $(\'#dynamic-fields\').html(\'\');
                        $(\'#submit-btn\').hide();
                    }
                },
                error: function() {
                    $(\'#package-container\').hide();
                    $(\'#dynamic-fields\').html(\'\');
                    $(\'#submit-btn\').hide();
                }
            });
        });
    });
</script>

';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
