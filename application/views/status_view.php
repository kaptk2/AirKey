<?php $this->load->helper('form'); ?>
<div class='container'>
	<div class='content'>
		<h3>Current Issues</h3>
		<?php echo form_open('status/currentIssues'); ?>
			<table>
				<tr>
					<th>MAC</th><th>AP Name</th><th>Status</th><th>Action</th>
				</tr>
				<?php
				if(empty($issue))
					print '<tr><td colspan="4">No Issues</td></tr>';
				else
				{
					$odd = true;
					foreach($issue as $row)
					{
						print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rows
						print '<td>'.$row->mac.'</td>';
						print '<td>'.$row->ap_name.'</td>';
						print '<td>'.$row->alarm_status.'</td>';
						print '<td>
						<select name="action['.$row->mac.']">
							<option value="Nothing">Do Nothing</option>
							<option value="Acknowledged">Acknowledge</option>
							<option value="Ignore">Ignore</option>
						</select>
					</td>';
						print '</tr>';
					}
					print '<tr class="tr_footer">';
					print '<td colspan="4"><input type="submit" value="Submit"></td>';
					print '</tr>';
				}
				?>
			</table>
		</form>
		<h3>Last Log</h3>
		<table>
			<tr>
				<th>MAC</th><th>Version</th><th>Uptime</th><th>Time Stamp</th><th>Alarm Status</th>
			</tr>
			<?php
			if(empty($log))
				print '<tr><td colspan="5">Empty Log</td></tr>';
			else
			{
				$odd = true;
				foreach($log as $row)
				{
					print '<tr'.(($odd = !$odd)?' class="tr_alt"':'').'>'; // alternate row colors on table rows
					print '<td>'.$row->mac.'</td>';
					print '<td>'.$row->ap_version.'</td>';
					print '<td>'.$row->uptime.'</td>';
					print '<td>'.$row->time_stamp.'</td>';
					print '<td>'.$row->alarm_status.'</td>';
					print '</tr>';
				}
				print '<tr class="tr_footer">';
				// TODO paginate log
				print '<td>First</td>';
				print '<td>Previous</td>';
				print '<td>&nbsp;</td>';
				print '<td>Next</td>';
				print '<td>Last</td>';
				print '</tr>';
			}
			?>
		</table>
	</div>
</div>
