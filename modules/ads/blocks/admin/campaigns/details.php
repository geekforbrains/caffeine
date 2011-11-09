<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <div class="area">
        <h2>Campaign Stats</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th width="175">Ad</th>
                <td>
                    <a href="<?php l('admin/ads/ads/edit/%d', $campaign['ad_cid']); ?>">
                        <?php echo $campaign['ad_name']; ?>
                    </a>
                </td>
            </tr>
            <tr>
                <th>Start Date</th>
                <td><?php echo date('M jS, Y', $campaign['start_date']); ?></td>
            </tr>
            <tr>
                <th>End Date</th>
                <td>
                    <?php if($campaign['end_date'] > 0): ?>
                        <?php echo date('M jS, Y', $campaign['end_date']); ?>
                    <?php else: ?>
                        <em>Continious</em>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td><?php echo ucfirst($campaign['status']); ?></td>
            </tr>
            <tr>
                <th>Total Impressions</th>
                <td><?php echo number_format($campaign['impressions']); ?></td>
            </tr>
            <tr>
                <th>Total Clicks</th>
                <td><?php echo number_format($campaign['clicks']); ?></td>
            </tr>
            <tr>
                <th>Click Through Rate (CTR)</th>
                <td>
                    <?php if($campaign['impressions'] > 0): ?>
                        <?php echo number_format(($campaign['clicks'] / $campaign['impressions']), 2); ?>%
                    <?php else: ?>
                        0%
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="area nomargin">
        <h2>Stats by Day</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th>Date</th>
                <th>Impressions</th>
                <th>Clicks</th>
                <th style="text-align: right">CTR</th>
            </tr>

            <?php if($stats): ?>
                <?php foreach($stats as $s): ?>
                    <tr>
                        <td><?php echo date('M jS, Y', $s['date']); ?></td>
                        <td><?php echo number_format($s['impressions']); ?></td>
                        <td><?php echo number_format($s['clicks']); ?></td>
                        <td align="right">
                            <?php if($s['impressions'] > 0): ?>
                                <?php echo number_format(($s['clicks'] / $s['impressions']), 2); ?>%
                            <?php else: ?>
                                0%
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4"><em>No stats.</em></td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
