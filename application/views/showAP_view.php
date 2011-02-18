
<script type="text/javascript">
  $(document).ready(function() {
      $('#changeNameButton').click(function(e) {
      e.preventDefault();
      var postTo= '<?php echo base_url()."configure/configAP/$mac" ?>';
      //alert('The URL is: ' + postTo);
      //$.post(postTo, { APname: $("input[name='APname']").val()} );
      //location.href = "<?php echo base_url(); ?>";
    });
  });
</script>

<?php

  echo "Name: ";
  if(!empty($name))
    echo '<input type="text" name="APname" value="'.$name.'" />';
  else
   echo '<input type="text" name="APname" value="" />';
  echo '&nbsp;<input type="button" id="changeNameButton" name="submit" value="Update">';
?>
