<?php
// statically decompiled from top.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddTop']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $isInsert = $db->insert('top', ['username' => Anti_xss($_POST['username']), 'amount' => Anti_xss($_POST['amount']), 'created_at' => time()]);
        if ($isInsert) {
            exit('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
        } else {
            exit('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
        }
    }
} else {
    if (isset($_POST['btnSaveOption']) && is_admin_account($data_user)) {
        if ($db->site('status_demo') != 0) {
            exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
        } else {
            foreach ($_POST as $__key => $value) {
                $key = $__key;
                $db->update('options', ['value' => $value], ' `key` = \'' . $key . '\' ');
            }
            exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
        }
    } else {
        echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Top nạp</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Nạp tiền</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Top nạp</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    TOP 10 NGƯỜI NẠP TIỀN THÁNG ';
        echo date('m');
        echo '                </div>
                <div class="d-flex">
                    <button data-bs-toggle="modal" data-bs-target="#exampleModalScrollable2" class="btn btn-sm btn-primary btn-wave waves-light waves-effect waves-light"><i class="ri-add-line fw-semibold align-middle"></i> Thêm top ảo</button>
                </div>
            </div>
            <div class="block-content">
                <div class="row items-push">
                    ';
        $i = 3;
        $day = date('m-Y');
        foreach ($db->get_list('SELECT SUM(amount) as total,username FROM `top` WHERE DATE_FORMAT(FROM_UNIXTIME(top.created_at), \'%m-%Y\') = \'' . $day . '\' GROUP BY `username` ORDER BY `total` DESC LIMIT 10') as $top) {
            echo '                        <div class="col-md-6 col-xl-3">
                            <div class="form-check form-block">

                                <label class="form-check-label" for="dm-project-new-people-1">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <img class="img-avatar img-avatar48" src="/assets/images/avt.png" alt="">
                                            <span class="ms-2">
                                                <span class="fw-bold">';
            echo $top['username'];
            echo '</span>
                                                <span class="d-block text-danger">';
            echo format_cash($top['total']);
            echo 'đ</span>
                                            </span>
                                        </div>
                                       <i role="button" class="fas fa-trash text-danger" onclick="confirmAction(`';
            echo $top['username'];
            echo '`)"></i>
                                    </div>

                                </label>
                            </div>
                        </div>
                    ';
        }
        echo '                </div>
            </div>
        </div>
    </div>
    <div class="content">

        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">CẤU HÌNH</hê>
            </div>
            <div class="block-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="row mb-3">
                                <label class="col-sm-6 col-form-label" for="example-hf-email">Thưởng top nạp</label>
                                <div class="col-sm-12">
                                    <textarea id="notice_topnap" name="notice_topnap">';
        echo $db->site('notice_topnap');
        echo '</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a type="button" class="btn btn-danger" href=""><i class="fa fa-fw fa-undo me-1"></i>
                        Reload</a>
                    <button type="submit" name="btnSaveOption" class="btn btn-primary">
                        <i class="fa fa-fw fa-save me-1"></i> Lưu Ngay </button>
                </form>
            </div>
        </div>

    </div>
</main>
<div class="modal fade" id="exampleModalScrollable2" tabindex="-1" aria-labelledby="exampleModalScrollable2" data-bs-keyboard="false" aria-hidden="true">
    <!-- Scrollable modal -->
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="staticBackdropLabel2">Thêm top nạp ảo</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tài khoản</label>
                                <input class="form-control" type="text" placeholder="Tên người dùng" name="username">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Số tiền</label>
                                <input class="form-control" type="number" placeholder="Số tiền" name="amount">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" name="AddTop" class="btn btn-primary btn-sm"><i class="fa fa-fw fa-plus me-1"></i>
                        Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    CKEDITOR.replace("notice_topnap");
    const confirmAction = (user) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa top nạp " + user,
            icon: \'warning\',
            showCancelButton: true,
            confirmButtonColor: \'#3085d6\',
            cancelButtonColor: \'#d33\',
            confirmButtonText: \'Đồng ý\',
            cancelButtonText: \'Hủy\'
        }).then(async (confirm) => {
            if (confirm.isConfirmed) {
                await Item(user);
            }
        });
    }

    const Item = async (user) => {
        Swal.fire({
            icon: "info",
            title: "Đang xử lý!",
            html: "Không được tắt trang này, vui lòng đợi trong giây lát!",
            timerProgressBar: true,
            allowOutsideClick: false,
            allowEscapeKey: false,
            allowEnterKey: false,
            didOpen: () => {
                Swal.showLoading();
            },
            willClose: () => {},
        });

        $.ajax({
            url: \'/model/admin/top\',
            method: "POST",
            dataType: "JSON",
            data: {
                user: user
            },
            success: function(result) {
                if (result.status == \'success\') {
                    Swal.fire(\'Thành công\',
                        `${result.msg}`,
                        \'success\').then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire(\'Thất Bại\', result.msg, \'error\');
                }
            },
            error: function(xhr, status, error) {
                Swal.fire(\'Thất Bại\', xhr.responseText, \'error\');
            }
        });
    }

</script>
';
        require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
    }
}
