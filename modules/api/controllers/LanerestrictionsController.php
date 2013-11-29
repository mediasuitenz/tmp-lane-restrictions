<?php

class LanerestrictionsController extends Controller {

    /**
     * /lanerestrictions/:id
     * Returns all lanerestrictions for given type and type_id
     */
    public function laneRestriction($app) {

        $app->get('/lanerestrictions/api/lanerestrictions/:id', function ($id) use ($app) {


            $laneRestriction = OsmLaneRestriction::model()->findByPk($id);

            if (empty($laneRestriction)) {
                ApiUtils::jsonError($app, 400, 'You must specify a valid id');
            }

            $data = LaneRestrictionUtils::lanerestrictionArray($laneRestriction);

            ApiUtils::jsonRender($app, $data);

        });
        return $app;
    }

    /**
     * /lanerestrictions
     * Returns all lanerestrictions for given type and type_id
     */
    public function laneRestrictionsGET($app) {

        $app->get('/lanerestrictions/api/lanerestrictions', function () use ($app) {

            $type = Yii::app()->request->getQuery('type');
            $typeId = Yii::app()->request->getQuery('type_id');
            $typeIds = Yii::app()->request->getQuery('type_ids');
            $nodeId = Yii::app()->request->getQuery('node_id');
            $nodeIds = Yii::app()->request->getQuery('node_ids');
            $path = Yii::app()->request->getQuery('path');
            $paths = Yii::app()->request->getQuery('paths');
            $hasRestrictions = Yii::app()->request->getQuery('has_restrictions');

            if (false === empty($typeId) && false === empty($typeIds)) {
                ApiUtils::jsonError($app, 400,
                    'You may specify either type_id or type_ids, not both'
                );
            }

            if (false === empty($nodeId) && false === empty($nodeIds)) {
                ApiUtils::jsonError($app, 400,
                    'You may specify either node_id or node_ids, not both'
                );
            }

            if (false === empty($path) && false === empty($paths)) {
                ApiUtils::jsonError($app, 400,
                    'You may specify either path or paths, not both'
                );
            }

            if ((false === empty($typeId) || false === empty($typeIds))
                && empty($type)
            ) {
                ApiUtils::jsonError($app, 400,
                    'You must specify type param with type_id or type_ids param'
                );
            }

            if (false === empty($typeIds)) {

                $typeIds = json_decode($typeIds, true);
                if (false === is_array($typeIds)) {
                    $message = 'type_ids must be an array eg. ' .
                        'type_ids=[123,124,125]';
                    ApiUtils::jsonError($app, 400, $message);
                }

            }

            if (false === empty($nodeIds)) {

                $nodeIds = json_decode($nodeIds, true);

                if (false === is_array($nodeIds)) {
                    $message = 'node_ids must be an array eg. ' .
                        'node_ids=[12312312,12412312,12512312]';
                    ApiUtils::jsonError($app, 400, $message);
                }

            }

            if (false === empty($path)) {

                $path = json_decode($path, true);

                if (false === is_array($path)) {
                    $message = 'path must be an array eg. ' .
                        'path=[12312312,12341233]';
                    ApiUtils::jsonError($app, 400, $message);
                }

                if (false === isset($path[0]) || false === isset($path[1])) {
                    $message = 'path array must have 2 values eg. ' .
                        'path=[12312312,12341233]';
                    ApiUtils::jsonError($app, 400, $message);
                }

            }

            if (false === empty($paths)) {

                $paths = json_decode($paths, true);

                if (false === is_array($paths)) {
                    $message = 'paths must be an array eg. ' .
                        'paths=[[12312312,12341233],[12412312,12341233]]';
                    ApiUtils::jsonError($app, 400, $message);
                }

                if (false === is_array($paths[0])) {
                    $message = 'paths must be an array of arrays eg. ' .
                        'paths=[[12312312,12341233],[12412312,12341233]]';
                    ApiUtils::jsonError($app, 400, $message);
                }

                if (false === isset($paths[0][0]) || false === isset($paths[0][1])) {
                    $message = 'paths must be an array of integer arrays eg. ' .
                        'paths=[[12312312,12341233],[12412312,12341233]]';
                    ApiUtils::jsonError($app, 400, $message);
                }

            }

            $laneRestrictions = OsmLaneRestriction::model()
                ->type($type)
                ->typeId($typeId)
                ->typeIds($typeIds)
                ->osmNode($nodeId)
                ->osmNodes($nodeIds)
                ->osmPath($path)
                ->osmPaths($paths)
                ->hasRestrictions($hasRestrictions)
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

    /**
     * /lanerestrictions
     * Creates a new lane restriction
     */
    public function laneRestrictionsPOST($app) {

        $app->post('/lanerestrictions/api/lanerestrictions', function () use ($app) {

            $laneRestriction = new OsmLaneRestriction;

            $body = json_decode($app->request()->getBody(), true);

            $laneRestriction->attributes = $body;

            if(false === $laneRestriction->save()) {
                ApiUtils::jsonError(
                    $app,
                    400,
                    $laneRestriction->getErrors()
                );
            }

            $data = LaneRestrictionUtils::lanerestrictionArray($laneRestriction);

            ApiUtils::jsonRender($app, $data);

        });
        return $app;
    }

    public function actionIndex() {

        $app = ApiUtils::createApi();

        $app = $this->laneRestriction($app);
        $app = $this->laneRestrictionsGET($app);
        $app = $this->laneRestrictionsPOST($app);

        $app->run();

    }

}
