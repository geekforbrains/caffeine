<h1><?php echo $category['name']; ?></h1>

<ul>
    <?php foreach($items as $i): ?>
        <li>
            <img src="<?php l('media/image/%d/0/50/50', $i['thumb_cid']); ?>" />

            <a href="<?php l('portfolio/item/%d', $i['cid']); ?>">
                <?php echo $i['name']; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
