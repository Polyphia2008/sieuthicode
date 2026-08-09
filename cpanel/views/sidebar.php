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
                    <a class="nav-main-link" href="/cpanel/home">
                        <i class="nav-main-link-icon fa fa-dashboard"></i>
                        <span class="nav-main-link-name">Dashboard</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/security">
                        <i class="nav-main-link-icon fa fa-shield-alt"></i>
                        <span class="nav-main-link-name">Bảo mật</span>
                    </a>
                </li>
                <li class="nav-main-heading">QUẢN LÝ GAME</li>
              
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Dịch vụ game</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/category/view">
                                <span class="nav-main-link-name">Danh mục chính</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/subcategory/view">
                                <span class="nav-main-link-name">Danh mục phụ</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-border-all"></i>
                        <span class="nav-main-link-name">Quản lý shop items</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/items/config">
                                <span class="nav-main-link-name">Cấu hình</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/items/category">
                                <span class="nav-main-link-name">Danh mục</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/items/orders">
                                <span class="nav-main-link-name">Đơn hàng <span class="badge bg-danger rounded-pill">';
echo format_cash($db->get_row('SELECT COUNT(id) as total FROM order_items WHERE `status` = \'pending\'')['total'] ?? 0);
echo '</span></span>
                            </a>
                        </li>
                    </ul>
                </li> -->
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/flashsale">
                        <i class="nav-main-link-icon fa fa-bolt"></i>
                        <span class="nav-main-link-name">Flash Sale</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                    <i class="nav-main-link-icon fa fa-gamepad"></i>
                        <span class="nav-main-link-name">Vòng quay</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/spin/unit">
                                <span class="nav-main-link-name">Cấu hình rút</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/spin">
                                <span class="nav-main-link-name">Vòng quay</span>
                            </a>
                        </li>
                    </ul>
                </li>
               
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-vector-square"></i>
                        <span class="nav-main-link-name">Dịch vụ</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/boosting/view">
                                <span class="nav-main-link-name">Danh mục</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/subboosting/view">
                                <span class="nav-main-link-name">Danh mục phụ</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/boosting/orders">
                                <span class="nav-main-link-name">Đơn hàng <span class="badge bg-danger rounded-pill">';
echo orderPendingTotal();
echo '</span></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/tag">
                        <i class="nav-main-link-icon fa fa-tag"></i>
                        <span class="nav-main-link-name">Nhãn dán</span>
                    </a>
                </li>
                <li class="nav-main-heading">GIAO DỊCH</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/account/sold">
                        <i class="nav-main-link-icon fa fa-shopping-cart"></i>
                        <span class="nav-main-link-name">Tài khoản đã bán</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-vector-square"></i>
                        <span class="nav-main-link-name">Rút tiền CTV</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/withdraw/config">
                                <span class="nav-main-link-name">Cấu hình</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/withdraw/orders">
                                <span class="nav-main-link-name">Đơn rút tiền <span class="badge bg-danger rounded-pill">';
echo orderWithdrawCtvPendingTotal();
echo '</span></span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/minigame/withdraws">
                        <i class="nav-main-link-icon fa fa-vector-square"></i>
                        <span class="nav-main-link-name">Rút vật phẩm</span>
                    </a>
                </li>
                <li class="nav-main-heading">QUẢN LÝ</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/users/list">
                        <i class="nav-main-link-icon fa fa-user-friends"></i>
                        <span class="nav-main-link-name">Thành viên</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-wallet"></i>
                        <span class="nav-main-link-name">Nạp tiền</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/recharge/card">
                                <span class="nav-main-link-name">Thẻ cào</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/recharge">
                                <span class="nav-main-link-name">Ngân hàng</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/recharge/top">
                                <span class="nav-main-link-name">Top nạp tiền</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-handshake"></i>
                        <span class="nav-main-link-name">Tiếp thị liên kết</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/affiliate/history">
                                <span class="nav-main-link-name">Nhật ký hoa hồng</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/affiliate/withdraw">
                                <span class="nav-main-link-name">Rút tiền</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/affiliate/config">
                                <span class="nav-main-link-name">Cấu hình</span>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/reviews">
                        <i class="nav-main-link-icon fa fa-comment-dots"></i>
                        <span class="nav-main-link-name">Đánh giá</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/promotions">
                        <i class="nav-main-link-icon fa fa-percent"></i>
                        <span class="nav-main-link-name">Khuyến mãi nạp tiền</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-blog"></i>
                        <span class="nav-main-link-name">Bài viết</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/blog/add">
                                <span class="nav-main-link-name">Viết bài mới</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/blog/list">
                                <span class="nav-main-link-name">Tất cả bài viết</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/blog/category">
                                <span class="nav-main-link-name">Chuyên mục</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-main-heading">HỆ THỐNG</li>
                <li class="nav-main-item">
                    <a class="nav-main-link nav-main-link-submenu" data-toggle="submenu" aria-haspopup="true" aria-expanded="false" href="#">
                        <i class="nav-main-link-icon fa fa-blog"></i>
                        <span class="nav-main-link-name">Tiện ích</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <!-- <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/tick/list">
                                <span class="nav-main-link-name">TICK</span>
                            </a>
                        </li> -->
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/pr/list">
                                <span class="nav-main-link-name">PR</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/cpanel/link/list">
                                <span class="nav-main-link-name">Link liên kết</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/banner">
                        <i class="nav-main-link-icon fa fa-image"></i>
                        <span class="nav-main-link-name">Banner</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/theme">
                        <i class="nav-main-link-icon fa fa-image"></i>
                        <span class="nav-main-link-name">Giao diện</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/cpanel/settings">
                        <i class="nav-main-link-icon fa fa fa-cog"></i>
                        <span class="nav-main-link-name">Cấu hình</span>
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
<script>
    $(document).ready(function() {
        var url = window.location.pathname;
        var urlRegExp = new RegExp(url.replace(/\\/$/, \'\') + "$");
        $(\'.nav-main-link\').each(function() {
            if (urlRegExp.test(this.href.replace(/\\/$/, \'\'))) {
                $(this).addClass(\'active\');
            }
        });
    });
</script>';
