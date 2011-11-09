<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Manage Ads</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2">Name</th>
        </tr>

        <?php if($ads): ?>
            <?php foreach($ads as $a): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/ads/ads/edit/%d', $a['cid']); ?>">
                            <?php echo $a['name']; ?>
                        </a>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/ads/ads/delete/%d', $a['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2"><em>No ads.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
