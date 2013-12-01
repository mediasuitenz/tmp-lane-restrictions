<?php

class LanerestrictionsModule extends CWebModule
{

    public $localTimeZone;
    public $serverTimeZone;
    public $apiDateTimeOutputFormat;

    public function init()
    {
        $this->setImport(array(
            'lanerestrictions.models.*',
            'lanerestrictions.components.*',
        ));

        //load submodules
        $this->setModules(array('api'));
    }

    public function beforeControllerAction($controller, $action)
    {
        if(parent::beforeControllerAction($controller, $action))
        {
            return true;
        }
        else
            return false;
    }
}
