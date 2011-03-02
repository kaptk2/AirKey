<head>
<title>Upload Files</title>
<link href="<?php echo site_url('static/style/swfupload.css'); ?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo site_url('static/swfupload/swfupload.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('static/swfupload/swfupload.queue.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('static/swfupload/fileprogress.js'); ?>"></script>
<script type="text/javascript" src="<?php echo site_url('static/swfupload/handlers.js'); ?>"></script>
<script type="text/javascript">
		var swfu;

		window.onload = function() {
			var settings = {
				flash_url : "<?php echo site_url('static/swfupload/swfupload.swf'); ?>",
				upload_url: "<?php echo site_url('static/upload.php'); ?>",
				post_params: {"PHPSESSID":"<?php echo session_id(); ?>",
											"path":"<?php echo $filePath; ?>"},
				file_size_limit : "100 MB",
				file_types : "*.txt;*.bin;*.sh",
				file_types_description : "All Files",
				file_upload_limit : 100,
				file_queue_limit : 0,
				custom_settings : {
					progressTarget : "fsUploadProgress",
					cancelButtonId : "btnCancel"
				},
				debug: false,

				// Button settings
				button_image_url: "<?php echo site_url('static/images/button.png'); ?>",
				button_width: "65",
				button_height: "29",
				button_placeholder_id: "spanButtonPlaceHolder",
				button_text: '<span class="theFont">Upload</span>',
				button_text_style: ".theFont { font-size: 14; }",
				button_text_left_padding: 12,
				button_text_top_padding: 3,
				
				// The event handler functions are defined in handlers.js
				file_queued_handler : fileQueued,
				file_queue_error_handler : fileQueueError,
				file_dialog_complete_handler : fileDialogComplete,
				upload_start_handler : uploadStart,
				upload_progress_handler : uploadProgress,
				upload_error_handler : uploadError,
				upload_success_handler : uploadSuccess,
				upload_complete_handler : uploadComplete,
				queue_complete_handler : queueComplete	// Queue plugin event
			};

			swfu = new SWFUpload(settings);
	     };
	</script>

<body>
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
