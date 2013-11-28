<?php

class LanerestrictionsController extends Controller {

    /**
     * /lanerestrictions
     * Returns all lanerestrictions for given type and type_id
     */
    public function laneRestrictions($app) {

        $app->get('/lanerestrictions/api/lanerestrictions', function () use ($app) {

            $type = Yii::app()->request->getQuery('type');
            $typeId = Yii::app()->request->getQuery('type_id');

            $laneRestrictions = OsmLaneRestriction::model()
                ->type($type)
                ->typeId($typeId)
                ->findAll();

            ApiUtils::jsonRender($app, $laneRestrictions);

        });
        return $app;
    }

    public function actionIndex() {

        $app = ApiUtils::createApi();

        $app = $this->laneRestrictions($app);

        $app->run();

    }

}
