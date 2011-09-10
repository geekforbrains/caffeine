<h1>Albums</h1>

<ul>
    <?php foreach($albums as $a): ?>
        <li>
            <a href="<?php l('gallery/%d', $a['cid']); ?>">
                <?php echo $a['name']; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
