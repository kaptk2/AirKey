<?php 
	//Default Variables
	$controller = "https://andrew.rimrockhosting.com";
	$mac = "08:00:27:EA:16:04";
	$key = "zNPIWnQy7K8OY4Q4X2Whu5AwnxU5e6zg";
	$module = "network";
?>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
URL: <input type="text" name="controller" value="<?php echo $controller; ?>" size="30" />/
		 <input type="text" name="module" value="<?php echo $module; ?>">
		 <input type="text" name="mac" value="<?php echo $mac; ?>" size="13" />/
		 <input type="text" name="key" value="<?php echo $key; ?>" />/
		 <br />
		 <input type="submit" name="submit" value="Submit Form"><br>
</form>

<?php
	if (isset($_POST['submit']))
	{
		$controller = $_POST['controller'];
		$mac = $_POST['mac'];
		$key = $_POST['key'];
		$module = $_POST['module'];

		//Build the URL
		$url = $controller.'/modules/'.$module.'/auth/'.$mac.'/'.$key;
		echo $url;
		//header("Location: $url");
	}
?>
