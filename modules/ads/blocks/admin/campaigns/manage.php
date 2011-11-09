<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Active Campaigns</h2>

    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Ad</th>
            <th>Start Date</th>
            <th colspan="2">End Date</th>
        </tr>

        <?php if($active): ?>
            <?php foreach($active as $c): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/ads/campaigns/details/%d', $c['cid']); ?>">
                            <?php echo $c['ad_name']; ?>
                        </a>
                    </td>
                    <td><?php echo date('M jS, Y', $c['start_date']); ?></td>
                    <td>
                        <?php if($c['end_date'] > 0): ?>
                            <?php echo date('M jS, Y', $c['end_date']); ?>
                        <?php else: ?>
                            <em>Continious</em>
                        <?php endif; ?>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/ads/campaigns/stop/%d', $c['cid']); ?>">
                            Stop
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><em>No active campaigns.</em></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<div class="area right">
    <h2>Recently Completed Campaigns</h2>

    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Ad</th>
            <th>Start Date</th>
            <th colspan="2">End Date</th>
        </tr>

        <?php if($completed): ?>
            <?php foreach($completed as $c): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/ads/campaigns/details/%d', $c['cid']); ?>">
                            <?php echo $c['ad_name']; ?>
                        </a>
                    </td>
                    <td><?php echo date('M jS, Y', $c['start_date']); ?></td>
                    <td>
                        <?php if($c['end_date'] > 0): ?>
                            <?php echo date('M jS, Y', $c['end_date']); ?>
                        <?php else: ?>
                            <em>Continious</em>
                        <?php endif; ?>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/ads/campaigns/delete/%d', $c['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><em>No recently completed campaigns.</em></td>
            </tr>
        <?php endif; ?>
    </table>
</div>

<div class="area right">
    <h2>Scheduled Campaigns</h2>

    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Ad</th>
            <th>Start Date</th>
            <th colspan="2">End Date</th>
        </tr>

        <?php if($scheduled): ?>
            <?php foreach($scheduled as $c): ?>
                <tr>
                    <td><?php echo $c['ad_name']; ?></td>
                    <td><?php echo date('M jS, Y', $c['start_date']); ?></td>
                    <td>
                        <?php if($c['end_date'] > 0): ?>
                            <?php echo date('M jS, Y', $c['end_date']); ?>
                        <?php else: ?>
                            <em>Continious</em>
                        <?php endif; ?>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/ads/campaigns/delete/%d', $c['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4"><em>No scheduled campaigns.</em></td>
            </tr>
        <?php endif; ?>
    </table>
</div>
