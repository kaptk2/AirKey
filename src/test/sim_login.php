<?php 
	//Default Variables
	$controller = "https://andrew.rimrockhosting.com";
	$mac = "08:00:27:EA:16:04";
	$key = "zNPIWnQy7K8OY4Q4X2Whu5AwnxU5e6zg";
	$version = "1";
?>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
URL: <input type="text" name="controller" value="<?php echo $controller; ?>" size="30" />/
		 <input type="text" name="mac" value="<?php echo $mac; ?>" size="13" />/
		 <input type="text" name="key" value="<?php echo $key; ?>" />/
		 <input type="text" name="version" value="<?php echo $version; ?>">
		 <br />
		 <input type="submit" name="submit" value="Submit Form"><br>
</form>

<?php
	if (isset($_POST['submit']))
	{
		$controller = $_POST['controller'];
		$mac = $_POST['mac'];
		$key = $_POST['key'];
		$version = $_POST['version'];

		//Build the URL
		$url = $controller.'/'.'register/auth/'.$mac.'/'.$key.'/'.$version;
		header("Location: $url");
	}
?>

