
<div id="content">
	<h2>Simple Demo</h2>
	<form id="upload" action="#" method="post" enctype="multipart/form-data">
		<p>This page demonstrates a simple usage of SWFUpload.  It uses the Queue Plugin to simplify uploading or cancelling all queued files.</p>

			<div class="fieldset flash" id="fsUploadProgress">
			<span class="legend">Upload Queue</span>
			</div>
		<div id="divStatus">0 Files Uploaded</div>
			<div>
				<span id="spanButtonPlaceHolder"></span>
				<input id="btnCancel" type="button" value="Cancel All Uploads" onclick="swfu.cancelQueue();" disabled="disabled" style="margin-left: 2px; font-size: 8pt; height: 29px;" />
			</div>
	</form>
	<div>
		<?php
		if(empty($files))
			print 'No Files';
		else
		{
			print "<ul>";
			foreach($files as $file)
				print '<li><a href="/fileeditor/codeedit/'.$filePath.'/'.$file.'">'.$file.'</a></li>';
			print "</ul>";
		}
		?>
	</div>
	<div>
		<form id="create" method="post" action="/fileeditor/createfile">
			<input type="hidden" name="filePath" value="<?php echo $filePath; ?>" />
			Enter File Name: <input type="text" name="fileName" />
			<input type="submit" value="Create File" />
		</form>
	</div>
</div>
</body>
