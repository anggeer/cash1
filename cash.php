<?php 
error_reporting(0);
class curl {
	var $ch, $agent, $error, $info, $cookiefile, $savecookie;	
	function curl() {
		$this->ch = curl_init();
		curl_setopt ($this->ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/530.1 (KHTML, like Gecko) Chrome/2.0.164.0 Safari/530.1');
		curl_setopt ($this->ch, CURLOPT_HEADER, 1);
		curl_setopt ($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt ($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($this->ch, CURLOPT_FOLLOWLOCATION,true);
		curl_setopt ($this->ch, CURLOPT_TIMEOUT, 30);
		curl_setopt ($this->ch, CURLOPT_CONNECTTIMEOUT,30);
	}
	function header($header) {
		curl_setopt ($this->ch, CURLOPT_HTTPHEADER, $header);
	}
	function timeout($time){
		curl_setopt ($this->ch, CURLOPT_TIMEOUT, $time);
		curl_setopt ($this->ch, CURLOPT_CONNECTTIMEOUT,$time);
	}
	function http_code() {
		return curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
	}
	function error() {
		return curl_error($this->ch);
	}
	function ssl($veryfyPeer, $verifyHost){
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $veryfyPeer);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $verifyHost);
	}
	function cookies($cookie_file_path) {
		$this->cookiefile = $cookie_file_path;;
		$fp = fopen($this->cookiefile,'wb');fclose($fp);
		curl_setopt ($this->ch, CURLOPT_COOKIEJAR, $this->cookiefile);
		curl_setopt ($this->ch, CURLOPT_COOKIEFILE, $this->cookiefile);
	}
	function proxy($sock) {
		curl_setopt ($this->ch, CURLOPT_HTTPPROXYTUNNEL, true); 
		curl_setopt ($this->ch, CURLOPT_PROXY, $sock);
	}
	function post($url, $data) {
		curl_setopt($this->ch, CURLOPT_POST, 1);	
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
		return $this->getPage($url);
	}
	function data($url, $data, $hasHeader=true, $hasBody=true) {
		curl_setopt ($this->ch, CURLOPT_POST, 1);
		curl_setopt ($this->ch, CURLOPT_POSTFIELDS, http_build_query($data));
		return $this->getPage($url, $hasHeader, $hasBody);
	}
	function get($url, $hasHeader=true, $hasBody=true) {
		curl_setopt ($this->ch, CURLOPT_POST, 0);
		return $this->getPage($url, $hasHeader, $hasBody);
	}	
	function getPage($url, $hasHeader=true, $hasBody=true) {
		curl_setopt($this->ch, CURLOPT_HEADER, $hasHeader ? 1 : 0);
		curl_setopt($this->ch, CURLOPT_NOBODY, $hasBody ? 0 : 1);
		curl_setopt ($this->ch, CURLOPT_URL, $url);
		$data = curl_exec ($this->ch);
		$this->error = curl_error ($this->ch);
		$this->info = curl_getinfo ($this->ch);
		return $data;
	}
}

function fetchCurlCookies($source) {
	preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $source, $matches);
	$cookies = array();
	foreach($matches[1] as $item) {
		parse_str($item, $cookie);
		$cookies = array_merge($cookies, $cookie);
	}
	return $cookies;
}

function string($length = 15)
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function angka($length = 15)
{
	$characters = '0123456789';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}
function fetch_value($str,$find_start,$find_end) {
	$start = @strpos($str,$find_start);
	if ($start === false) {
		return "";
	}
	$length = strlen($find_start);
	$end    = strpos(substr($str,$start +$length),$find_end);
	return trim(substr($str,$start +$length,$end));
}

function loop($socks,$timeout,$reff) {
	$curl = new curl();
	$curl->cookies('cookies/'.md5($_SERVER['REMOTE_ADDR']).'.txt');
	$curl->ssl(0, 2);
	$curl->timeout($timeout);
	$curl->proxy($socks);


	$deviceId =  string(15);
	$angka1 =  angka(1);
	$angka2 =  angka(2);
	$angka3 =  angka(3);
	$page_api = file_get_contents('https://www.fakenamegenerator.com');
	$address = fetch_value($page_api, '<div class="address">','</div>');
	$name = fetch_value($address, '<h3>','</h3>');
	preg_match_all('/<dl class="dl-horizontal">(.*?)<\/dl>/s', $page_api, $user);
	$mail = fetch_value($user[1][8], '<dd>','<div class="adtl">');
	$mail_p = explode('@', $mail);
	$domain = array ('@gmail.com','@yahoo.com','@mail.com','@yandex.com','@gmx.de','@t-online.de','@yahoo.co.id','@yahoo.co.uk');
	$random = rand(0,7);
	$email  = $mail_p[0].angka(4).$domain[$random];
	$uname = fetch_value($user[1][9], '<dd>','</dd>');
	$username = $uname.angka(4);
	$password = string(8);
	$hash = md5($password);

	
	$headers = array();
	$headers[] = 'Host: offercashapp.com';
	$headers[] = 'User-Agent: Dalvik/2.1.0 (Linux; U; Android 7.1.2; Redmi 4X MIUI/8.5.31)';
	$headers[] = 'Connection: Keep-Alive';
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';
	$curl->header($headers);

	$page = $curl->post('http://offercashapp.com/TNF6H9LcrLM7OloBKP76/register.php', 'name='.$name.'&password='.$hash.'&email='.$email.'&hash=ZvOhowBCLCOdGW6BMZSP&reff='.$reff.'&did=2'.$angka1.'6cdac1a'.$angka3.'a'.$angka2.'d');

	if (stripos($page, '"status":2')) {
		$post = $curl->post('http://offercashapp.com/TNF6H9LcrLM7OloBKP76/getPoints.php', '&password='.$hash.'&email='.$email.'&hash=ZvOhowBCLCOdGW6BMZSP&did=2'.$angka1.'6cdac1a'.$angka3.'a'.$angka2.'d');

		if (stripos($post, '"status":2')) {
			echo "SUCCESS\n";
			flush();
			ob_flush();
		} else {
			echo "FAILED\n";
			flush();
			ob_flush();
		}
	} else {
		echo "SOCKS DIE | ".$socks."\n";
		flush();
		ob_flush();
	}
}

echo "CREATED BY YUDHA TIRA PAMUNGKAS\n";
echo "Reff (Ex: OC74BDDX): ";
$reff = trim(fgets(STDIN));
if ($reff == "") {
	die ("Refferal cannot be blank!\n");
}
echo "Name file proxy (Ex: proxy.txt): ";
$namefile = trim(fgets(STDIN));
if ($namefile == "") {
	die ("Proxy cannot be blank!\n");
}
echo "Timeout : ";
$timeout = trim(fgets(STDIN));
if ($timeout == "") {
	die ("Cannot be blank!\n");
}
echo "Please wait";
sleep(1);
echo ".";
sleep(1);
echo ".";
sleep(1);
echo ".\n";
$file = file_get_contents($namefile) or die ("File not found!\n");
$socks = explode("\r\n",$file);
$total = count($socks);
echo "Total proxy: ".$total."\n";

$i = 0;
foreach ($socks as $value) {
	loop($value, $timeout, $reff);
	$i++;
}



?>