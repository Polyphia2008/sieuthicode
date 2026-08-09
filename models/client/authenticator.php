<?php
// statically decompiled from authenticator.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($_POST['type'] == 'ChangeGoogle2FA') {
        if ($user) {
            $getUser = $db->get_row(' SELECT * FROM `users` WHERE `username` = \'' . Anti_xss($data_user['username']) . '\' AND `banned` = \'0\' ');
            if (!$getUser) {
                exit(JsonMsg('error', 'Tài khoản này đã bị khóa bởi BQT!'));
            } else {
                if (empty($_POST['secret'])) {
                    exit(JsonMsg('error', 'Vui lòng nhập mã xác minh 2FA!'));
                } else {
                    $google2fa = new PragmaRX\\Google2FA\\Google2FA();
                    if (!$google2fa->verifyKey($getUser['secretkey'], Anti_xss($_POST['secret']))) {
                        exit(JsonMsg('error', 'Mã xác minh không chính xác!'));
                    } else {
                        $isUpdate = $db->update('users', ['status_2fa' => $data_user['status_2fa'] == 1 ? 0 : 1], ' `id` = \'' . Anti_xss($getUser['id']) . '\' ');
                        exit(JsonMsg('success', 'Lưu thành công'));
                    }
                }
            }
        } else {
            exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
        }
    } else {
        if ($_POST['type'] == 'VerifyGoogle2FA') {
            if (empty($_POST['token'])) {
                exit(JsonMsg('error', 'Vui lòng đăng nhập !'));
            } else {
                $token = Anti_xss($_POST['token']);
                $getUser = $db->get_row(' SELECT * FROM `users` WHERE `token` = \'' . $token . '\' AND `banned` = \'0\' ');
                if (!$getUser) {
                    exit(JsonMsg('error', 'Vui lòng đăng nhập!'));
                } else {
                    if (empty($_POST['code'])) {
                        exit(JsonMsg('error', 'Vui lòng nhập mã xác minh!'));
                    } else {
                        $google2fa = new PragmaRX\\Google2FA\\Google2FA();
                        if (!$google2fa->verifyKey($getUser['secretkey'], Anti_xss($_POST['code']))) {
                            insert_log($getUser['id'], '[Warning] Phát hiện có người đang cố gắng nhập mã xác minh');
                            exit(JsonMsg('error', 'Mã xác minh không chính xác!'));
                        } else {
                            insert_log($getUser['id'], 'Đăng nhập vào hệ thống bằng phương thức tài khoản');
                            $db->update('users', ['login_attempts' => 0], ' `id` = \'' . $getUser['id'] . '\' ');
                            $session->send($getUser['username']);
                            exit(JsonMsg('success', 'Đăng nhập thành công'));
                        }
                    }
                }
            }
        }
    }
}
