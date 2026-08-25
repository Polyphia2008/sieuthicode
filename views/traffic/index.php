<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']).'/libs/init.php';if(!$user){new Redirect('/login');exit;}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SubmitProof'])) {
    $posted = (string) ($_POST['csrf_token'] ?? '');
    if (!hash_equals((string) ($_SESSION['csrf_token'] ?? ''), $posted)) {
        header('HTTP/1.1 419 Authentication Timeout', true, 419);
        exit('Invalid CSRF');
    }
    $taskId = (int) ($_POST['task_id'] ?? 0);
    $proof = trim((string) ($_POST['proof'] ?? ''));
    $sub = $db->get_row(
        "SELECT s.*,t.`wait_seconds` FROM `traffic_submissions` s"
        . " JOIN `traffic_tasks` t ON t.`id`=s.`task_id`"
        . " WHERE s.`task_id`='" . $taskId . "' AND s.`user_id`='" . (int) $data_user['id'] . "' LIMIT 1"
    );
    if (!$sub) {
        $_SESSION['traffic_error'] = 'Bạn cần bắt đầu nhiệm vụ trước';
    } elseif (time() - strtotime((string) $sub['started_at']) < (int) $sub['wait_seconds']) {
        $_SESSION['traffic_error'] = 'Bạn chưa chờ đủ thời gian yêu cầu';
    } elseif ($proof === '') {
        $_SESSION['traffic_error'] = 'Vui lòng nhập mã hoặc minh chứng';
    } elseif ($sub['status'] === 'approved') {
        $_SESSION['traffic_error'] = 'Nhiệm vụ đã được duyệt';
    } else {
        $db->update('traffic_submissions', [
            'proof' => $proof,
            'status' => 'pending',
            'submitted_at' => date('Y-m-d H:i:s'),
        ], "`id`='" . (int) $sub['id'] . "'");
        $_SESSION['traffic_success'] = 'Đã gửi minh chứng, vui lòng chờ admin duyệt';
    }
    new Redirect('/kiem-tien-online');
    exit;
}
$title='Nhiệm vụ Traffic | '.$db->site('title');require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/header.php');
$tasks=$db->get_list("SELECT t.*,s.status AS submission_status,s.proof FROM `traffic_tasks` t LEFT JOIN `traffic_submissions` s ON s.`task_id`=t.`id` AND s.`user_id`='".(int)$data_user['id']."' WHERE t.`status`=1 ORDER BY t.`id` DESC");
$history=$db->get_list("SELECT s.*,t.title FROM `traffic_submissions` s JOIN `traffic_tasks` t ON t.id=s.task_id WHERE s.user_id='".(int)$data_user['id']."' ORDER BY s.id DESC LIMIT 30");
?>
<link rel="stylesheet" href="/assets/css/traffic.css?v=<?= (int)(@filemtime(APP_ROOT.'/assets/css/traffic.css')?:1) ?>">
<nav class="earning-mobile-nav"><a href="/kiem-tien-online" class="active"><i class="fas fa-coins"></i> Traffic</a><a href="#traffic-history"><i class="fas fa-history"></i> Lịch sử</a><a href="/customer/balance"><i class="fas fa-wallet"></i> Số dư</a></nav>
<section class="screen traffic-page"><div class="center"><div class="traffic-layout"><main>
<div class="traffic-hero"><h1>🎯 Nhiệm vụ Traffic</h1><p>Hoàn thành đúng các bước và gửi mã/minh chứng để admin duyệt thưởng.</p><ol><li>Nhấn bắt đầu nhận nhiệm vụ</li><li>Tìm kiếm từ khóa theo hướng dẫn</li><li>Truy cập đúng website</li><li>Hoàn thành yêu cầu và lấy mã</li><li>Gửi minh chứng chờ duyệt</li></ol></div>
<?php if(!empty($_SESSION['traffic_error'])):?><div class="alert alert-danger"><?=htmlspecialchars($_SESSION['traffic_error'],ENT_QUOTES);unset($_SESSION['traffic_error']);?></div><?php endif;?>
<?php if(!empty($_SESSION['traffic_success'])):?><div class="alert alert-success"><?=htmlspecialchars($_SESSION['traffic_success'],ENT_QUOTES);unset($_SESSION['traffic_success']);?></div><?php endif;?>
<div class="traffic-tasks">
<?php if (count($tasks) === 0): ?>
  <div class="traffic-empty-state">
    <i class="fas fa-inbox"></i>
    <h3>Hiện chưa có nhiệm vụ Traffic</h3>
    <p>API Link4m/LAYMA/Link2m chỉ dùng để rút gọn liên kết, không tự cung cấp danh sách nhiệm vụ. Admin cần tạo nhiệm vụ trong cPanel trước.</p>
    <?php if (is_admin_account($data_user)): ?><a href="/cpanel/traffic" class="traffic-start">Mở trang quản lý nhiệm vụ</a><?php endif; ?>
  </div>
<?php else: foreach ($tasks as $task): $st=$task['submission_status']??''; ?>
  <article class="traffic-task"><div><h3><?=htmlspecialchars($task['title'],ENT_QUOTES)?></h3><p>Từ khóa: <strong><?=htmlspecialchars($task['keyword'],ENT_QUOTES)?></strong></p><small>Chờ tối thiểu <?= (int)$task['wait_seconds']?> giây · Thưởng <?=format_cash($task['reward'])?>đ</small></div><div class="traffic-actions"><?php if($st==='approved'):?><span class="task-approved">✓ Đã nhận thưởng</span><?php else:?><a target="_blank" rel="noopener" href="/traffic/start/<?=$task['id']?>" class="traffic-start">Bắt đầu</a><form method="post"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="task_id" value="<?=$task['id']?>"><input name="proof" placeholder="Mã/minh chứng" value="<?=htmlspecialchars((string)($task['proof']??''),ENT_QUOTES)?>"><button name="SubmitProof">Gửi duyệt</button></form><?php endif;?></div></article>
<?php endforeach; endif; ?>
</div>
</main><aside><div class="traffic-guide"><h3>Hướng dẫn</h3><p>Không đóng trang nhiệm vụ quá sớm. Phần thưởng chỉ được cộng sau khi admin xác nhận minh chứng.</p></div></aside></div>
<div id="traffic-history" class="traffic-history"><h2>Lịch sử nhận nhiệm vụ</h2><table><tr><th>Nhiệm vụ</th><th>Trạng thái</th><th>Thưởng</th></tr><?php if(!$history):?><tr><td colspan="3" class="traffic-history-empty">Bạn chưa tham gia nhiệm vụ nào.</td></tr><?php else:foreach($history as $h):?><tr><td><?=htmlspecialchars($h['title'],ENT_QUOTES)?></td><td><?=htmlspecialchars($h['status'],ENT_QUOTES)?></td><td><?=format_cash($h['reward'])?>đ</td></tr><?php endforeach;endif;?></table></div>
</div></section><?php require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php');?>
