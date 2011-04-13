<div class="area">
	<h2>Settings</h2>
	<table class="stripe" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Application</th>
			<th>Status</th>
			<th>Account</th>
			<th style="text-align: right">Action</th>
		</tr>
		<tr>
			<td>Twitter</td>
			<td>
				<?php if($twitter): ?>
					Activated
				<?php else: ?>
					Disabled
				<?php endif; ?>
			</td>
			<td>
				<?php if($twitter): ?>
					<a href="http://twitter.com/<?php echo $twitter['token']['screen_name']; ?>" target="_blank">
						<?php echo $twitter['token']['screen_name']; ?>
					</a>
				<?php else: ?>
					-
				<?php endif; ?>
			</td>
			<td align="right">
				<?php if($twitter): ?>
					<a href="<?php l('twitter/disable'); ?>">Disable</a>
				<?php else: ?>
					<a href="<?php l('twitter/activate'); ?>">Activate</a>
				<?php endif; ?>
			</td>
		</tr>
	</table>
</div>
