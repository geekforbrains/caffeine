<?php

class Multilanguage_Admin_ModuleController extends Controller {

    /**
     * Display a table of modules that support multilanguage content. These modules are determined
     * by them responding to the "multilanguage.register" event.
     *
     * Route: admin/multilanguage/modules/manage
     */
    public static function manage()
    {
        $modules = Multilanguage::getRegisteredModules();

        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('Module');
        
        if($modules)
        {
            foreach($modules as $module)
            {
                $row = $table->addRow();
                $row->addCol(Html::a()->get($module, 'admin/multilanguage/modules/manage/' . strtolower($module)));
            }
        }
        else
        {
            $row = $table->addRow();
            $row->addCol('<em>No modules</em>');
        }

        return array(
            'title' => 'Manage Modules',
            'content' => $table->render()
        );
    }

    /**
     * Displays a table of the current modules content.
     *
     * Route: admin/multilanguage/modules/manage/:slug
     *
     * @param string $module The name of the module to get content for.
     */
    public static function manageModule($module)
    {
        $output = array();
        $content = Multilanguage::getModuleContent($module);

        if($content)
        {
            foreach($content as $type => $typeContent)
            {
                $table = Html::table();
                $header = $table->addHeader();
                $header->addCol('Content');

                if($typeContent)
                {
                    foreach($typeContent as $id => $data)
                    {
                        $data = String::truncate($data, 200, '...');
                        $row = $table->addRow();
                        $row->addCol(Html::a()->get($data, 'admin/multilanguage/modules/manage/' . $module . '/' . $type . '/' . $id));
                    }
                }
                else
                    $table->addRow()->addCol('<em>No content.</em>');

                $output[] = array(
                    'title' => ucfirst($type) . ' Content',
                    'content' => $table->render()
                );
            }
        }
        else
        {
            $output = array(
                'title' => 'No Content',
                'content' => '<p>This module has not specified any content to manage.</p>'
            );
        }

        return $output;
    }

    /**
     * Displays a form for creating a new version of the current content type in the available languages.
     *
     * Route: admin/multilanguage/modules/manage/:slug/:slug/:num
     *
     * @param string $module The module the content belongs to
     * @param string $type The type of content within the module we are editing
     * @param int $typeId The id of $type to create a new language version for
     */
    public static function manageContent($module, $type, $typeId)
    {
        $typeInfo = Multilanguage::getContentType($module, $type);

        if(isset($_POST['create_content']) && Html::form()->validate())
        {
            $contentId = Multilanguage::content()->insert(array(
                'language_id' => $_POST['language_id'],
                'type_id' => $typeId,
                'module' => $module,
                'type' => $type
            ));

            if($contentId)
            {
                $status = true;
                $data = $_POST;
                unset($data['language_id']);
                unset($data['create_content']);
                unset($data['form_id']);

                foreach($data as $k => $v)
                {
                    $tmpId = null;

                    switch($typeInfo[$k])
                    {
                        case 'text':
                            $tmpId = Multilanguage::text()->insert(array(
                                'content_id' => $contentId,
                                'name' => $k,
                                'content' => $v
                            ));
                            break;

                        case 'textarea':
                            $tmpId = Multilanguage::textarea()->insert(array(
                                'content_id' => $contentId,
                                'name' => $k,
                                'content' => $v
                            ));
                            break;
                            
                        case 'file':
                            $tmpId = Multilanguage::file()->insert(array(
                                'content_id' => $contentId,
                                'name' => $k,
                                'file_id' => $v
                            ));
                            break;

                        default:
                            Dev::debug('multilanguage', 'ERROR: Attempting to create content of unkown type "' . $k . '"');
                            Message::error('Error creating content, unkown content type encountered.');
                    }

                    if(!$tmpId)
                    {
                        Message::error('Error creating content for the "' . $k . '" type, please try again.');
                        Multilanguage::content()->delete($contentId);
                        $status = false;
                        break;
                    }
                }

                if($status)
                    Message::ok('Content created successfully.');
            }
            else
                Message::error('Unkown error creating content, please try again.');
        }

        $translations = Multilanguage::content()
            ->select('multilanguage_contents.*, multilanguage_languages.name AS language')
            ->leftJoin('multilanguage_languages', 'multilanguage_languages.id', '=', 'multilanguage_contents.language_id')
            ->where('module', '=', $module)
            ->andWhere('type', '=', $type)
            ->andWhere('type_id', '=', $typeId)
            ->all();

        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('Language', array('colspan' => 2));

        if($translations)
        {
            foreach($translations as $t)
            {
                $row = $table->addRow();

                $row->addCol(
                    Html::a()->get(
                        $t->language, 
                        'admin/multilanguage/modules/manage/' . $module . '/' . $type . '/' . $typeId . '/edit/' . $t->id
                    )
                );

                $row->addCol(
                    Html::a()->get(
                        'Delete', 
                        'admin/multilanguage/modules/manage/' . $module . '/' . $type . '/' . $typeId . '/delete/' . $t->id
                    ),
                    array('class' => 'right')
                );
            }
        }
        else
            $table->addRow()->addCol('<em>No languages for this content.</em>', array('colspan' => 2));

        return array(
            array(
                'title' => 'Create Translation',
                //'content' => Html::form()->build($form)
                'content' => self::_buildForm($module, $type, $typeId, $typeInfo)
            ),
            array(
                'title' => 'Translations',
                'content' => $table->render()
            )
        );
    }

