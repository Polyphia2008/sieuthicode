<?php
/**
 * The former remote self-updater has been retired.
 *
 * Deploy updates through Git/cPanel File Manager instead. Automatically
 * downloading and extracting executable PHP from a remote server is unsafe and
 * unreliable on shared hosting.
 */
http_response_code(410);
header('Content-Type: text/plain; charset=UTF-8');
exit('Trình cập nhật tự động đã được tắt. Hãy cập nhật source qua Git hoặc cPanel File Manager.');
