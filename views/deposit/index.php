<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Thông tin tài khoản - ' . $db->site('title');
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div class="breadCrumbs">
    <div class="screen">
        <div class="center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                <li class="breadcrumb-item active"><a class="text-decoration-none" href=""><span>Nạp tiền</span></a></li>
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
                        <a href="account/bang-dieu-huong"><img src="/assets/images/sidebar_arrow_right.svg" alt=""></a>
                        <h2 class="title-account">Nạp tiền</h2>
                    </div>
                </div>
                <div class="content-account header-desktop-account">
                    <h2 class="title-account">Nạp tiền</h2>
                    <hr>
                </div>
                <div class="content-account">
                    <div class="component-tabs">
                        <ul class="nav nav-nguyennhieu nav-naptien" id="custom-tabs-three-tab-lang" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="tabs-lang" data-toggle="pill" href="#tabs-lang-vi"
                                    role="tab" aria-controls="tabs-lang-vi" aria-selected="true">Nạp thẻ cào</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="tabs-lang" data-toggle="pill" href="#tabs-lang-en" role="tab"
                                    aria-controls="tabs-lang-en" aria-selected="true">ATM/MOMO tự động</a>
                            </li>
                        </ul>
                        <div class="tab-content my-4" id="custom-tabs-three-tabContent-lang">
                            <div class="tab-pane fade show active" id="tabs-lang-vi" role="tabpanel"
                                aria-labelledby="tabs-lang">
                                <form class="container-napthe validation-form" novalidate id="submit-napthe" enctype="multipart/form-data">
                                    <div class="napthe1">
                                        <div class="group-input input-selectize">
                                            <div class="input-element">
                                                <select id="select-nhacungcap" name="telco" class="select-component" data-max="1" data-search="false"
                                                    multiple="multiple" placeholder="Chọn nhà cung cấp..." required>
                                                    <option value="VIETTEL">VIETTEL</option>
                                                    <option value="VINAPHONE">VINAPHONE</option>
                                                    <option value="MOBIFONE">MOBIFONE</option>
                                                    <option value="ZING">ZING</option>
                                                    <option value="GARENA">GARENA</option>
                                                    <option value="VCOIN">VCOIN</option>
                                                    <option value="GATE">GATE</option>
                                                </select>
                                            </div>
                                            <label for="select-nhacungcap" class="text-dark font-weight-bold">Nhà cung cấp</label>
                                        </div>
                                        <div class="group-input input-text">
                                            <div class="input-element">
                                                <input type="hidden" class="input-text" name="csrf_token" value="';
    echo generate_csrf_token();
    echo '" required>
                                                <input name="pin" type="text" id="input-mathe" placeholder="Nhập mã số thẻ của bạn" required>
                                            </div>
                                            <label for="input-mathe" class="text-dark font-weight-bold">Mã số thẻ</label>
                                        </div>
                                        <div class="group-input input-text">
                                            <div class="input-element">
                                                <input name="serial" type="text" id="input-seri" placeholder="Nhập mã số se ri của bạn" required>
                                            </div>
                                            <label for="input-seri" class="text-dark font-weight-bold">Số seri</label>
                                        </div>
                                        <div class="group-input input-text">
                                            <div class="input-element d-flex align-items-center gap-2">
                                                <input name="captcha" type="text" placeholder="Nhập mã bảo vệ" required>
                                                <img src="/model/captcha" alt="Captcha" id="captcha-image" style="height: 25px; border: 1px solid #ccc; border-radius: 5px;">
                                                <i class="reload-icon" id="reload-captcha" style="cursor: pointer; font-size: 24px;">🔄</i>
                                            </div>
                                            <label for="input-seri" class="text-dark font-weight-bold">Mã bảo vệ</label>
                                        </div>
                                    </div>
                                    <div class="napthe2">
                                        <div class="group-input input-text">
                                            <div class="input-radio-menhgia">
                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(10000 - 10000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia258" value="10000" required>
                                                    <label for="radio-menhgia258">
                                                        <h4><b>10,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(20000 - 20000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia259" value="20000" required>
                                                    <label for="radio-menhgia259">
                                                        <h4><b>20,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(30000 - 30000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia260" value="30000" required>
                                                    <label for="radio-menhgia260">
                                                        <h4><b>30,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(50000 - 50000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia261" value="50000" required>
                                                    <label for="radio-menhgia261">
                                                        <h4><b>50,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(100000 - 100000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia262" value="100000" required>
                                                    <label for="radio-menhgia262">
                                                        <h4><b>100,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(200000 - 200000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia263" value="200000" required>
                                                    <label for="radio-menhgia263">
                                                        <h4><b>200,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(500000 - 500000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia265" value="500000" required>
                                                    <label for="radio-menhgia265">
                                                        <h4><b>500,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(1000000 - 1000000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia266" value="1000000" required>
                                                    <label for="radio-menhgia266">
                                                        <h4><b>1,000,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                                <div class="container-radio-menhgia">
                                                    <input type="radio" data-mota="Nhận ';
    echo format_cash(300000 - 300000 * $db->site('card_ck') / 100);
    echo ' vào số dư tài khoản" class="radio-menhgia" name="amount" id="radio-menhgia264" value="300000" required>
                                                    <label for="radio-menhgia264">
                                                        <h4><b>300,000</b></h4>
                                                        <p><b>Nhận ';
    echo 100 - $db->site('card_ck');
    echo '%</b></p>
                                                    </label>
                                                </div>

                                            </div>
                                            <label class="text-dark font-weight-bold">Chọn mệnh giá</label>
                                        </div>
                                    </div>
                                    <div class="note-menhgiathe" style="grid-column: 1 / 3;">
                                        <p class="mota-menhgia mb-2"></p>
                                        <p class="text-danger">*Chú ý: Nạp thẻ sai mệnh giá mất 50% giá trị thẻ.</p>
                                    </div>
                                    <div class="grid grid-2 mt-5 gap-10 container-grid13" style="grid-column: 1 / 3;">
                                        <a class="default-button-sub" href="/customer/profile">Trở về</a>
                                        <button class="default-button" type="submit">Nạp thẻ</button>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane fade show" id="tabs-lang-en" role="tabpanel" aria-labelledby="tabs-lang">
                                <div class="container-naptien">
                                    <div class="naptien1">
                                        <div class="group-input input-text">
                                            <div class="input-radio-banking" id="bank-options">

                                            </div>
                                            <label class="text-dark font-weight-bold">Chọn ngân hàng</label>
                                        </div>
                                        <div class="bank-naptien" id="bank-naptien">
                                            <p><span>Số tài khoản:</span> <strong class="text-info" id="account-number"></strong>
                                                <span class="ml-3 copy copyButton" id="copy-account" data-text=""><i class="far fa-copy"></i></span>
                                            </p>
                                            <p><span>Chủ tài khoản:</span> <strong class="text-info" id="account-name"></strong></p>
                                            <p><span>Ngân hàng:</span> <strong class="text-info" id="bank-name"></strong></p>
                                            <p><span>Nội dung chuyển khoản:</span> <strong class="text-success" id="transfer-content"></strong>
                                                <span class="ml-3 copy copyButton" id="copy-transfer" data-text=""><i class="far fa-copy"></i></span>
                                            </p>
                                            <p>Vui lòng chuyển khoản đúng theo nội dung được hướng dẫn.</p>
                                        </div>

                                    </div>
                                    <div class="naptien2" id="naptien2">
                                        <div class="group-input input-text">
                                            <label class="text-dark font-weight-bold">Quét QR để thao tác nhanh</label>
                                            <img class="qr-code-naptien" id="qr-code" src="" alt="QR Code">
                                        </div>
                                    </div>
                                    <div class="note-menhgiathe">
                                        <div class="mt-3" id="content"></div>
                                        <p class="text-danger mt-3">*Chú ý: Kiểm tra kỹ trước khi chuyển khoản. Sai nội dung, sai tài khoản không được hoàn trả.</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById(\'reload-captcha\').addEventListener(\'click\', function() {
        document.getElementById(\'captcha-image\').src = \'/model/captcha?\' + Date.now();
    });
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
