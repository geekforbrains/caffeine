<div class="area">
    <h2>Manage Albums</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2">Album</th>
        </tr>
    
        <?php if($albums): ?>
            <?php foreach($albums as $a): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/video/edit/%d', $a['cid']); ?>">
                            <?php echo $a['name']; ?>
                        </a>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/video/delete/%d', $a['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2"><em>No albums.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
