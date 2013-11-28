<?php

class ApiModule extends CWebModule
{

    public $defaultController = 'incidents';

    public function init()
    {
        $this->setImport(array(
            'lanerestrictions.models.*',
            'lanerestrictions.components.*',
            'application.modules.lanerestrictions.modules.api.models.*',
            'application.modules.lanerestrictions.modules.api.components.*',
        ));

        $module = Yii::app()->getModule('lanerestrictions');

    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action)) {
            return true;
        } else {
            return false;
        }
    }
}
