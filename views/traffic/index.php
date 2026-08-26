<?php
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
require_once APP_ROOT . '/libs/traffic.php';
if (!$user) { new Redirect('/login'); exit; }
$title = 'Nhiệm vụ Traffic | ' . $db->site('title');
require_once realpath($_SERVER['DOCUMENT_ROOT'] . '/views/header.php');
$userId = (int) $data_user['id'];
$tasks = $db->get_list(
    "SELECT t.*,(SELECT COUNT(*) FROM `traffic_attempts` a WHERE a.`task_id`=t.`id`"
    . " AND a.`user_id`='".$userId."' AND DATE(a.`created_at`)=CURDATE()"
    . " AND a.`status` IN ('issued','completed')) AS used_today"
    . " FROM `traffic_tasks` t WHERE t.`status`=1 ORDER BY t.`id` DESC"
);
$history = $db->get_list(
    "SELECT a.*,t.`title` FROM `traffic_attempts` a JOIN `traffic_tasks` t ON t.`id`=a.`task_id`"
    . " WHERE a.`user_id`='".$userId."' ORDER BY a.`id` DESC LIMIT 30"
);
$earningStats = $db->get_row(
    "SELECT COUNT(*) AS completed_total,COALESCE(SUM(`reward`),0) AS earned_total,"
    . "SUM(DATE(`completed_at`)=CURDATE()) AS completed_today FROM `traffic_attempts`"
    . " WHERE `user_id`='".$userId."' AND `status`='completed'"
) ?: [];
$trafficWallet = traffic_wallet_get($userId);
$minWithdraw = traffic_min_withdraw();
$withdrawHistory = $db->get_list("SELECT * FROM traffic_withdrawals WHERE user_id='".$userId."' ORDER BY id DESC LIMIT 20");
$statusLabels = [
    'issued' => ['Đang thực hiện','is-progress','fa-clock'],
    'completed' => ['Đã hoàn thành','is-completed','fa-check-circle'],
    'failed' => ['Khởi tạo thất bại','is-failed','fa-circle-xmark'],
    'expired' => ['Đã hết hạn','is-expired','fa-hourglass-end'],
];
$bonusTasks = array_values(array_filter($tasks, function ($task) {
    return traffic_bonus_is_active($task);
}));
?>
<link rel="stylesheet" href="/assets/css/traffic.css?v=<?= (int)(@filemtime(APP_ROOT.'/assets/css/traffic.css')?:1) ?>">
<nav class="earning-mobile-nav"><a href="/kiem-tien-online" class="active"><i class="fas fa-coins"></i> Nhiệm vụ</a><a href="#traffic-history"><i class="fas fa-clock-rotate-left"></i> Lịch sử</a><a href="/customer/balance"><i class="fas fa-wallet"></i> Số dư</a></nav>
<section class="screen traffic-page"><div class="center">
<?php if ($bonusTasks): ?>
<div class="home-promo-strip" id="traffic-promo-strip" role="button" tabindex="0"><div class="home-promo-strip-bg"></div><div class="home-promo-strip-inner"><span class="home-promo-strip-icon"><i class="fas fa-gift"></i></span><div class="home-promo-strip-text"><span class="home-promo-strip-tag">Khuyến mãi</span><span class="home-promo-strip-msg">Đang có <strong><?=count($bonusTasks)?> nhiệm vụ thưởng thêm trong khung giờ vàng</strong> — Click để xem chi tiết</span></div><span class="home-promo-strip-cta"><i class="fas fa-arrow-right"></i></span></div></div>
<div class="traffic-promo-modal" id="traffic-promo-modal"><div class="promo-modal-card"><button type="button" class="promo-modal-close">&times;</button><h3><i class="fas fa-gift"></i> Ưu đãi khung giờ vàng</h3><?php foreach($bonusTasks as $bt):?><div class="promo-task-row"><div><strong><?=htmlspecialchars($bt['title'],ENT_QUOTES)?></strong><small><?=htmlspecialchars(substr((string)$bt['bonus_start'],0,5))?> - <?=htmlspecialchars(substr((string)$bt['bonus_end'],0,5))?></small></div><span>+<?=format_cash($bt['bonus_reward'])?>đ</span></div><?php endforeach;?></div></div>
<?php endif; ?>
  <header class="traffic-banner">
    <div class="traffic-banner-copy"><span class="traffic-eyebrow"><i class="fas fa-bolt"></i> KIẾM TIỀN ONLINE</span><h1>Hoàn thành nhiệm vụ,<br><strong>nhận thưởng tự động</strong></h1><p>Hệ thống tạo phiên xác thực riêng và cộng tiền ngay khi bạn vượt link thành công.</p><a href="#available-tasks" class="traffic-primary-cta"><i class="fas fa-wand-magic-sparkles"></i> Bắt đầu nhận nhiệm vụ</a></div>
    <div class="traffic-banner-visual"><dotlottie-wc class="traffic-bear-lottie" src="https://lottie.host/5552ac56-d8c2-4824-a242-b35944e13f3a/TOn7GsY3Y3.lottie" autoplay loop aria-hidden="true"></dotlottie-wc></div>
  </header>

  <div class="traffic-stats">
    <article><span class="stat-icon is-blue"><i class="fas fa-list-check"></i></span><div><small>Nhiệm vụ đang mở</small><strong><?= count($tasks) ?></strong></div></article>
    <article><span class="stat-icon is-green"><i class="fas fa-circle-check"></i></span><div><small>Hoàn thành hôm nay</small><strong><?= (int)($earningStats['completed_today']??0) ?></strong></div></article>
    <article><span class="stat-icon is-orange"><i class="fas fa-gift"></i></span><div><small>Tổng thưởng đã nhận</small><strong><?= format_cash((int)$trafficWallet['total_earned']) ?>đ</strong></div></article>
    <article><span class="stat-icon is-purple"><i class="fas fa-wallet"></i></span><div><small>Số dư nhiệm vụ</small><strong><?= format_cash((int)$trafficWallet['balance']) ?>đ</strong></div></article>
  </div>

  <?php if(!empty($_SESSION['traffic_error'])):?><div class="traffic-alert is-error"><i class="fas fa-triangle-exclamation"></i><span><?=htmlspecialchars($_SESSION['traffic_error'],ENT_QUOTES);unset($_SESSION['traffic_error']);?></span></div><?php endif;?>
  <?php if(!empty($_SESSION['traffic_success'])):?><div class="traffic-alert is-success"><i class="fas fa-circle-check"></i><span><?=htmlspecialchars($_SESSION['traffic_success'],ENT_QUOTES);unset($_SESSION['traffic_success']);?></span></div><?php endif;?>

  <div class="traffic-content-grid">
    <main id="available-tasks">
      <div class="traffic-section-title"><div><span>NHIỆM VỤ KHẢ DỤNG</span><h2>Chọn nhiệm vụ để bắt đầu</h2></div><i class="fas fa-arrow-trend-up"></i></div>
      <div class="traffic-tasks">
      <?php if (!$tasks): ?>
        <div class="traffic-empty-state"><i class="fas fa-inbox"></i><h3>Hiện chưa có nhiệm vụ Traffic</h3><p>Vui lòng quay lại sau khi admin tạo nhiệm vụ mới.</p><?php if(is_admin_account($data_user)):?><a href="/cpanel/traffic" class="traffic-start">Mở trang quản lý</a><?php endif;?></div>
      <?php else: foreach($tasks as $task): $used=(int)($task['used_today']??0);$max=max(1,(int)$task['max_per_day']);$remaining=max(0,$max-$used);$percent=min(100,($used/$max)*100);$activeBonus=traffic_bonus_is_active($task);$displayReward=traffic_effective_reward($task); ?>
        <article class="traffic-task-card">
          <div class="task-card-top"><span class="task-number">#<?= (int)$task['id'] ?></span><span class="task-reward"><i class="fas fa-coins"></i> +<?=format_cash($displayReward)?>đ</span></div><?php if($activeBonus):?><div class="task-bonus-label"><i class="fas fa-gift"></i> Bonus +<?=format_cash($task['bonus_reward'])?>đ đến <?=htmlspecialchars(substr((string)$task['bonus_end'],0,5))?></div><?php endif;?>
          <svg viewBox="0 0 300 160" class="task-illustration" aria-hidden="true"><defs><linearGradient id="taskBg<?=$task['id']?>" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#2b5fed" stop-opacity=".08"/><stop offset="1" stop-color="#10b981" stop-opacity=".08"/></linearGradient></defs><rect x="10" y="5" width="280" height="150" rx="20" fill="url(#taskBg<?=$task['id']?>)" stroke="#e2e8f0"/><rect x="60" y="30" width="180" height="90" rx="10" fill="#fff" stroke="#dce3ed"/><rect x="60" y="30" width="180" height="20" rx="10" fill="#f1f5f9"/><circle cx="72" cy="40" r="3.5" fill="#ef4444"/><circle cx="82" cy="40" r="3.5" fill="#f59e0b"/><circle cx="92" cy="40" r="3.5" fill="#10b981"/><rect x="110" y="36" width="80" height="8" rx="4" fill="#cbd5e1"/><rect x="75" y="60" width="100" height="16" rx="8" fill="#f8fafc" stroke="#cbd5e1"/><path d="M85 108L105 98L125 103L145 86L165 76" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round"/><circle cx="220" cy="72" r="14" fill="#3b82f6"/><text x="220" y="77" fill="#fff" font-size="13" font-weight="900" text-anchor="middle">₫</text><circle cx="195" cy="99" r="11" fill="#10b981"/><text x="195" y="103" fill="#fff" font-size="10" font-weight="900" text-anchor="middle">+</text></svg>
          <h3><?=htmlspecialchars($task['title'],ENT_QUOTES)?></h3>
          <p><i class="fas fa-shield-halved"></i> Xác thực tự động theo phiên trình duyệt và địa chỉ mạng</p>
          <div class="task-quota"><div><span>Lượt hôm nay</span><strong><?=$used?>/<?=$max?></strong></div><div class="quota-track"><i style="width:<?=$percent?>%"></i></div></div>
          <?php if($remaining<=0):?><button class="traffic-start is-disabled" disabled><i class="fas fa-ban"></i> Đã hết lượt hôm nay</button>
          <?php else:?><form class="traffic-start-form" method="post" action="/model/traffic/start"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="task_id" value="<?=$task['id']?>"><button class="traffic-start" type="submit"><i class="fas fa-play"></i> Nhận nhiệm vụ <small>Còn <?=$remaining?> lượt</small></button></form><?php endif;?>
        </article>
      <?php endforeach; endif; ?>
      </div>
    </main>
    <aside class="traffic-howto"><h3><i class="fas fa-route"></i> Quy trình xác thực</h3><ol><li><b>1</b><div><strong>Nhận nhiệm vụ</strong><span>Tạo mã phiên dùng một lần</span></div></li><li><b>2</b><div><strong>Vượt link</strong><span>Hoàn thành yêu cầu của provider</span></div></li><li><b>3</b><div><strong>Quay về hệ thống</strong><span>Kiểm tra tài khoản, phiên, thiết bị và IP</span></div></li><li><b>4</b><div><strong>Nhận thưởng</strong><span>Cộng tiền tự động vào số dư</span></div></li></ol><div class="traffic-security-note"><i class="fas fa-lock"></i><p>Dùng cùng trình duyệt và mạng trong suốt quá trình. Không chia sẻ link hoặc đổi VPN.</p></div></aside>
  </div>

  <section class="traffic-wallet-section"><div class="traffic-section-title"><div><span>VÍ NHIỆM VỤ</span><h2>Quản lý tiền thưởng</h2></div><i class="fas fa-wallet"></i></div><div class="traffic-wallet-grid"><article class="wallet-balance-card"><small>Số dư có thể sử dụng</small><strong><?=format_cash((int)$trafficWallet['balance'])?>đ</strong><p>Bạn có thể chuyển sang số dư chính hoặc gửi yêu cầu rút ngân hàng.</p></article><article class="wallet-action-card"><h3>Chuyển vào số dư chính</h3><form class="traffic-wallet-form"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="action" value="transfer"><input type="number" min="1" max="<?=(int)$trafficWallet['balance']?>" name="amount" placeholder="Số tiền muốn chuyển" required><button>Chuyển số dư</button></form></article><article class="wallet-action-card"><h3>Rút về ngân hàng</h3><p class="wallet-minimum">Tối thiểu <?=format_cash($minWithdraw)?>đ</p><form class="traffic-wallet-form"><input type="hidden" name="csrf_token" value="<?=htmlspecialchars(generate_csrf_token(),ENT_QUOTES)?>"><input type="hidden" name="action" value="withdraw"><input type="number" min="<?=$minWithdraw?>" max="<?=(int)$trafficWallet['balance']?>" name="amount" placeholder="Số tiền rút" required><input name="bank_name" placeholder="Tên ngân hàng" required><input name="account_number" placeholder="Số tài khoản" required><input name="account_name" placeholder="Tên chủ tài khoản" required><button>Gửi yêu cầu rút</button></form></article></div><div class="wallet-withdraw-history"><h3>Lịch sử rút tiền</h3><?php if(!$withdrawHistory):?><p>Chưa có yêu cầu rút tiền.</p><?php else:?><div class="traffic-table-wrap"><table><tr><th>Số tiền</th><th>Ngân hàng</th><th>Trạng thái</th><th>Ngày tạo</th></tr><?php foreach($withdrawHistory as $w):?><tr><td><?=format_cash($w['amount'])?>đ</td><td><?=htmlspecialchars($w['bank_name'],ENT_QUOTES)?> · <?=htmlspecialchars($w['account_number'],ENT_QUOTES)?></td><td><?=['pending'=>'Chờ xử lý','completed'=>'Đã thanh toán','rejected'=>'Bị từ chối'][$w['status']]??$w['status']?></td><td><?=$w['created_at']?></td></tr><?php endforeach;?></table></div><?php endif;?></div></section>

  <section id="traffic-history" class="traffic-history"><div class="traffic-section-title"><div><span>HOẠT ĐỘNG GẦN ĐÂY</span><h2>Lịch sử nhiệm vụ</h2></div><i class="fas fa-clock-rotate-left"></i></div><div class="traffic-table-wrap"><table><thead><tr><th>Nhiệm vụ</th><th>Thời gian</th><th>Trạng thái</th><th>Thưởng</th></tr></thead><tbody><?php if(!$history):?><tr><td colspan="4" class="traffic-history-empty">Bạn chưa tham gia nhiệm vụ nào.</td></tr><?php else:foreach($history as $h):$status=$statusLabels[$h['status']]??['Không xác định','is-unknown'];?><tr><td><strong><?=htmlspecialchars($h['title'],ENT_QUOTES)?></strong><small>#<?= (int)$h['id'] ?></small></td><td><?=date('d/m/Y H:i',strtotime($h['created_at']))?></td><td><div class="history-status-display"><span class="history-status-icon <?=$status[1]?>"><i class="fas <?=$status[2]?>"></i></span><div><strong class="<?=$status[1]?>"><?=$status[0]?></strong><small>Trạng thái</small></div></div></td><td class="history-reward">+<?=format_cash($h['reward'])?>đ</td></tr><?php endforeach;endif;?></tbody></table></div></section>
