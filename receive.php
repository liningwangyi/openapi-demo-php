<?php
require_once(__DIR__ . "/env.php");
require_once(__DIR__ . "/util/Log.php");
require_once(__DIR__ . "/util/Cache.php");
require_once(__DIR__ . "/crypto/DingtalkCrypt.php");

$signature = $_GET["signature"];
$timeStamp = $_GET["timestamp"];
$nonce = $_GET["nonce"];

$encrypt = json_decode($GLOBALS['HTTP_RAW_POST_DATA'])->encrypt;

$crypt = new DingtalkCrypt(TOKEN, ENCODING_AES_KEY, SUITE_KEY);

$msg = "";
$res = "success";
$errCode = $crypt->DecryptMsg($signature, $timeStamp, $nonce, $encrypt, $msg);
if ($errCode == 0)
{
    Log::i(json_encode($_GET) . "  " . $msg);
    $eventMsg = json_decode($msg);
    $eventType = $eventMsg->EventType;
    if ("suite_ticket" === $eventType)
    {
        Cache::setSuiteTicket($eventMsg->SuiteTicket);
        
        $info = $_GET;
        $info["encrypt"] = $encrypt;
        Cache::set("ticket_info", json_encode($info));
    }
    else if ("tmp_auth_code" === $eventType)
    {
        Cache::setTmpAuthCode(json_encode($eventMsg));
    }
    else if ("change_auth" === $eventType)
    {
        //handle auth change event
    }
    else if ("check_update_suite_url" === $eventType)
    {
        $res = $eventMsg->Random;
        $testSuiteKey = $eventMsg->TestSuiteKey;
    }
    else
    {
        //should never happen
    }
}
else 
{
    Log::e(json_encode($_GET) . "  ERR:" . $errCode);
}

$encryptMsg = "";
$errCode = $crypt->EncryptMsg($res, $timeStamp, $nonce, $encryptMsg);
if ($errCode == 0) 
{
    echo $encryptMsg;
    Log::i("RESPONSE: " . $encryptMsg);
} 
else 
{
    Log::e("RESPONSE ERR: " . $errCode);
}