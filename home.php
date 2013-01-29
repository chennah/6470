<html>
<title>
Home
</title>
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<style type="text/css">
#top{
	left: 0px;
	top:0px;
	z-index:100;
	position:fixed;
	display:block;
	background-color:262626;
	height:40px;
	width:100%;
	overflow: hidden;
	color:white;
}

.sidebar{
	left:0px;
	top:40px;
	position:fixed;
	display:block;
	background-color:0099E5;
	width:20%;
	bottom:0px;
}

a:link,a:visited{
	font-family:arial, helvetica, sans-serif;
	color:#262626;
	text-decoration:none;
	font-size:90%;
	line-height:1.5;
}

a:hover,a:active{
	text-decoration:underline;
}
.main{
left:300px;
top:70px;
position:absolute;
display:block;
font-size:125%;
}
.headline{
overflow: hidden;
left: 300 px;
top:125px;
position:absolute;
display:block;
font-size:200%;
font-family:arial, helvetica, sans-serif;
color:#262626;
min-height:100% 
}
.addclass{
left:0px;
top: 50px;
position: relative;
}
.classlist{
left:0px;

position: relative;
}
#addclass{
	border:1px solid #7d99ca; 
	height: 50px;
	width: 170px;
	-webkit-border-radius: 3px; -moz-border-radius: 3px;border-radius: 3px;
	font-family:arial, helvetica, sans-serif; padding: 7px 10px 10px 10px; 
	text-shadow: -1px -1px 0 rgba(0,0,0,0.3);font-weight:bold; 
	text-align: center; color: #FFFFFF; background-color: #0099E5;
 background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #0099E5), color-stop(100%, #006394));
 background-image: -webkit-linear-gradient(top, #0099E5, #006394);
 background-image: -moz-linear-gradient(top, #0099E5, #006394);
 background-image: -ms-linear-gradient(top, #0099E5, #006394);
 background-image: -o-linear-gradient(top, #0099E5, #006394);
 background-image: linear-gradient(top, #0099E5, #006394);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#0099E5, endColorstr=#006394);
font-size:60%;

	position:center;
 }
</style>
<script src='jquery.cookie.js'></script>
<script src='login_success_notify.js'></script>

</head>
<body onload="getClassList()" style="overflow-y:scroll">
<div id ="top">
This is where some stuff will go
</div>
<div class ="sidebar">
This is where the nav stuff will go
</div>
<div class="main" overflow="hidden">
<a href = "home.html">Home</a>
</div>
<div class = "headline">
<div class = "headline1" id="headline1"></div>

<div class = "addclass">
<FORM METHOD="LINK" ACTION="newclass.html">
<INPUT TYPE="submit" id ="addclass" VALUE="+ Add a class">
</FORM>
<div class="classList">
<?php
	require_once 'login_HIDDEN.php';
	$connection = mysql_connect($sql_server, $sql_username, $sql_password) or die(mysql_error());
	mysql_select_db($login_database_name, $connection) or die(mysql_error());
	$user = $_COOKIE['Status_Login_Username'];
	$select_user_query = "SELECT classes FROM login WHERE username='$user'";
	$result = mysql_query($select_user_query, $connection) or die(mysql_error());
	$dat = mysql_fetch_array($result);
	$thatdat = $dat[0];

	mysql_close($connection);
?>
<script type="text/javascript">
function getClassList(){
if($.cookie('Status_Login_RAND') == null){
        // alert('Entered the if block');
        window.location = "index.html"
                
    }
}
</script>
<script type="text/javascript">
		var stuff = "<?php echo $thatdat; ?>";
		var ar = stuff.split(";");
		for(var i=0;i <ar.length;i++){
			document.write("<a href='classhome.html'>"+ar[i]+"</a><br />");
		}
	
</script>
</div>
</div>


</div>
</body>
</html>





