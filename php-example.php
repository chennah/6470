<?

#
# Example PHP server-side script for generating
# responses suitable for use with jquery-tokeninput
#

# Connect to the database
require_once 'login_HIDDEN.php';
mysql_pconnect($sql_server, $sql_username, $sql_password) or die(mysql_error());
mysql_select_db($login_database_name) or die(mysql_error());

# Perform the query
$query = sprintf("SELECT classname from classes WHERE classname LIKE '%%%s%%' ORDER BY popularity DESC LIMIT 10", mysql_real_escape_string($_GET["q"]));
$arr = array();
$rs = mysql_query($query);

# Collect the results
while($obj = mysql_fetch_object($rs)) {
    $arr[] = $obj;
}

# JSON-encode the response
$json_response = json_encode($arr);

# Optionally: Wrap the response in a callback function for JSONP cross-domain support
if($_GET["callback"]) {
    $json_response = $_GET["callback"] . "(" . $json_response . ")";
}

# Return the response
echo $json_response;

?>
