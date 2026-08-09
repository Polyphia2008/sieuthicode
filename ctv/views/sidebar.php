<?php
// statically decompiled from sidebar.php  [structured; all 1 record(s) structured]

echo '<nav id="sidebar" aria-label="Main Navigation">
    <div class="bg-header-dark">
        <div class="content-header bg-white-5">
            <a class="fw-semibold text-white tracking-wide" href="/cpanel/home">
                <span class="smini-hidden">
                    STC <span class="opacity-75">Software</span>
                </span>
            </a>
            <div>

                <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="class-toggle" data-target="#dark-mode-toggler" data-class="far fa" onclick="Dashmix.layout(\'dark_mode_toggle\');">
                    <i class="far fa-moon" id="dark-mode-toggler"></i>
                </button>
                <button type="button" class="btn btn-sm btn-alt-secondary d-lg-none" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times-circle"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="js-sidebar-scroll">
        <div class="content-side">
            <ul class="nav-main">
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/ctv/home">
                        <i class="nav-main-link-icon fa fa-dashboard"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li>

                <li class="nav-main-heading">QUẢN LÝ GAME</li>
                ';
if ($data_user['ctv_account'] == 1) {
    echo '                <li class="nav-main-item">
                    <a class="nav-main-link" href="/ctv/subcategory/view">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Danh mục game</span>
                    </a>
                </li>
                ';
}
echo '                ';
if ($data_user['ctv_boosting'] == 1) {
    echo '                <li class="nav-main-item">
                    <a class="nav-main-link" href="/ctv/boosting/orders">
                        <i class="nav-main-link-icon fa fa-vector-square"></i>
                        <span class="nav-main-link-name">Đơn cày thuê <span class="badge bg-danger rounded-pill">';
    echo format_cash($db->get_row('SELECT COUNT(id) as total FROM orders WHERE `status` = \'pending\' AND `receiver` IS NULL')['total'] ?? 0);
    echo '</span></span>
                    </a>
                </li>
                ';
}
echo '                <li class="nav-main-heading">GIAO DỊCH</li>
                ';
if ($data_user['ctv_boosting'] == 1) {
    echo '                <li class="nav-main-item">
                    <a class="nav-main-link" href="/ctv/boosting/receive">
                        <i class="nav-main-link-icon fa fa-history"></i>
                        <span class="nav-main-link-name">Dịch vụ đã nhận</span>
                    </a>
                </li>
                ';
}
echo '                ';
if ($data_user['ctv_account'] == 1) {
    echo '                <li class="nav-main-item">
                    <a class="nav-main-link" href="/ctv/account/sold">
                        <i class="nav-main-link-icon fa fa-shopping-cart"></i>
                        <span class="nav-main-link-name">Tài khoản đã bán</span>
                    </a>
                </li>
                ';
}
echo '                <li class="nav-main-item">
                    <a class="nav-main-link" href="/ctv/withdraw">
                        <i class="nav-main-link-icon fa fa-shopping-cart"></i>
                        <span class="nav-main-link-name">Rút tiền CTV</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>';
