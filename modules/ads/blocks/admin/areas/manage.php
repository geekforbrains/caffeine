<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Manage Areas</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Area</th>
            <th colspan="2">Slug</th>
        </tr>

        <?php if($areas): ?>
            <?php foreach($areas as $a): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/ads/areas/edit/%d', $a['cid']); ?>">
                            <?php echo $a['name']; ?>
                        </a>
                    </td>
                    <td><?php echo $a['slug']; ?></td>
                    <td align="right">
                        <a href="<?php l('admin/ads/areas/delete/%d', $a['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="3"><em>No areas.</em></td>
            </tr>
        <?php endif; ?>
    </table>
</div>
