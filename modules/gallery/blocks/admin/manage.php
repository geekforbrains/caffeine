<div class="area">
    <h2>Manage Albums</h2>
<form method="post" action="<?php l('admin/gallery/manage'); ?>">
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Album</th>
            <th>Number of Photos</th>
			<th>Weight</th>
			<th>&nbsp;</th>
        </tr>

        <?php if($albums): ?>
            <?php foreach($albums as $a): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/gallery/edit/%d', $a['cid']); ?>">
                            <?php echo $a['name']; ?></td>
                        </a>
                    </td>
                    <td><?php echo $a['photo_count']; ?></td>
					<td align="center">
						<input type="text" name="weights[]" size="1" value="<?php echo $a['weight']; ?>" />
					</td>
                    <td align="right">
                        <a href="<?php l('admin/gallery/delete/%d', $a['cid']); ?>" onclick="return confirm('Deleting an album will delete all photos associated with it. Are you sure you woud like to continue?');">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3"><em>No albums.</em></td></tr>
        <?php endif; ?>
    </table>
		<div class="buttons">
			<input type="submit" value="Update Display Order" />
		</div>
</form>
</div>
