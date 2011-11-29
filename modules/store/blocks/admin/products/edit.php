<?php View::load('Store', 'admin/sidenav'); ?>

<div class="area right">
    <div class="area">
        <h2>Edit Product</h2>
        <form method="post" action="<?php echo Router::current_url(); ?>">
            <ul>
                <li class="text medium">
                    <label>Title</label>
                    <input type="text" name="title" value="<?php echo $product['title']; ?>" />
                    <?php Validate::error('title'); ?>
                </li>
                <li class="text medium">
                    <label>Blurb</label>
                    <input type="text" name="blurb" value="<?php echo $product['blurb']; ?>" />
                </li>
                <li class="textarea medium">
                    <label>Short Description</label>
                    <textarea name="short_description"><?php echo $product['short_description']; ?></textarea>
                </li>
                <li class="textarea medium">
                    <label>Detailed Description</label>
                    <textarea class="tinymce" name="long_description"><?php echo $product['long_description']; ?></textarea>
                    <?php Validate::error('long_description'); ?>
                </li>
                <li class="text medium">
                    <label>Price</label>
                    <input type="text" name="price" value="<?php echo number_format($product['price'], 2); ?>" />
                    <?php Validate::error('price'); ?>
                </li>
                <li class="text medium">
                    <label>SKU</label>
                    <input type="text" name="sku" value="<?php echo $product['sku']; ?>" />
                </li>
                <li class="select medium">
                    <label>Category</label>
                    <select name="category_cid[]" multiple="multiple">
                        <?php foreach($categories as $c): ?>
                            <?php $sel = (in_array($c['cid'], $product_categories)) ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $c['cid']; ?>" <?php echo $sel; ?>>
                                <?php echo $c['indent'] . $c['name']; ?>
                            </option>
                        <?php endforeach;?>
                    </select>
                    <?php Validate::error('category_cid'); ?>
                </li>
                <li class="select medium">
                    <label>Shipping Size</label>
                    <select name="shipping_size_cid">
                        <?php foreach($sizes as $s): ?>
                            <?php $sel = ($s['cid'] == $product['shipping_size_cid']) ? 'selected="selected"' : ''; ?>
                            <option value="<?php echo $s['cid']; ?>" <?php echo $sel; ?>>
                                <?php echo $s['size']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </li>
                <li class="small checkbox">
                    <label>Is Used</label>
                    <input type="checkbox" name="is_used" <?php if($product['is_used'] > 0) echo 'checked="checked"'; ?>/> 
                    Product is used
                </li>
                <li class="small checkbox">
                    <label>Is Featured</label>
                    <input type="checkbox" name="is_featured" <?php if($product['is_featured'] > 0) echo 'checked="checked"'; ?>/> 
                    Mark product as featured
                </li>
                <li class="buttons">
                    <input type="submit" name="update_product" value="Update" />
                </li>
            </ul>
        </form>
    </div>

    <div class="area">
        <div class="area left half">
            <h2>Manage Photos</h2>
            <table class="stripe" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th colspan="2">Photo</th>
                </tr>
                <?php if($images): ?>
                    <?php foreach($images as $i): ?>
                        <tr valign="top">
                            <td><img src="<?php l('media/image/%d/0/75/75', $i['media_cid']); ?>" /></td>
                            <td align="right">
                                <a href="<?php l('admin/store/products/edit/%d/delete-photo/%d', 
                                    $product['cid'], $i['media_cid']); ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2"><em>No photos</em></td></tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="area right half">
            <h2>Upload Photo</h2>
            <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
                <ul>
                    <li class="text medium">
                        <label>Photo</label>
                        <input type="file" name="photo" />
                    </li>
                    <li class="buttons">
                        <input type="submit" name="upload_photo" value="Upload" />
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <div class="area">
        <div class="area left half">
            <h2>Manage Files</h2>
            <table class="stripe" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th colspan="2">File</th>
                </tr>

                <?php if($files): ?>
                    <?php foreach($files as $f): ?>
                        <tr>
                            <td>
                                <a href="<?php l('media/download/%d', $f['cid']); ?>">
                                    <?php echo $f['name']; ?>
                                </a>
                            </td>
                            <td align="right">
                                <a href="<?php l('admin/store/products/edit/%d/delete-file/%d', 
                                    $product['cid'], $f['cid']); ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2"><em>No files</em></td></tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="area right half">
            <h2>Upload File</h2>
            <form method="post" action="<?php echo Router::current_url(); ?>" enctype="multipart/form-data">
                <ul>
                    <li class="text medium">
                        <label>File</label>
                        <input type="file" name="file" />
                    </li>
                    <li class="buttons">
                        <input type="submit" name="upload_file" value="Upload" />
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <div class="area">
        <div class="area left half">
            <h2>Manage Options</h2>
            <table class="stripe" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th colspan="2">Option</th>
                </tr>
                <?php if($options): ?>
                    <?php foreach($options as $o): ?>
                        <tr>
                            <td>
                                <a href="<?php l('admin/store/products/edit/%d/edit-option/%d', 
                                    $product['cid'], $o['cid']); ?>">
                                    <?php echo $o['name']; ?>
                                </a>
                            </td>
                            <td align="right">
                                <a href="<?php l('admin/store/products/edit/%d/delete-option/%d',
                                    $product['cid'], $o['cid']); ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="2"><em>No options</em></td></tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="area right half">
            <h2>Create Option</h2>
            <form method="post" action="<?php echo Router::current_url(); ?>">
                <ul>
                    <li class="text medium">
                        <label>Option</label>
                        <input type="text" name="option" />
                        <?php Validate::error('option'); ?>
                    </li>
                    <li class="buttons">
                        <input type="submit" name="create_option" value="Create" />
                    </li>
                </ul>
            </form>
        </div>
    </div>

    <div class="area">
        <div class="area left half">
            <h2>Manage Related Products</h2>
            <table class="stripe" border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <th colspan="2">Product</th>
                </tr>

                <?php if($related_products): ?>
                    <?php foreach($related_products as $p): ?>
                        <tr>
                            <td>
                                <a href="<?php l('admin/store/products/edit/%d', $p['cid']); ?>">
                                    <?php echo $p['title']; ?>
                                </a>
                            </td>
                            <td align="right">
                                <a href="<?php l('admin/store/products/edit/%d/delete-related/%d', $product['cid'], $p['cid']); ?>">
                                    Delete
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2"><em>No related products.</em></td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <div class="area right half">
            <h2>Add Related Products</h2>
            <form method="post" action="<?php echo Router::current_url(); ?>">
                <ul>
                    <li class="select small">
                        <label>Product</label>
                        <select name="related_product_cid">
                            <option value="">-</option>
                            <?php foreach($products as $p): ?>
                                <?php if($p['cid'] !== $product['cid']): ?>
                                    <option value="<?php echo $p['cid']; ?>">
                                        <?php echo $p['title']; ?>
                                    </option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </select>
                        <?php echo Validate::error('related_product_cid'); ?>
                    </li>
                    <li class="buttons">
                        <input type="submit" name="add_related_product" value="Add Related Product" />
                    </li>
                </ul>
            </form>
        </div>
    </div>
</div>
