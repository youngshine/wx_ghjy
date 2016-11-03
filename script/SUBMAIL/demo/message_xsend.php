<?PHP
    /*
     | Submail message/xsend API demo
     | SUBMAIL SDK Version 2.0 --PHP
     | copyright 2011 - 2015 SUBMAIL
     |--------------------------------------------------------------------------
     */
    
    /*
     |载入 app_config 文件
     |--------------------------------------------------------------------------
     */
    require '../app_config.php';
    
    /*
     |载入 SUBMAILAutoload 文件
     |--------------------------------------------------------------------------
     */
    
    require_once('../SUBMAILAutoload.php');
    
    /*
     |初始化 MESSAGEXsend 类
     |--------------------------------------------------------------------------
     */
    
    $submail=new MESSAGEXsend($message_configs);
    
    /*
     |必选参数
     |--------------------------------------------------------------------------
     |设置短信接收的11位手机号码
     |--------------------------------------------------------------------------
     */
    
    //$submail->setTo('13505921357');
	$submail->setTo($_REQUEST['phone']);
    
    /*
     |必选参数
     |--------------------------------------------------------------------------
     |设置短信模板ID
     |--------------------------------------------------------------------------
     */
    
    $submail->SetProject('HFIyI2'); //根号数理化
    
    /*
     |可选参数
     |--------------------------------------------------------------------------
     |添加文本变量
     |可多次调用
     |--------------------------------------------------------------------------
     */
    
    //$submail->AddVar('code','198277');
	$submail->AddVar('code',$_REQUEST['code']);
    
    /*
     |调用 xsend 方法发送短信
     |--------------------------------------------------------------------------
     */
    
    $xsend=$submail->xsend();
    
    
    /*
     |打印服务器返回值
     |--------------------------------------------------------------------------
     */
    
    print_r($xsend);
