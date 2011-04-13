<?php $this->load->helper('form'); ?> 
		<div class='container'>
			<div id='contentLeft'>
				<h3>Pending Access Points</h3>
				<?php echo form_open('manage/managePending'); ?>
					<table>
						<tr>
							<th>MAC</th><th>Approve</th><th>Delete</th>
						</tr>
						<?php
						if(empty($pending))
							print '<tr><td colspan="3">None Pending</td></tr>';
						else
						{
							$odd = true;
							foreach($pending as $row)
							{
								print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rows
								print '<td>'.$row->mac.'</td>';
								print '<td><input type="checkbox" name="approve[]" value="'.$row->mac.'"></td>';
								print '<td><input type="checkbox" name="delete[]" value="'.$row->mac.'"></td>';
								print '</tr>';
							}
							print '<tr class="tr_footer">';
							print '<td colspan="3"><input type="submit" value="Submit"></td>';
							print '</tr>';
						}
						?>
					</table>
				</form>
			</div>
			<div id='contentRight'>
				<h3>Network Details</h3>
				<?php echo form_open('manage/deleteActive'); ?>
					<table>
						<tr>
							<th>Name</th>
							<th>MAC</th>
							<th>Last Checkin</th>
							<th>Group</th>
							<th>Delete</th>
						</tr>
						<?php
							if(empty($active))
								print '<tr><td colspan="5">No Active Access Points</td></tr>';
							else
							{
								$odd = true;
								foreach($active as $row)
								{
									$name = (!empty($row->ap_name) ? $row->ap_name:"Add Name");
									print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rowsprint
									print '<td><a href="'.site_url("manage/editAP/".$row->mac).'">'.$name.'</a></td>';
									print '<td><a title="View AP Info" href="'.site_url("manage/editAP/".$row->mac).'">'.$row->mac.'</a></td>';
									print '<td>'.$row->time_stamp.'</td>';
									print '<td><a href="'.site_url('manage/editAP/'.$row->mac).'">'.$row->group_name.'</a></td>';
									print '<td><input type="checkbox" name="delete[]" value="'.$row->mac.'"></td>';
									print '</tr>';
								}
								print '<tr class="tr_footer">';
								print '<td colspan="5"><input type="submit" value="Submit"></td>';
								print '</tr>';
							}
							?>
					</table>
				</form>
				<?php
					if(!empty($active))
					{
						echo "<p>Page Navigation goes here</p>"; //TODO
					}
				?>
			</div>
		</div>
