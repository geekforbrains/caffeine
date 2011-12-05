<?php View::load('Portfolio', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Item</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="select small">
                <label>Category</label>
                <select name="category_cid">
                    <option value="">-</option>
                    <?php foreach($categories as $c): ?>
                        <option value="<?php echo $c['cid']; ?>">
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php echo Validate::error('category_cid'); ?>
            </li>
            <li class="text small">
                <label>Name</label>
                <input type="text" name="name" />
                <?php echo Validate::error('name'); ?>
            </li>
            <li class="textarea medium">
                <label>Description</label>
                <textarea name="description" class="tinymce"></textarea>
            </li>

            <!-- start extra data fields -->

            <li class="text small">
                <label>Client</label>
                <input type="text" name="client" />
            </li>
            <li class="text small">
                <label>Role</label>
                <input type="text" name="role" />
            </li>
            <li class="text small">
                <label>Date</label>
                <input type="text" name="date" />
            </li>
            <li class="text small">
                <label>Weight</label>
                <input type="text" name="weight" />
            </li>

            <!-- end extra data fields -->

            <li class="buttons">
                <input type="submit" value="Create Item" />
            </li>
        </ul>
    </form>
</div>