    /**
     * Displays a form for editing a translation.
     *
     * Route: admin/multilanguage/modules/manage/:slug/:slug/:num
     *
     * @param string $module The module the content belongs to
     * @param string $type The type of content within the module we are editing
     * @param int $typeId The id of $type to create a new language version for
     * @param int $contentId The if of the translation to edit.
     */
    public static function editContent($module, $type, $typeId, $contentId)
    {
        $typeInfo = Multilanguage::getContentType($module, $type);

        if(isset($_POST['update_content']) && Html::form()->validate())
        {
            $status = true;
            $data = $_POST;
            unset($data['language_id']);
            unset($data['update_content']);
            unset($data['form_id']);

            foreach($data as $k => $v)
            {
                $tmpId = null;

                switch($typeInfo[$k])
                {
                    case 'text':
                        $tmpId = Multilanguage::text()->where('content_id', '=', $contentId)->update(array(
                            'name' => $k,
                            'content' => $v
                        ));
                        break;

                    case 'textarea':
                        $tmpId = Multilanguage::textarea()->where('content_id', '=', $contentId)->update(array(
                            'name' => $k,
                            'content' => $v
                        ));
                        break;
                        
                    case 'file':
                        $tmpId = Multilanguage::file()->where('content_id', '=', $contentId)->update(array(
                            'name' => $k,
                            'file_id' => $v
                        ));
                        break;

                    default:
                        Dev::debug('multilanguage', 'ERROR: Attempting to update content of unkown type "' . $k . '"');
                        Message::error('Error updating content, unkown content type encountered.');
                }

                if($tmpId < 0) // 0 == nothing changed in update, but update was successful
                {
                    Message::error('Error updating content for the "' . $k . '" type, please try again.');
                    $status = false;
                    break;
                }
            }

            if($status)
                Message::ok('Content updated successfully.');
        }

        $data = array(); // Data to be injected into the form

        $content = Multilanguage::content()->find($contentId);

        $data['language_id'] = $content->language_id;

        $texts = Multilanguage::text()->where('content_id', '=', $contentId)->all();

        if($texts)
            foreach($texts as $t)
                $data[$t->name] = $t->content;

        $textareas = Multilanguage::textarea()->where('content_id', '=', $contentId)->all();

        if($textareas)
            foreach($textareas as $t)
                $data[$t->name] = $t->content;

        return array(
            'title' => 'Edit Translation',
            'content' => self::_buildForm($module, $type, $typeId, $typeInfo, $data)
        );
    }

    /**
     * Deletes the contents of a translation and redirects back to the manage modules page.
     */
    public static function deleteContent($module, $type, $typeId, $contentId)
    {
        Multilanguage::text()->where('content_id', '=', $contentId)->delete();
        Multilanguage::textarea()->where('content_id', '=', $contentId)->delete();

        // TODO Add file support to delete files then clear db record

        if(Multilanguage::content()->delete($contentId))
            Message::ok('Translation deleted successfully.');
        else
            Message::error('Error deleting translation, please try again.');

        Url::redirect('admin/multilanguage/modules/manage/' . $module . '/' . $type . '/' . $typeId);
    }

    /**
     * Builds the contents form based on parameters returned from event.
     *
     * @param array $data An array of data to populate the form fields with when editing, key is field name, value is field value
     */
    private static function _buildForm($module, $type, $typeId, $typeInfo, $data = array())
    {
        $langs = Multilanguage::language()->orderBy('name')->all();
        $sortedLangs = array('' => 'Choose One');

        foreach($langs as $lang)
            $sortedLangs[$lang->id] = $lang->name;

        $form[] = array(
            'fields' => array(
                'language_id' => array(
                    'title' => 'Language',
                    'type' => 'select',
                    'options' => $sortedLangs,
                    'validate' => array('required'),
                    'selected' => (isset($data['language_id'])) ? $data['language_id'] : 0
                )
            )
        );

        foreach($typeInfo as $colName => $colType)
        {
            $form[0]['fields'][$colName] = array(
                'title' => ucfirst($colName),
                'type' => $colType,
                'validate' => array('required'),
                'default_value' => isset($data[$colName]) ? $data[$colName] : ''
            );
        }

        // Determine if we are creating or editing
        if($data)
        {
            $form[0]['fields']['update_content'] = array(
                'type' => 'submit',
                'value' => 'Update Content'
            );
        }
        else
        {
            $form[0]['fields']['create_content'] = array(
                'type' => 'submit',
                'value' => 'Create Content'
            );
        }

        return Html::form()->build($form);
    }

}
