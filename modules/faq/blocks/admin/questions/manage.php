<div class="area left short">
    <h3>Navigation</h3>
    <?php echo Menu::build('admin/faq/questions', 0); ?>
</div>

<div class="area right">
    <h2>Manage Questions &amp; Answers</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th colspan="2">Question</th>
        </tr>

        <?php if($questions): ?>
            <?php foreach($questions as $q): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/faq/questions/edit/%d', $q['cid']); ?>">
                            <?php echo $q['question']; ?>
                        </a>
                    </td>
                    <td align="right">
                        <a href="<?php l('admin/faq/questions/delete/%d', $q['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="2"><em>No questions.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
