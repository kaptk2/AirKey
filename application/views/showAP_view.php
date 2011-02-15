
<script type="text/javascript">
  $(document).ready(function() {
      $('#changeNameButton').click(function(e) {
      e.preventDefault();
      var postTo= '<?php echo base_url()."configure/configAP/$mac" ?>';
      //alert('The URL is: ' + postTo);
      $.post(postTo, { APname: $("input[name='APname']").val()} );
      location.href = "<?php echo base_url(); ?>";
    });
  });
</script>

<?php
  $this->load->helper('form');
  echo form_open("configure/configAP/$mac");
  echo "Name: ";
  if(!empty($name))
    echo form_input('APname',$name);
  else
   echo form_input('APname');
  echo '&nbsp;<input type="submit" id="changeNameButton" name="submit" value="Update">';
  echo form_close();
?>
