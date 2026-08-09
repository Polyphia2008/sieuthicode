<?php
// statically decompiled from faq.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$title = 'Câu hỏi thường gặp  - ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
echo '<div class="ws-px-2 ws-mx-auto ws-max-w-7xl" style="min-height:90vh;">
    <div class="ws-mb-[2rem]">
        <div style="font-family: \'Roboto Condensed\', sans-serif;">
            <div class="ws-max-w-6xl ws-mx-auto ws-px-2 ws-my-4">
                <h2 class="ws-mb-2 ws-font-semibold ws-text-2xl">CÂU HỎI THƯỜNG GẶP</h2>
                <div class="ws-bg-white ws-p-3">
                    ';
echo $db->site('faq');
echo '                </div>
            </div>
        </div>
    </div>
    ';
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/footer.php');
