<?php
// statically decompiled from functions.php  [structured; all 57 record(s) structured]

$rsa = new RSACrypt();

function Anti_xss($data)
{
    $data = str_replace(['&amp;', '&lt;', '&gt;'], ['&amp;amp;', '&amp;lt;', '&amp;gt;'], $data);
    $data = preg_replace('/(&#*\\w+)[\\x00-\\x20]+;/u', '$1;', $data);
    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
    $data = preg_replace('#(<[^>]+?[\\x00-\\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
    $data = preg_replace('#([a-z]*)[\\x00-\\x20]*=[\\x00-\\x20]*([`\'"]*)[\\x00-\\x20]*j[\\x00-\\x20]*a[\\x00-\\x20]*v[\\x00-\\x20]*a[\\x00-\\x20]*s[\\x00-\\x20]*c[\\x00-\\x20]*r[\\x00-\\x20]*i[\\x00-\\x20]*p[\\x00-\\x20]*t[\\x00-\\x20]*:#iu', '$1=$2nojavascript...', $data);
    $data = preg_replace('#([a-z]*)[\\x00-\\x20]*=([\'"]*)[\\x00-\\x20]*v[\\x00-\\x20]*b[\\x00-\\x20]*s[\\x00-\\x20]*c[\\x00-\\x20]*r[\\x00-\\x20]*i[\\x00-\\x20]*p[\\x00-\\x20]*t[\\x00-\\x20]*:#iu', '$1=$2novbscript...', $data);
    $data = preg_replace('#([a-z]*)[\\x00-\\x20]*=([\'"]*)[\\x00-\\x20]*-moz-binding[\\x00-\\x20]*:#u', '$1=$2nomozbinding...', $data);
    $data = preg_replace('#(<[^>]+?)style[\\x00-\\x20]*=[\\x00-\\x20]*[`\'"]*.*?expression[\\x00-\\x20]*\\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\\x00-\\x20]*=[\\x00-\\x20]*[`\'"]*.*?behaviour[\\x00-\\x20]*\\([^>]*+>#i', '$1>', $data);
    $data = preg_replace('#(<[^>]+?)style[\\x00-\\x20]*=[\\x00-\\x20]*[`\'"]*.*?s[\\x00-\\x20]*c[\\x00-\\x20]*r[\\x00-\\x20]*i[\\x00-\\x20]*p[\\x00-\\x20]*t[\\x00-\\x20]*:*[^>]*+>#iu', '$1>', $data);
    $data = preg_replace('#</*\\w+:\\w[^>]*+>#i', '', $data);
    $query_string = $_SERVER['QUERY_STRING'];
    $sql_injection = ['union', 'coockie', 'concat', 'alter', 'exec', 'shell', 'wget', '**/', '/**', '0x3a', 'null', 'DR/**/OP/', 'drop', '/*', '*/', '*', '--', ';', '||', '\' #', 'or 1=1', '\'1\'=\'1', 'BUN', 'S@BUN', 'char', 'OR%', '`', '[', ']', '<', '>', '++', 'script', '1,1', 'substring', 'ascii', 'sleep(', 'insert', 'between', 'values', 'truncate', 'benchmark', 'sql', 'mysql', '%27', '%22', '(', ')', '<?', '<?php', '?>', '../', '/localhost', '127.0.0.1', 'loopback', ':', '%0A', '%0D', '%3C', '%3E', '%00', '%2e%2e', 'input_file', 'execute', 'mosconfig', 'environ', 'scanner', 'path=.', 'mod=.', 'eval\\(', 'javascript:', 'base64_', 'boot.ini', 'etc/passwd', 'self/environ', 'md5', 'echo.*kae', '=%27$', '\'', '"'];
    foreach ($sql_injection as $key) {
        if (255 < strlen($query_string) || strpos(strtolower($query_string), strtolower($key)) !== false) {
            new Redirect('/');
        }
    }
    $data = addslashes(trim($data));
    while (true) {
        $old_data = $old_data;
        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        if (!($old_data !== $data)) { break; }

    }
    return $data;
}

function format_cash($price)
{
    return str_replace(',', '.', number_format($price));
}

function custom_cal_days_in_month($month, $year)
{
    if ($month < 1 || 12 < $month || $year < 0) {
        return false;
    } else {
        $nextMonth = $month % 12 + 1;
        $nextYear = $month == 12 ? $year + 1 : $year;
        $lastDayOfNextMonth = mktime(0, 0, 0, $nextMonth, 0, $nextYear);
        $numberOfDays = date('d', $lastDayOfNextMonth);
        return $numberOfDays;
    }
}

function qr_bank($type, $stk, $accountname, $amount, $comment)
{
    if ($type == 'MOMO') {
        $result = 'data:image/png;base64,' . base64_encode(file_get_contents('https://chart.googleapis.com/chart?chs=500x500&cht=qr&chl=2|99|' . $stk . '|||0|0|' . $amount . '|' . $comment . '|transfer_myqr'));
    } else {
        $result = 'https://api.vietqr.io/' . $type . '/' . $stk . '/' . $amount . '/' . $comment . '/qronly2.jpg?accountName=' . $accountname;
    }
    return $result;
}

function pagination_client($url, $start, $total, $kmess)
{
    $out = [];
    $out[] = '<ul class="pagination pagination-custom">';
    $neighbors = 4;
    if ($total <= $start) {
        $start = max(0, $total - ($total % $kmess == 0 ? $kmess : $total % $kmess));
    } else {
        $start = max(0, $start - $start % $kmess);
    }
    $base_link = '<li class="page-item"><a class="page-link" href="' . strtr($url, ['%' => '%%']) . 'page=%d">%s</a></li>';
    if (0 < $start) {
        $prev_page = max(0, $start - $kmess) / $kmess + 1;
        $out[] = sprintf('<li class="page-item pre-1"><a class="page-link" href="' . strtr($url, ['%' => '%%']) . 'page=%d" rel="prev"></a></li>', $prev_page);
    }
    if ($kmess * $neighbors < $start) {
        $out[] = sprintf($base_link, 1, '1');
    }
    if ($kmess * ($neighbors + 1) < $start) {
        $out[] = '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }
    $nCont = $tmpStart;
    while (1 <= $nCont) {
        if ($kmess * $nCont <= $start) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        --$nCont;
    }
    $out[] = '<li class="page-item active"><span class="page-link">' . $start / $kmess + 1 . '</span></li>';
    $tmpMaxPages = ($total - 1) / $kmess * $kmess;
    $nCont = 3;
    while ($nCont <= $neighbors) {
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        ++$nCont;
    }
    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages) {
        $out[] = '<li class="page-item disabled"><span class="page-link">...</span></li>';
    }
    if ($start + $kmess * $neighbors < $tmpMaxPages) {
        $out[] = sprintf($base_link, $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    }
    if ($start + $kmess < $total) {
        $next_page = min($total, $start + $kmess) / $kmess + 1;
        $out[] = sprintf('<li class="page-item next-1"><a class="page-link" href="' . strtr($url, ['%' => '%%']) . 'page=%d" rel="next"></a></li>', $next_page);
    }
    $out[] = '</ul>';
    return implode('', $out);
}

function pagination_account($url, $start, $total, $kmess)
{
    $out[] = '<ul class="pagination pagination-custom">';
    $neighbors = 4;
    if ($total <= $start) {
        $start = max(0, $total - ($total % $kmess == 0 ? $kmess : $total % $kmess));
    } else {
        $start = max(0, $start - $start % $kmess);
    }
    $base_link = '<li class="page-item"><a class="page-link" onclick="page=%d;load_account()">%s</a></li>';
    if (0 < $start) {
        $prev_page = $start - $kmess;
        $out[] = sprintf('<li class="page-item pre-1"><a class="page-link" onclick="page=%d;load_account()" rel="prev"></a></li>', $prev_page / $kmess + 1);
    }
    if ($kmess * $neighbors < $start) {
        $out[] = sprintf($base_link, 1, '1');
    }
    if ($kmess * ($neighbors + 1) < $start) {
        $out[] = '<li class="page-item disabled hidden-xs"><span class="page-link">...</span></li>';
    }
    $nCont = $tmpStart;
    while (1 <= $nCont) {
        if ($kmess * $nCont <= $start) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        --$nCont;
    }
    $out[] = '<li class="page-item active"><span class="page-link">' . $start / $kmess + 1 . '</span></li>';
    $tmpMaxPages = ($total - 1) / $kmess * $kmess;
    $nCont = 3;
    while ($nCont <= $neighbors) {
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        ++$nCont;
    }
    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages) {
        $out[] = '<li class="page-item disabled hidden-xs"><span class="page-link">...</span></li>';
    }
    if ($start + $kmess * $neighbors < $tmpMaxPages) {
        $out[] = sprintf($base_link, $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    }
    if ($start + $kmess < $total) {
        $next_page = $start + $kmess;
        $out[] = sprintf('<li class="page-item next-1"><a class="page-link" onclick="page=%d;load_account()" rel="next"></a></li>', $next_page / $kmess + 1);
    }
    $out[] = '</ul>';
    return implode('', $out);
}

function pagination($url, $start, $total, $kmess)
{
    $out[] = ' <div class="paging_simple_numbers"><ul class="pagination">';
    $neighbors = 4;
    if ($total <= $start) {
        $start = max(0, $total - ($total % $kmess == 0 ? $kmess : $total % $kmess));
    } else {
        $start = max(0, $start - $start % $kmess);
    }
    $base_link = '<li class="paginate_button page-item previous "><a class="page-link" href="' . strtr($url, ['%' => '%%']) . 'page=%d' . '">%s</a></li>';
    $out[] = $start == 0 ? '' : sprintf($base_link, $start / $kmess, 'Previous');
    if ($kmess * $neighbors < $start) {
        $out[] = sprintf($base_link, 1, '1');
    }
    if ($kmess * ($neighbors + 1) < $start) {
        $out[] = '<li class="paginate_button page-item previous disabled"><a class="page-link">...</a></li>';
    }
    $nCont = $tmpMaxPages;
    while (1 <= $nCont) {
        if ($kmess * $nCont <= $start) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        --$nCont;
    }
    $out[] = '<li class="paginate_button page-item previous active"><a class="page-link">' . $start / $kmess + 1 . '</a></li>';
    $tmpMaxPages = ($total - 1) / $kmess * $kmess;
    $nCont = 3;
    while ($nCont <= $neighbors) {
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        ++$nCont;
    }
    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages) {
        $out[] = '<li class="paginate_button page-item previous disabled"><a class="page-link">...</a></li>';
    }
    if ($start + $kmess * $neighbors < $tmpMaxPages) {
        $out[] = sprintf($base_link, $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    }
    if ($start + $kmess < $total) {
        $display_page = $total < $start + $kmess ? $total : $start / $kmess + 2;
        $out[] = sprintf($base_link, $display_page, 'Next');
    }
    $out[] = '</ul></div>';
    return implode('', $out);
}

function pagination2($url, $start, $total, $kmess)
{
    $out[] = ' <div class="paging_simple_numbers"><ul class="pagination">';
    $neighbors = 4;
    if ($total <= $start) {
        $start = max(0, $total - ($total % $kmess == 0 ? $kmess : $total % $kmess));
    } else {
        $start = max(0, $start - $start % $kmess);
    }
    $base_link = '<li class="paginate_button page-item previous "><a class="page-link" href="' . strtr($url, ['%' => '%%']) . '%d' . '">%s</a></li>';
    $out[] = $start == 0 ? '' : sprintf($base_link, $start / $kmess, 'Previous');
    if ($kmess * $neighbors < $start) {
        $out[] = sprintf($base_link, 1, '1');
    }
    if ($kmess * ($neighbors + 1) < $start) {
        $out[] = '<li class="paginate_button page-item previous disabled"><a class="page-link">...</a></li>';
    }
    $nCont = $tmpMaxPages;
    while (1 <= $nCont) {
        if ($kmess * $nCont <= $start) {
            $tmpStart = $start - $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        --$nCont;
    }
    $out[] = '<li class="paginate_button page-item previous active"><a class="page-link">' . $start / $kmess + 1 . '</a></li>';
    $tmpMaxPages = ($total - 1) / $kmess * $kmess;
    $nCont = 3;
    while ($nCont <= $neighbors) {
        if ($start + $kmess * $nCont <= $tmpMaxPages) {
            $tmpStart = $start + $kmess * $nCont;
            $out[] = sprintf($base_link, $tmpStart / $kmess + 1, $tmpStart / $kmess + 1);
        }
        ++$nCont;
    }
    if ($start + $kmess * ($neighbors + 1) < $tmpMaxPages) {
        $out[] = '<li class="paginate_button page-item previous disabled"><a class="page-link">...</a></li>';
    }
    if ($start + $kmess * $neighbors < $tmpMaxPages) {
        $out[] = sprintf($base_link, $tmpMaxPages / $kmess + 1, $tmpMaxPages / $kmess + 1);
    }
    if ($start + $kmess < $total) {
        $display_page = $total < $start + $kmess ? $total : $start / $kmess + 2;
        $out[] = sprintf($base_link, $display_page, 'Next');
    }
    $out[] = '</ul></div>';
    return implode('', $out);
}

function myip()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } else {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    }
    return $ip;
}

function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function check_email($data)
{
    if (preg_match('/^.+@.+$/', $data, $matches)) {
        return true;
    } else {
        return false;
    }
}

function gettime()
{
    return date('Y/m/d H:i:s', time());
}

function random($string, $int)
{
    return substr(str_shuffle($string), 0, $int);
}

function JsonMsg($status, $msg)
{
    return json_encode(['status' => $status, 'msg' => $msg]);
}

function status_withdraw_orders($status)
{
    $statusMapping = [2 => '<button class="el-button el-button--success el-button--small">Đã thanh toán</button>', 1 => '<button class="el-button el-button--primary el-button--small">Bị hủy</button>', 0 => '<button class="el-button el-button--warning el-button--small">Đang chờ</button>'];
    return $statusMapping[$status] ?? '<span class="ant-tag css-eq3tly ant-tag-red">Khác</span>';
}

function status_withdraw_orders_admin($status)
{
    $statusMapping = [2 => '<span class="badge bg-success rounded-lg text-white" style="background-color: #1A5D1A">Đã thanh toán</span>', 1 => '<span class="badge bg-danger rounded-lg text-white" style="background-color: #FF6666">Bị hủy</span>', 0 => '<span class="badge bg-warning rounded-lg" style="background-color: #FFC436">Đang chờ</span>'];
    return $statusMapping[$status] ?? '<span class="ant-tag css-eq3tly ant-tag-red">Khác</span>';
}

function getDurationMappingValue($duration)
{
    $durationMapping = ['monthly' => '1 Tháng', 'twomonthly' => '2 Tháng', 'quarterly' => '3 Tháng', 'semi_annually' => '6 Tháng', 'annually' => '1 Năm', 'biennially' => '2 Năm', 'triennially' => '3 Năm'];
    return isset($durationMapping[$duration]) ? $durationMapping[$duration] : '';
}

function isValidPassword($password)
{
    if (preg_match('/[#&\\/]/', $password)) {
        return false;
    } else {
        return true;
    }
}

function encryptData($data)
{
    global $rsa;
    $rsa->setPrivateKey(realpath($_SERVER['DOCUMENT_ROOT']) . '/security/clientPrivate.pem');
    $rsa->setPublicKey(realpath($_SERVER['DOCUMENT_ROOT']) . '/security/serverPublic.pem');
    return $rsa->encryptWithPublicKey($data);
}

function decodecryptData($data)
{
    global $rsa;
    $rsa->setPrivateKey(realpath($_SERVER['DOCUMENT_ROOT']) . '/security/serverPrivate.pem');
    $rsa->setPublicKey(realpath($_SERVER['DOCUMENT_ROOT']) . '/security/clientPublic.pem');
    return $rsa->decryptWithPrivateKey($data);
}

function check_img($img)
{
    $filename = $_FILES[$img]['name'];
    $ext = explode('.', $filename);
    $ext = end($ext);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($ext, $arr_type)) {
        return true;
    }
}

