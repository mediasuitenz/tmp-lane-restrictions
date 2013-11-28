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
            $typeIds = Yii::app()->request->getQuery('type_ids');

            if (empty($type)) {
                ApiUtils::jsonError($app, 400, 'You must specify type param');
            }

            if (empty($typeId) && empty($typeIds)) {
                ApiUtils::jsonError($app, 400,
                    'You must specify either typeId or typeIds param'
                );
            }

            if (false === empty($typeId) && false === empty($typeIds)) {
                ApiUtils::jsonError($app, 400,
                    'You must not specify both typeId or typeIds params'
                );
            }

            $laneRestrictions = OsmLaneRestriction::model()
                ->type($type)
                ->typeId($typeId)
                ->typeIds(json_decode($typeIds, true))
                ->findAll();

            //run the collection through array map to convert objects
            //to well formed data array
            $data = array_map(
                array('LanerestrictionUtils', 'lanerestrictionArray'),
                $laneRestrictions
            );

            ApiUtils::jsonRender($app, $data);

        });
        return $app;
    }

    public function actionIndex() {

        $app = ApiUtils::createApi();

        $app = $this->laneRestrictions($app);

        $app->run();

    }

}
