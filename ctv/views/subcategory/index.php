<?php
// statically decompiled from index.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/ctv/views/header.php';
if (isset($_GET['limit'])) {
    $limit = Anti_xss($_GET['limit']);
} else {
    $limit = 12;
}
if (isset($_GET['page'])) {
    $page = Anti_xss($_GET['page']);
} else {
    $page = 3;
}
$from = ($page - 1) * $limit;
$where = ' `id` > 0 ';
$listDatatable = $db->get_list(' SELECT * FROM `subcategory` WHERE ' . $where . ' ORDER BY `stt` ASC LIMIT ' . $from . ',' . $limit . ' ');
$totalDatatable = $db->num_rows(' SELECT * FROM `subcategory` WHERE ' . $where . ' ORDER BY id DESC ');
$urlDatatable = pagination('/ctv/subcategory/view?', $from, $totalDatatable, $limit);
echo '<main id="main-container">
    <div class="content">
        <div class="d-md-flex justify-content-md-between align-items-md-center py-3 pt-md-3 pb-md-0 text-center text-md-start">
            <div>
                <h1 class="h3 mb-1">
                    Danh mục game
                </h1>
            </div>
        </div>
    </div>
    
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh mục game (';
echo $totalDatatable;
echo ')</h3>
            </div>
            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-borderless table-striped table-vcenter">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th class="text-center">Ảnh</th>
                                <th class="text-center">Tên</th>
                                <th class="text-center">Giá trị</th>
                                <th class="text-center">
                                    Thao tác
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
foreach ($listDatatable as $category) {
    $detail = json_decode($category['detail'], true);
    echo '                                <tr onchange="updateForm(\'';
    echo $category['id'];
    echo '\')">
                                    <td>';
    echo $category['id'];
    echo '</td>
                                    <td width="10%"><img width="100%" src="';
    echo DOMAIN . '/' . $detail['thumb'];
    echo '" /></td>
                                    <td class="text-center">';
    echo $detail['name_product'];
    echo '</td>
                                    <td class="text-center">';
    echo format_cash($db->get_row(' SELECT SUM(`money`) FROM `accounts` WHERE `sub_id` = \'' . $category['id'] . ('\' AND `username_post` = \'' . $data_user['username'] . '\''))['SUM(`money`)'] ?? 0);
    echo '                                    </td>
                                  
                                    <td class="text-center fs-sm">
                                        <a class="btn btn-sm btn-success" href="/ctv/subcategory/account/list/';
    echo $category['id'];
    echo '">
                                            <i class="fa fa-list"></i>
                                            List tài khoản
                                        </a>
                                    </td>
                                </tr>
                            ';
}
echo '                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <p class="dataTables_info">Showing ';
echo $limit;
echo ' of ';
echo format_cash($totalDatatable);
echo ' Results</p>
                    </div>
                    <div class="col-sm-12 col-md-7 mb-3">
                        ';
echo $limit < $totalDatatable ? $urlDatatable : '';
echo '                    </div>
                </div>
            </div>
        </div>
    </div>

</main>
';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/ctv/views/footer.php';