function parse_order_id($des, $memo)
{
    $re = '/' . $memo . '\\d+/im';
    preg_match_all($re, $des, $matches, PREG_SET_ORDER, 0);
    if (count($matches) == 0) {
        return null;
    } else {
        $orderCode = $matches[0][0];
        $prefixLength = strlen($memo);
        $orderId = substr($orderCode, $prefixLength);
        return $orderId;
    }
}

function timeAgo($time_ago)
{
    $time_ago = date('Y-m-d H:i:s', $time_ago);
    $time_ago = strtotime($time_ago);
    $cur_time = time();
    $time_elapsed = $cur_time - $time_ago;
    $seconds = $days;
    $minutes = round($time_elapsed / 60);
    $hours = round($time_elapsed / 3600);
    $days = round($time_elapsed / 86400);
    $weeks = round($time_elapsed / 604800);
    $months = round($time_elapsed / 2600640);
    $years = round($time_elapsed / 31207680);
    if ($seconds <= 60) {
        return $seconds . ' giây trước';
    } else {
        if ($minutes <= 60) {
            return $minutes . ' phút trước';
        } else {
            if ($hours <= 24) {
                return $hours . ' tiếng trước';
            } else {
                if ($days <= 7) {
                    if ($days == 1) {
                        return 'Hôm qua';
                    } else {
                        return $days . ' ngày trước';
                    }
                } else {
                    if ($weeks <= 4.3) {
                        return $weeks . ' tuần trước';
                    } else {
                        if ($months <= 12) {
                            return $months . ' tháng trước';
                        } else {
                            return $years . ' năm trước';
                        }
                    }
                }
            }
        }
    }
}

