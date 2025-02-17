<?php
if (file_exists('./config-session.php')) {
    exit('Access Denied');
}
error_reporting(E_ALL ^ E_NOTICE);

if ($_POST['dbserver']) {
    $dbserver = $_POST['dbserver'];
    $dbuser = $_POST['dbuser'];
    $dbpw = $_POST['dbpw'];
    $dbname = $_POST['dbname'];
    $sessionkey = $_POST['sessionkey'];
    $anonymouskey = $_POST['anonymouskey'];

    if ($_POST['createdb'] == 'no') {
        $mysqli = new mysqli($dbserver, $dbuser, $dbpw, $dbname);
    } else {
        $mysqli = new mysqli($dbserver, $dbuser, $dbpw);
    }

    $mysqli->query("SET CHARACTER SET 'utf8mb4'");
    $mysqli->query("SET NAMES 'utf8mb4'");

    if ($_POST['createdb'] == 'yes') {
        $mysqli->query("CREATE DATABASE IF NOT EXISTS `" . addslashes($dbname) . "` default charset utf8mb4 COLLATE utf8mb4_general_ci");
        $mysqli->select_db($dbname);
    }

    if ($_POST['createtable'] == 'yes') {
        $mysqli->query("DROP TABLE IF EXISTS `case`");
        $mysqli->query("CREATE TABLE IF NOT EXISTS `case` (
  `cid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `hidden` int(11) NOT NULL DEFAULT '0',
  `dateofad` char(10) NOT NULL DEFAULT '',
  `univname` varchar(200) NOT NULL DEFAULT '',
  `funding` char(50) DEFAULT '',
  `label` varchar(200) DEFAULT '',
  `languagescore` varchar(200) NOT NULL DEFAULT '',
  `gpa` varchar(200) NOT NULL DEFAULT '',
  `subject` char(50) NOT NULL DEFAULT '',
  `noteforchoose` text NOT NULL,
  `schoolname` varchar(300) NOT NULL DEFAULT '',
  `subsubject` char(100) NOT NULL DEFAULT '',
  `noteforad` text NOT NULL,
  PRIMARY KEY (`cid`),
  KEY `subject` (`subject`)
) ENGINE=Innodb  DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;");
    }

    file_put_contents('./config.php', '<?php
if(!defined(\'IN_ADRAIN\')) exit(\'Access Denied\');

$dbserver = ' . var_export($dbserver, true) . ';
$dbuser = ' . var_export($dbuser, true) . ';
$dbpw = ' . var_export($dbpw, true) . ';
$dbname  = ' . var_export($dbname, true) . ';');

    file_put_contents('./config-session.php', '<?php
if(!defined(\'IN_ADRAIN\')) exit(\'Access Denied\');
$sessionkey = ' . var_export($sessionkey, true) . ';');

    file_put_contents('./anonymous_config.php', '<?php
$key = ' . var_export($anonymouskey, true) . ';');

    echo 'Successful!';
} else {
    ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>LUG@USTC SEC@USTC ADRAIN PROJECT - THE DOCKER</title>

    <!-- Bootstrap core CSS -->
    <link href="/static/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <style type="text/css">
    body {
      padding-top: 20px;
      padding-bottom: 20px;
    }

    .navbar {
      margin-bottom: 20px;
    }
    </style>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/static/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="/static/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <body>

	  <div class="bs-docs-header" id="content" style="padding-top: 20px; padding-bottom: 20px; margin-top: 20px; margin-bottom: 20px;background-color:  #6f5499; color: #fff; position: relative;">
      <div class="container">
        <h2>作为科大人，你要自信！</h2>
        <p>科大校友不仅不仅从事高科技行业的众多，而且很团结，经常活动聚会，有互相帮助的优良传统。</p>
      </div>
    </div>

    <div class="container">
<br/>
	   <form action="install.php" method="post">
	   <div class="input-group">
			  <span class="input-group-addon">MySQLi 的服务器地址:</span>
			  <input type="text" name="dbserver" class="form-control" placeholder="e.g. 127.0.0.1">
		</div><br/>


	   <div class="input-group">
			  <span class="input-group-addon">MySQLi 的账号名称:</span>
			  <input type="text" name="dbuser" class="form-control" placeholder="e.g. adrain">
		</div><br/>


	   <div class="input-group">
			  <span class="input-group-addon">MySQLi 的密码:</span>
			  <input type="text" name="dbpw" class="form-control" placeholder="e.g. 3469r9fasdh0w0t">
		</div><br/>


	   <div class="input-group">
			  <span class="input-group-addon">MySQLi 的数据库名称:</span>
			  <input type="text" name="dbname" class="form-control" placeholder="e.g. 127.0.0.1">
		</div><br/>

		<div class="input-group">
			  <span class="input-group-addon">输入一个随机的字符串（请尽量长）用于临时保存会话:</span>
			  <input type="text" name="sessionkey" class="form-control" placeholder="e.g. 357tegdh0iq02y3iwfsodhoag">
		</div><br/>

		<div class="input-group">
			<span class="input-group-addon">输入一个随机的字符串（请尽量长）用于匿名化用户名（学号）:</span>
			<input type="text" name="anonymouskey" class="form-control" placeholder="e.g. 357tegdh0iq02y3iwfsodhoag">
		</div>

	   <div class="input-group">
			  <span class="input-group-addon">需要我创建数据库吗？:</span>

			  <select name="createdb" class="form-control">
				<option value="yes">是</option>
				<option value="no">否</option>
			  </select>
		</div><br/>


	   <div class="input-group">
			  <span class="input-group-addon">需要我创建表吗？:</span>

			  <select name="createtable" class="form-control">
				<option value="yes">是</option>
				<option value="no">否</option>
			  </select>
		</div><br/>

		<button type="submit" class="btn btn-success">开始安装</button>
	   </form>
    </div> <!-- /container -->


	<br/>
	<div class="container" style="color: #444;text-align:center;">
	<p>2017 - 2022 Admission Rain LUG@USTC, SEC@USTC</p>

	<p>The University of Science and Technology of China is going to be a worldwide first-class university. The massive support from us is of great importance.</p>
	</div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/static/jquery/1.11.2/jquery.min.js"></script>
    <script src="/static/bootstrap/3.3.2/js/bootstrap.min.js"></script>

  </body>
</html>
<?php
}
