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

      if (selector != "" || selector != "Enter MAC Address" || selector != "Enter Group Name")
      {
        $.post(baseUrl, {
          applyType: selectorType,
          applyTo: selector
        }, function(data) {
          $("input[name='currentVersion']").val(data.currentVersion);
          $("input[name='modules']").val(data.modules);
          $("input[name='run']").val(data.run);
        }, 'json')
      }
    });

    $('#updateButton').click(function(e) {
      e.preventDefault(); // Stop the page refresh

      var baseUrl = "<?php echo 'modules/'.$moduleName.'/'.$action ?>"

      var applyType = $('#selectorDropDown').val()
      var applyTo = $('#selector').val()
      var currentVersion = $("input[name='currentVersion']").val()
      var modules = $("input[name='modules']").val()

      $.post(baseUrl, {
        applyType: applyType,
        applyTo: applyTo,
        currentVersion : currentVersion,
        modules: modules
      }, function() {
        $("#statusBar").show();
        $("#statusBar").text("Success!");
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
