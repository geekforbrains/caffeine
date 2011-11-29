<h1>Store</h1>

<?php if($recent): ?>
    <?php foreach($recent as $r): ?>
        <p>
            <a href="<?php l('store/product/%s', $r['slug']); ?>">
                <?php if($r['images']): ?>
                    <img src="<?php l('media/image/%d/0/75/75', $r['images'][0]['media_cid']); ?>" />
                <?php else: ?>
                    <img src="<?php l('media/image/0/75/75'); ?>" />
                <?php endif; ?>
            </a>

            <a href="<?php l('store/product/%s', $r['slug']); ?>">
                <?php echo $r['title']; ?>
            </a>
        </p>
    <?php endforeach; ?>
<?php else: ?>
    <p><em>No recent products</em></p>
<?php endif; ?>
