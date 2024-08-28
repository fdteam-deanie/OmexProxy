<?php

$token = 'eyJhbGciOiJIUzUxMiIsInR5cCI6IkpXVCJ9.eyJyb2xlIjoiSW5kaXZpZHVhbCIsInVzZXJJZCI6Ink2cUVmYnN5OVZtcm1qTXp1QzZya0E9PSIsImVtYWlsIjoidjVYRVZZVnMyNHh6RlFvRmVuYTAzOTE3YVZ6U2dMZ3ZUbFpvTmZTcGdkVmZCeEIvUHVLbXBTdTJUbE92ZkpFUnlEWWhmdUlvNEZ0a0N4alh0RXZQNGc9PSIsIm5iZiI6MTY5NzY0MzkwOSwiZXhwIjoxNjk3NjQ1NzA5LCJpYXQiOjE2OTc2NDM5MDl9.ef2R7JEOyoib-My_c8zeHaZ2JTuuFziNM1-Ij3BLph8knGn0ZCaIyIX0kmlINFz-g81vivDvC5H6ZSK70Vn94w';

$agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/118.0.0.0 Safari/537.36';

$headers = [

	':authority: lift-api.vfsglobal.com',
	':method: POST',
	':path: /appointment/CheckIsSlotAvailable',
	':scheme: https',
	'Accept: application/json, text/plain, */*',
	'Accept-Encoding: gzip, deflate, br',
	'Accept-Language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
	'Authorize: '.$token,
	'Content-Type: application/json;charset=UTF-8',
	'Content-Length: '.$length,
	'Origin: https://visa.vfsglobal.com',
	'Referer: https://visa.vfsglobal.com/',
	'Route: blr/en/pol',
	'User-Agent: '.$agent,

];

$ci = curl_init();

curl_setopt($ci, CURLOPT_URL, 'https://lift-api.vfsglobal.com/appointment/CheckIsSlotAvailable');
curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ci, CURLOPT_COOKIEFILE, 'c.txt');
curl_setopt($ci, CURLOPT_COOKIEJAR, 'c.txt');
curl_setopt($ci, CURLOPT_POST, 1);
curl_setopt($ci, CURLOPT_POSTFIELDS, $post);

$ce = curl_exec($ci);
curl_close($ci);

echo $ce;

?>