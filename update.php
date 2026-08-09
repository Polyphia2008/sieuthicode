<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
$whitelist = ['127.0.0.1', '::1'];
$arrContextOptions = ['ssl' => ['verify_peer' => false, 'verify_peer_name' => false]];
if (in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
    exit('Localhost không thể sử dụng chức năng này');
} else {
    if ($db->site('status_update') == 1) {
        if ($config['version'] != file_get_contents('https://server.sieuthicode.net/version.php?version=SHOPNICK')) {
            define('filename', 'update_' . random('ABC123456789', 6) . '.zip');
            define('serverfile', 'https://server.sieuthicode.net/Xc232aAsasdd2gpclone7v89b85398zZxvpoam29n2sj.zip');
            file_put_contents(filename, file_get_contents(serverfile));
            $file = filename;
            $path = pathinfo(realpath($file), PATHINFO_DIRNAME);
            $zip = new ZipArchive();
            $res = $zip->open($file);
            if ($res === true) {
                $zip->extractTo($path);
                $zip->close();
                unlink(filename);
                $query = file_get_contents(DOMAIN . '/install.php', false, stream_context_create($arrContextOptions));
                if ($query) {
                    unlink('install.php');
                }
                $file = fopen('update.txt', 'a');
                if ($file) {
                    $data = '[UPDATE] Phiên cập nhật phiên bản gần nhất vào lúc ' . gettime() . PHP_EOL;
                    fwrite($file, $data);
                    fclose($file);
                }
                exit('Cập nhật thành công!');
            } else {
                exit('Cập nhật thất bại!');
            }
        } else {
            exit('Không có phiên bản mới nhất');
        }
    } else {
        exit('Chức năng cập nhật tự động đang được tắt');
    }
}
