<?php
// 应用公共文件
function return_res($status, $message, $data, $HttpStatus = 200){
    $result = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];
    return json($result, $HttpStatus);
}

function swooleCurl($type='', $token= '',$param = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://chat.test:9502?token=" . $token . "&type=" . $type );
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //    设置post数据
    $post_data = $param;
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    $output = curl_exec($ch);
    if($output === FALSE ){
        return false;
    }
    curl_close($ch);
    return true;
}