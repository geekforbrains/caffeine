<?php View::insert('includes/header'); ?>

    <?php if($subNav = Menu::build(0, 'admin/%s/%s', array('attributes' => array('class' => 'menu')))): ?>
        <div class="grid_3">
            <div class="box">
                <h2>Navigation</h2>
                <div class="block">
                    <?php echo Menu::build(0, 'admin/%s/%s', array('attributes' => array('class' => 'menu'))); ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="grid_<?php echo ($subNav) ? '9' : '12'; ?>">
        <?php if($adminContent): ?>
            <?php foreach($adminContent as $content): ?>
                <div class="box">
                    <h2><?php echo $content['title']; ?></h2>
                    <div class="block">
                        <?php echo $content['content']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p><em>Nothing to display</em></p>
        <?php endif; ?>
    </div>

<?php View::insert('includes/footer'); ?>
