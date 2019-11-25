<?php
require_once ("Gc.php");
require_once("core/include/common.php");
if( !contains( $_SERVER['HTTP_HOST'], array("127.0.0.1", "localhost", "192.168.") ) ) {
    header("location:".Gc::$url_base."index.php?go=".Gc::$appName.".index.index");
    die();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Lang" content="zh_CN">
    <meta name="author" content="skygreen">
    <meta http-equiv="Reply-to" content="skygreen2001@gmail.com">
    <meta name="description" content="<?php echo Gc::$site_name?>">
    <meta name="keywords" content="<?php echo Gc::$site_name?>">
    <meta name="creation-date" content="08/08/2017">
    <meta name="revisit-after" content="15 days">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?php echo Gc::$url_base?>favicon.ico" mce_href="favicon.ico" type="image/x-icon">
    <title><?php echo Gc::$site_name ?></title>
    <style type="text/css">
        body {
          font-size: 13px;
          font-family:'Microsoft YaHei',"微软雅黑",Arial, sans-serif,'Open Sans';
          margin:0;
          padding:0;
          border:0 none;
          min-height: 360px;
        }
        p {
          margin:5px;
        }
        .en{
          font-family:Arial,verdana,Geneva,Helvetica,sans-serif;
        }
        h1{
          font-size: 40px;
          font-weight: lighter;
        }
        a {
          cursor: pointer;
        }
        a:link {
          text-decoration: none;
        }
        a:visited {
          text-decoration: none;
        }
        a:hover {
          text-decoration: none;
        }
        .main {
          position: absolute;
          top: 50%;
          margin-top: -300px;
          width : 100%;
          /* height: 100%; */
        }
        .inbox {
          width: 360px;
          margin: 0 auto;
        }
        div.content-container{
          border: 2px solid #eee;
          font-size: 24px;
          width: 360px;
          height: 360px;
          border-radius: 100%;
        }
        div.content{
          display: inline-block;
          margin-top: 22%;
          margin-left: 33%;
        }
        div.content a{
          color: #666;
          position: relative;
          letter-spacing: 2px;
          height: 36px;
          line-height: 36px;
        }
        div.content a:hover{
          color: #77cc6d;
          text-decoration: none;
          transition: color .2s;
          -webkit-transition: color .2s;
          -moz-transition: color .2s;
          -ms-transition: color .2s;
          -o-transition: color .2s;
        }
        div.content a::after{
          content: "";
          position: absolute;
          left: 0;
          width: 100%;
          height: 2px!important;
          bottom: -2px!important;
          transform: scaleX(0);
        }
        div.content a:hover::after{
          background-color: #77cc6d!important;
          transition: transform .4s ease;
          transform: scaleX(1);
          transform-origin: left;
        }
        .content-down{
          color: #999;
          text-align: center;
          margin: 10px auto 0px auto;
        }

        footer {
          position: fixed;
          bottom: 0px;
          width: 100%;
          text-align: center;
          height: 40px;
          line-height: 44px;
          color: #888;
          background-color: #fefefe;
          letter-spacing: 2px;
          -webkit-transition: all 0.3s ease-in-out;
          -moz-transition: all 0.3s ease-in-out;
          -ms-transition: all 0.3s ease-in-out;
          -o-transition: all 0.3s ease-in-out;
        }

        footer:hover {
          background-color: #e8e8e8;
          -webkit-transition: all 0.3s ease-in-out;
          -moz-transition: all 0.3s ease-in-out;
          -ms-transition: all 0.3s ease-in-out;
          -o-transition: all 0.3s ease-in-out;
        }
        footer div {
          width: 480px;
          margin: 0 auto;
        }
        footer a{
          color: #888;
          padding-bottom: 2px;
        }
        footer a:hover{
          color: #77cc6d;
          /*font-size: 14px;*/
          border-bottom: 1px solid #77cc6d;
          transition: color .2s;
          -webkit-transition: color .2s;
          -moz-transition: color .2s;
          -ms-transition: color .2s;
          -o-transition: color .2s;
        }
        @media (max-width: 800px) {
            .main {
              width: auto;
            }
            h1 {
              font-size: 20px;
              margin-top: 35%;
            }
            div.content-container {
              width: 280px;
              height: 280px;
              margin: 0 auto;
            }
            div.content {
              margin-top: 15%;
              margin-left: 30%;
            }
            footer {
              height: 70px;
              line-height: 30px;
            }
            footer div {
              width: 80%;
            }
        }
    </style>
</head>
<body>
    <div class="main">
        <h1 align="center">欢迎来到 <span class="en"><?php echo Gc::$site_name ?></span> 技术玩家乐园 </h1>
        <div class="inbox">
            <div class="content-container">
                <div class="content" align="center">
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=<?php echo Gc::$appName ?>.index.index">网站前台</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>app/html5/index.php">手机应用</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=admin.index.index">管理后台</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=report.index.index">报表系统</a></p>
                    <p><a target="_blank" href="<?php echo Gc::$url_base?>index.php?go=model.index.index">通用模版</a></p>
                </div>
            </div>
        </div>
        <div class="content-down">
          <p id="current-time"></p>
        </div>
    </div>
    <footer><?php $help_url="https://github.com/skygreen2001/betterlife.gitbook" ?>
        <div>
            <a href="<?php echo Gc::$url_base?>tools/dev/index.php" target="_blank">工程重用</a> | <a href="<?php echo Gc::$url_base?>tools/tools/db/manual/db_normal.php" target="_blank">数据库说明书</a> |
            <a href="<?php echo Gc::$url_base?>tools/tools/autocode/db_onekey.php" target="_blank">代码生成器</a> | <a href="<?php echo Gc::$url_base?>tools/tools/autocode/report_onekey.php" target="_blank">报表生成器</a> |
            <a href="<?php echo Gc::$url_base?>tools/tools/index.php" target="_blank">工具箱</a> | <a href="<?php echo $help_url ?>" target="_blank">帮助</a>
        </div>
    </footer>

    <script type="text/javascript">
    /*******************************Date Prototype Shim**************************************/
    // 对Date的扩展，将 Date 转化为指定格式的String
    // 月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符，
    // 年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字)
    // 例子：
    // (new Date()).format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423
    // (new Date()).format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18
    Date.prototype.format = function(fmt)
    {
      fmt = fmt || "yyyy-MM-dd";
      var o = {
        "M+" : this.getMonth()+1,                 //月份
        "d+" : this.getDate(),                    //日
        "h+" : this.getHours(),                   //小时
        "m+" : this.getMinutes(),                 //分
        "s+" : this.getSeconds(),                 //秒
        "q+" : Math.floor((this.getMonth()+3)/3), //季度
        "S"  : this.getMilliseconds()             //毫秒
      };
      if(/(y+)/.test(fmt))
        fmt = fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length));
      for(var k in o)
        if(new RegExp("("+ k +")").test(fmt))
          fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
      return fmt;
    };
    document.getElementById("current-time").innerHTML = new Date().format('yyyy-MM-dd hh:mm:ss');
    setInterval(function(){
      document.getElementById("current-time").innerHTML = new Date().format('yyyy-MM-dd hh:mm:ss');
    },1000)
    </script>
</body>
</html>
