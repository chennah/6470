<html>
<title>
Class Home
</title>
<head>
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]);

        var options = {
          title: 'Company Performance',
          hAxis: {title: 'Year', titleTextStyle: {color: 'red'}}
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
<script src='jquery.cookie.js'></script>
<style type="text/css">
#add1,
#add2{
	border:1px solid #7d99ca; 
	-webkit-border-radius: 3px; -moz-border-radius: 3px;border-radius: 3px;
	font-family:arial, helvetica, sans-serif; padding: 10px 10px 10px 10px; 
	text-shadow: -1px -1px 0 rgba(0,0,0,0.3);font-weight:bold; 
	text-align: center; color: #FFFFFF; background-color: #0099E5;
 background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #0099E5), color-stop(100%, #006394));
 background-image: -webkit-linear-gradient(top, #0099E5, #006394);
 background-image: -moz-linear-gradient(top, #0099E5, #006394);
 background-image: -ms-linear-gradient(top, #0099E5, #006394);
 background-image: -o-linear-gradient(top, #0099E5, #006394);
 background-image: linear-gradient(top, #0099E5, #006394);filter:progid:DXImageTransform.Microsoft.gradient(GradientType=0,startColorstr=#0099E5, endColorstr=#006394);
	width:50%;
	height: 50px;
	font-size:45%;
	position:center;
	margin-bottom:20px;
 }
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
font-size:80%;
}

a:hover,a:active{
	text-decoration:underline;
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
.main{
left:300px;
top:70px;
position:absolute;
display:block;
font-size:125%;
}
</style>
</head>
<body style="overflow-y:scroll">
<div id ="top">
</div>
<div class ="sidebar">
</div>
<div class="main" overflow="hidden">
<a href = "home.php">Home</a>
</div>
<div class="headline" id="headline">
<?php
	require_once 'login_HIDDEN.php';
	$connection = mysql_connect($sql_server, $sql_username, $sql_password) or die(mysql_error());
	mysql_select_db($login_database_name, $connection) or die(mysql_error());
	$classname = $_COOKIE['class'];
	$user = $_COOKIE['Status_Login_Username'];
	$select_user_query = "SELECT instructor FROM $classname WHERE username='$user'";
	$result = mysql_query($select_user_query, $connection) or die(mysql_error());
	$dat = mysql_fetch_array($result);
	$thatdat = $dat[0];
	if($thatdat=="yes"){
		$get_q = "SELECT prompt FROM questions WHERE classID = '1234'";
		$q_array = mysql_query($get_q, $connection) or die(mysql_error());
		$data = mysql_fetch_array($q_array);
		$str ='';
		
		$arr = array();
		$rs = mysql_query($get_q, $connection);
		while($obj = mysql_fetch_object($rs)) {
 		   $arr[] = $obj;
 		   
		}
		$max = count($arr);
		for($i=0; $i< $max; $i++){
			$str.= $arr[$i]->prompt.';';
		}
//		echo $str;
		}
	mysql_close($connection);
?>
<script type="text/javascript">
function newQueston(){
	window.location("newquestion.html");
}
</script>
<script type="text/javascript">
		var stuff = "<?php echo $thatdat; ?>"
		
		if(stuff=="yes"){
			document.write("Welcome, Instructor " + $.cookie("Status_Login_Username")+"<br /> <br />");
			var saved= "<?php echo $str; ?>"
			var element1 = document.createElement("input");
 			element1.type = "button";
 			element1.name="add1";
 			element1.id="add1";
 			element1.value="+ Add a New Question";
 			$(element1).bind("click", function() {
           		
           		window.location = "newquestion.html"
           		//window.location("www.google.com");
			});
			var table = document.getElementById("headline");
			table.appendChild(element1);
			//document.write('<input type="button" id="add1" value="+ Add a New Question" onclick="newQuestion()">');
			document.write("<br />");
			var arr = saved.split(";");
			for(var j=0;j <arr.length;j++){
				document.write("<a href='newquestion.html'>"+arr[j]+"</a><br />");
			}
			
		}
		else{
			document.write("Welcome, Student " + $.cookie("Status_Login_Username"));
		}
		$('#headline').click(function() {
		window.location("newquestion.html");
		});
</script>

</div>

</body>
</html>