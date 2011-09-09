<div class="area left short">
    <h3>Navigation</h2>
    <?php echo Menu::build('admin/faq/questions', 0); ?>
</div>

<div class="area right">
    <h2>Create Question</h2>
    <form method="post" action="<?php echo Router::current_url(); ?>">
        <ul>
            <li class="select small">
                <label>Category</label>
                <select name="category_cid">
                    <?php foreach($categories as $c): ?>
                        <option value="<?php echo $c['cid']; ?>">
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li class="text small">
                <label>Question</label>
                <input type="text" name="question" />
                <?php echo Validate::error('question'); ?>
            </li>
            <li class="textarea medium">
                <label>Answer</label>
                <textarea name="answer"></textarea>
                <?php echo Validate::error('answer'); ?>
            </li>
            <li class="buttons">
                <input type="submit" value="Create Question" />
            </li>
        </ul>
    </form>
</div>
