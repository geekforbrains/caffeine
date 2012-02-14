<?php

class Multilanguage_Admin_LanguageController extends Controller {

    /**
     * Displays a table of languages to be supported.
     */
    public static function manage()
    {
        $table = Html::table();
        $table->addHeader()->addCol('Languages');

        $langs = Multilanguage::language()->orderBy('name')->all();

        if($langs)
        {
            foreach($langs as $lang)
            {
                $row = $table->addRow();
                $row->addCol(Html::a()->get($lang->name, 'admin/multilanguage/languages/edit/' . $lang->id));
                $row->addCol(
                    Html::a()->get('Delete', 'admin/multilanguage/languages/delete/' . $lang->id),
                    array('class' => 'right')
                );
            }
        }
        else
            $table->addRow()->addCol('<em>No languages</em>', array('colspan' => 2));

        return array(
            'title' => 'Manage Languages',
            'content' => $table->render()
        );
    }

}
