<h1>Categories</h1>

<ul>
    <?php foreach($categories as $c): ?>
        <li>
            <a href="<?php l('portfolio/category/%s', $c['slug']); ?>">
                <?php echo $c['name']; ?>
            </a>
        </li>
    <?php endforeach; ?>
</ul>
