<?php
// statically decompiled from reffer.php  [structured; all 1 record(s) structured]

require_once realpath($_SERVER['DOCUMENT_ROOT']) . '/libs/init.php';
if (isset($_GET['ref'])) {
    $user_id = Anti_xss($_GET['ref']);
    $row = $db->get_row('SELECT * FROM `users` WHERE `id` = \'' . $user_id . '\' AND `banned` = 0');
    if ($row) {
        $_SESSION['ref'] = $row['id'];
        $db->cong('users', 'ref_click', 1, ' `id` = \'' . $row['id'] . '\' ');
        new Redirect('/');
    }
    new Redirect('/');
}
new Redirect('/');
echo ' ';
