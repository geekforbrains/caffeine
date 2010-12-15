<?php View::insert('includes/header') ?>

<div id="content">
	<?php echo Message::display(); ?>
	<?php View::content(); ?>
</div>

<?php View::insert('includes/footer') ?>