function create_slug($string)
{
    $search = ['#(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)#', '#(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)#', '#(ì|í|ị|ỉ|ĩ)#', '#(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)#', '#(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)#', '#(ỳ|ý|ỵ|ỷ|ỹ)#', '#(đ)#', '#(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)#', '#(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)#', '#(Ì|Í|Ị|Ỉ|Ĩ)#', '#(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)#', '#(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)#', '#(Ỳ|Ý|Ỵ|Ỷ|Ỹ)#', '#(Đ)#', '/[^a-zA-Z0-9\\-\\_]/'];
    $replace = ['a', 'e', 'i', 'o', 'u', 'y', 'd', 'A', 'E', 'I', 'O', 'U', 'Y', 'D', '-'];
    $string = preg_replace($search, $replace, $string);
    $string = preg_replace('/(-)+/', '-', $string);
    $string = strtolower($string);
    return $string;
}

function toslug($str)
{
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9-\\s]/', '', $str);
    $str = preg_replace('/([\\s]+)/', '', $str);
    return $str;
}

function upload_multiple_file($name, $folder, $i = 0)
{
    $rand = rand(0, 99999999999999);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];
    $destination_path = realpath($_SERVER['DOCUMENT_ROOT']);
    $path = $destination_path . '/upload/' . $folder . '/';
    if ($_FILES[$name]['error'][$i] == 0) {
        $arr = explode('.', $_FILES[$name]['name'][$i]);
        if (in_array(strtolower(end($arr)), $arr_type)) {
            move_uploaded_file($_FILES[$name]['tmp_name'][$i], $path . md5($_FILES[$name]['name'][$i] . $rand) . '.' . end($arr));
        }
        $image = 'upload/' . $folder . '/' . md5($_FILES[$name]['name'][$i] . $rand) . '.' . end($arr);
    }
    return $image;
}

