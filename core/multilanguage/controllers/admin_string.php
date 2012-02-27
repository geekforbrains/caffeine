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

        if(isset($_POST['create_translation']) && Html::form()->validate())
        {
            $tmpId = Multilanguage::string()->insert(array(
                'stringcontent_id' => $string->id,
                'language_id' => $_POST['language_id'],
                'content' => $_POST['content']
            ));

            if($tmpId)
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
                'create_translation' => array(
                    'type' => 'submit',
                    'value' => 'Create Translation'
                )
            )
        );

        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('String');
        $header->addCol('Language', array('colspan' => 2));

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
                $row->addCol(Html::a()->get(
                    String::truncate($t->content, 100, '...'),
                    'admin/multilanguage/strings/edit/' . $id . '/' . $t->id
                ));
                $row->addCol($t->language);
                $row->addCol(
                    Html::a()->get('Delete', 'admin/multilanguage/strings/delete/' . $id . '/' . $t->id),
                    array('class' => 'right')
                );
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

    /**
     * Display a form for editing a strings translation.
     */
    public static function editContent($stringId, $contentId)
    {
        if(!$translation = Multilanguage::string()->find($contentId))
            return ERROR_NOTFOUND;

        if(isset($_POST['update_translation']) && Html::form()->validate())
        {
            $status = Multilanguage::string()->where('id', '=', $contentId)->update(array(
                'content' => $_POST['content']
            ));

            if($status)
            {
                Message::ok('Translation updated successfully.');
                $translation = Multilanguage::string()->find($contentId);
            }
            else
                Message::error('Error updating translation, please try again.');
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
                    'validate' => array('required'),
                    'selected' => $translation->language_id
                ),
                'content' => array(
                    'title' => 'Translated String',
                    'type' => (strlen($translation->content) > 25) ? 'textarea' : 'text',
                    'validate' => array('required'),
                    'default_value' => $translation->content
                ),
                'update_translation' => array(
                    'type' => 'submit',
                    'value' => 'Update Translation'
                )
            )
        );

        return array(
            'title' => 'Edit Translation',
            'content' => Html::form()->build($form)
        );
    }

    /**
     * Deletes a strings translation and redirects back to the string manage page.
     */
    public static function deleteContent($stringId, $contentId)
    {
        if(Multilanguage::string()->delete($contentId))
            Message::ok('Translation deleted successfully.');
        else
            Message::error('Error deleting translation, please try again.');

        Url::redirect('admin/multilanguage/strings/manage/' . $stringId);
    }

    /**
     * Deletes an actual stored string and redirects back to manage strings page. This will
     * also delete any translations associated with it.
     */
    public static function delete($stringId)
    {
        Multilanguage::string()->where('stringcontent_id', '=', $stringId)->delete();

        if(Multilanguage::stringcontent()->delete($stringId))
            Message::ok('String deleted successfully.');
        else
            Message::error('Error deleting string, please try again.');

        Url::redirect('admin/multilanguage/strings/manage');
    }

}
