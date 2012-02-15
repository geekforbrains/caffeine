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

        if($_POST && Html::form()->validate())
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
                unset($data['submit']);
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
                    'validate' => array('required')
                )
            )
        );

        foreach($typeInfo as $name => $type)
        {
            $form[0]['fields'][$name] = array(
                'title' => ucfirst($name),
                'type' => $type,
                'validate' => array('required')
            );
        }

        $form[0]['fields']['submit'] = array(
            'type' => 'submit',
            'value' => 'Create Content'
        );

        // TODO Make table of language conversion
        $table = Html::table();
        $header = $table->addHeader();
        $header->addCol('Language', array('colspan' => 2));

        // TODO Add rows of languages for this content
        $row = $table->addRow();
        $row->addCol('<em>No languages for this content.</em>', array('colspan' => 2));

        return array(
            array(
                'title' => 'Create Content',
                'content' => Html::form()->build($form)
            ),
            array(
                'title' => 'Conversions',
                'content' => $table->render()
            )
        );
    }

}
