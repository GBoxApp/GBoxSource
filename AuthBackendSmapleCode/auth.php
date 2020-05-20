<?php

error_reporting(E_ERROR); 
ini_set("display_errors","Off");

require_once('RNCryptor.class.php');

$data = file_get_contents("php://input");
$password = md5('https://gbox.run/Public/Source.json'); //演示，客户端所提交的参数为密文，需要先解密，此为官方源样例，此处必须修改为您的源地址链接

$cryptor = new Decryptor();
$strParams = $cryptor->decrypt($data, $password);
$params = json_decode($strParams, true);

$udid = $params['udid'];                //用户所提交的udid
$pwd = $params['pwd'];                  //用户所提交的密码
$timestamp = $params['timestamp'];

$status = false;
if ($udid && $pwd && $timestamp) {
    //Todo
    //验证逻辑: 根据udid和密码判断是否已经授权
    $grant = '123456';
    $array = require "code.php";     //演示，读取存放授权信息的文件

    if (array_key_exists($udid, $array)) {
        $realPwd = $result[$udid];
        if ($realPwd === $pwd) {
            $status = ture;
        }
    }
}

//输出在GBox加密后的上锁app键值对的内容 xxx_unlock_kvp.json
if ($status) {
    $path = './Source_unlock_kvp.json';   //演示，读取存放kvp的文件
    $text = file_get_contents($path);

    $json = array(
        'success' => true,
        'data' => json_decode($text, true),
    );

    echo json_encode($json);
}
//验证失败输出
else {
    $json = array(
        'success' => false,
        'message' => 'type message here'
    );

    echo json_encode($json);
}

