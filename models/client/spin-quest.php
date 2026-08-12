<?php
// statically decompiled from spin-quest.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']) {
        exit(jsonMsg('error', 'Invalid CSRF Protection Token'));
    } else {
        if ($user) {
            if (isset($_POST['action']) && $_POST['action'] == 'play') {
                try {
                    if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
                        exit(JsonMsg('error', 'Vui lòng chọn vòng quay'));
                    } else {
                        $id = Anti_xss($_POST['id']);
                        if (empty($id)) {
                            exit(JsonMsg('error', 'Vui lòng chọn vòng quay'));
                        } else {
                            if ($db->site('status_demo') != 0) {
                                exit(JsonMsg('error', 'Đây là trang web demo bạn không thể thực hiện chức năng này !'));
                            } else {
                                $spinQuest = $db->get_row('SELECT * FROM `spin_quests` WHERE `id` = \'' . $id . '\' AND `status` = 1');
                                if (!$spinQuest) {
                                    exit(JsonMsg('error', 'Vòng quay không tồn tại'));
                                } else {
                                    $getUser = $db->get_row('SELECT * FROM `users` WHERE `id` = \'' . $data_user['id'] . '\' AND `banned` = 0');
                                    if (!$getUser) {
                                        exit(JsonMsg('error', 'Người dùng không tồn tại hoặc đã bị cấm.'));
                                    } else {
                                        if (canPlay($spinQuest) !== true) {
                                            exit(JsonMsg('error', 'Trò chơi đang bảo trì, vui lòng thử lại sau'));
                                        } else {
                                            $result = playGame($spinQuest);
                                            if ($result['data'] === null && $result['location'] === null) {
                                                exit(JsonMsg('error', 'Trò chơi đang bảo trì, vui lòng thử lại sau'));
                                            } else {
                                                $total = $spinQuest['price'] - $spinQuest['price'] * $spinQuest['sale'] / 100;
                                                if (!is_numeric($total) || $total < 0) {
                                                    exit(JsonMsg('error', 'Dữ liệu không hợp lệ'));
                                                } else {
                                                    if ($data_user['money'] < $total) {
                                                        exit(JsonMsg('error', 'Số dư của bạn không đủ ' . format_cash($total) . 'đ, vui lòng nạp thêm để thực hiện'));
                                                    } else {
                                                        $isMoney = RemoveCredits($data_user['id'], $total, 'Chơi vòng quay #' . $spinQuest['name']);
                                                        if ($isMoney) {
                                                            if (getRowRealtime('users', $data_user['id'], 'money') < -500) {
                                                                Banned($data_user['id'], 'Gian lận khi chơi minigame');
                                                                exit(JsonMsg('error', 'Bạn đã bị khoá tài khoản vì gian lận'));
                                                            } else {
                                                                $coin = $result['data']['value'];
                                                                $text = $result['data']['text'];
                                                                if (strpos((string) $coin, ',') !== false) {
                                                                    $data_array = array_map('intval', explode(',', $coin));
                                                                    $coin = rand(min($data_array), max($data_array));
                                                                } else {
                                                                    $coin = (int) $coin;
                                                                }
                                                                $content = $text;
                                                                $trans_id = strtoupper('GD' . substr(md5(uniqid(mt_rand(), true)), 0, 16));
                                                                $date = gettime();
                                                                $db->query('INSERT INTO `spin_quest_logs`(`trans_id`, `prize`, `price`, `status`,`spin_id`,`name_spin`,`content`, `user_id`,`username`, `spin_quest_id`, `created_at`,`updated_at`) VALUES (\'' . $trans_id . '\', \'' . $result['data']['value'] . '\', \'' . $total . '\', \'Completed\',\'' . $spinQuest['id'] . '\',\'' . $spinQuest['name'] . '\' ,\'' . $content . '\', \'' . $data_user['id'] . '\',\'' . $data_user['username'] . '\', \'' . $id . '\', \'' . $date . '\',\'' . $date . '\')');
                                                                $db->query('UPDATE `users` SET `coin` = `coin` + ' . $coin . ' WHERE `username` = \'' . $data_user['username'] . '\'');
                                                                $db->query('UPDATE `spin_quests` SET `played` = `played` + 1 WHERE `id` = \'' . $id . '\'');
                                                                exit(json_encode(['status' => 'success', 'msg' => $content, 'location' => $result['location'] ?? null]));
                                                            }
                                                        } else {
                                                            exit(JsonMsg('error', 'Đã xảy ra lỗi, vui lòng liên hệ admin'));
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    exit(JsonMsg('error', 'Đã xảy ra lỗi ngoại lệ'));
                }
            } else {
                if (isset($_POST['action']) && $_POST['action'] == 'try') {
                    $mode = isset($_POST['mode']) ? $_POST['mode'] : 'trial';
                    try {
                        if (!isset($_POST['id']) || !is_numeric($_POST['id'])) {
                            exit(JsonMsg('error', 'Vui lòng chọn vòng quay'));
                        } else {
                            $id = Anti_xss($_POST['id']);
                            $unit = $db->site('unit') ?? null;
                            if (empty($id)) {
                                exit(JsonMsg('error', 'Vui lòng chọn vòng quay'));
                            } else {
                                $spinQuest = $db->get_row('SELECT * FROM `spin_quests` WHERE `id` = \'' . $id . '\' AND `status` = 1');
                                if (!$spinQuest) {
                                    exit(JsonMsg('error', 'Vòng quay không tồn tại'));
                                } else {
                                    $getUser = $db->get_row('SELECT * FROM `users` WHERE `id` = \'' . $data_user['id'] . '\' AND `banned` = 0');
                                    if (!$getUser) {
                                        exit(JsonMsg('error', 'Người dùng không tồn tại hoặc đã bị cấm.'));
                                    } else {
                                        if (canPlay($spinQuest) !== true) {
                                            exit(JsonMsg('error', 'Trò chơi đang bảo trì, vui lòng thử lại sau'));
                                        } else {
                                            $result = tryGame($spinQuest);
                                            if ($result['data'] === null && $result['location'] === null) {
                                                exit(JsonMsg('error', 'Trò chơi đang bảo trì, vui lòng thử lại sau'));
                                            } else {
                                                $coin = $result['data']['value'];
                                                $text = $result['data']['text'];
                                                if (strpos((string) $coin, ',') !== false) {
                                                    $data_array = array_map('intval', explode(',', $coin));
                                                    $coin = rand(min($data_array), max($data_array));
                                                } else {
                                                    $coin = (int) $coin;
                                                }
                                                $content = '( Quay Thử ): ' . $text . '!';
                                                exit(json_encode(['status' => 'success', 'msg' => $content, 'location' => $result['location'] ?? null]));
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } catch (Exception $e) {
                        exit(JsonMsg('error', 'Đã xảy ra lỗi ngoại lệ'));
                    }
                }
            }
        } else {
            exit(JsonMsg('error', 'Vui lòng đăng nhập để thực hiện'));
        }
    }
}
