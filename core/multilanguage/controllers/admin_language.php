<?php

class Multilanguage_Admin_LanguageController extends Controller {

    /**
     * Displays a table of languages to be supported.
     *
     * Route: admin/multilanguage/languages/manage
     */
    public static function manage()
    {
        $table = Html::table();
        $table->addHeader()->addCol('Languages', array('colspan' => 2));

        $langs = Multilanguage::language()->orderBy('name')->all();

        if($langs)
        {
            foreach($langs as $lang)
            {
                $row = $table->addRow();
                $row->addCol(Html::a()->get($lang->name, 'admin/multilanguage/languages/edit/' . $lang->id));
                $row->addCol(
                    Html::a('Delete', 'admin/multilanguage/languages/delete/' . $lang->id, array(
                        'onclick' => "return confirm('Delete this language?')"
                    )),
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

    /**
     * Displays a form for creating new languages.
     *
     * Route: admin/multilanguage/languages/create
     */
    public static function create()
    {
        if(isset($_POST['create_language']) && Html::form()->validate())
        {
            if(!Multilanguage::language()->where('code', 'LIKE', $_POST['code'])->first())
            {
                $id = Multilanguage::language()->insert(array(
                    'name' => $_POST['name'],
                    'code' => strtolower($_POST['code'])
                ));

                if($id)
                {
                    Message::ok('Language created successfully.');
                    $_POST = array(); // Clear form
                }
                else
                    Message::error('Error creating language, please try again.');
            }
            else
                Message::error('A language with that code already exists.');
        }

        $form[] = array(
            'fields' => array(
                'name' => array(
                    'title' => 'Language Name <em>(Ex: Russian)</em>',
                    'type' => 'text',
                    'validate' => array('required')
                ),
                'code' => array(
                    'title' => '2 Letter Language Code <em>(Ex: ru)<em>',
                    'type' => 'text',
                    'validate' => array('required', 'min:2'),
                    'attributes' => array(
                        'maxlength' => 2
                    )
                ),
                'create_language' => array(
                    'type' => 'submit',
                    'value' => 'Create Language'
                )
            )
        );

        return array(
            'title' => 'Create Language',
            'content' => Html::form()->build($form)
        );
    }

    /**
     * Displays a form for editing a current language.
     *
     * Route: admin/multilanguage/languages/edit/:num
     *
     * @param int $id The id of the language to edit.
     */
    public static function edit($id)
    {
        if(!$lang = Multilanguage::language()->find($id))
            return ERROR_404;

        if(isset($_POST['update_language']) && Html::form()->validate())
        {
            $status = Multilanguage::language()->where('id', '=', $id)->update(array(
                'name' => $_POST['name'],
                'code' => $_POST['code']
            ));

            if($status)
                Message::ok('Language updated successfully.');
            else
                Message::error('Error updating language, please try again.');
        }

        $form[] = array(
            'fields' => array(
                'name' => array(
                    'title' => 'Language Name <em>(Ex: Russian)</em>',
                    'type' => 'text',
                    'validate' => array('required'),
                    'default_value' => $lang->name
                ),
                'code' => array(
                    'title' => '2 Letter Language Code <em>(Ex: ru)<em>',
                    'type' => 'text',
                    'validate' => array('required', 'min:2'),
                    'attributes' => array(
                        'maxlength' => 2
                    ),
                    'default_value' => $lang->code
                ),
                'update_language' => array(
                    'type' => 'submit',
                    'value' => 'Update Language'
                )
            )
        );

        return array(
            'title' => 'Edit Language',
            'content' => Html::form()->build($form)
        );
    }

    /**
     * Deletes a language and redirect to admin/multilanguage/languages/manage
     *
     * Route: admin/multilanguage/languages/delete/:num
     *
     * @param int $id The id of the language to delete.
     */
    public static function delete($id)
    {
        if(Multilanguage::language()->delete($id))
            Message::ok('Language deleted successfully.');
        else
            Message::error('Error deleting language, please try again.');

        Url::redirect('admin/multilanguage/languages/manage');
    }

}
