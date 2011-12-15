<h1><?php echo $page->title; ?></h1>

<?php echo $page->body; ?>

<hr />

<?php if(isset($_FILES['test'])): ?>
    <?php $image = Media::image()->save('test'); ?>
    <?php if(!$image->hasError()): ?>
        <?php die('Success! - ' . $image->getId()); ?>
    <?php else: ?>
        <?php die($image->getError()); ?>
    <?php endif; ?>
<?php endif; ?>

<?php echo Html::form()->openMultipart(); ?>
    <input type="file" name="test" />
    <input type="submit" />
<?php echo Html::form()->close(); ?>

<img src="<?php echo Media::image()->get(1, 90, 25); ?>" />
