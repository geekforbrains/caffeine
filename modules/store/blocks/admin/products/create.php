<?php View::load('Store', 'admin/sidenav'); ?>

<div class="area right">
    <h2>Create Product</h2>
    <form method="post" action="<?php l('admin/store/products/create'); ?>">
        <ul>
            <li class="text medium">
                <label>Title</label>
                <input type="text" name="title" value="<?php echo Input::post('title'); ?>" />
                <?php Validate::error('title'); ?>
            </li>
            <li class="text medium">
                <label>Blurb</label>
                <input type="text" name="blurb" value="<?php echo Input::post('blurb'); ?>" />
            </li>
            <li class="textarea medium">
                <label>Short Description</label>
                <textarea name="short_description"><?php echo Input::post('short_description'); ?></textarea>
            </li>
            <li class="textarea medium">
                <label>Detailed Description</label>
                <textarea class="tinymce" name="long_description"><?php echo Input::post('long_description'); ?></textarea>
                <?php Validate::error('long_description'); ?>
            </li>
            <li class="text medium">
                <label>Price</label>
                <input type="text" name="price" value="0.00" value="<?php echo Input::post('price'); ?>" />
                <?php Validate::error('price'); ?>
            </li>
            <li class="text medium">
                <label>SKU</label>
                <input type="text" name="sku" value="<?php echo Input::post('sku'); ?>" />
            </li>
            <li class="select medium">
                <label>Category</label>
                <select name="category_cid[]" multiple="multiple">
                    <?php foreach($categories as $c): ?>
                        <option value="<?php echo $c['cid']; ?>">
                            <?php echo $c['indent']; ?>
                            <?php echo $c['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php Validate::error('category_cid'); ?>
            </li>
            <li class="select medium">
                <label>Shipping Size</label>
                <select name="shipping_size_cid">
                    <option value="">Choose One</option>
                    <?php foreach($sizes as $s): ?>
                        <option value="<?php echo $s['cid']; ?>">
                            <?php echo $s['size']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </li>
            <li class="small checkbox">
                <label>Is Featured</label>
                <input type="checkbox" name="is_featured" />
                Product is featured
            </li>
            <li class="small checkbox">
                <label>Is Used</label>
                <input type="checkbox" name="is_used" />
                Product is used
            </li>
            <li class="buttons">
                <input type="submit" value="Create" />
            </li>
        </ul>
    </form>
</div>
