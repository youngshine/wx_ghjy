<?php
/*
    根号平台ghjy / 2016-4 微信服务器
    Copyright 2014 All Rights Reserved
*/

// data 两个数据库（微信公众号、内部系统）
// 16-8-15 公众号数据库不要？？？
//require_once('db/database_connection.php'); 
require_once('db/database_connection_ent.php'); 

define("TOKEN", "weixin");

$wechatObj = new wechatCallbackapiTest();

if (!isset($_GET['echostr'])) {
    $wechatObj->responseMsg();
}else{
    $wechatObj->valid();
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if($tmpStr == $signature){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $this->logger("R ".$postStr);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "event":
                	$result = $this->receiveEvent($postObj);
                    break;
                case "text":
                	$result = $this->receiveText($postObj);
                    break;
                case "voice":
                	//$result = $this->receiveVoice($postObj);
                	$result = $this->receiveText($postObj);
                    break;
            }
            $this->logger("T ".$result);
            echo $result;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveEvent($object)
    {
        $content = "";
        switch ($object->Event)
        {
            // 不做推荐账号，只做扫码关注并绑定，公众号数据库也淘汰？？
			// 扫码的话，内部数据库一定存在记录 studentID=scan_id，补齐wxID
			case "subscribe":	
				if (isset($object->EventKey) And $object->EventKey != '' ){
	                $myWxID = $object->FromUserName;
					$content = '';
					$scene_id = str_replace('qrscene_','',$object->EventKey);
					//$scene_id = (int)$scene_id;
					//$scene_id = parseInt($scene_id);
				
					// 先判断，是否已经绑定。可能当初关注过又取消关注，所以subscribe
					$sql = "SELECT wxID From `ghjy_student` Where studentID=$scene_id";
					$result = mysql_query($sql);
					$row = mysql_fetch_assoc($result);
					if($row['wxID'] == ''){
		                $query = "UPDATE `ghjy_student` Set wxID = '$myWxID' 
							WHERE studentID = $scene_id";
		            	$result = mysql_query($query);
						$content = "场景扫码关注成功！\n同时成功绑定账号，您可以通过微信报读课程、查看教学过程、接收上下课提醒服务等。";
					}else if($row['wxID']==$myWxID){
						$content = "场景扫码关注成功！\n账号之前绑定过，请查看［我的账号］";
					}else{
						$content = "场景扫码关注成功！\n账号绑定失败，被别的微信绑定。";
					}	
	                $result = $this->responseClick($object, $content);
					return $result;
		            break;
	            }else{ // 普通关注，不是扫码参数二维码
	                $content = array();
	                $a = array(
	                    "Title"=>"根号教育",  
	                    "Description"=>"教育培训互联网＋平台，科技感知教育。\n免费服务电话：400-6680-118。", 
	                    "PicUrl"=>"http://www.xzpt.org/wx_ghjy/assets/img/product/jiaoshi.jpg", 
	                    "Url" =>"http://www.xzpt.org/wx_ghjy/product.html?$object->FromUserName"
	                ); 
	                $content = array($a);
	                $result = $this->transmitNews($object, $content);
	                return $result; 
	                break;
	            } 
                //break; 
            case "unsubscribe":
                //$query = "DELETE FROM member WHERE wxID = '$object->FromUserName'";
            	/*
				$query = "UPDATE member set current=0 
					WHERE wxID = '$object->FromUserName'";
            	$result = mysql_query($query);
            
            	$content = "Bye 欢迎再来（已经从数据库Member表禁用current=0，不删除，）";
            	$result = $this->transmitText($object, $content);
        		return $result; */
                break;  
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "experience":
                        $content = "【预约体验课程】\n".
                            "回复留下电话号码，\n" .
							"客服人员会与您联络" ;
                         
	                    //$result = $this->transmitText($object, $content);
	                    $result = $this->responseClick($object, $content);
        				return $result;
                        break;
      
                }
                break;
			// 扫码关注并绑定，如果已经关注过，但是未绑定系统wxID=''，则绑定
			// 如果已经绑定，则提示绑定过，不能重复绑定
			// 推荐的功能，暂时不要，scan_id不同	
			case "SCAN":
			/*
				//$content = "扫码，来自邀请ID=".$object->EventKey;
				$content = "您已经关注过。\n请查看'我的账号'";
                $result = $this->responseClick($object, $content);
				return $result;
	             //要实现统计分析，则需要扫描事件写入数据库，这里可以记录 EventKey及用户OpenID，扫描时间
	            break;  */
				
				$content = '';
				$myWxID = $object->FromUserName;
				// 已经关注过，则发生scan这个事件
				// 未绑定wxID=''则绑定，否则提示绑定过
				$scene_id = $object->EventKey; //没有qrscene_前缀
				//$scene_id = parseInt($scene_id);
				
				$sql = "SELECT wxID From `ghjy_student` Where studentID=$scene_id";
				$result = mysql_query($sql);
				$row = mysql_fetch_assoc($result);
					
				if($row['wxID'] == ''){
	                $query = "UPDATE `ghjy_student` Set wxID = '$myWxID' 
						WHERE studentID = $scene_id";
	            	$result = mysql_query($query);
					$content = "场景扫码 \n账号绑定成功！您可以通过微信报读课程、查看教学过程、接收上下课提醒服务等。";
				}else if($row['wxID']==$myWxID){
					$content = "场景扫码 \n账号之前绑定过，请查看［我的账号］";
				}else{
					$content = "场景扫码 \n账号绑定失败，已经被别的微信绑定";
				}			
				//$content = "您已经关注过。\n请查看'我的账号'";
                $result = $this->responseClick($object, $content);
				return $result;
	             //要实现统计分析，则需要扫描事件写入数据库，这里可以记录 EventKey及用户OpenID，扫描时间
	            break; 
            default:
                break; 
        }

    }
	
	// 代替transmit text，避免转发到多客服
    private function responseClick($object, $content)
    {
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[text]]></MsgType>
            <Content><![CDATA[$content]]></Content>
            </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }
    
    private function receiveText($object)
    {
        /*
        $content = array();
        $a = array(
            "Title"=>"根号教育",  
            "Description"=>"", 
            "PicUrl"=>"http://ysmall.sinaapp.com/assets/title.jpg", 
            //"Url" =>"http://www.yiqizo.com/weixin/whlj/index.html?$object->FromUserName" //带入当前用户
            //"Url" =>"http://www.yiqizo.com/weixin/whlj/index.html?1"
            "Url" =>"index.html?$object->FromUserName"
        ); 
        $b = array(
            "Title"=>"会员中心",  
            "Description"=>"", 
            "PicUrl"=>"http://ysmall.sinaapp.com/assets/contact.jpg", 
            "Url" =>"member.html?$object->FromUserName"
        );
        $content = array($a,$b);
        $result = $this->transmitNews($object, $content);
        return $result;
		*/
        //$content = "请选择下方导航菜单进行相应操作。\n免费服务电话：\n400-6680-118";
        //$result = $this->transmitText($object, $content);
        $result = $this->transmitText($object);
        return $result; 
    }
    
    //private function transmitText($object, $content)
    private function transmitText($object)
    {
        $textTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[transfer_customer_service]]></MsgType>
            </xml>";
        $result = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time());
        return $result;
    }

    private function transmitNews($object, $newsArray)
    {
        if(!is_array($newsArray)){
            return;
        }
        $itemTpl = "    <item>
            <Title><![CDATA[%s]]></Title>
            <Description><![CDATA[%s]]></Description>
            <PicUrl><![CDATA[%s]]></PicUrl>
            <Url><![CDATA[%s]]></Url>
            </item>
            ";
        
        $item_str = "";
        foreach ($newsArray as $item){
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);
        }
        $newsTpl = "<xml>
            <ToUserName><![CDATA[%s]]></ToUserName>
            <FromUserName><![CDATA[%s]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[news]]></MsgType>
            <Content><![CDATA[]]></Content>
            <ArticleCount>%s</ArticleCount>
            <Articles>
            $item_str</Articles>
            </xml>";

        $result = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($newsArray));
        return $result;
    }

    private function logger($log_content)
    {
      
    }
}
?>