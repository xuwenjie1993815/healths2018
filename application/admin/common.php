<?php 
    // HTTP请求（支持HTTP/HTTPS，支持GET/POST）
    function http_request($url, $data = null,$headers){
        $curl = curl_init();
        if ($headers) {
            if ($headers == '1') {
                $headers = array("Content-Type:application/json;charset='utf-8'","Accept:application/json");
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (! empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    function post_curls($url, $post,$headers){
        $curl = curl_init(); // 启动一个CURL会话
        if ($headers) {
            if ($headers == '1') {
                $headers = array("Content-type:application/json;charset='utf-8'","Accept:application/json");
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $res = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl); // 关闭CURL会话
        return $res; // 返回数据，json格式
    }

    //发送短信
    function send_sms($phoneNum,$message){
        $data['phoneNum'] = $phoneNum;
        $data['message'] = $message;
        $url = config('path')."/bloodEntity/sendMessage";
        $res = http_request($url,$data);
        $res = json_decode($res,true);
        if ($res and !$res['error']) {
            return array('code' => 1);
        }else{
            return array('code' => 2,'msg' => $res['error']);
        }
    }
?>