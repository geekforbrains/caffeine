<?php

class Multilanguage_Admin_StringController extends Controller {

    /**
     * Displays a table of strings that have been passed through the t() function and are available
     * for translation.
     *
     * Route: admin/multilanguage/strings/manage
     */
    public static function manage()
    {
        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('String', array('colspan' => 2));

        $strings = Multilanguage::stringcontent()->orderBy('content')->all();

        if($strings)
        {
            foreach($strings as $string)
            {
                $row = $table->addRow();
                $row->addCol(Html::a()->get(
                    String::truncate($string->content, 100, '...'), 
                    'admin/multilanguage/strings/manage/' . $string->id)
                );
                $row->addCol(
                    Html::a()->get('Delete', 'admin/multilanguage/strings/delete/' . $string->id),
                    array('class' => 'right')
                );
            }
        }
        else
            $table->addRow()->addCol('<em>No strings.</em>', array('colspan' => 2));

        return array(
            'title' => 'Manage Strings',
            'content' => $table->render()
        );
    }

    /**
     * Displays a form for creating a new translation of the current string. Also displays a table
     * of current translations for the current string.
     *
     * Route: admin/multilanguage/strings/manage/:id
     *
     * @param int $id The id of the string to create translations for
     */
    public static function manageContent($id)
    {
        if(!$string = Multilanguage::stringcontent()->find($id))
            return ERROR_NOTFOUND;

        if($_POST && Html::form()->validate())
        {
            $id = Multilanguage::string()->insert(array(
                'stringcontent_id' => $string->id,
                'language_id' => $_POST['language_id'],
                'content' => $_POST['content']
            ));

            if($id)
            {
                Message::ok('Translation created successfully.');
                unset($_POST['language_id']); // Clear selected language
            }
            else
                Message::error('Error creating translation, please try again.');
        }

        $langs = Multilanguage::language()->orderBy('name')->all();
        $sortedLangs = array('' => 'Choose One');

        foreach($langs as $l)
            $sortedLangs[$l->id] = $l->name;

        $form[] = array(
            'fields' => array(
                'language_id' => array(
                    'title' => 'Language',
                    'type' => 'select',
                    'options' => $sortedLangs,
                    'validate' => array('required')
                ),
                'content' => array(
                    'title' => 'Translated String',
                    'type' => (strlen($string->content) > 25) ? 'textarea' : 'text',
                    'validate' => array('required'),
                    'default_value' => $string->content
                ),
                'submit' => array(
                    'type' => 'submit',
                    'value' => 'Create Translation'
                )
            )
        );

        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('Language', array('colspan' => 3));

        $translations = Multilanguage::string()
            ->select('multilanguage_strings.*, multilanguage_languages.name AS language')
            ->leftJoin('multilanguage_languages', 'multilanguage_languages.id', '=', 'multilanguage_strings.language_id')
            ->where('multilanguage_strings.stringcontent_id', '=', $id)
            ->orderBy('multilanguage_languages.name')
            ->all();

        if($translations)
        {
            foreach($translations as $t)
            {
                $row = $table->addRow();
                $row->addCol(String::truncate($t->content, 100, '...'));
                $row->addCol($t->language);
                $row->addCol('Delete', array('class' => 'right'));
            }
        }
        else
            $table->addRow()->addCol('<em>No translations.</em>', array('colspan' => 3));

        return array(
            array(
                'title' => 'Create String Translation',
                'content' => Html::form()->build($form)
            ),
            array(
                'title' => 'Translations',
                'content' => $table->render()
            )
        );
    }

}
