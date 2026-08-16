<?php
// statically decompiled from header.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user || !is_admin_account($data_user)) {
    new Redirect('/');
    exit();
} else {
    if ($db->site('status_only_ip_login_admin') == 1) {
        if ($data_user['ip'] != myip()) {
            new Redirect('/logout');
        }
    }
    if ($db->site('status_security') == 1) {
        if (!$db->get_row('SELECT * FROM `ip_white` WHERE `ip` = \'' . myip() . '\' ')) {
            new Redirect('/block');
        }
    }
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/classes/helper.php';
    echo '<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>STC PANEL</title>
    <meta name="title" content="';
    echo $db->site('title');
    echo '">
    <meta name="keywords" content="';
    echo $db->site('keywords');
    echo '">
    <meta name="description" content="';
    echo $db->site('description');
    echo '">

    <meta property="og:type" content="website">
    <meta property="og:url" content="';
    echo DOMAIN;
    echo '">
    <meta property="og:title" content="';
    echo $db->site('title');
    echo '">
    <meta property="og:description" content="';
    echo $db->site('description');
    echo '">
    <meta property="og:image" content="';
    echo DOMAIN . $db->site('anhbia');
    echo '">

    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="';
    echo DOMAIN;
    echo '">
    <meta property="twitter:title" content="';
    echo $db->site('title');
    echo '">
    <meta property="twitter:description" content="';
    echo $db->site('description');
    echo '">
    <meta property="twitter:image" content="';
    echo DOMAIN . $db->site('anhbia');
    echo '">

    <meta name="author" content="';
    echo $db->site('author');
    echo '">
    <meta name="csrf-token" content="';
    echo htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8');
    echo '">
    <link rel="icon" href="';
    echo DOMAIN . $db->site('favicon');
    echo '" type="image/x-icon" />

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/back-end/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css">
    <link rel="stylesheet" href="/assets/back-end/js/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="/assets/back-end/js/plugins/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" id="css-main" href="/assets/back-end//css/dashmix.min-5.9.css">
    <script src="/assets/back-end/ckeditor/ckeditor.js"></script>
    <script src="/assets/js/jquery.min.js"></script>
    <link rel="stylesheet" href="/assets/back-end/css/simple-notify.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Token lay tu session qua json_encode de tranh loi escape/XSS khi
        // render vao JavaScript. KHONG log, KHONG dua vao URL.
        window.csrf_token = ';
    echo json_encode(generate_csrf_token(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
    echo ';
        var csrf_token = window.csrf_token; // backward-compatible cho caller cu

        // Nguon token thong nhat: uu tien meta tag, fallback window.csrf_token.
        window.getAdminCsrfToken = function () {
            var meta = document.querySelector(\'meta[name="csrf-token"]\');
            var token = meta ? String(meta.getAttribute(\'content\') || \'\') : \'\';
            if (!token && typeof window.csrf_token === \'string\') {
                token = window.csrf_token;
            }
            return token;
        };

        // Prefilter GIOI HAN NGHIEM: chi tu dong gan csrf_token cho POST
        // same-origin toi dung 2 endpoint noi bo /model/admin/delete va
        // /model/admin/update. Khong ap dung cho CDN/API/domain khac,
        // khong ghi de csrf_token da co, fail-closed khi khong co token.
        (function () {
            function isTargetAdminEndpoint(url) {
                var parsed;
                try {
                    parsed = new URL(url, window.location.href);
                } catch (e) {
                    return false;
                }
                if (parsed.origin !== window.location.origin) {
                    return false;
                }
                var allowed = [
                    \'/model/admin/delete\',
                    \'/model/admin/delete/\',
                    \'/model/admin/update\',
                    \'/model/admin/update/\'
                ];
                return allowed.indexOf(parsed.pathname) !== -1;
            }

            function notifyMissingToken() {
                var msg = \'Không tìm thấy mã bảo mật, vui lòng tải lại trang\';
                if (typeof Swal !== \'undefined\' && Swal && typeof Swal.fire === \'function\') {
                    Swal.fire(\'Thất Bại\', msg, \'error\');
                } else if (typeof showMessage === \'function\') {
                    try { showMessage(msg, \'error\'); } catch (e) { alert(msg); }
                } else {
                    alert(msg);
                }
            }

            function attachToken(options) {
                var token = window.getAdminCsrfToken();
                if (!token) {
                    return false; // fail-closed: caller phai huy request
                }
                var data = options.data;
                if (typeof FormData !== \'undefined\' && data instanceof FormData) {
                    if (!data.has(\'csrf_token\')) {
                        data.append(\'csrf_token\', token);
                    }
                } else if (typeof data === \'string\') {
                    if (!/(^|&)csrf_token=/.test(data)) {
                        options.data = data + (data ? \'&\' : \'\')
                            + \'csrf_token=\' + encodeURIComponent(token);
                    }
                } else {
                    if (data == null || typeof data !== \'object\') {
                        data = {};
                    }
                    if (data.csrf_token == null || data.csrf_token === \'\') {
                        data.csrf_token = token;
                    }
                    options.data = data;
                }
                return true;
            }

            if (window.jQuery && typeof window.jQuery.ajaxPrefilter === \'function\') {
                window.jQuery.ajaxPrefilter(function (options, originalOptions, jqXHR) {
                    var method = String(options.type || options.method || \'GET\').toUpperCase();
                    if (method !== \'POST\') {
                        return;
                    }
                    if (!isTargetAdminEndpoint(options.url || \'\')) {
                        return;
                    }
                    var origBeforeSend = options.beforeSend;
                    options.beforeSend = function (xhr, settings) {
                        // Kiem tra TRUOC khi gui: khong co token -> huy request,
                        // khong bao gio gui request chac chan nhan 419.
                        if (!window.getAdminCsrfToken()) {
                            notifyMissingToken();
                            return false;
                        }
                        if (typeof origBeforeSend === \'function\') {
                            return origBeforeSend.call(this, xhr, settings);
                        }
                    };
                    if (!attachToken(options)) {
                        // Du phong: abort ngay ca khi attach that bai.
                        jqXHR.abort();
                        notifyMissingToken();
                    }
                });
            }
        })();
    </script>
    <script>
    (function () {
        "use strict";
        function refreshChatBadge() {
            var badge = document.getElementById("sidebar-chat-unread");
            if (!badge) { return; }
            fetch("/model/admin/chat/conversations?page=1", { credentials: "same-origin", headers: { "X-Requested-With": "XMLHttpRequest" } })
                .then(function (res) { return res.json(); })
                .then(function (json) {
                    if (json.status !== "success" || !json.data) { return; }
                    var total = parseInt(json.data.total_unread, 10) || 0;
                    badge.textContent = total > 99 ? "99+" : String(total);
                    badge.style.display = total > 0 ? "" : "none";
                })
                .catch(function () {});
        }
        document.addEventListener("DOMContentLoaded", function () {
            refreshChatBadge();
            setInterval(refreshChatBadge, 10000);
        });
    })();
    </script>
</head>
<script>
    function showMessage(message, type) {
        const commonOptions = {
            effect: \'fade\',
            speed: 300,
            customClass: null,
            customIcon: null,
            showIcon: true,
            showCloseButton: true,
            autoclose: true,
            autotimeout: 3000,
            gap: 20,
            distance: 20,
            type: \'outline\',
            position: \'right top\'
        };

        const options = {
            success: {
                status: \'success\',
                title: \'Thành công!\',
                text: message,
            },
            error: {
                status: \'error\',
                title: \'Thất bại!\',
                text: message,
            }
        };
        new Notify(Object.assign({}, commonOptions, options[type]));
    }
</script>
<style>
    .top-filter {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        margin-bottom: 25px;
    }

    .filter-show {
        width: 150px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
    }

    .filter-label {
        font-size: 14px;
        font-weight: 500;
        margin-right: 8px;
        white-space: nowrap;
        text-transform: uppercase;
    }

    .filter-select {
        height: 40px;
        background-color: transparent;
    }

    .filter-short {
        width: 225px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
    }
</style>

<body>
    <div id="page-container" class="sidebar-o enable-page-overlay side-scroll page-header-fixed side-trans-enabled page-header-dark">
        ';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/sidebar.php';
    echo '        <header id="page-header">
            <div class="content-header">
                <div class="space-x-1">
                    <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>
                <div class="space-x-1">
                    <a href="/cpanel/boosting/orders?user_id=&username=&trans_id=&service=&group=&display=pending&createdate=&limit=10&shortByDate=" type="button" class="btn btn-alt-secondary">
                        Đơn cày <span class="badge bg-danger rounded-pill">';
    echo orderPendingTotal();
    echo '</span>
                    </a>
                    <a href="/cpanel/withdraw/orders" type="button" class="btn btn-alt-secondary">
                        Đơn rút tiền <span class="badge bg-danger rounded-pill">';
    echo orderWithdrawCtvPendingTotal();
    echo '</span>
                    </a>
                    <a href="/" type="button" class="btn btn-alt-secondary">
                        Trang chủ
                    </a>
                </div>
            </div>
            <div id="page-header-search" class="overlay-header bg-header-dark">
                <div class="bg-white-10">
                    <div class="content-header">
                        <form class="w-100" action="be_pages_generic_search.html" method="POST">
                            <div class="input-group">
                                <button type="button" class="btn btn-alt-primary" data-toggle="layout" data-action="header_search_off">
                                    <i class="fa fa-fw fa-times-circle"></i>
                                </button>
                                <input type="text" class="form-control border-0" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div id="page-header-loader" class="overlay-header bg-header-dark">
                <div class="bg-white-10">
                    <div class="content-header">
                        <div class="w-100 text-center">
                            <i class="fa fa-fw fa-sun fa-spin text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </header>';
}
