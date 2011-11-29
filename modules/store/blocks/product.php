<h1><?php echo $product['title']; ?></h1>
<p><?php echo $product['blurb']; ?></p>
<p>
    <?php echo $symbol; ?><?php echo number_format($product['price'], 2); ?>
    <?php echo $currency; ?>
</p>

<?php if($product['images']): ?>
    <img src="<?php l('media/image/%d/0/500/0', $product['images'][0]['media_cid']); ?>" />
    <br /> 
    <?php foreach($product['images'] as $i): ?>
        <img src="<?php l('media/image/%d/0/75/75', $i['media_cid']); ?>" />
    <?php endforeach; ?>
<?php else: ?>
    <img src="<?php l('media/image/0/150/150'); ?>" />
<?php endif; ?>

<p><?php echo $product['description']; ?></p>

<form method="post" action="<?php l('store/cart'); ?>">
    <input type="hidden" name="add_to_cart" value="true" />
    <input type="hidden" name="product_cid" value="<?php echo $product['cid']; ?>" />

    <?php foreach($options as $o): ?>
        <?php echo $o['name']; ?><br />

            <?php $sel = true; ?>
            <?php foreach($o['values'] as $v): ?>
                <input type="radio" name="options[<?php echo $o['cid']; ?>]" value="<?php echo $v['cid']; ?>" <?php if($sel) echo 'checked="checked"'; ?> />
                <?php echo $v['value']; ?>

                <?php if($v['price'] > 0): ?>
                    (<?php echo $symbol . $v['price']; ?>)
                <?php endif; ?>

                <?php if($sel) $sel = false; ?>
                <br />
            <?php endforeach ;?>

        <br />
    <?php endforeach; ?>

    Qty: <input type="text" name="qty" value="1" /><br />
    <input type="submit" value="Add to Cart" />
</form>
