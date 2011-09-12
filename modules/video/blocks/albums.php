<h1>Albums</h1>

<?php foreach($albums as $a): ?>

    <a href="<?php l('videos/%d', $a['cid']); ?>">
        <?php echo $a['name']; ?>
    </a><br />

<?php endforeach; ?>
