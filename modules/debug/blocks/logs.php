<table border="1" cellpadding="5" cellspacing="5">
	<?php foreach($logs as $log): ?>
		<tr>
			<td><?php echo date('H:i:s', $log['timestamp']); ?></td>
			<td><?php echo $log['class']; ?></td>
			<td><?php echo $log['message']; ?></td>
		</tr>
	<?php endforeach; ?>
</table>