function upload_file($name, $folder)
{
    $rand = rand(0, 99999999999999);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];
    $destination_path = realpath($_SERVER['DOCUMENT_ROOT']);
    $path = $destination_path . '/upload/' . $folder . '/';
    if ($_FILES[$name]['error'] == 0) {
        $arr = explode('.', $_FILES[$name]['name']);
        if (in_array(strtolower(end($arr)), $arr_type)) {
            move_uploaded_file($_FILES[$name]['tmp_name'], $path . md5($_FILES[$name]['name'] . $rand) . '.' . end($arr));
        }
        $image = 'upload/' . $folder . '/' . md5($_FILES[$name]['name'] . $rand) . '.' . end($arr);
    }
    return $image;
}

function update_file($name, $old_link, $folder)
{
    $rand = rand(0, 99999999999999);
    $arr_type = ['jpg', 'jpeg', 'png', 'gif'];
    $destination_path = realpath($_SERVER['DOCUMENT_ROOT']);
    $path = $destination_path . '/upload/' . $folder . '/';
    if ($_FILES[$name]['error'] == 0) {
        $arr = explode('.', $_FILES[$name]['name']);
        if (in_array(strtolower(end($arr)), $arr_type)) {
            move_uploaded_file($_FILES[$name]['tmp_name'], $path . md5($_FILES[$name]['name'] . $rand) . '.' . end($arr));
        }
        $image = 'upload/' . $folder . '/' . md5($_FILES[$name]['name'] . $rand) . '.' . end($arr);
    } else {
        $image = $destination_path;
    }
    return $image;
}