</div></section>

<script type="module" src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.6.2/dist/dotlottie-wc.js"></script>
<div class="traffic-loading-modal" id="traffic-loading-modal" aria-hidden="true"><div class="loading-card"><div class="loading-spinner"><i class="fas fa-link"></i></div><h3>Đang tạo nhiệm vụ...</h3><p>Hệ thống đang tạo phiên xác thực và lấy link từ nhà cung cấp. Vui lòng không đóng trang.</p><div class="loading-progress"><i></i></div></div></div>
<script>
document.querySelectorAll('.traffic-start-form').forEach(function(form){form.addEventListener('submit',function(){var modal=document.getElementById('traffic-loading-modal');modal.classList.add('is-visible');modal.setAttribute('aria-hidden','false');var btn=form.querySelector('button');btn.disabled=true;btn.innerHTML='<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';});});
document.querySelectorAll('.traffic-wallet-form').forEach(function(form){form.addEventListener('submit',function(e){e.preventDefault();var btn=form.querySelector('button'),fd=new FormData(form);btn.disabled=true;btn.textContent='Đang xử lý...';fetch('/model/traffic/wallet',{method:'POST',credentials:'same-origin',headers:{'X-Requested-With':'XMLHttpRequest'},body:fd}).then(function(r){return r.json()}).then(function(j){alert(j.msg||'Đã xử lý');if(j.status==='success')location.reload();}).catch(function(){alert('Lỗi kết nối');}).finally(function(){btn.disabled=false;btn.textContent=form.querySelector('[name=action]').value==='transfer'?'Chuyển số dư':'Gửi yêu cầu rút';});});});
(function(){var strip=document.getElementById('traffic-promo-strip'),modal=document.getElementById('traffic-promo-modal');if(!strip||!modal)return;function open(){modal.classList.add('is-visible')}function close(){modal.classList.remove('is-visible')}strip.addEventListener('click',open);strip.addEventListener('keydown',function(e){if(e.key==='Enter'||e.key===' '){e.preventDefault();open()}});modal.querySelector('.promo-modal-close').addEventListener('click',close);modal.addEventListener('click',function(e){if(e.target===modal)close()});})();
<?php if(($_GET['traffic']??'')==='success'):?>document.addEventListener('DOMContentLoaded',function(){if(typeof alertSuccess==='function')alertSuccess('Hoàn thành nhiệm vụ','Tiền thưởng đã được cộng vào số dư');history.replaceState({},document.title,'/kiem-tien-online');});<?php endif;?>
</script>
<?php require_once realpath($_SERVER['DOCUMENT_ROOT'].'/views/footer.php');?>
