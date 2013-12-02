<?php

class LanerestrictionsController extends Controller {

    /**
     * /lanerestrictions/:id
     * Returns all lanerestrictions for given type and type_id
     */
    public function laneRestriction($app) {

        $app->get('/lanerestrictions/api/lanerestrictions/:id', function ($id) use ($app) {

            $request = $app->request();
            $format = $request->params('format');

            $laneRestriction = OsmLaneRestriction::model()->findByPk($id);

            if (empty($laneRestriction)) {
                ApiUtils::jsonError($app, 400, 'You must specify a valid id');
            }

            $data = LaneRestrictionUtils::lanerestrictionArray($laneRestriction);

            if ($format === 'geojson') {
                ApiUtils::geoJsonRender($app, array($data));
            } else {
                ApiUtils::jsonRender($app, $data);
            }

        });
        return $app;
    }

    /**
     * /lanerestrictions
     * Returns all lanerestrictions for given type and type_id
     */
    public function laneRestrictionsGET($app) {

        $app->get('/lanerestrictions/api/lanerestrictions', function () use ($app) {

            $request = $app->request();

            //setup variables from URL
            $type            = $request->params('type');
            $typeId          = $request->params('type_id');
            $typeIds         = $request->params('type_ids');
            $nodeId          = $request->params('node_id');
            $nodeIds         = $request->params('node_ids');
            $path            = $request->params('path');
            $paths           = $request->params('paths');
            $hasRestrictions = $request->params('has_restrictions');
            $limit           = $request->params('limit');
            $offset          = $request->params('offset');
            $startsAt        = $request->params('starts_at');
            $endsAt          = $request->params('ends_at');
            $latlng          = $request->params('nearby');
            $distance        = $request->params('distance');
            $format          = $request->params('format');

            $startsAt = str_replace(' ', '+', $startsAt);
            $endsAt = str_replace(' ', '+', $endsAt);

            //Perform checks
            $msg = false;
            $msg = ApiUtils::checkTypeParams($msg, $type, $typeId, $typeIds);
            $msg = ApiUtils::checkNodeParams($msg, $nodeId, $nodeIds);
            $msg = ApiUtils::checkPathParams($msg, $path, $paths);
            $msg = ApiUtils::checkDateTimeParams($msg, $startsAt, $endsAt);
            $msg = ApiUtils::checkGeoParams($msg, $latlng, $distance);
            if ($msg) {
                ApiUtils::jsonError($app, 400, $msg);
            }

            $latlng = explode(',', $latlng);
            $lat = empty($latlng[0]) ? null : $latlng[0];
            $lng = empty($latlng[1]) ? null : $latlng[1];


            //Fetch lane restriction objects
            $laneRestrictions = OsmLaneRestriction::model()
                ->type($type)
                ->typeId($typeId)
                ->typeIds(json_decode($typeIds, true))
                ->osmNode($nodeId)
                ->osmNodes(json_decode($nodeIds, true))
                ->osmPath(json_decode($path, true))
                ->osmPaths(json_decode($paths, true))
                ->hasRestrictions($hasRestrictions)
                ->limit($limit)
                ->offset($offset)
                ->startsAt($startsAt)
                ->endsAt($endsAt)
                ->nearby($lat, $lng, $distance)
                ->findAll();

            //run the collection through array map to convert objects
            //to well formed data arrays
            $data = array_map(
                array('LanerestrictionUtils', 'lanerestrictionArray'),
                $laneRestrictions
            );

            if ($format === 'geojson') {
                ApiUtils::geoJsonRender($app, $data);
            } else {
                ApiUtils::jsonRender($app, $data);
            }

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

        Yii::app()->end();
    }

}
