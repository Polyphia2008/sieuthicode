<?php
// statically decompiled from update.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && is_admin_account($data_user)) {
    $id = Anti_xss($_GET['id']);
    $spinQuest = $db->get_row('SELECT * FROM `spin_quests` WHERE `id` = \'' . $id . '\' ');
    if (!$spinQuest) {
        new Redirect('/cpanel/spin');
    }
    $prizes = json_decode($spinQuest['prizes'], true);
} else {
    new Redirect('/cpanel/spin');
}
if (isset($_POST['UpdateSpin']) && is_admin_account($data_user)) {
    if ($db->site('status_demo') != 0) {
        exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
    } else {
        $cover = null;
        $image = null;
        if (check_img('cover')) {
            unlink('../../..' . $spinQuest['cover']);
            $rand = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dir = '/upload/spin/spin' . $rand . '.png';
            $tmp_name = $_FILES['cover']['tmp_name'];
            $addlogo = move_uploaded_file($tmp_name, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dir);
            if ($addlogo) {
                $db->update('spin_quests', ['cover' => $uploads_dir], ' `id` = \'' . $spinQuest['id'] . '\' ');
            }
        }
        if (check_img('image')) {
            unlink('../../..' . $spinQuest['image']);
            $rands = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dirs = '/upload/spin/spin' . $rands . '.png';
            $tmp_names = $_FILES['image']['tmp_name'];
            $addlogos = move_uploaded_file($tmp_names, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dirs);
            if ($addlogos) {
                $db->update('spin_quests', ['image' => $uploads_dirs], ' `id` = \'' . $spinQuest['id'] . '\' ');
            }
        }
        if (check_img('play')) {
            unlink('../../..' . $spinQuest['play']);
            $randss = random('0123456789QWERTYUIOPASDGHJKLZXCVBNM', 4);
            $uploads_dirss = '/upload/spin/spin' . $randss . '.png';
            $tmp_namess = $_FILES['play']['tmp_name'];
            $addlogoss = move_uploaded_file($tmp_namess, realpath($_SERVER['DOCUMENT_ROOT']) . $uploads_dirss);
            if ($addlogoss) {
                $db->update('spin_quests', ['play' => $uploads_dirss], ' `id` = \'' . $spinQuest['id'] . '\' ');
            }
        }
        $isInsert = $db->update('spin_quests', ['name' => Anti_xss($_POST['name']), 'price' => Anti_xss($_POST['price']), 'sale' => Anti_xss($_POST['sale']), 'descr' => $_POST['descr']], ' `id` = \'' . $spinQuest['id'] . '\' ');
        if ($isInsert) {
            insetLog($data_user['id'], 'Chỉnh sửa vòng quay (' . Anti_xss($_POST['name']) . ' ID ' . $spinQuest['id'] . ').');
            exit('<script type="text/javascript">if(!alert("Lưu thành công !")){window.history.back().location.reload();}</script>');
        } else {
            exit('<script type="text/javascript">if(!alert("Lưu thất bại !")){window.history.back().location.reload();}</script>');
        }
    }
} else {
    if (isset($_POST['UpdatePrice']) && is_admin_account($data_user)) {
        if ($db->site('status_demo') != 0) {
            exit('<script type="text/javascript">if(!alert("Đây là trang web demo bạn không thể thực hiện chức năng này !")){window.history.back().location.reload();}</script>');
        } else {
            if (!isset($_POST['prizes']) || !(is_array($_POST['prizes']))) {
                exit('<script type="text/javascript">if(!alert("Giá trị không hợp lệ")){window.history.back().location.reload();}</script>');
            } else {
                $prizes = $_POST['prizes'];
                $formattedPrizes = [];
                foreach ($prizes as $prize) {
                    $formattedPrizes[] = ['percent' => $prize['percent'], 'value' => $prize['value'], 'text' => $prize['text'], 'min' => $prize['min'] ?? null, 'max' => $prize['max'] ?? null, 'random' => $prize['random'] ?? null];
                }
                $prizesJson = json_encode($formattedPrizes);
                $isUpdate = $db->update('spin_quests', ['prizes' => $prizesJson, 'updated_at' => gettime()], ' `id` = \'' . $spinQuest['id'] . '\' ');
                if ($isUpdate) {
                    insetLog($data_user['id'], 'Cập nhật giải thưởng vòng quay (' . $spinQuest['name'] . ')');
                    exit('<script type="text/javascript">if(!alert("Cập nhật thành công !")){window.history.back().location.reload();}</script>');
                } else {
                    exit('<script type="text/javascript">if(!alert("Cập nhật thất bại !")){window.history.back().location.reload();}</script>');
                }
            }
        }
    } else {
        echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Cập nhật vòng quay [';
        echo $spinQuest['name'];
        echo ']
                </h1>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="cover" class="form-label">Ảnh Bìa</label>
                                <input class="form-control" type="file" id="cover" name="cover">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Ảnh Vòng Quay</label>
                                <input class="form-control" type="file" id="image" name="image">
                            </div>
                            <div class="mb-3">
                                <label for="image" class="form-label">Nút Quay</label>
                                <input class="form-control" type="file" id="play" name="play">
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="name" class="form-label">Tên Vòng Quay</label>
                                    <input class="form-control" type="text" id="name" name="name" value="';
        echo $spinQuest['name'];
        echo '" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="price" class="form-label">Giá Mỗi Lượt</label>
                                    <input class="form-control" type="text" id="price" name="price" value="';
        echo $spinQuest['price'];
        echo '" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="price" class="form-label">Khuyến mãi</label>
                                    <input class="form-control" type="text" id="sale" name="sale" value="';
        echo $spinQuest['sale'];
        echo '" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="descr" class="form-label">Hướng Dẫn Chơi</label>
                                <textarea class="form-control ckeditor" id="descr" name="descr" rows="3">';
        echo $spinQuest['descr'];
        echo '</textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Trạng thái</label>
                                <select class="form-control" id="status" name="status">
                                    <option value="1" ';
        echo $spinQuest['status'] == 1 ? 'selected' : '';
        echo '>Hoạt động</option>
                                    <option value="0" ';
        echo $spinQuest['status'] == 0 ? 'selected' : '';
        echo '>Không hoạt động</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary w-100" type="submit" name="UpdateSpin">Cập Nhật</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body text-start">
                        <form action="" method="POST">
                            <div class="row">
                                ';
        $i = 2;
        while ($i < 8) {
            echo '                                    <div class="col-md-3 mb-3">
                                      
                                        <a class="block block-link-pop block-rounded block-themed block-fx-shadow" href="javascript:void(0)">
                                            <div class="block-header">
                                                <h3 class="block-title">
                                                    <i class="fa fa-gift me-1"></i>Phần Thưởng #';
            echo $i;
            echo '                                                 
                                                </h3>
                                            </div>
                                           
                                            <div class="block-content">
                                                <div class="py-2">
                                                    <div class="mb-3">
                                                        <label for="percent" class="form-label">Tỉ Lệ</label>
                                                        <input class="form-control" type="text" id="percent" name="prizes[';
            echo $i;
            echo '][percent]" value="';
            echo $prizes[$i]['percent'] ?? 10;
            echo '" placeholder="10" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="value" class="form-label">Giá Trị</label>
                                                        <input class="form-control" type="text" id="value" name="prizes[';
            echo $i;
            echo '][value]" value="';
            echo $prizes[$i]['value'] ?? 0;
            echo '" placeholder="0" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="value" class="form-label">Thông báo trúng thưởng</label>
                                                        <input class="form-control" type="text" id="text" name="prizes[';
            echo $i;
            echo '][text]" value="';
            echo $prizes[$i]['text'] ?? 0;
            echo '" placeholder="0" required>
                                                    </div>
                                                </div>
                                            </div>

                                        </a>
                                    </div>
                                ';
            ++$i;
        }
        echo '                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary w-100" type="submit" name="UpdatePrice">Cập Nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

';
        require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
    }
}
