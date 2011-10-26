<h1><?php echo $item['name']; ?></h1>

Description: <?php echo $item['description']; ?><br />
Client: <?php echo $item['client']; ?><br />
Role: <?php echo $item['role']; ?><br />
Date: <?php echo date('M jS, Y', $item['date']); ?><br />


<?php foreach($item['photos'] as $photo): ?>
    <img src="<?php l('media/image/%d/0/100/100', $photo['cid']); ?>" /><br />
<?php endforeach; ?>

<?php foreach($item['videos'] as $video): ?>
    <?php echo Video::embed($video['cid'], 200, 150); ?><br />
<?php endforeach; ?>
