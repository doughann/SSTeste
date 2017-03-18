<table id="ddw_dates_blocked" width="100%" class="list">
<thead>
	<tr>
		<td class="left">Type</td>
		<td class="left">Recurring</td>
		<td class="left">Days</td>
		<td class="left">Action</td>
	</tr>
</thead>
<tbody>
	<?php foreach($datesCollection as $ddwDate) : ?>
		<tr class="date-collection-item" id="<?php echo $ddwDate->ddwd_id;?>">	
			<td class="left">
				<select name="type">
					<?php foreach(DDWDateType::collection() as $key=>$value) : ?>
						<option value="<?php echo $value;?>"
							<?php if ($ddwDate->type == $value) : ?>selected="selected"<?php endif; ?>
						><?php echo $key;?></option>
					<?php endforeach; ?>
				</select>	
			</td>
			<td class="left">
				<?php if ($ddwDate->recurring) : ?>
					<input type="checkbox" name="recurring" value="1" checked="checked">
				<?php else: ?>
					<input type="checkbox" name="recurring" value="1">
				<?php endif; ?>
			</td>
			<td class="left">
				<input type="text" name="date_start" value="<?php echo $ddwDate->unformatDateString($ddwDate->date_start);?>" class="date">
				<?php if ($ddwDate->type == DDWDateType::Single) $hide = "display:none;"; else $hide = "display:inline-block"; ?>				
				<input type="text" name="date_end" value="<?php echo $ddwDate->unformatDateString($ddwDate->date_end);?>" class="date" style="<?php echo $hide;?>">	
			</td>
			<td class="left">
				<a href="" value="Update" class="update button">update</a>
				<a href="" value="Delete" class="delete button">delete</a>
			</td>
		</tr>
	<?php endforeach; ?>
</tbody>
<tfoot>
	<tr class="title">
		<td colspan="4">Add a date to block</td>
	</tr>
	<tr id="frmDate">	
		<td>
			<select name="type">
				<option value="single">single</option>
				<option value="range">date range</option>
			</select>
		</td>
		<td>
			<input type="checkbox" name="recurring" value="1">
		</td>
		<td>
			<input type="text" name="date_start" value="" class="date">
			<input type="text" name="date_end" value="" class="date">
		</td>
		<td>
			<a href="" value="Add" class="button add">add</a>
		</td>
	</tr>	
</tfoot>
</table>