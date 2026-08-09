<?php
// statically decompiled from helper.php  [structured; all 1 record(s) structured]

$domain = $_SERVER['HTTP_HOST'];
$purchase_code = $db->site('purchase_code') ?? '';
if (!checkLicense($purchase_code, $domain)) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btnSaveLicense'])) {
        if ($db->site('status_demo') != 0) {
            echo '<script>alert("Không được dùng chức năng này vì đây là trang web demo."); history.back();</script>';
            exit();
        } else {
            foreach ($_POST as $__key => $value) {
                $key = $__key;
                $db->update('options', ['value' => $value], ' `key` = \'' . $key . '\' ');
            }
            if (!checkLicense($db->site('purchase_code'), $domain)) {
                echo '<script>alert("Bản quyền không hợp lệ"); history.back();</script>';
                exit();
            } else {
                echo '<script>alert("Lưu thành công!"); history.back();</script>';
                exit();
            }
        }
    } else {
        echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea , #764ba2);
            color: #fff;
        }
        .card {
            /* background: rgba(255, 255, 255, 0.1); */
            /* backdrop-filter: blur(15px); */
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        }
        h3 {
            font-size: 2.2rem;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .btn-primary {
            background: #f5576c;
            border: none;
            padding: 0.85rem;
            font-size: 1.3rem;
            border-radius: 50px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: #f5576c;
            transform: translateY(-7px);
        }
        input.form-control:focus {
            
            box-shadow: none;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="card p-5">
        
        <h3 class="text-center mb-4">Kích Hoạt Bản Quyền</h3>

        <form method="POST">
            <div class="mb-4">
                <label for="purchase_code" class="form-label">Mã bản quyền (license key)</label>
                <input type="text" class="form-control" id="purchase_code" name="purchase_code" placeholder="Nhập mã bản quyền" value="';
        echo htmlspecialchars($db->site('purchase_code'));
        echo '" required>
            </div>

            <button type="submit" class="btn btn-primary w-100" name="btnSaveLicense">Kích Hoạt</button>
        </form>
    </div>

</body>

</html>
';
        exit();
    }
}
