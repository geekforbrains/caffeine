<?php View::insert('includes/header'); ?>

<?php $subnav = Menu::build(0, 'admin/%s/%s'); ?>

<?php if($subnav): ?>
    <div class="area left short">
        <h3>Navigation</h3>
        <?php echo $subnav; ?>
    </div>

    <div class="area right">
        <h2><?php echo View::getTitle(); ?></h2>
        <?php echo $adminContent; ?>
    </div>
<?php else: ?>
    <div class="area">
        <h2><?php echo View::getTitle(); ?></h2>
        <?php echo $adminContent; ?>
    </div>
<?php endif; ?>

<?php View::insert('includes/footer'); ?>
