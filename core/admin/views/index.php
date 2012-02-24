<?php View::insert('includes/header'); ?>

<!-- start content -->
<div class="content container_12">

    <?php if($subNav = Menu::build(-1, 'admin/%s', array('attributes' => array('class' => 'menu')))): ?>
        <div class="grid_3 spacer">
            <h2>Navigation</h2>
            <?php echo $subNav; ?>
        </div>
    <?php endif; ?>

    <div class="grid_<?php echo ($subNav) ? '9' : '12'; ?>">
        <?php if(is_array($adminContent) && $adminContent): ?>
            <?php if(isset($adminContent[0])): ?>

                <?php foreach($adminContent as $content): ?>
                    <div class="grid_12 spacer">
                        <h1><?php echo $content['title']; ?></h1>
                        <?php echo $content['content']; ?>
                    </div>
                <?php endforeach; ?>

            <?php else: ?>
                    
                <div class="grid_12 spacer">
                    <h2><?php echo $adminContent['title']; ?></h2>
                    <?php echo $adminContent['content']; ?>
                </div>

            <?php endif; ?>
        <?php else: ?>

            <div class="grid_12 spacer">
                <p><em>Nothing to display</em></p>
            </div>

        <?php endif; ?>
    </div>

    <div class="clear">&nbsp;</div>

    <?php /*
    <div class="grid_12 spacer">
        <h1>Table</h1>

        <div class="topright">
            <form>
                Search: 
                <input type="text" />
                <a class="btn mini blue" href="#">Go</a>
            </form>
        </div>

        <table class="sortable">
            <thead>
                <tr>
                    <th width="10"><input class="checkall" type="checkbox" /></th>
                    <th>Header One</th>
                    <th>Header Two</th>
                    <th class="right">Header Right</th>
                </tr>
            </thead>
            <tbody class="sort_items">
                <tr class="sort_item">
                    <td><input type="checkbox" /></td>
                    <td><a href="#">Item A1</a></td>
                    <td>Item A2</td>
                    <td class="right">Item Right</td>
                </tr>
                <tr class="sort_item">
                    <td><input type="checkbox" /></td>
                    <td><a href="#">Item B1</a></td>
                    <td>Item B2</td>
                    <td class="right">Item Right</td>
                </tr>
                <tr class="sort_item">
                    <td><input type="checkbox" /></td>
                    <td><a href="#">Item C1</a></td>
                    <td>Item C2</td>
                    <td class="right">Item Right</td>
                </tr>
                <tr class="sort_item">
                    <td><input type="checkbox" /></td>
                    <td><a href="#">Item D1</a></td>
                    <td>Item D2</td>
                    <td class="right">Item Right</td>
                </tr>
                <tr class="sort_item">
                    <td><input type="checkbox" /></td>
                    <td><a href="#">Item E1</a></td>
                    <td>Item E2</td>
                    <td class="right">Item Right</td>
                </tr>
            </tbody>
        </table>

        <div class="buttons">
            <a class="btn blue modal" href="#">Show Modal</a>
        </div>

        <div class="buttons right">
            <a class="btn blue" href="#">Submit</a>&nbsp;
            <a class="btn gray" href="#">Cancel</a>
        </div>
    </div>
    <div class="clear">&nbsp;</div>

    <div class="grid_3 spacer">
        <h2>Menu</h2>
        <ul class="menu">
            <li><a href="#">Item One</a></li>
            <li><a href="#">Item Two</a></li>
            <li><a href="#">Item Three</a></li>
            <li class="noborder"><a href="#">Item Four</a></li>
        </ul>
    </div>
    <div class="grid_9 spacer">
        <h1>Form</h1>
        <form>
            <ul>
                <!-- mini form items -->
                <li class="text mini">
                    <label>Text Mini</label>
                    <input type="text" />
                    <input type="text" />
                    <input type="text" />
                </li>
                <li class="select mini">
                    <label>Select Mini</label>
                    <select class="chzn-select"><option>-</option></select>
                    <select class="chzn-select"><option>-</option></select>
                    <select class="chzn-select"><option>-</option></select>
                </li>

                <!-- small form items -->
                <li class="text">
                    <label>Text Small <em>(Default)</em></label>
                    <input type="text" />
                </li>
                <li class="select">
                    <label>Select Small <em>(Default)</em></label>
                    <select class="chzn-select"><option>-</option></select>
                </li>
                <li class="textarea small">
                    <label>Textarea Small</label>
                    <textarea></textarea>
                </li>

                <!-- medium form items -->
                <li class="text medium">
                    <label>Text Medium</label>
                    <input type="text" />
                </li>
                <li class="select medium">
                    <label>Select Medium</label>
                    <select class="chzn-select"><option>-</option></select>
                </li>
                <li class="textarea medium">
                    <label>Textarea Medium <em>(Default)</em></label>
                    <textarea class="tinymce"></textarea>
                </li>

                <!-- large form items -->
                <li class="text large">
                    <label>Text Large</label>
                    <input type="text" />
                </li>
                <li class="select large">
                    <label>Select Large</label>
                    <select class="chzn-select"><option>-</option></select>
                </li>
                <li class="textarea large">
                    <label>Textarea Large</label>
                    <textarea></textarea>
                </li>

                <!-- full form items -->
                <li class="text full">
                    <label>Text Full</label>
                    <input type="text" />
                </li>
                <li class="select full">
                    <label>Select Full</label>
                    <select class="chzn-select"><option>-</option></select>
                </li>
                <li class="textarea full">
                    <label>Textarea Full</label>
                    <textarea></textarea>
                </li>
                <li class="checkbox">
                    <label>Checkboxes</label>
                    <div class="row"><input type="checkbox" /> Checkbox One</div>
                    <div class="row"><input type="checkbox" /> Checkbox Two</div>
                    <div class="row"><input type="checkbox" /> Checkbox Three</div>
                </li>
                <li class="radio">
                    <label>Radios</label>
                    <div class="row"><input type="radio" /> Radio One</div>
                    <div class="row"><input type="radio" /> Radio Two</div>
                    <div class="row"><input type="radio" /> Radio Three</div>
                </li>
                <li class="buttons">
                    <a class="btn blue">Submit</a>&nbsp;
                    <a class="btn gray">Cancel</a>
                </li>
            </ul>
        </form>
    </div>
    <div class="clear">&nbsp;</div>
    */ ?>
</div>
<!-- end content -->

<?php View::insert('includes/footer'); ?>
