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
                $row = $table->addRow(); // possibly attributes in this call?
                $row->addCol(Html::a()->get($module, 'multilanguage/modules/edit/' . strtolower($module)));
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
        $content = Multilanguage::getModuleContent($module);

        $table = Html::table();
    }

}
