<?php 
	//Default Variable
	$key = "1234";
?>

<form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
Key: <input type="text" name="key" value="<?php echo $key; ?>" /><br/>
URL: <input type="text" name="url" />
		 <br />
		 <input type="submit" name="submit" value="Submit Form"><br>
</form>

<?php
	if (isset($_POST['submit']))
	{
		$url = $_POST["url"];

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

		// Since CURL does not work... hack work around
		$file = "encrypted.txt";
		$fp = fopen($file, "w");
		fwrite($fp, $output);
		fclose($fp);

		// Key to decode message
		$key = $_POST['key'];

		echo "<p>Outputed text:</p>";
		echo "<pre>";
		// use decode script
		system("./decode.sh $key $file");
		echo "</pre>";
	}
?>
