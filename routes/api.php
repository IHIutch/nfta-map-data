<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$app->get('/', function ($request, $response) {
    $params = $request->getQueryParams();

    $query = "SELECT shapes.shape_pt_lat, shapes.shape_pt_lon, shapes.shape_id, routes.route_long_name, routes.route_color
    FROM shapes 
    INNER JOIN trips 
    ON shapes.shape_id = trips.shape_id 
     INNER JOIN routes
        ON trips.route_id = routes.route_id 
    WHERE trips.route_id = " . $params['route'] .
        " AND trips.shape_id = " . $params['trip'];

    $result = $this->get('db')->query($query);
    $result->execute();
    $result = $result->fetchAll(PDO::FETCH_ASSOC);

    $route_path = [];
    foreach ($result as $res) {
        array_push($route_path, [
            $res['shape_pt_lat'], $res['shape_pt_lon']
        ]);
    };

    $data = [];
    $data['route_path'] = $route_path;
    $data['route_name'] = $result[0]['route_long_name'];
    $data['route_color'] = $result[0]['route_color'];

    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/all-routes/?', function ($request, $response) {


    $response->getBody()->write(json_encode($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/route/{route_id}', function ($request, $response, $args) {
    $route_id = $args['route_id'];

    $route_info = ORM::for_table('routes')
        ->select_many('route_long_name', 'route_color')
        ->where('route_id', $route_id)
        ->find_one();

    $route_path = ORM::for_table('shapes')
        ->select_many('shapes.shape_pt_lat', 'shapes.shape_pt_lon')
        ->join('trips', [
            'shapes.shape_id',
            '=',
            'trips.shape_id'
        ])
        ->where([
            'trips.route_id' => $route_id,
            'trips.shape_id' => 1
        ])
        ->order_by_asc('trips.trip_id')
        ->find_many();

    $path = [];
    foreach ($route_path as $res) {
        array_push($path, [
            $res->shape_pt_lat, $res->shape_pt_lon
        ]);
    };

    $data = [];
    $data['route_path'] = $path;
    $data['route_name'] = $route_info->route_long_name;
    $data['route_color'] = $route_info->route_color;

    $data = json_encode($data);

    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

// $app->get('/api/route/{route_id}/stops/?', function ($request, $response) {


//     $response->getBody()->write($data);
//     return $response->withHeader('Content-Type', 'application/json');
// });

// $app->get('/api/stop/{stop_id}/?', function ($request, $response) {


//     $response->getBody()->write($data);
//     return $response->withHeader('Content-Type', 'application/json');
// });

// $app->get('/api/all-stops/?', function ($request, $response) {


//     $response->getBody()->write($data);
//     return $response->withHeader('Content-Type', 'application/json');
// });
