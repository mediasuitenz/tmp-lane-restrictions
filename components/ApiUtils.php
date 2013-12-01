<?php
class ApiUtils {

    //messages
    const TYPE_ID_EMPTY = 'You may specify either type_id or type_ids, not both';
    const TYPE_IDS_WITH_EMPTY_TYPE = 'You must specify type param with type_id or type_ids param';
    const TYPE_IDS_NOT_ARRAY = 'type_ids must be an array eg. type_ids=[123,124,125]';
    const NODE_IDS_BOTH_NOT_EMPTY = 'You may specify either node_id or node_ids, not both';
    const NODE_IDS_NOT_ARRAY = 'node_ids must be an array eg. node_ids=[12312312,12412312,12512312]';
    const PATHS_BOTH_NOT_EMPTY = 'You may specify either path or paths, not both';
    const PATH_NOT_ARRAY = 'path must be an array eg. path=[12312312,12341233]';
    const PATH_ARRAY_CONTENTS_NOT_VALID = 'path array must have 2 values eg. path=[12312312,12341233]';
    const PATHS_NOT_ARRAY = 'paths must be an array eg. paths=[[12312312,12341233],[12412312,12341233]]';
    const PATHS_INVALID_ARRAY = 'paths must be an array of arrays eg. paths=[[12312312,12341233],[12412312,12341233]]';
    const PATHS_INVALID_CHILD_ARRAY = 'paths must be an array of integer arrays eg. paths=[[12312312,12341233],[12412312,12341233]]';
    const STARTS_AT_INVALID = 'starts_at must be in ISO8601 format';
    const ENDS_AT_INVALID = 'ends_at must be in ISO8601 format';
    const ENDS_AT_REQUIRED = 'because starts_at is specified, you must also specify ends_at';
    const STARTS_AT_REQUIRED = 'because ends_at is specified, you must also specify starts_at';

    /**
     * Creates a Slim api app
     *
     * @return \Slim\Slim - app
     */
    public static function createApi() {

        Application::disableWebLogging();

        $app = new \Slim\Slim(array(
            'debug' => !(getenv('ENVIRONMENT') === 'PRODUCTION'),
            'mode' => getenv('ENVIRONMENT'),
        ));

        $app->add(new \Slim\Middleware\ContentTypes());

        return $app;
    }

    /**
     * Sets headers to json, takes data and renders it as json
     *
     * @param  \Slim\Slim   $app  - Slim app
     * @param  array        $data - array of data to be rendered as json
     */
    public static function jsonRender($app, array $data) {
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $response->body(json_encode($data));
    }

    public static function jsonError($app, $status, $message) {
        $response = $app->response();
        $response->header('Content-Type', 'application/json');
        $app->halt($status, json_encode(array('errors' => $message)));
    }

    public static function checkTypeParams($msg, $type, $typeId, $typeIds) {
        if (false === empty($typeId) && false === empty($typeIds)) {
            $msg = self::TYPE_ID_EMPTY;
        }
        if ((false === empty($typeId) || false === empty($typeIds))
            && empty($type)
        ) {
            $msg = self::TYPE_IDS_WITH_EMPTY_TYPE;
        }
        if (false === empty($typeIds)) {
            $typeIds = json_decode($typeIds, true);
            if (false === is_array($typeIds)) {
                $msg = self::TYPE_IDS_NOT_ARRAY;
            }
        }
        return $msg;
    }

    public static function checkNodeParams($msg, $nodeId, $nodeIds) {
        if (false === empty($nodeId) && false === empty($nodeIds)) {
            $msg = self::NODE_IDS_BOTH_NOT_EMPTY;
        }
        if (false === empty($nodeIds)) {
            $nodeIds = json_decode($nodeIds, true);
            if (false === is_array($nodeIds)) {
                $msg = self::NODE_IDS_NOT_ARRAY;
            }
        }
        return $msg;
    }

    public static function checkPathParams($msg, $path, $paths) {
        if (false === empty($path) && false === empty($paths)) {
            $msg = self::PATHS_BOTH_NOT_EMPTY;
        }
        if (false === empty($path)) {
            $path = json_decode($path, true);
            if (false === is_array($path)) {
                $msg = self::PATH_NOT_ARRAY;
            }
            if (false === isset($path[0]) || false === isset($path[1])) {
                $msg = self::PATH_ARRAY_CONTENTS_NOT_VALID;
            }
        }
        if (false === empty($paths)) {
            $paths = json_decode($paths, true);
            if (false === is_array($paths)) {
                $msg = self::PATHS_NOT_ARRAY;
            }
            if (false === is_array($paths[0])) {
                $msg = self::PATHS_INVALID_ARRAY;
            }
            if (false === isset($paths[0][0]) || false === isset($paths[0][1])) {
                $msg = self::PATHS_INVALID_CHILD_ARRAY;
            }
        }
        return $msg;
    }

    public static function checkDateTimeParams($msg, $startsAt = null, $endsAt = null) {

        if (empty($startsAt) && false === empty($endsAt)) {
            $msg = self::ENDS_AT_REQUIRED;
        }

        if (empty($endsAt) && false === empty($startsAt)) {
            $msg = self::STARTS_AT_REQUIRED;
        }

        if (false === empty($startsAt)) {
            if (false === DateTime::createFromFormat(DateTime::ISO8601, $startsAt)) {
                $msg = self::STARTS_AT_INVALID;
            }
        }
        if (false === empty($endsAt)) {
            if (false === DateTime::createFromFormat(DateTime::ISO8601, $endsAt)) {
                $msg = self::ENDS_AT_INVALID;
            }
        }
        return $msg;
    }

}
