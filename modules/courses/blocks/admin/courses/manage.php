<?php View::load('Courses', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Manage Courses</h2>
    <table class="stripe" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <th>Name</th>
            <th colspan="2">Category</th>
        </tr>

        <?php if($courses): ?>
            <?php foreach($courses as $c): ?>
                <tr>
                    <td>
                        <a href="<?php l('admin/courses/courses/edit/%d', $c['cid']); ?>">
                            <?php echo $c['name']; ?>
                        </a>
                    </td>
                    <td><?php echo $c['category']; ?></td>
                    <td align="right">
                        <a href="<?php l('admin/courses/courses/delete/%d', $c['cid']); ?>">
                            Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="3"><em>No courses.</em></td></tr>
        <?php endif; ?>
    </table>
</div>
