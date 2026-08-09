<?php
// statically decompiled from edit.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && $data_user['level'] == 'admin') {
    $id = Anti_xss($_GET['id']);
    $query = $db->get_row('SELECT * FROM `accounts` WHERE `id` = \'' . $id . '\' ');
    if (!$query) {
        new Redirect('/');
    }
    $query_product = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $query['type_category'] . '\'');
    $detail_product = json_decode($query_product['detail'], true);
    $detail = json_decode($query['detail'], true);
    $arr_data = $detail['data'];
} else {
    new Redirect('/');
}
echo '<main id="main-container">
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Chỉnh sửa tài khoản [';
echo $id;
echo ']</h3>
            </div>

            <div class="block-content">
                <form class="form-data" id="form-update">
                    <input hidden name="id" value="';
echo $id;
echo '">
                    <input hidden name="type_category" value="';
echo $query['type_category'];
echo '">
                    <input hidden name="action" value="update">
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label class="form-label">Loại tài khoản</label>
                                <select class="form-control select2bs4" name="type">
                                    <option value="ACCOUNT" ';
echo $query['type'] == 'ACCOUNT' ? 'selected="selected"' : '';
echo '>
                                        Tài khoản</option>
                                    <option value="RANDOM" ';
echo $query['type'] == 'RANDOM' ? 'selected="selected"' : '';
echo '>Vận
                                        may</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 mb-2">
                            <div class="form-group">
                                <label class="form-label">Loại trò chơi</label>
                                <select class="form-control select2bs4" name="type_category" disabled>
                                    ';
foreach ($db->get_list('SELECT DISTINCT `type_category`,`detail` FROM `subcategory` WHERE `type` IN (\'ACCOUNT\',\'RANDOM\')') as $info) {
    $detail = json_decode($info['detail'], true);
    echo '                                        <option value="';
    echo $info['type_category'];
    echo '" ';
    echo $info['type_category'] == $query['type_category'] ? 'selected="selected"' : '';
    echo '>
                                            ';
    echo $detail['name_product'];
    echo '</option>
                                    ';
}
echo '                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Giá tiền</label>
                                <input class="form-control" name="cash" type="number" placeholder="Giá tiền" value="';
echo $query['money'];
echo '">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Khuyến mại %</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" placeholder="Khuyến mại" name="sale" value="';
echo $query['sale'];
echo '">

                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="form-group">
                                <label class="form-label">Trạng thái</label>
                                <select class="form-control select2bs4" name="status">
                                    <option value="on" ';
echo $query['status'] == 'on' ? 'selected="selected"' : '';
echo '>Chưa
                                        bán</option>
                                    <option value="off" ';
echo $query['status'] == 'off' ? 'selected="selected"' : '';
echo '>Đã
                                        bán</option>
                                </select>
                            </div>
                        </div>
                        ';
$i = 2;
while ($i < count($arr_data)) {
    echo '
                            <div class="col-md-4 mb-2">
                                <div class="form-group">
                                    <label class="form-label">';
    echo $arr_data[$i]['label'];
    echo '</label>
                                    ';
    if ($arr_data[$i]['type'] == 'input' || $arr_data[$i]['type'] == 'number' || $arr_data[$i]['type'] == 'password') {
        echo '                                        <input class="form-control" name="';
        echo $arr_data[$i]['name'];
        echo '" placeholder="';
        echo $arr_data[$i]['label'];
        echo '" type="text" value="';
        echo decodecryptData($arr_data[$i]['value']);
        echo '">
                                    ';
    } else {
        if ($arr_data[$i]['type'] == 'select') {
            echo '                                        <select class="form-control select2bs4" name="';
            echo $arr_data[$i]['name'];
            echo '">
                                            ';
            $explode = explode('|', $detail_product['data'][$i]['value']);
            $a = 2;
            while ($a < count($explode)) {
                echo '                                                <option value="';
                echo $explode[$a];
                echo '" ';
                echo decodecryptData($arr_data[$i]['value']) == $explode[$a] ? 'selected="selected"' : '';
                echo '>
                                                    ';
                echo $explode[$a];
                echo '</option>
                                            ';
                ++$a;
            }
            echo '                                        </select>
                                    ';
        }
    }
    echo '                                </div>
                            </div>
                        ';
    ++$i;
}
echo '                        ';
if ($query['type'] == 'ACCOUNT') {
    echo '                            <div class="col-md-12 mb-2">
                                <div class="form-group">
                                    <label class="form-label">Nội dung bằng hình ảnh</label>
                                    <center>
                                        <span class="btn-file">
                                            <input name="image[]" type="file" class="form-control" multiple>
                                        </span>
                                        <br>
                                    </center>
                                    ';
    $arr_image = json_decode($query['image'], true);
    $i = 2;
    while ($i < count($arr_image)) {
        echo '                                        <img class="w-15 active lazyLoad" src="';
        echo DOMAIN . '/' . $arr_image[$i];
        echo '" height="80px" width="128px">
                                    ';
        ++$i;
    }
    echo '                                </div>
                            </div>
                        ';
}
echo '                    </div>
                    <button type="button" id="EditAccount" class="btn btn-success btn-block btn-lg shadow-lg mt-3" onclick="Update()">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</main>
<script>
    function Update() {
        $(\'#EditAccount\').html(\'<i class="fas fa-spinner fa-pulse"></i> Đang xử lý...\').prop(\'disabled\',
            true);
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
        $.ajax({
            url: \'/model/admin/account\',
            type: \'POST\',
            dataType: \'JSON\',
            data: new FormData($(\'form#form-update\')[0]),
            cache: false,
            contentType: false,
            processData: false,
            success: function(data) {
                showMessage(data.msg, data.status);
                $(\'#EditAccount\').html(
                        \'Xác nhận\')
                    .prop(\'disabled\', false);
            }
        });
    }
</script>

';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
