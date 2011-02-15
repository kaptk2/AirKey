<body>
  <script type="text/javascript">
    $(document).ready(function() {
      $("a#iframe").fancybox({
        'width' : '75%',
        'height' : '75%',
        'autoScale' : false,
        'transitionIn' : 'none',
        'transitionOut' : 'none',
        'type' : 'iframe'
      });

      $("#modules").val('None');

      $("#modules").change(function() {
        if ( $("#modules").val() != "None" ) {
          var module='modules/' + $("#modules").val() + '/changeConfig';
          $("#editBox").load(module);
        } else {
          $("#editBox").text("No module selected");
        }
      });

      $("#actionDropDown").change(function() {
        var actionDropDown = $("#actionDropDown").val();
        if (actionDropDown == "delete")
          $('#group').attr("disabled", true);
        else
          $('#group').removeAttr("disabled");
      });

    });
  </script>

  <div id="main">
  <div id="logo"><h1><a href="manage">AirKey</a></h1></div>
  <div id="menu">
    <ul>
      <li><a href="manage">home</a></li>
      <li><a class="selected" href="configure">configure modules</a></li>
      <li><a href="https://salt.rocky.edu/manager/">access control</a></li>
      <li><a href="airkeyconfig">airKey configuration</a></li>
      <li><a href="http://www.riverstonecom.net/about">about</a></li>
    </ul>
  </div>
  <div id="site_content">
    <div id="first_column">
    <div class="disignBoxFirst">
      <div class="boxFirstHeader"> Configure Modules</div>
      <div class="boxContent">
        <p>You can configure the default settings for modules, apply custom modifications to specific access points or groups of access points.</p>
      </div>
    </div>
    <div class="disignBoxFirst">
      <div class="boxFirstHeader"> Installed Modules </div>
      <div class="boxContent">
        <?php
          if(empty($modules))
          {
            print 'No Installed Modules';
          }
          else
          {
            print '<table>';
            print '<tr><th>Module Name</th><th align="center">Edit Defaults</th><th align="center">Delete Module</th></tr>';
            foreach($modules as $item)
            {
              $item = substr($item, 0, -4);
              print '<tr>';
              print '<td>'.$item.'</td>';
              print '<td align="center"><a id="iframe" href="modules/'.$item.'/editDefaults" ">Edit?</td>';
              print '<td align="center"><input type="checkbox" id="deletebox" value="'.$item.'"></td>';
            }
            print '</table>';
          }
        ?>
      </div>
    </div>
    </div>
    <div id="content">
      <div class="disignBoxSecond">
        <div class="boxContent">
        <h1>Customize Module</h1>
        <p>
          Select a module:
          <?php
            if(empty($modules))
            {
              print 'No Installed Modules';
            }
            else
            {
              print '<select id="modules">';
              print '<option value="None" selected>None</option>';
              foreach($modules as $item)
              {
                $item = substr($item, 0, -4);
                print '<option value="'.$item.'">'.$item.'</option>';
              }
              print '</select>';
            }
          ?>
        </p>
        <h3>Configuration:</h3>
        <p id="editBox">
          No module selected
          <div id="statusBar"></div>
        </p>
        </div>
      </div>

      <div class="disignBoxSecond">
        <div class="boxContent">
          <h1>Create AP Group</h1>
          <?php
            $this->load->helper('form');
            echo form_open('configure/search');
          ?>
          <p>Search for AP: <input type="text" size="20" /><input type="submit" value="Search" /></p>
          <?php
            echo form_close();
            echo form_open('configure/group')
          ?>
          <p align="center">
            Action:
            <select name="actionDropDown" id="actionDropDown">
              <option value="create">Create Group with Selected</option>
              <option value="add">Add Selected to Group</option>
              <option value="delete">Delete Selected from Group</option>
            </select><br />
            Group Name: <input name="groupName" id="group" type="text" size="18" />
          </p>
          <?php
            if(empty($active))
            {
              print 'No Active Access Points';
            }
            else
            {

              print '<table align="center">';
              print '<tr><th>MAC Address</th><th align="center">Current Group</th><th align="center">Select</th></tr>';
              foreach($active as $row)
              {
                print '<tr>';
                print '<td>'.$row->mac.'</td>';
                print '<td align="center">'.$row->groupName.'</td>';
                print '<td align="center"><input name="mac[]" value="'.$row->mac.'" type="checkbox" /></td>';
              }
              print '</table>';
            }
            echo form_submit('submit', 'Submit');
            echo form_close();
          ?>
        </div>
      </div>
    </div>

  </div>
