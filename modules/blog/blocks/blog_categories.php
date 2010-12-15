<? if($categories): ?>
    <strong>Categories</strong><br />
    <ul>
    <? foreach($categories as $category): ?>
        <li>
            <a href="<?=Router::url('blog/category/%s', $category['slug'])?>">
                <?=$category['name']?>
            </a>
        </li>
    <? endforeach; ?>
    </ul>
<? endif; ?>
