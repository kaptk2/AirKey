<?php 
	//Default Variables
	$controller = "https://andrew.rimrockhosting.com";
	$mac = "08:00:27:EA:16:04";
	$key = "zNPIWnQy7K8OY4Q4X2Whu5AwnxU5e6zg";
	$module = "network";
?>
<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
URL: <input type="text" name="controller" value="<?php echo $controller; ?>" size="30" />/
		 <input type="text" name="module" value="<?php echo $module; ?>" size="10" />/
		 <input type="text" name="mac" value="<?php echo $mac; ?>" size="13" />/
		 <input type="text" name="key" value="<?php echo $key; ?>" size="30"/>
		 <br />
		 <input type="submit" name="submit" value="Submit Form" /><br>
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

		$ch = curl_init($url);
		// ignore invalid ssl
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , false);
		// follow redirects
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		// misc options
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// run curl
		$output = curl_exec($ch);
		curl_close($ch);

		echo "<p>Outputed text:</p>";
		echo "<pre>";
		echo $output;
		echo "</pre>";

	}
?>

<h3>Test Remote File</h3>
<form name="form2" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		Remote File: <input type="text" name="file_name" />
		<br />
		<input type="submit" name="submit1" value="Submit Form" /><br>
</form>

<?php
	if (isset($_POST['submit1']))
	{
	$file_name = $_POST['file_name'];
	$remote_file = $controller.'/static/modules/'.$module.'/'.$file_name;
	//echo "<a href='$remote_file'>$remote_file</a>";
	header("Location: $remote_file");
	}
?>
