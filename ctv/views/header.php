<?php
// statically decompiled from header.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (!$user || $data_user['ctv'] != 1) {
    new Redirect('/');
    exit();
} else {
    if ($db->site('status_only_ip_login_admin') == 1) {
        if ($data_user['ip'] != myip()) {
            new Redirect('/logout');
        }
    }
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/classes/helper.php';
    echo '<!doctype html>
<html lang="vi">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>STC PANEL</title>
    <meta name="description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave | This is the demo of Dashmix! You need to purchase a license for legal use! | DEMO">
    <meta name="author" content="pixelcave">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework | DEMO">
    <meta property="og:site_name" content="Dashmix">
    <meta property="og:description" content="Dashmix - Bootstrap 5 Admin Template &amp; UI Framework created by pixelcave | This is the demo of Dashmix! You need to purchase a license for legal use! | DEMO">
    <meta property="og:type" content="website">
    <meta property="og:url" content="">
    <meta property="og:image" content="">
    <link rel="shortcut icon" href="assets/media/favicons/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/media/favicons/favicon-192x192.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/media/favicons/apple-touch-icon-180x180.png">
    <link rel="stylesheet" href="/assets/back-end/js/plugins/magnific-popup/magnific-popup.css">
    <!-- <link rel="stylesheet" href="/assets/back-end/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/back-end/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="/assets/back-end/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css"> -->
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
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap5.min.css">
    <script src="/assets/back-end/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap5.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script> 
        var csrf_token = "';
    echo generate_csrf_token();
    echo '" 
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
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/ctv/views/sidebar.php';
    echo '        <header id="page-header">
            <div class="content-header">
                <div class="space-x-1">
                    <button type="button" class="btn btn-alt-secondary" data-toggle="layout" data-action="sidebar_toggle">
                        <i class="fa fa-fw fa-bars"></i>
                    </button>
                </div>
                <div class="space-x-1">
                    <a href="/" type="button" class="btn btn-alt-secondary">
                        Về trang chủ
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
