<? View::insert('includes/header'); ?>

<div class="row-fluid">
    <div class="span12">
        <div class="page-header"><h2>Manage Pages</h2></div>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th colspan="2">Title</th>
                </tr>
            </thead>
            <tbody>
                <? if($pages): ?>
                    <? foreach($pages as $page): ?>
                        <tr>
                            <td><?= $page->title; ?></td>
                            <td class="right"><?= _a('Delete', 'admin/page/delete/' . $page->id); ?></td>
                        </tr>
                    <? endforeach; ?>
                <? else: ?>
                    <tr><td colspan="2"><em>No pages.</em></td></tr>
                <? endif; ?>
            </tbody>
        </table>
    </div>
</div>

<? View::insert('includes/footer'); ?>
