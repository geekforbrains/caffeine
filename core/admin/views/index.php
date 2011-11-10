<?php View::insert('includes/header'); ?>

<?php $subnav = Menu::build(0, 'admin/%s/%s'); ?>

<?php if($subnav): ?>

    <div class="area left short">
        <h3>Navigation</h3>
        <?php echo $subnav; ?>
    </div>

    <div class="area right">
        <?php if(is_array($adminContent)): ?>

            <?php foreach($adminContent as $content): ?>
                <div class="area">
                    <?php if(is_array($content)): ?>
                        <h2><?php echo $content['title']; ?></h2>
                        <?php echo $content['content']; ?>
                    <?php else: ?>
                        <h2><?php echo View::getTitle(); ?></h2>
                        <?php echo $content; ?>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

        <?php else: ?>

            <h2><?php echo View::getTitle(); ?></h2>
            <?php echo $adminContent; ?>

        <?php endif; ?>
    </div>

<?php else: ?>

    <div class="area">
        <h2><?php echo View::getTitle(); ?></h2>
        <?php echo $adminContent; ?>
    </div>

<?php endif; ?>

<?php View::insert('includes/footer'); ?>
