<?php 
use Jenssegers\Agent\Agent;

function getDevice($device = null)
{
    if(isset($device)) {
        return $device;
    }
    //agent check tablet mobile desktop
    $agent = new Agent();
    if($agent->isMobile() || $agent->isTablet()) {
        return MOBILE;
    } else {
        return PC;
    }
}
function getDevice2($device = null)
{
    if(isset($device)) {
        return $device;
    }
    //agent check tablet mobile desktop
    $agent = new Agent();
    if($agent->isTablet()) {
        return TABLET;
    } else if($agent->isMobile()) {
        return MOBILE;
    } else {
        return PC;
    }
}
function trimRequest($request)
{
    $input = $request->all();
    // use a closure here because array_walk_recursive passes
    // two params to the callback, the item + the key. If you were to just
    // call trim directly, you could end up inadvertently trimming things off
    // your array values, and pulling your hair out to figure out why.
    array_walk_recursive($input, function(&$in) {
        $in = trim($in);
    });
    $request->merge($input);
}
// show 0 for null
function getZero($number = null)
{
	if($number != '') {
		return $number;
	}
	return 0;
}
//cut trim text
function limit_text($text, $len) {
    if (strlen($text) < $len) {
        return $text;
    }
    $text_words = explode(' ', $text);
    $out = null;
    foreach ($text_words as $word) {
        if ((strlen($word) > $len) && $out == null) {

            return substr($word, 0, $len) . "...";
        }
        if ((strlen($out) + strlen($word)) > $len) {
            return $out . "...";
        }
        $out.=" " . $word;
    }
    return $out;
}
function image_exists($url)
{
    $c = @getimagesize($url);
    if($c) {
        return true;
    }
    return false;
}
function UR_exists($url)
{
   $headers=get_headers($url);
   return stripos($headers[0],"200 OK")?true:false;
}
function remote_file_exists($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    if( $httpCode == 200 ){return true;}
    return false;
}
//check file exist
function remoteFileExists($url, $type = 1)
{
    $c1 = true;
    if($type == 1) {
        $c1 = image_exists($url);
    }
    $c2 = UR_exists($url);
    $c3 = remote_file_exists($url);
    if($c1 === true || $c2 === true || $c3 === true) {
        return true;
    }
    return false;
}
//Get image dimensions facebook og:image
function getImageDimensionsOg($image='')
{
    $currentUrl = url()->current();
    if(strpos($currentUrl, 'localhost') !== false) {
        return;
    }
    if(!empty($image)) {
        $imageUrl = url($image);
        $checkFile = remoteFileExists($imageUrl);
        if($checkFile === false) {
            return;
        }
        list($width, $height) = @getimagesize($imageUrl);
        $string = '<meta property="og:image:width" content="'.$width.'" /><meta property="og:image:height" content="'.$height.'" />';
        return $string;
    }
    return;
}
// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
//upload file
function uploadImageFromUrl($url, $dir, $name='') {
    if($name == '') {
        $name = basename($url);
    }
    $path = public_path().'/images/'.$dir.'/'.$name;
    $directory = './images/'.$dir;
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true);
    }
    file_put_contents($path, file_get_contents($url));
    return $name;
}
//return slug from url
function getSlugFromUrl($url='',$currentUrl=null) {
    if($currentUrl != null) {
        $url = url()->current();
    }
    $url = trim(parse_url($url, PHP_URL_PATH), '/');
    $ur = explode('/', $url);
    $u = explode('.', $ur[count($ur)-1]);
    return $u[0];
}
function str_split_unicode($str, $length = 1) {
    $tmp = preg_split('~~u', $str, -1, PREG_SPLIT_NO_EMPTY);
    if ($length > 1) {
        $chunks = array_chunk($tmp, $length);
        foreach ($chunks as $i => $chunk) {
            $chunks[$i] = join('', (array) $chunk);
        }
        $tmp = $chunks;
    }
    return $tmp;
}
function toNumber($dest = 0)
{
    if ($dest) {
        return ord(strtolower($dest)) - 96;
    }
    else {
        return 0;
    }
}
function setArrayKey(&$array, $key) {
    reset ($array);
    while (key($array) !== $key) {
        if (next($array) === false) throw new Exception('Invalid key');
    }
}
