<script type="text/javascript">
  $(document).ready(function() {

    $('#nameLink').click(function(e) {
      e.preventDefault(); // Stop the link from being followed
      var editBox= $('#nameLink').attr("href");
      //alert('The edit box would contain this URL: ' + editBox);
      $("#nameRow").load(editBox);
    });
  });
</script>

<body>
  <div id="main">
  <div id="logo"><h1><a href="manage">AirKey</a></h1></div>
  <div id="menu">
    <ul>
      <li><a class="selected" href="manage">home</a></li>
      <li><a href="configure">configure modules</a></li>
      <li><a href="https://salt.rocky.edu/manager/">access control</a></li>
      <li>
        <a href="airkeyconfig">airKey configuration</a>
        <ul id="submenu">
          <li><a href="#">Subitem One</a></li>
          <li><a href="#">Second Subitem</a></li>
          <li><a href="#">Numero Tres</a></li>
        </ul>
      </li>
      <li><a href="http://www.riverstonecom.net/about">about</a></li>
    </ul>
  </div>
  <div id="site_content">
    <div id="first_column">
    <div class="disignBoxFirst">
      <div class="boxFirstHeader"> Dashboard View</div>
      <div class="boxContent">
        <p>The dashboard gives you an at a glance view of the status of your newtwork. It also allows you to approve new access points </p>
      </div>
    </div>
    <div class="disignBoxFirst">
      <div class="boxFirstHeader"> Pending Approval </div>
      <div class="boxContent">
        <?php
          $this->load->helper('form');

          if(empty($pending))
          {
            print 'None Pending';
          }
          else
          {
            echo form_open('manage/pendingAP');
            print '<table>';
            print '<tr><th>MAC Address</th><th align="center">Approve?</th><th align="center">Delete?</th></tr>';
            foreach($pending as $row)
            {
              print '<tr>';
              print '<td>'.$row->mac.'</td>';
              print '<td align="center"><input type="checkbox" name="approve[]" value="'.$row->mac.'"></td>';
              print '<td align="center"><input type="checkbox" id="delete[]" value="'.$row->mac.'"></td>';
              print '</tr>';
            }
            print '</table>';
            echo form_submit('submit', 'Submit');
            echo form_close();
          }
        ?>
      </div>
    </div>
    </div>
    <div id="content">
      <div class="disignBoxSecond">
        <div class="boxContent">
        <h1>Heartbeat Log</h1>
          <?php
            if(empty($heartbeat))
            {
              print 'Log file is empty';
            }
            else
            {
              print '<table border="1" align="center">';
              print '<tr><th>MAC Address</th><th>Uptime</th><th>Version</th><th>Last Check-in</th></tr>';
              foreach($heartbeat as $row)
              {
                print '<tr>';
                print '<td>'.$row->mac.'</td>';
                print '<td>'.$row->uptime.'</td>';
                print '<td>'.$row->version.'</td>';
                print '<td>'.$row->tStamp.'</td>';
                print '</tr>';
              }
              print '</table>';
            }
          ?>
        </div>
      </div>

      <div class="disignBoxSecond">
        <div class="boxContent">
          <h1>Network Overview</h1>
          <table border="1" align="center" width="50%">
            <tr><th align="center">Total AP's</th><th align="center">AP's with Problems</th><th align="center">Pending Commands</th></tr>
            <tr>
              <td align="center"><?php echo $activeAP; ?></td>
              <td align="center">
                <?php 
                  $ds = count($dangers);

                  if($ds)
                  {
                    echo "<ul>\n";
                    foreach($dangers as $danger)
                    {
                      $name = empty($danger->name)?$danger->mac:$danger->name;
                      echo "\t<li>{$name}</li>\n";
                    }
                    echo "</ul>\n";
                  }
                  else
                    echo "None";
                ?>
</td>
              <td align="center"><?php echo $pendingCmd; ?></td>
            </tr>
          </table>
          <br />
          <p><strong>Search for AP:</strong> <input type="text" size="20" /><input type="submit" value="Search" /></p>
          <p><strong>Currently Active Access Points:</strong></p>
          <?php
            if(empty($active))
            {
              print 'No Active Access Points';
            }
            else
            {
              echo form_open('manage/activeAP');
              print '<table align="center">';
              print '<tr><th>MAC Address</th><th>Name</th><th>Group</th><th align="right">Deactivate?</th><th align="right">Delete?</th></tr>';
              foreach($active as $row)
              {
                print '<tr>';
                print '<td>'.$row->mac.'</td>';
                if(!empty($row->name))
                  print '<td align="center" id="nameRow"><a id="nameLink" href="'.site_url("configure/configAP/{$row->mac}").'">'.$row->name.'</td>';
                else
                  print '<td align="center" id="nameRow"><a id="nameLink" href="'.site_url("configure/configAP/{$row->mac}").'">Add</a></td>';
                print '<td>'.$row->groupName.'</td>';
                print '<td align="center"><input type="checkbox" name="deactivate[]" value="'.$row->mac.'"></td>';
                print '<td align="center"><input type="checkbox" name="delete[]" value="'.$row->mac.'"></td>';
                print '</tr>';
              }
              print '</table>';
              echo form_submit('submit', 'Submit');
              echo form_close();
            }
          ?>
        </div>
      </div>
    </div>

  </div>
