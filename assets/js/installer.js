/**
 * Trình cài đặt Siêu Thị Code — wizard logic.
 *
 * Không chứa credential. CSRF token được inject qua thuộc tính data-csrf trên
 * thẻ <body> (được server render), KHÔNG hardcode trong file này.
 *
 * Accessibility:
 *  - Thông báo lỗi/thành công dùng aria-live.
 *  - Khi đổi bước, focus chuyển tới heading của bước mới.
 *  - Enter ở bước 1/2 chỉ chuyển bước, KHÔNG kích hoạt cài đặt cuối cùng.
 */
(function () {
  'use strict';

  var body = document.body;
  var CSRF = body.getAttribute('data-csrf') || '';
  var API = body.getAttribute('data-api') || '/install/api.php';

  var state = { db: null, admin: null };
  var currentStep = 1;
  var TOTAL = 3;

  function $(id) { return document.getElementById(id); }

  function esc(s) {
    return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
      return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
    });
  }

  function setMsg(box, text, ok) {
    if (!box) { return; }
    box.className = 'stc-msg ' + (ok ? 'is-ok' : 'is-err');
    box.textContent = text;
  }

  function clearMsg(box) {
    if (!box) { return; }
    box.className = 'stc-msg';
    box.textContent = '';
  }

  function busy(btn, on) {
    if (!btn) { return; }
    if (on) {
      btn.dataset.label = btn.innerHTML;
      btn.innerHTML = '<span class="stc-spin" aria-hidden="true"></span>Đang xử lý...';
      btn.disabled = true;
      btn.setAttribute('aria-busy', 'true');
    } else {
      btn.innerHTML = btn.dataset.label || btn.innerHTML;
      btn.disabled = false;
      btn.removeAttribute('aria-busy');
    }
  }

  function post(payload, onDone) {
    var data = new URLSearchParams();
    data.append('csrf_token', CSRF);
    Object.keys(payload).forEach(function (k) { data.append(k, payload[k]); });
    fetch(API, {
      method: 'POST',
      body: data,
      credentials: 'same-origin',
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(function (r) {
        return r.json().then(function (j) { return { code: r.status, json: j }; });
      })
      .then(function (res) { onDone(res.code, res.json); })
      .catch(function () { onDone(0, { msg: 'Không thể kết nối tới máy chủ. Vui lòng thử lại.' }); });
  }

  function updateProgress(n) {
    var pct = Math.min(100, Math.round((n / TOTAL) * 100));
    var fill = $('progress-fill');
    var count = $('progress-count');
    var label = $('progress-label');
    var bar = $('progress-bar');
    if (fill) { fill.style.width = pct + '%'; }
    if (count) { count.textContent = 'Bước ' + n + '/' + TOTAL; }
    if (label) {
      label.textContent = (n === 1) ? 'Kết nối database'
        : (n === 2) ? 'Tài khoản quản trị' : 'Xác nhận & cài đặt';
    }
    if (bar) { bar.setAttribute('aria-valuenow', String(n)); }
  }

  function showStep(n) {
    currentStep = n;
    var steps = document.querySelectorAll('.stc-step');
    for (var i = 0; i < steps.length; i++) { steps[i].classList.remove('is-on'); }
    var target = (n === 'done') ? $('step-done') : $('step-' + n);
    if (target) { target.classList.add('is-on'); }

    // Ẩn progress ở màn hình hoàn tất.
    var prog = document.querySelector('.stc-progress');
    if (prog) { prog.style.display = (n === 'done') ? 'none' : ''; }

    if (n !== 'done') { updateProgress(n); }

    // Chuyển focus tới heading của bước mới (a11y).
    if (target) {
      var heading = target.querySelector('.stc-step__title, h1');
      if (heading) {
        heading.setAttribute('tabindex', '-1');
        heading.focus();
      }
    }
  }

  function renderSummary() {
    var d = state.db || {};
    var a = state.admin || {};
    var el = $('summary');
    if (!el) { return; }
    // Bước 3: hiển thị host/port/name/username DB + admin, TUYỆT ĐỐI không password.
    el.innerHTML =
      '<dl>' +
      '<dt>Database host</dt><dd>' + esc(d.host) + '</dd>' +
      '<dt>Database port</dt><dd>' + esc(d.port) + '</dd>' +
      '<dt>Database name</dt><dd>' + esc(d.name) + '</dd>' +
      '<dt>Database username</dt><dd>' + esc(d.user) + '</dd>' +
      '<dt>Họ tên quản trị</dt><dd>' + esc(a.name) + '</dd>' +
      '<dt>Tên đăng nhập</dt><dd>' + esc(a.username) + '</dd>' +
      '<dt>Email</dt><dd>' + esc(a.email) + '</dd>' +
      '</dl>';
  }

  function bind() {
    var formDb = $('form-db');
    var formAdmin = $('form-admin');
    var doInstall = $('do-install');
    var back2 = $('back-2');
    var back3 = $('back-3');

    if (formDb) {
      formDb.addEventListener('submit', function (e) {
        e.preventDefault(); // Enter ở bước 1 chỉ test kết nối, không cài đặt.
        var btn = formDb.querySelector('button[type="submit"]');
        clearMsg($('msg-1'));
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
            state.db = {
              host: $('db_host').value.trim(),
              port: $('db_port').value.trim(),
              name: $('db_name').value.trim(),
              user: $('db_user').value.trim()
            };
            setMsg($('msg-1'), j.msg || 'Kết nối thành công.', true);
            setTimeout(function () { showStep(2); }, 350);
          } else {
            setMsg($('msg-1'), j.msg || 'Kết nối thất bại.', false);
          }
        });
      });
    }

    if (formAdmin) {
      formAdmin.addEventListener('submit', function (e) {
        e.preventDefault(); // Enter ở bước 2 chỉ lưu thông tin, không cài đặt.
        var btn = formAdmin.querySelector('button[type="submit"]');
        clearMsg($('msg-2'));
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
            state.admin = {
              name: $('admin_name').value.trim(),
              username: $('admin_username').value.trim(),
              email: $('admin_email').value.trim()
            };
            renderSummary();
            showStep(3);
          } else {
            setMsg($('msg-2'), j.msg || 'Thông tin chưa hợp lệ.', false);
          }
        });
      });
    }

    if (doInstall) {
      // Chỉ nút "Cài đặt ngay" ở bước 3 mới kích hoạt cài đặt cuối cùng.
      doInstall.addEventListener('click', function () {
        var btn = doInstall;
        clearMsg($('msg-3'));
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
    }

    if (back2) { back2.addEventListener('click', function () { showStep(1); }); }
    if (back3) { back3.addEventListener('click', function () { showStep(2); }); }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { bind(); updateProgress(1); });
  } else {
    bind();
    updateProgress(1);
  }
})();
