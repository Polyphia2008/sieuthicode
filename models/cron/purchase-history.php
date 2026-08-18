<?php
// cPanel cron: php /absolute/path/models/cron/purchase-history.php
if (PHP_SAPI !== 'cli') { http_response_code(403); exit('CLI only'); }
$_SERVER['DOCUMENT_ROOT'] = realpath(__DIR__ . '/../..');
$_SERVER['HTTP_HOST'] = 'localhost';
require_once $_SERVER['DOCUMENT_ROOT'] . '/libs/init.php';
$cutoff=time()-14*86400;
$rows=$db->get_list("SELECT `id`,`id_acc` FROM `history_buy` WHERE CAST(`created_at` AS UNSIGNED)>0 AND CAST(`created_at` AS UNSIGNED)<'".$cutoff."'");
$ids=array_map(function($r){return (int)$r['id'];},$rows);
$accountIds=array_values(array_filter(array_map(function($r){return (int)$r['id_acc'];},$rows)));
$db->query('START TRANSACTION');$ok=true;
if($ids){$list=implode(',',$ids);$ok=$db->query("DELETE FROM `reviews` WHERE `type`='account' AND `history_id` IN (".$list.")")!==false;}
if($accountIds){$ok=$db->query("DELETE FROM `accounts` WHERE `id` IN (".implode(',',$accountIds).")")!==false&&$ok;}
$ok=$db->query("DELETE FROM `history_buy` WHERE CAST(`created_at` AS UNSIGNED)>0 AND CAST(`created_at` AS UNSIGNED)<'".$cutoff."'")!==false&&$ok;
if(!$ok){$db->query('ROLLBACK');fwrite(STDERR,"purge failed\n");exit(1);} $db->query('COMMIT');echo 'purged '.count($ids)." purchase histories\n";