function to_slug($str)
{
    $str = trim(mb_strtolower($str));
    $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
    $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
    $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
    $str = preg_replace('/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
    $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
    $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
    $str = preg_replace('/(đ)/', 'd', $str);
    $str = preg_replace('/[^a-z0-9-\\s]/', '', $str);
    $str = preg_replace('/([\\s]+)/', '-', $str);
    return $str;
}

function generate_csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function checkFormatCard($type, $seri, $pin)
{
    $seri = strlen($seri);
    $pin = strlen($pin);
    $data = [];
    if ($type == 'Viettel' || $type == 'Viettel' || $type == 'VT' || $type == 'VIETTEL') {
        if ($seri != 11 && $seri != 14) {
            $data = ['status' => false, 'msg' => 'Độ dài seri không phù hợp'];
            return $data;
        } else {
            if ($pin != 13 && $pin != 15) {
                $data = ['status' => false, 'msg' => 'Độ dài mã thẻ không phù hợp'];
                return $data;
            } else {
                if ($type == 'Mobifone' || $type == 'Mobifone' || $type == 'Mobi' || $type == 'MOBIFONE') {
                    if ($seri != 15) {
                        $data = ['status' => false, 'msg' => 'Độ dài seri không phù hợp'];
                        return $data;
                    } else {
                        if ($pin != 12) {
                            $data = ['status' => false, 'msg' => 'Độ dài mã thẻ không phù hợp'];
                            return $data;
                        } else {
                            if ($type == 'VNMB' || $type == 'Vnmb' || $type == 'VNM' || $type == 'VNMOBI') {
                                if ($seri != 16) {
                                    $data = ['status' => false, 'msg' => 'Độ dài seri không phù hợp'];
                                    return $data;
                                } else {
                                    if ($pin != 12) {
                                        $data = ['status' => false, 'msg' => 'Độ dài mã thẻ không phù hợp'];
                                        return $data;
                                    } else {
                                        if ($type == 'Vinaphone' || $type == 'Vinaphone' || $type == 'Vina' || $type == 'VINAPHONE') {
                                            if ($seri != 14) {
                                                $data = ['status' => false, 'msg' => 'Độ dài seri không phù hợp'];
                                                return $data;
                                            } else {
                                                if ($pin != 14) {
                                                    $data = ['status' => false, 'msg' => 'Độ dài mã thẻ không phù hợp'];
                                                    return $data;
                                                } else {
                                                    if ($type == 'Garena' || $type == 'Garena') {
                                                        if ($seri != 9) {
                                                            $data = ['status' => false, 'msg' => 'Độ dài seri không phù hợp'];
                                                            return $data;
                                                        } else {
                                                            if ($pin != 16) {
                                                                $data = ['status' => false, 'msg' => 'Độ dài mã thẻ không phù hợp'];
                                                                return $data;
                                                            } else {
                                                                if ($type == 'Zing' || $type == 'Zing' || $type == 'ZING') {
                                                                    if ($seri != 12) {
                                                                        $data = ['status' => false, 'msg' => 'Độ dài seri không phù hợp'];
                                                                        return $data;
                                                                    } else {
                                                                        if ($pin != 9) {
                                                                            $data = ['status' => false, 'msg' => 'Độ dài mã thẻ không phù hợp'];
                                                                            return $data;
                                                                        } else {
                                                                            if ($type == 'Vcoin' || $type == 'VTC') {
                                                                                if ($seri != 12) {
                                                                                    $data = ['status' => false, 'msg' => 'Độ dài seri không phù hợp'];
                                                                                    return $data;
                                                                                } else {
                                                                                    if ($pin != 12) {
                                                                                        $data = ['status' => false, 'msg' => 'Độ dài mã thẻ không phù hợp'];
                                                                                        return $data;
                                                                                    } else {
                                                                                        $data = ['status' => true, 'msg' => 'Success'];
                                                                                        return $data;
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

function display_service($status)
{
    $statusMapping = ['pending' => '<span class="text-warning">Đang chờ</span>', 'completed' => '<span class="text-success">Hoàn thành</span>', 'cancelled' => '<span class="text-danger">Đã hủy</span>', 'cancelled_refund' => '<span class="text-danger">Hủy đơn hoàn tiền</span>', 'error_refund' => '<span class="text-danger">Lỗi đơn hoàn tiền</span>', 'progress' => '<span class="text-danger">Đang chạy</span>', 'partial' => '<span class="text-danger">Chạy thiếu (Đã hoàn tiền)</span>', 'processing' => '<span class="text-warning">Đang xử lý</span>'];
    return $statusMapping[$status] ?? '<span class="text-warning">Khác</span>';
}

function display_service_admin($status)
{
    $statusMapping = ['pending' => '<span class="badge bg-warning">Đang chờ</span>', 'completed' => '<span class="badge bg-success">Hoàn thành</span>', 'cancelled' => '<span class="badge bg-danger">Đã hủy</span>', 'cancelled_refund' => '<span class="badge bg-danger">Hủy đơn hoàn tiền</span>', 'error_refund' => '<span class="badge bg-danger">Lỗi đơn hoàn tiền</span>', 'progress' => '<span class="badge bg-warning">Đang chạy</span>', 'partial' => '<span class="badge bg-danger">Chạy thiếu (Đã hoàn tiền)</span>', 'processing' => '<span class="badge bg-warning">Đang xử lý</span>'];
    return $statusMapping[$status] ?? '<span class="badge bg-warning">Khác</span>';
}

function display_card($status)
{
    $statusMapping = ['pending' => '<span class="text-warning">Đang chờ xử lý</span>', 'completed' => '<span class="text-success">Thành công</span>', 'error' => '<span class="text-danger">Thất bại</span>'];
    return $statusMapping[$status] ?? '<span class="badge bg-warning">Khác</span>';
}

function rank_recharge($top)
{
    $statusMapping = [1 => '/assets/svg/1.svg', 2 => '/assets/svg/2.svg', 3 => '/assets/svg/3.svg', 4 => '/assets/svg/4.svg', 5 => '/assets/svg/5.svg', 6 => '/assets/images/6.png'];
    return $statusMapping[$top] ?? '<span class="badge bg-warning">Khác</span>';
}

function status_withdraw($data)
{
    $statuses = [0 => '<span class="badge bg-info">Đang xử lý</span>', 2 => '<span class="badge bg-success">Đã thanh toán</span>', 1 => '<span class="badge bg-danger">Hủy</span>'];
    return isset($statuses[$data]) ? $statuses[$data] : '';
}

function convertBankImage($bank)
{
    $statusMapping = ['mbbank' => '/assets/bank/mbbank.png', 'acb' => '/assets/bank/acb.png', 'vietcombank' => '/assets/bank/vcb.png', 'bidv' => '/assets/bank/bidv.png', 'techcombank' => '/assets/bank/techcombank.png', 'vietinbank' => '/assets/bank/vietinbank.png'];
    return $statusMapping[strtolower(str_replace(' ', '', $bank))] ?? '';
}

function getHourAndMinute($dateTimeString)
{
    $dateTime = new DateTime($dateTimeString);
    $hour = $dateTime->format('H');
    $minute = $dateTime->format('i');
    return sprintf('%02d:%02d', $hour, $minute);
}

function getFlashSaleStatus($startDateTimeString, $endDateTimeString)
{
    $currentTime = time();
    $startTime = strtotime($startDateTimeString);
    $endTime = strtotime($endDateTimeString);
    if ($currentTime < $startTime) {
        return ['status' => 1, 'msg' => 'Sắp diễn ra'];
    } else {
        if ($endTime < $currentTime) {
            return ['status' => 0, 'msg' => 'Đã kết thúc'];
        } else {
            return ['status' => 2, 'msg' => 'Đang diễn ra'];
        }
    }
}

function calculateDiscountPercentage($oldPrice, $newPrice)
{
    if ($oldPrice <= 0 || $newPrice < 0 || $oldPrice <= $newPrice) {
        return 0;
    } else {
        return round(($oldPrice - $newPrice) / $oldPrice * 100, 2);
    }
}

function calculateDiscountFromAmount($currentPrice, $discountAmount)
{
    if ($currentPrice <= 0 || $discountAmount < 0 || $currentPrice <= $discountAmount) {
        return 0;
    } else {
        return round($discountAmount / ($currentPrice + $discountAmount) * 100, 2);
    }
}

function convertToDateOnly($datetime_string)
{
    $datetime = new DateTime($datetime_string);
    return $datetime->format('Y-m-d H:i:s');
}

function canPlay($spinQuest)
{
    $arr = isset($spinQuest['prizes']) ? $spinQuest['prizes'] : [];
    $arr = json_decode($arr, true);
    $totalPercent = 2;
    foreach ($arr as $item) {
        $totalPercent += isset($item['percent']) ? $item['percent'] : 0;
    }
    if ($totalPercent === 0) {
        return false;
    } else {
        return true;
    }
}

function playGame($spinQuest)
{
    $items = isset($spinQuest['prizes']) ? $spinQuest['prizes'] : [];
    $items = json_decode($items, true);
    $weightedItems = [];
    foreach ($items as $__key => $item) {
        $index = $__key;
        if (!($item['percent'] === 0)) {
            $i = 2;
            while ($i < $item['percent']) {
                $weightedItems[] = $index;
                ++$i;
            }
        }
    }
    $randomIndex = $weightedItems[array_rand($weightedItems)] + 1;
    $location = null;
    switch ($randomIndex) {
        case 1:
            $location = 3;
            break;
        case 2:
            $location = 4;
            break;
        case 3:
            $location = 5;
            break;
        case 4:
            $location = 6;
            break;
        case 5:
            $location = 7;
            break;
        case 6:
            $location = 8;
            break;
        case 7:
            $location = 9;
            break;
        case 8:
            $location = 10;
            break;
        default:
            $location = null;
            break;
    }
    return ['data' => isset($items[$randomIndex - 1]) ? $items[$randomIndex - 1] : null, 'location' => $location];
}

function tryGame($spinQuest)
{
    $items = isset($spinQuest['prizes']) ? $spinQuest['prizes'] : [];
    $items = json_decode($items, true);
    if (empty($items)) {
        return ['data' => null, 'location' => null];
    } else {
        $randomIndex = array_rand($items) + 1;
        $location = null;
        switch ($randomIndex) {
            case 1:
                $location = 3;
                break;
            case 2:
                $location = 4;
                break;
            case 3:
                $location = 5;
                break;
            case 4:
                $location = 6;
                break;
            case 5:
                $location = 7;
                break;
            case 6:
                $location = 8;
                break;
            case 7:
                $location = 9;
                break;
            case 8:
                $location = 10;
                break;
            default:
                $location = null;
                break;
        }
        return ['data' => isset($items[$randomIndex - 1]) ? $items[$randomIndex - 1] : null, 'location' => $location];
    }
}

function obfuscateUsername($username)
{
    $lastThree = mb_substr($username, -3);
    $firstThree = mb_substr($username, 0, 3);
    return '***' . $firstThree . $lastThree . '**';
}

function saveViewedProduct($product_id)
{
    $expiry = time() + 3600;
    if (isset($_COOKIE['viewed_products'])) {
        $viewed_products = json_decode($_COOKIE['viewed_products'], true);
    } else {
        $viewed_products = [];
    }
    if (!in_array($product_id, $viewed_products)) {
        $viewed_products[] = $product_id;
        setcookie('viewed_products', json_encode($viewed_products), $expiry, '/');
    }
}

function removeViewedProduct($product_id)
{
    if (isset($_COOKIE['viewed_products'])) {
        $viewed_products = json_decode($_COOKIE['viewed_products'], true);
        $key = array_search($product_id, $viewed_products);
        if ($key !== false) {
            /* UNSET_DIM */
            $viewed_products = array_values($viewed_products);
            $expiry = time() + 43200;
            setcookie('viewed_products', json_encode($viewed_products), $expiry, '/');
            $_COOKIE['viewed_products'] = json_encode($viewed_products);
        }
    }
}

function countViewedProducts()
{
    if (isset($_COOKIE['viewed_products'])) {
        $viewed_products = json_decode($_COOKIE['viewed_products'], true);
        return count($viewed_products);
    } else {
        return 0;
    }
}

function loadViewedProducts()
{
    if (isset($_COOKIE['viewed_products'])) {
        $viewed_products = json_decode($_COOKIE['viewed_products'], true);
        return $viewed_products;
    } else {
        return [];
    }
}

function isValidPhoneNumber($phone)
{
    $phone = preg_replace('/\\s+|\\+/', '', $phone);
    return preg_match('/^(0[2-9]{1}[0-9]{8,9})$/', $phone);
}

function checkPhoneNumberLength($phone)
{
    $phone = preg_replace('/[^0-9]/', '', $phone);
    if (empty($phone)) {
        return ['valid' => false, 'message' => 'Số điện thoại không được để trống'];
    } else {
        $length = strlen($phone);
        if ($length < 10 || 11 < $length) {
            return ['valid' => false, 'message' => 'Số điện thoại phải có 10 hoặc 11 số'];
        } else {
            return ['valid' => true, 'message' => 'Số điện thoại hợp lệ', 'cleaned_number' => $phone];
        }
    }
}

function tinhKetQua($so_tien, $he_so)
{
    return round($so_tien / 1000 * $he_so);
}

function checkLicense($purchase_code, $domain, $type = 'SHOPNICKV5')
{
    $curl = curl_init();
    curl_setopt_array($curl, [CURLOPT_URL => 'https://license.sieuthicode.net/api/verify-purchase-code', CURLOPT_RETURNTRANSFER => true, CURLOPT_POST => true, CURLOPT_POSTFIELDS => ['purchase_code' => $purchase_code, 'domain' => $domain, 'type' => $type]]);
    $response = curl_exec($curl);
    curl_close($curl);
    $data = json_decode($response, true);
    if ($data && $data['status'] === 'success' && $data['status_code'] === 200) {
        return true;
    } else {
        return false;
    }
}

function checkAccessAttempts($max_attempts = 5)
{
    global $db;
    $ip_address = myip();
    $attempt = $db->get_row('SELECT * FROM `failed_attempts` WHERE `ip_address` = \'' . $ip_address . '\' AND `type` = \'Spam Request\' ');
    if ($attempt && $max_attempts <= $attempt['attempts']) {
        $db->insert('banned_ips', ['ip' => $ip_address, 'attempts' => $attempt['attempts'], 'create_gettime' => gettime(), 'banned' => 1, 'reason' => 'Spam Request']);
        $db->remove('failed_attempts', ' `ip_address` = \'' . $ip_address . '\' ');
        return true;
    } else {
        if ($attempt) {
            $db->cong('failed_attempts', 'attempts', 1, ' `ip_address` = \'' . $ip_address . '\' ');
        } else {
            $db->insert('failed_attempts', ['ip_address' => $ip_address, 'attempts' => 1, 'type' => 'Spam Request', 'create_gettime' => gettime()]);
        }
        return true;
    }
}

class Redirect
{
    public function __construct($url = null)
    {
        if ($url) {
            echo '<script>location.href="' . $url . '";</script>';
        }
    }
}
