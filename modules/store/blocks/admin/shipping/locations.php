<div class="area">
    <div class="area left half">
        <h2>Create Country</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <ul>
                <li class="text medium">
                    <label>Country</label>
                    <input type="text" name="country" />
                    <?php Validate::error('country'); ?>
                </li>
                <li class="buttons">
                    <input type="submit" name="add_country" value="Add" />
                </li>
            </ul>
        </form>
    </div>

    <div class="area right half">
        <h2>Create State/Province</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <ul>
                <li class="select medium">
                    <label>Country</label>
                    <select name="country_cid">
                        <option value="">Choose One</option>
                        <?php foreach($countries as $c): ?>
                            <option value="<?php echo $c['cid']; ?>">
                                <?php echo $c['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php Validate::error('country_cid'); ?>
                </li>
                <li class="text medium">
                    <label>State/Province</label>
                    <input type="text" name="state" />
                    <?php Validate::error('state'); ?>
                </li>
                <li class="buttons">
                    <input type="submit" name="add_state" value="Add" />
                </li>
            </ul>
        </form>
    </div>
    <div class="clear"></div>

    <div class="area">
        <h2>Countries</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th colspan="2">Country</th>
            </tr>
            <?php if($countries): ?>
                <?php foreach($countries as $c): ?>
                    <tr>
                        <td>
                            <a href="<?php l('admin/store/shipping/edit-country/%d', $c['cid']); ?>">
                                <?php echo $c['name']; ?>
                            </a>
                        </td>
                        <td align="right">
                            <a href="<?php l('admin/store/shipping/delete-country/%d', $c['cid']); ?>">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="2"><em>No countries</em></td></tr>
            <?php endif; ?>
        </table>
    </div>

    <div class="area">
        <h2>State/Provinces</h2>
        <table class="stripe" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <th>State/Province</th>
                <th colspan="2">Country</th>
            </tr>
            <?php if($states): ?>
                <?php foreach($states as $s): ?>
                    <tr>
                        <td>
                            <a href="<?php l('admin/store/shipping/edit-state/%d', $s['cid']); ?>">
                                <?php echo $s['name']; ?>
                            </a>
                        </td>
                        <td><?php echo $s['country']; ?></td>
                        <td align="right">
                            <a href="<?php l('admin/store/shipping/delete-state/%d', $s['cid']); ?>">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3"><em>No state/provinces</em></td></tr>
            <?php endif; ?>
        </table>
    </div>
</div>
