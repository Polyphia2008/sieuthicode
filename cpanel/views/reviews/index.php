<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_POST['AddTag']) && $data_user['level'] == 'admin') {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $url_icon = null;
        if (check_img('image')) {
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/tag/tag' . $rand . '.png';
            $tmp_name = $_FILES['image']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $url_icon = $uploads_dir;
            }
        }
        $isInsert = $db->insert('tag', ['name' => Anti_xss($_POST['name']), 'images' => $url_icon, 'create_date' => gettime()]);
        if ($isInsert) {
            insetLog($data_user['id'], 'Thêm nhãn dán mới vào hệ thống.');
            exit('<script type="text/javascript">if(!alert("Thêm thành công !")){window.history.back().location.reload();}</script>');
        } else {
            exit('<script type="text/javascript">if(!alert("Thêm thất bại !")){window.history.back().location.reload();}</script>');
        }
    }
} else {
    echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Đánh giá
                </h1>
            </div>
            
        </div>
    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh đánh giá từ khách hàng</h3>
            </div>
            <div class="block-content block-content-full overflow-x-auto">

                <table class="table table-borderless table-striped table-vcenter" id="datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th class="text-center">Khách hàng</th>
                            <th class="text-center">Người bán</th>
                            <th class="text-center">Số sao</th>
                            <th class="text-center">Nội dung</th>
                            <th class="text-center">Thời gian</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        ';
    foreach ($db->get_list(' SELECT * FROM `reviews` ORDER BY `id` ASC') as $row) {
        echo '                            <tr>

                                <td>';
        echo $row['id'];
        echo '</td>
                                <td class="text-center"><a class="text-primary" href="/cpanel/user/edit/';
        echo $row['user_id'];
        echo '">';
        echo getRowUser($row['user_id'], 'username');
        echo ' [ID ';
        echo $row['user_id'];
        echo ']</a>
                                    </td>
                                <td class="text-center">';
        echo $row['seller'];
        echo '</td>
                                <td class="text-center">';
        echo $row['rating'];
        echo ' sao</td>
                                <td class="text-center">';
        echo $row['review'];
        echo '</td>
                                <td class="text-center">';
        echo $row['created_at'];
        echo '</td>
                                <td class="text-center fs-sm">
                                    <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="confirmAction(';
        echo $row['id'];
        echo ')">
                                        <i class="fa fa-fw fa-times"></i>
                                    </a>
                                </td>
                            </tr>
                        ';
    }
    echo '                    </tbody>
                </table>

            </div>
        </div>

    </div>
</main>

<script>
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });

    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa đánh giá " + id,
            icon: \'warning\',
            showCancelButton: true,
            confirmButtonColor: \'#3085d6\',
            cancelButtonColor: \'#d33\',
            confirmButtonText: \'Đồng ý\',
            cancelButtonText: \'Hủy\'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: \'/model/admin/delete\',
                    method: "POST",
                    dataType: "JSON",
                    data: {
                        action: \'removeReview\',
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
        });
    }
</script>
';
    require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
}
