<?php View::load('Ads', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Campaign</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="select small">
                <label>Ad</label>
                <select name="ad_cid">
                    <option value="">-</option>
                    <?php foreach($ads as $a): ?>
                        <option value="<?php echo $a['cid']; ?>">
                            <?php echo $a['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li class="text small">
                <label>Start Date <em>(Ex: "Jan 1st", "Today", "Tomorrow")</em></label>
                <input type="text" name="start_date" />
            </li>
            <li class="text small">
                <label>End Date <em>Leave blank for continuous</em></label>
                <input type="text" name="end_date" />
            </li>
            <li class="buttons">
                <input type="submit" value="Create Campaign" />
            </li>
        </ul>
    </form>
</div>
