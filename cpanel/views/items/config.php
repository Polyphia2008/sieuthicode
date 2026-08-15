<?php
// statically decompiled from config.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['SaveSettings']) && is_admin_account($data_user)) {
    if (check_img('thumb_items')) {
        $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 3);
        $uploads_dir = '../../../upload/theme/thumb_' . $rand . '.png';
        $tmp_name = $_FILES['thumb_items']['tmp_name'];
        $add = move_uploaded_file($tmp_name, $uploads_dir);
        if ($add) {
            $db->update('options', ['value' => '/upload/theme/thumb_' . $rand . '.png'], ' `key` = \'thumb_items\' ');
        }
    }
    foreach ($_POST as $__key => $value) {
        $key = $__key;
        $db->update('options', ['value' => $value], ' `key` = \'' . $key . '\' ');
    }
    exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <h4 class="page-title fw-semibold fs-18 mb-0">Cấu hình shop items</h4>
            <div class="ms-md-1 ms-0">
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Shop items</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Cấu hình</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <div class="block-title">
                    CẤU HÌNH
                </div>
            </div>
            <div class="block-content">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Tên danh mục</label>
                                    <input type="text" class="form-control" name="title_shop_items" placeholder="" value="';
    echo $db->site('title_shop_items');
    echo '">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Giao dịch ảo</label>
                                    <input type="number" class="form-control" name="fake_items" placeholder="" value="';
    echo $db->site('fake_items');
    echo '">
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="form-group">
                                    <label for="exampleInputFile">Thumbnail</label>
                            
                                    <img class="w-100 active mb-1" id="img_1" src="/assets/back-end/img/image-size.png">

                                    <div class="custom-file text-left">
                                        <input type="file" name="thumb_items" class="form-control image-preview-before-upload" data-preview="#viewer" accept=".jpg, .png, .jpeg, .gif, .bmp, .tif, .tiff|image/*" onchange="document.getElementById(\'img_1\').src = window.URL.createObjectURL(this.files[0])">
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <img width="500px" src="';
    echo $db->site('thumb_items');
    echo '" />
                                <hr>
                            </div>
                        </div>
                        <div class="form-group mb-3">
                            <label>Status</label>
                            <select class="form-control select2bs4" name="status_items">
                                <option ';
    echo $db->site('status_items') == 1 ? 'selected' : '';
    echo ' value="1">ON
                                </option>
                                <option ';
    echo $db->site('status_items') == 0 ? 'selected' : '';
    echo ' value="0">
                                    OFF
                                </option>
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label for="exampleInputEmail1">Lưu ý</label>
                            <textarea id="notice_items" name="notice_items">';
    echo $db->site('notice_items');
    echo '</textarea>
                        </div>
                    </div>
                    <div class="card-footer clearfix">
                        <button name="SaveSettings" class="btn btn-info btn-icon-left m-b-10" type="submit"><i class="fas fa-save mr-1"></i>Lưu Ngay</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
   
    <script>
        const confirmAction = (id) => {
            Swal.fire({
                title: \'Xác Nhận!\',
                text: "Bạn đồng ý thực hiện xóa bank " + id,
                icon: \'warning\',
                showCancelButton: true,
                confirmButtonColor: \'#3085d6\',
                cancelButtonColor: \'#d33\',
                confirmButtonText: \'Đồng ý\',
                cancelButtonText: \'Hủy\'
            }).then(async (confirm) => {
                if (confirm.isConfirmed) {
                    await Item(id);
                }
            });
        }

        const Item = async (id) => {
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
                url: \'/model/admin/delete\',
                method: "POST",
                dataType: "JSON",
                data: {
                    action: \'removeBank\',
                    id: id
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
    echo '    <script>
        CKEDITOR.replace("notice_items");
    </script>';
}
