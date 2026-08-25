<?php
function traffic_provider_token($provider){global $db;$r=$db->get_row("SELECT `api_token` FROM `traffic_providers` WHERE `provider`='".$db->escape($provider)."' AND `active`=1 LIMIT 1");if(!$r)return '';try{$v=decodecryptData((string)$r['api_token']);return is_string($v)?$v:'';}catch(Throwable $e){return '';}}
function traffic_http_get($url){if(!function_exists('curl_init'))return [false,'Hosting chưa bật cURL'];$ch=curl_init($url);curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_FOLLOWLOCATION=>false,CURLOPT_CONNECTTIMEOUT=>8,CURLOPT_TIMEOUT=>20,CURLOPT_SSL_VERIFYPEER=>true,CURLOPT_USERAGENT=>'Sieuthicode-Traffic/1.0']);$body=curl_exec($ch);$code=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE);$err=curl_error($ch);curl_close($ch);if($body===false||$code<200||$code>=300)return [false,$err?:('HTTP '.$code)];return [true,(string)$body];}
function traffic_shorten($provider,$destination,$backup=''){
 $destination=trim((string)$destination);if(!filter_var($destination,FILTER_VALIDATE_URL)||!in_array(strtolower(parse_url($destination,PHP_URL_SCHEME)),['http','https'],true))return [false,'URL đích không hợp lệ'];
 $token=traffic_provider_token($provider);if($token==='')return [false,'Provider chưa được cấu hình token'];
 if($provider==='link4m')$url='https://link4m.co/api-shorten/v2?'.http_build_query(['api'=>$token,'url'=>$destination]);
 elseif($provider==='link2m')$url='https://link2m.net/api-shorten/v2?'.http_build_query(['api'=>$token,'url'=>$destination]);
 elseif($provider==='layma')$url='https://api.layma.net/api/admin/shortlink/quicklink?'.http_build_query(['tokenUser'=>$token,'format'=>'json','url'=>$destination,'link_du_phong'=>$backup]);
 else return [false,'Provider không hợp lệ'];
 [$ok,$body]=traffic_http_get($url);if(!$ok)return [false,$body];$json=json_decode($body,true);
 if($provider==='layma'){if(!is_array($json)||empty($json['success'])||empty($json['html']))return [false,'LAYMA trả dữ liệu không hợp lệ'];$short=$json['html'];}
 else {if(!is_array($json)||($json['status']??'')!=='success'||empty($json['shortenedUrl']))return [false,(string)($json['message']??'Provider trả dữ liệu không hợp lệ')];$short=$json['shortenedUrl'];}
 return filter_var($short,FILTER_VALIDATE_URL)?[true,$short]:[false,'Link rút gọn không hợp lệ'];
}
