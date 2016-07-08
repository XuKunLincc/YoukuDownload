<?php
//header('Content-type: text/json');
$handle = fopen("http://play.youku.com/play/get.json?vid=".$_GET['id']."&ct=12",'rb');
$jsonData = "";
$h = [19, 1, 4, 7, 30, 14, 28, 8, 24, 17, 6, 35, 34, 16, 9, 10, 13, 22, 32, 29, 31, 21, 18, 3, 2, 23, 25, 27, 11, 20, 5, 15, 12, 0, 33, 26];

/**
 * 打开网页
 */

function getFileId($e, $t){
    if (null == $e || "" == $e) return "";
    $n = "";
    $i = implode(array_slice(str_split($e),0,8));
    $o = "00";
    $r = implode(array_slice(str_split($e),10,strlen($e)));
    return $n = $i . $o . $r;
}


function r($e){
    if(!$e) return "";
    $e = "$e";
    $s = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
    for($i = strlen($e), $n = 0, $t = ""; $i > $n;){
        //$e = str_split($e);

        $o = 255 & ord(($e[$n++]));
        if($n == $i){
            $t .= $s[$o >> 2];
            $t .= "++";
            break;
        }

        $r = ord(($e[$n++]));
        if($n == $i){
            $t .= $s[$o >> 2];
            $t .= $s[((3 & $o) << 4 ) | ((240 & $r) >> 4)];
            $t .= $s[(15 & $r) << 2];
            $t .= "=";
            break;
        }
        $a = ord($e[$n++]);
        $t .= $s[$o >> 2];
        $t .= $s[(3 & $o) << 4 | (240 & $r) >> 4];
        $t .= $s[(15 & $r) << 2 | (192 & $a) >> 6];
        $t .= $s[63 & $a];
    }
    return $t;
}

function o($e) {
    if (!$e) return "";
    $e = "$e";
    $l = Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);
    for ($a = strlen($e), $r = 0, $s = ""; $a > $r;) {
        do $t = $l[255 & ord($e[$r++])];
        while ($a > $r && -1 == $t);
        if (-1 == $t) break;
        do $n = $l[255 & ord($e[$r++])];
        while ($a > $r && -1 == $n);
        if (-1 == $n) break;
        $s .= chr($t << 2 | (48 & $n) >> 4);
        do {
            $i = 255 & ord($e[$r++]);
            if (61 == $i) return $s;
            $i = $l[$i];
        } while ($a > $r && -1 == $i);
        if (-1 == $i) break;
        $s .= chr((15 & $n) << 4 | (60 & $i) >> 2);
        do {
            $o = 255 & ord($e[$r++]);
            if (61 == $o) return $s;
            $o = $l[$o];
        } while ($a > $r && -1 == $o);
        if (-1 == $o) break;
        $s .= chr((3 & $i) << 6 | $o);
    }
    return $s;
}

function a($e, $t) {
    for ($n = 0, $i = [], $o = 0, $r = "", $a = 0; 256 > $a; $a++) $i[$a] = $a;
    for ($a = 0; 256 > $a; $a++){
        $o = ($o + $i[$a] + ord($e[($a % strlen($e))])) % 256;
        $n = $i[$a];
        $i[$a] = $i[$o];
        $i[$o] = $n;
    }

    $a = 0;
    $o = 0;

    for ($s = 0; $s < strlen($t); $s++){
        $a = ($a + 1) % 256;
        $o = ($o + $i[$a]) % 256;
        $n = $i[$a];
        $i[$a] = $i[$o];
        $i[$o] = $n;
        $r .= chr(ord($t[$s]) ^ $i[($i[$a] + $i[$o]) % 256]);
    }
    return $r;
}


function s($e, $t) {
    for ($n = [], $i = 0; $i < strlen($e); $i++) {
        $o = 0;
        $o = $e[$i] >= "a" && $e[$i] <= "z" ? ord($e[$i]) - ord("a") : $e[$i] - "0" + 26;
        for ($r = 0; 36 > $r; $r++) if ($t[$r] == $o) {
            $o = $r;
            break;
        }
        $n[$i] = $o > 25 ? $o - 26 : chr($o + 97);
    }
    return implode($n);
}

while(!feof($handle)){
    $jsonData .= fread($handle,10000);
}
    fclose($handle);

    $jsonObj = json_decode($jsonData);
    $mdata = $jsonObj->{'data'};
    $dataStream = $mdata->{'stream'};
    $index = 0;
    for($i = 0; $i < count($dataStream); $i++){
        if($dataStream[$i]->{'stream_type'} == "3gphd"){
            $index = $i;
            break;
        }
    }
    $u = explode("_",a(s("b4et" . "o0b" . "4", $h)."", o($mdata->{'security'}->{'encrypt_string'})));
    $sid = $u[0];
    $token = $u[1];
    $ip = $mdata->{'security'}->{'ip'};
    $fileId = getFileId($dataStream[$index]->{"stream_fileid"},0);
    $K = $dataStream[$index]->{'segs'}[0]->{'key'};
    $W = $dataStream[$index]->{'segs'}[0]->{'total_milliseconds_video'} / 1e3;
    $C = urlencode(r(a(s("boa4" . "poz" . "1", $h)."", $sid . "_" . $fileId . "_" . $token)));
    $url = 'http://k.youku.com/player/getFlvPath/sid/'.$sid."_"."00"."/st/"."mp4"."/fileid/".$fileId."?K=".$K."&hd=1". "&myp=0&ts=".$W."&ypp=0"."&ymovie=1"."&ep=".$C."&ctype=12"."&ev=1"."&token=".$token."&oip=".$ip;
    $reJson = array('URL'=>$url);
    echo json_encode($reJson);


?>