<script type="text/javascript">
  $(document).ready(function() {

    $('#selectorDropDown').change(function() {
      if ( $('#selectorDropDown').val() == "group") {
        $('#selector').val("Enter Group Name");
      } else {
        $('#selector').val("Enter MAC Address");
      }
    });

    $('#selector').focus(function() {
      var textBox = $(this).val();
      if ( textBox == "Enter Group Name" || textBox == "Enter MAC Address" ) {
        $(this).attr("value","");
      }
    });

    $('#selector').change(function() {
      var selector = $('#selector').val()
      var selectorType = $('#selectorDropDown').val()
      var baseUrl = "<?php echo 'modules/'.$moduleName.'/getConfig' ?>"

      if (selector != " " || selector != "Enter MAC Address" || selector != "Enter Group Name")
      {
        $.post(baseUrl, {
          applyType: selectorType,
          applyTo: selector
        }, function(data) {
          $("input[name='packageName']").val(data.packageName);
          $("input[name='remoteFile']").val(data.remoteFile);
          $("input[name='localFile']").val(data.localFile);
          $("input[name='command']").val(data.command);
        }, 'json')
      }
    });

    $('#updateButton').click(function(e) {
      e.preventDefault(); // Stop the page refresh

      var baseUrl = "<?php echo 'modules/'.$moduleName.'/'.$action ?>"

      var applyType = $('#selectorDropDown').val()
      var applyTo = $('#selector').val()
      var packageName = $("input[name='packageName']").val()
      var remoteFile = $("input[name='remoteFile']").val()
      var localFile = $("input[name='localFile']").val()
      var command = $("input[name='command']").val()

      $.post(baseUrl, {
        applyType: applyType,
        applyTo: applyTo,
        packageName :packageName,
        remoteFile: remoteFile,
        localFile: localFile,
        command: command
      }, function(data) {
        $("#statusBar").show();
        $("#statusBar").text("Success");
        $("#statusBar").fadeOut(7000);
      })
    });
  });
</script>
Apply to:
<select id="selectorDropDown">
  <option value="mac">MAC</option>
  <option value="group">Group</option>
</select>

<input id="selector" type="text" size="20" maxlength="255" value="Enter MAC Address" />
