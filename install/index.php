<?php
/**
 * Trình cài đặt Siêu Thị Code — wizard 3 bước.
 *
 * Bước 1: kết nối database. Bước 2: tài khoản quản trị. Bước 3: xác nhận + cài đặt.
 * Trang này chỉ render form; mọi hành động thay đổi trạng thái đi qua install/api.php
 * (POST + CSRF + rate limit + mutex). Không in password, không hotlink CDN.
 */

require_once __DIR__ . '/bootstrap.php';

installer_guard_not_installed(false);

$csrf = installer_csrf_token();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex,nofollow">
<title>Cài đặt Siêu Thị Code</title>
<style>
:root{--bg:#0f1420;--card:#171d2d;--line:#2a3350;--txt:#e6e9f2;--mut:#8b93ad;--acc:#4f7cff;--ok:#2fbf71;--err:#e5484d}
*{box-sizing:border-box}
body{margin:0;font-family:system-ui,-apple-system,"Segoe UI",Roboto,Arial,sans-serif;background:linear-gradient(160deg,#0b0f1a,#101828);color:var(--txt);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px}
.wrap{width:100%;max-width:640px}
.card{background:var(--card);border:1px solid var(--line);border-radius:14px;padding:28px;box-shadow:0 12px 40px rgba(0,0,0,.35)}
h1{font-size:20px;margin:0 0 4px}
.sub{color:var(--mut);font-size:13px;margin:0 0 22px}
.steps{display:flex;gap:8px;margin:0 0 24px;list-style:none;padding:0}
.steps li{flex:1;text-align:center;font-size:12px;color:var(--mut);padding:8px 4px;border-bottom:2px solid var(--line)}
.steps li.on{color:var(--txt);border-color:var(--acc)}
label{display:block;font-size:13px;color:var(--mut);margin:14px 0 6px}
input{width:100%;padding:10px 12px;border-radius:8px;border:1px solid var(--line);background:#0f1524;color:var(--txt);font-size:14px}
input:focus{outline:none;border-color:var(--acc)}
.row2{display:grid;grid-template-columns:2fr 1fr;gap:12px}
.btn{margin-top:20px;width:100%;padding:12px;border:0;border-radius:8px;background:var(--acc);color:#fff;font-size:15px;font-weight:600;cursor:pointer}
.btn[disabled]{opacity:.55;cursor:not-allowed}
.ghost{background:#222a44}
.msg{display:none;margin-top:16px;padding:12px 14px;border-radius:8px;font-size:13px}
.msg.err{display:block;background:rgba(229,72,77,.12);border:1px solid rgba(229,72,77,.5);color:#ffb4b6}
.msg.ok{display:block;background:rgba(47,191,113,.12);border:1px solid rgba(47,191,113,.5);color:#a9efc9}
.step{display:none}
.step.on{display:block}
.sum{font-size:13px;color:var(--mut);line-height:1.7;background:#0f1524;border:1px solid var(--line);border-radius:8px;padding:12px 14px;margin-top:14px}
.sum b{color:var(--txt)}
.foot{margin-top:18px;font-size:12px;color:var(--mut);text-align:center}
.done{text-align:center;padding:18px 0}
.done .big{font-size:44px;line-height:1}
.spin{display:inline-block;width:14px;height:14px;border:2px solid rgba(255,255,255,.35);border-top-color:#fff;border-radius:50%;animation:r .7s linear infinite;vertical-align:-2px;margin-right:8px}
@keyframes r{to{transform:rotate(360deg)}}
</style>
</head>
<body>
<div class="wrap">
  <div class="card">
    <h1>Cài đặt Siêu Thị Code</h1>
    <p class="sub">Trình cài đặt sẽ thiết lập database và tài khoản quản trị trong 3 bước.</p>

    <ol class="steps" id="steps">
      <li class="on" data-s="1">1. Database</li>
      <li data-s="2">2. Quản trị viên</li>
      <li data-s="3">3. Hoàn tất</li>
    </ol>

    <!-- Bước 1: Database -->
    <section class="step on" id="step-1">
      <form id="form-db" autocomplete="off">
        <div class="row2">
          <div>
            <label for="db_host">Database Host</label>
            <input id="db_host" name="db_host" value="localhost" required>
          </div>
          <div>
            <label for="db_port">Port</label>
            <input id="db_port" name="db_port" type="number" value="3306" required>
          </div>
        </div>
        <label for="db_name">Database Name</label>
        <input id="db_name" name="db_name" required>
        <label for="db_user">Database Username</label>
        <input id="db_user" name="db_user" required>
        <label for="db_pass">Database Password</label>
        <input id="db_pass" name="db_pass" type="password">
        <button class="btn" type="submit">Kiểm tra kết nối</button>
        <div class="msg" id="msg-1"></div>
      </form>
    </section>

    <!-- Bước 2: Tài khoản quản trị -->
    <section class="step" id="step-2">
      <form id="form-admin" autocomplete="off">
        <label for="admin_name">Họ và tên</label>
        <input id="admin_name" name="admin_name" required>
        <label for="admin_username">Tên đăng nhập</label>
        <input id="admin_username" name="admin_username" required>
        <label for="admin_email">Email</label>
        <input id="admin_email" name="admin_email" type="email" required>
        <label for="admin_password">Mật khẩu (tối thiểu 8 ký tự)</label>
        <input id="admin_password" name="admin_password" type="password" required>
        <label for="admin_password_confirm">Xác nhận mật khẩu</label>
        <input id="admin_password_confirm" name="admin_password_confirm" type="password" required>
        <button class="btn" type="submit">Lưu thông tin</button>
        <button class="btn ghost" type="button" id="back-2">Quay lại</button>
        <div class="msg" id="msg-2"></div>
      </form>
    </section>

    <!-- Bước 3: Xác nhận & cài đặt -->
    <section class="step" id="step-3">
      <p class="sub">Kiểm tra lại thông tin rồi bấm "Cài đặt ngay".</p>
      <div class="sum" id="summary"></div>
      <button class="btn" id="do-install">Cài đặt ngay</button>
      <button class="btn ghost" id="back-3">Quay lại</button>
      <div class="msg" id="msg-3"></div>
    </section>

    <!-- Hoàn tất -->
    <section class="step" id="step-done">
      <div class="done">
        <div class="big">&#10003;</div>
        <h1>Cài đặt thành công</h1>
        <p class="sub">Website của bạn đã sẵn sàng. Trình cài đặt đã tự khoá để bảo mật.</p>
        <a class="btn" style="display:block;text-decoration:none;line-height:1.4" href="/login">Đăng nhập quản trị</a>
      </div>
    </section>
  </div>
  <p class="foot">Siêu Thị Code &mdash; Trình cài đặt</p>
</div>

<script>
(function () {
  'use strict';
  var CSRF = <?php echo json_encode($csrf, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
  var API = '/install/api.php';

  var state = { db: null, admin: null };

  function $(id) { return document.getElementById(id); }

  function showStep(n) {
    var steps = document.querySelectorAll('.step');
    for (var i = 0; i < steps.length; i++) steps[i].classList.remove('on');
    var tabs = document.querySelectorAll('#steps li');
    for (var j = 0; j < tabs.length; j++) tabs[j].classList.remove('on');
    if (n === 'done') {
      $('step-done').classList.add('on');
      return;
    }
    $('step-' + n).classList.add('on');
    for (var k = 0; k < n; k++) tabs[k].classList.add('on');
  }

  function setMsg(box, text, ok) {
    box.className = 'msg ' + (ok ? 'ok' : 'err');
    box.textContent = text;
  }

  function post(body, onDone) {
    var data = new URLSearchParams();
    data.append('csrf_token', CSRF);
    Object.keys(body).forEach(function (k) { data.append(k, body[k]); });
    fetch(API, { method: 'POST', body: data, credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (r) { return r.json().then(function (j) { return { code: r.status, json: j }; }); })
      .then(function (res) { onDone(res.code, res.json); })
      .catch(function () { onDone(0, { msg: 'Không thể kết nối tới máy chủ.' }); });
  }

  function busy(btn, on) {
    if (on) {
      btn.dataset.label = btn.innerHTML;
      btn.innerHTML = '<span class="spin"></span>Đang xử lý...';
      btn.disabled = true;
    } else {
      btn.innerHTML = btn.dataset.label || btn.innerHTML;
      btn.disabled = false;
    }
  }

  // Bước 1: test DB
  $('form-db').addEventListener('submit', function (e) {
    e.preventDefault();
    var btn = this.querySelector('button[type="submit"]');
    busy(btn, true);
    post({
      action: 'test_db',
      db_host: $('db_host').value.trim(),
      db_port: $('db_port').value.trim(),
      db_name: $('db_name').value.trim(),
      db_user: $('db_user').value.trim(),
      db_pass: $('db_pass').value
    }, function (code, j) {
      busy(btn, false);
      if (code === 200 && j.status === 'success') {
        state.db = { host: $('db_host').value.trim(), port: $('db_port').value.trim(), name: $('db_name').value.trim() };
        setMsg($('msg-1'), j.msg || 'OK', true);
        setTimeout(function () { showStep(2); }, 400);
      } else {
        setMsg($('msg-1'), j.msg || 'Kết nối thất bại.', false);
      }
    });
  });

  // Bước 2: lưu admin
  $('form-admin').addEventListener('submit', function (e) {
    e.preventDefault();
    var btn = this.querySelector('button[type="submit"]');
    busy(btn, true);
    post({
      action: 'save_admin',
      admin_name: $('admin_name').value.trim(),
      admin_username: $('admin_username').value.trim(),
      admin_email: $('admin_email').value.trim(),
      admin_password: $('admin_password').value,
      admin_password_confirm: $('admin_password_confirm').value
    }, function (code, j) {
      busy(btn, false);
      if (code === 200 && j.status === 'success') {
        state.admin = { name: $('admin_name').value.trim(), username: $('admin_username').value.trim(), email: $('admin_email').value.trim() };
        renderSummary();
        showStep(3);
      } else {
        setMsg($('msg-2'), j.msg || 'Thông tin chưa hợp lệ.', false);
      }
    });
  });

  function renderSummary() {
    var d = state.db || {};
    var a = state.admin || {};
    $('summary').innerHTML =
      'Database: <b>' + esc(d.name) + '</b> tại <b>' + esc(d.host) + ':' + esc(d.port) + '</b><br>' +
      'Quản trị viên: <b>' + esc(a.username) + '</b> &lt;' + esc(a.email) + '&gt;<br>' +
      'Họ tên: <b>' + esc(a.name) + '</b>';
  }
  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  // Bước 3: cài đặt
  $('do-install').addEventListener('click', function () {
    var btn = this;
    busy(btn, true);
    post({ action: 'install' }, function (code, j) {
      busy(btn, false);
      if (code === 200 && j.status === 'success') {
        showStep('done');
      } else {
        setMsg($('msg-3'), j.msg || 'Cài đặt thất bại.', false);
      }
    });
  });

  $('back-2').addEventListener('click', function () { showStep(1); });
  $('back-3').addEventListener('click', function () { showStep(2); });
})();
</script>
</body>
</html>
