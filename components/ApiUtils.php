<?php
class ApiUtils {

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

}
