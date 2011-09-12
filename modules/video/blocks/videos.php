<h1><?php echo $album['name']; ?></h1>

<div style="width: 500px">
    <?php foreach($videos as $v): ?>

        <img src="<?php l('media/image/%d/0/100/100', $v['media_cid']); ?>"><br />

        <strong><?php echo $v['title']; ?></strong><br />
        <?php echo $v['description']; ?></br />

        <?php echo Video::embed($v['cid'], 250, 200); ?>

        <br /><br />

    <?php endforeach; ?>
</div>
