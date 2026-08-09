<?php
// statically decompiled from account.php  [structured; all 1 record(s) structured]

$title = 'Dashboard';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/header.php';
if (isset($_GET['id']) && $data_user['level'] == 'admin') {
    $id = Anti_xss($_GET['id']);
    $row = $db->get_row('SELECT * FROM `flash_sales` WHERE `id` = \'' . $id . '\' ');
    if (!$row) {
        new Redirect('/cpanel/flashsale');
    }
    $products = $db->get_list('SELECT * FROM `accounts` WHERE `status` = \'on\' ORDER BY `id` DESC');
    $productOptions = [];
    foreach ($products as $product) {
        $detail = json_decode($product['detail'], true);
        $arr_detail = $detail['data'];
        $productOptions[] = ['id' => $product['id'], 'text' => 'Người đăng: ' . $product['username_post'] . ' - ID Acc: ' . $product['id'] . ' - Tài khoản: ' . decodecryptData($arr_detail[0]['value']) . ' - Giá: ' . format_cash($product['money'] - $product['money'] * $product['sale'] / 100)];
    }
} else {
    new Redirect('/cpanel/flashsale');
}
echo '<style>
    /* styles.css */

    .loading-spinner {
        display: none;
        width: 3rem;
        height: 3rem;
        border: 0.4rem solid rgba(255, 0, 0, 0.3);
        border-top: 0.4rem solid #ff0000;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>
<main id="main-container">
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Thêm danh mục phụ</h3>
            </div>
            <div class="block-content">
            <div class="alert alert-danger alert-dismissible" role="alert">
            <h3 class="alert-heading fs-4 my-2">Lưu ý</h3>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <p class="mb-0">Giá giảm có nghĩa là giá tiền giảm cho tài khoản đó</p>
            <p class="mb-0">Ví dụ: Tài khoản gốc giá là 300.000, set giá giảm 20.000 thì Flash Sale bán ra là 280.000</p>
          </div>
                <form id="addProductsToFlashSaleForm">
                    <div id="productList" class="product-list">
                        <div class="product-item" data-index="0">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="productID_0">Sản Phẩm:</label>
                                    <select id="productID_0" name="productID[]" class="form-control js-select2" required>
                                        ';
foreach ($productOptions as $product) {
    echo '                                            <option value="';
    echo $product['id'];
    echo '">';
    echo $product['text'];
    echo '</option>
                                        ';
}
echo '                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="discountPrice_0">Giá Giảm:</label>
                                    <input type="number" id="discountPrice_0" name="discountPrice[]" class="form-control" placeholder="Giá Giảm" required>
                                </div>
                                <div class="col-md-2 mb-3">
                                    <label for="">Thao tác</label><br>
                                    <button type="button" class="btn btn-danger btn-block remove-product">Xóa</button>
                                </div>
                            </div>
                            <div class="loading-spinner" id="loadingSpinner"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3 mb-3">Thêm Sản Phẩm</button>
                    <button type="button" id="addMoreProducts" class="btn btn-primary mt-3 mb-3">Thêm Sản Phẩm Khác</button>
                </form>

            </div>
        </div>

    </div>
    <div class="content">
        <div class="block block-rounded">
            <div class="block-header block-header-default">
                <h3 class="block-title">Danh sách flash sale [';
echo $row['name'];
echo ']</h3>
            </div>

            <div class="block-content">
                <div class="table-responsive">
                    <table class="table table-striped" id="datatable">
                        <thead class="thead-light">
                            <tr>
                                <th>ID</th>
                                <th>ID sản phẩm</th>
                                <th>Hình ảnh</th>
                                <th>Người đăng</th>
                                <th>Tài khoản</th>
                                <th>Giá gốc</th>
                                <th>Khuyến mại</th>
                                <th>Trạng thái</th>
                                <th>Xóa</th>
                                <th>Thời gian</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
foreach ($db->get_list(' SELECT * FROM `flash_sale_products` WHERE `flash_sale_id` = \'' . $id . '\' ORDER BY `id` DESC') as $info) {
    $account = $db->get_row('SELECT * FROM `accounts` WHERE `id` = \'' . $info['product_id'] . '\'');
    $check = $db->get_row('SELECT * FROM `subcategory` WHERE `type_category` = \'' . $account['type_category'] . '\'');
    $detail_product = json_decode($check['detail'], true);
    $arr_img = json_decode($account['image'], true);
    $detail = json_decode($account['detail'], true);
    if ($account['status'] == 'on') {
        $status = 'Chưa bán';
    } else {
        if ($account['status'] == 'off') {
            $status = 'Đã bán';
        }
    }
    echo '                                <tr>
                                    <td>';
    echo $info['id'];
    echo '</td>
                                    <td>';
    echo $info['product_id'];
    echo '</td>
                                    <td>';
    echo $account['type'] == 'ACCOUNT' ? '<img width="100px" src="' . DOMAIN . '/' . $arr_img[0] . '">' : '<img width="100px" src="' . DOMAIN . '/' . $detail_product['thumb'] . '">';
    echo '</td>
                                    <td class="text-center">';
    echo $account['username_post'];
    echo '</td>
                                    <td class="text-center">';
    echo decodecryptData($arr_detail[0]['value']);
    echo '</td>
                                    <td class="text-center">';
    echo format_cash($account['money'] - $account['money'] * $account['sale'] / 100);
    echo '</td>
                                    <td class="text-center">';
    echo format_cash($info['discount_price']);
    echo '</td>
                                    <td class="text-center">';
    echo $status;
    echo '</td>
                                    <td class="text-center fs-sm">
                                        <a class="btn btn-sm btn-danger" href="javascript:void(0)" onclick="confirmAction(';
    echo $info['id'];
    echo ')">
                                            <i class="fa fa-fw fa-times"></i>
                                        </a>
                                    </td>
                                    <td class="text-center">';
    echo $info['created_at'];
    echo '</td>
                                </tr>
                            ';
}
echo '                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener(\'DOMContentLoaded\', function() {
        let productCount = 1;
        const productOptions = ';
echo json_encode($productOptions);
echo ';
        const loadingSpinner = document.getElementById(\'loadingSpinner\');

        function generateProductOptionsHTML() {
            return productOptions.map(product => `<option value="${product.id}">${product.text}</option>`).join(\'\');
        }

        function showLoading() {
            loadingSpinner.style.display = \'block\';
        }

        function hideLoading() {
            loadingSpinner.style.display = \'none\';
        }

        function initializeSelect2() {
            $(\'.js-select2\').select2();
        }

        // Initialize select2 for the first select element
        initializeSelect2();
        // Thêm sản phẩm khác
        document.getElementById(\'addMoreProducts\').addEventListener(\'click\', function() {
            showLoading();

            setTimeout(() => {
                const productList = document.getElementById(\'productList\');
                const newProductItem = document.createElement(\'div\');
                newProductItem.classList.add(\'product-item\');
                newProductItem.setAttribute(\'data-index\', productCount);

                newProductItem.innerHTML = `
                        <div class="row align-items-center">
                            <div class="col-md-6 mb-3">
                                <label for="productID_${productCount}">Sản Phẩm:</label>
                                <select id="productID_${productCount}" name="productID[]" class="form-control js-select2" required>
                                    ${generateProductOptionsHTML()}
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="discountPrice_${productCount}">Giá Giảm:</label>
                                <input type="number" id="discountPrice_${productCount}" name="discountPrice[]" class="form-control" placeholder="Giá Giảm" required>
                            </div>
                            <div class="col-md-2 mb-3">
                                 <label for="">Thao tác</label><br>
                                <button type="button" class="btn btn-danger btn-block remove-product">Xóa</button>
                            </div>
                        </div>
                    `;

                productList.appendChild(newProductItem);
                initializeSelect2();
                productCount++;
                hideLoading();
            }, 500); // Thời gian giả lập quá trình tải
        });

        // Xóa sản phẩm
        document.getElementById(\'productList\').addEventListener(\'click\', function(event) {
            if (event.target.classList.contains(\'remove-product\')) {
                showLoading();

                setTimeout(() => {
                    const productItem = event.target.closest(\'.product-item\');
                    productItem.remove();
                    hideLoading();
                }, 500); // Thời gian giả lập quá trình tải
            }
        });

        // Xử lý form submit
        document.getElementById(\'addProductsToFlashSaleForm\').addEventListener(\'submit\', function(event) {
            event.preventDefault();

            const productIDs = Array.from(document.querySelectorAll(\'select[name="productID[]"]\')).map(select => select.value);
            const discountPrices = Array.from(document.querySelectorAll(\'input[name="discountPrice[]"]\')).map(input => input.value);

            const requestData = productIDs.map((productID, index) => ({
                flashSaleID: ';
echo $id;
echo ',
                productID: productID,
                discountPrice: discountPrices[index]
            }));

            fetch(\'/model/admin/flashsale\', {
                    method: \'POST\',
                    headers: {
                        \'Content-Type\': \'application/json\'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage(data.msg, data.status);
                    } else {
                        showMessage(data.msg, data.status);
                    }
                });
        });
    });


    const confirmAction = (id) => {
        Swal.fire({
            title: \'Xác Nhận!\',
            text: "Bạn đồng ý thực hiện xóa tài khoản khỏi chiến dịch " + id,
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
                        action: \'removeAccountFlashSale\',
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
    $(function() {
        $("#datatable").DataTable({
            "responsive": true,
            "autoWidth": false,
        });
    });
</script>

';
require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/cpanel/views/footer.php';
