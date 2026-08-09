<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user) {
    new Redirect('/');
    exit();
} else {
    $title = 'Sự kiện';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
    echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="/event"><span>Sự kiện</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="screen">
    <div class="center">
        <div class="container-event">
            ';
    if ($data_user['gift'] == 0) {
        echo '                <div class="event-type12">
                    <div>
                        ';
        echo $db->site('notice_event');
        echo '                    </div>
                    <form class="d-flex flex-row gap-10 validation-form" novalidate id="form-event">
                        <a class="default-button-sub" href="account">Trang chủ</a>
                        <input type="hidden" class="input-text" name="csrf_token" value="';
        echo generate_csrf_token();
        echo '" required>
                        <button class="default-button" type="submit" name="submit-event">Nhận quà ngay</button>
                    </form>
                </div>
            ';
    } else {
        echo '                <div class="event-type12">
                    <img src="/assets/images/401.png" alt="">
                    <div>
                        <p>Không đạt yêu cầu để thực hiện thao tác này!!</p>
                    </div>
                    <div class="button-back">
                        <a class="default-button" href="/">Quay về trang chủ</a>
                    </div>
                </div>
            ';
    }
    echo '        </div>
    </div>
</div>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
}
