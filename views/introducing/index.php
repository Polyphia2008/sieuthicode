<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Giới thiệu về chúng tôi';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div id="bre-nickgame">
    <div class="breadCrumbs">
        <div class="screen">
            <div class="center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="/"><span>Trang chủ</span></a></li>
                    <li class="breadcrumb-item active"><a class="text-decoration-none" href="/introducing"><span>Giới thiệu</span></a></li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="screen">
    <div class="center">
        <div class="content w-clear">
            ';
echo $db->site('notice_home');
echo '        </div>
    </div>
</div>
';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
