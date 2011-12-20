<h1>Index</h1>

<?php
if($_POST)
{
    $video = Media::video()->saveFromUrl($_POST['url']);

    if(!$video->hasError())
        die('Success! - ' . $video->getId());
    else
        die($video->getError());
}
?>

<?php echo Html::form()->open(); ?>

    <input type="text" name="url" />
    <input type="submit" value="Add Video" />

<?php echo Html::form()->close(); ?>

<?php echo Media::video()->embed(3); ?>
