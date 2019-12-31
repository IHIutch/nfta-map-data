<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$app->get('/', function ($request, $response) {

    $response->getBody()->write("hello");
    return $response;
});

$app->get('/api/all-routes/stops', function ($request, $response) {
    $routes = ORM::for_table('routes')
        ->distinct()->select('route_id')
        ->select_many('route_long_name')
        ->order_by_asc('route_id')
        ->find_many();

    foreach ($routes as $route) {
        $temp_trips = ORM::for_table('trips')
            ->select_many('trips.trip_id', 'stop_times.stop_id', 'stop_times.stop_sequence')
            ->join('stop_times', [
                'stop_times.trip_id',
                '=',
                'trips.trip_id'
            ])
            ->where([
                'trips.route_id' => $route->route_id,
                'trips.service_id' => 1,
            ])
            ->order_by_asc('trip_id')
            ->order_by_asc('stop_sequence')
            ->find_many();

        $temp_stops = [];
        foreach ($temp_trips as $trip) {
            array_push($temp_stops, [
                'trip_id' => $trip->trip_id,
                'stop_id' => $trip->stop_id,
                'stop_sequence' => $trip->stop_sequence
            ]);
        };

        $data = [];
        $data['route_long_name'] = $route->route_long_name;
        $data['route_id'] = $route->route_id;
        $data['stops'] = $temp_stops;
    };

    $data = json_encode($data);

    $response->getBody()->write(($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/all-routes/paths', function ($request, $response) {
    $routes = ORM::for_table('routes')
        ->distinct()->select('route_id')
        ->select_many('route_long_name')
        ->order_by_asc('route_id')
        ->find_many();

    foreach ($routes as $route) {
        $temp_trips = ORM::for_table('trips')
            ->select_many('trips.trip_id', 'trips.shape_id', 'shapes.shape_pt_lat', 'shapes.shape_pt_lon', 'shapes.shape_pt_sequence')
            ->join('shapes', [
                'shapes.shape_id',
                '=',
                'trips.shape_id'
            ])
            ->where([
                'trips.route_id' => $route->route_id,
                'trips.service_id' => 1,
            ])
            ->order_by_asc('trip_id')
            ->order_by_asc('shape_pt_sequence')
            ->find_many();

        $temp_shapes = [];
        foreach ($temp_trips as $trip) {
            array_push($temp_shapes, [
                'trip_id' => $trip->trip_id,
                'shape_id' => $trip->shape_id,
                'shape_pt_sequence' => $trip->shape_pt_sequence,
                'shape_pt_lat' => $trip->shape_pt_lat,
                'shape_pt_lon' => $trip->shape_pt_lon
            ]);
        };

        $data = [];
        $data['route_long_name'] = $route->route_long_name;
        $data['route_id'] = $route->route_id;
        $data['shapes'] = $temp_shapes;
    };

    $data = json_encode($data);

    $response->getBody()->write(($data));
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/route/{route_id}/path', function ($request, $response, $args) {
    $route_id = $args['route_id'];

    $route_info = ORM::for_table('routes')
        ->select_many('route_long_name', 'route_color')
        ->where('route_id', $route_id)
        ->find_one();

    $route_shape = ORM::for_table('trips')
        ->select_many('trips.trip_id', 'trips.shape_id', 'shapes.shape_pt_lat', 'shapes.shape_pt_lon', 'shapes.shape_pt_sequence')
        ->join('shapes', [
            'shapes.shape_id',
            '=',
            'trips.shape_id'
        ])
        ->where([
            'trips.route_id' => $route_id,
            'trips.service_id' => 1
        ])
        ->order_by_asc('trip_id')
        ->order_by_asc('shape_pt_sequence')
        ->find_many();

    $temp_shape = [];
    foreach ($route_shape as $shape) {
        array_push($temp_shape, [
            'trip_id' => $shape->trip_id,
            'shape_id' => $shape->shape_id,
            'shape_pt_sequence' => $shape->shape_pt_sequence,
            'shape_pt_lat' => $shape->shape_pt_lat,
            'shape_pt_lon' => $shape->shape_pt_lon
        ]);
    };

    $data = [];
    $data['route_name'] = $route_info->route_long_name;
    $data['route_color'] = $route_info->route_color;
    $data['route_shape'] = $temp_shape;

    $data = json_encode($data);

    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/route/{route_id}/stops', function ($request, $response, $args) {
    $route_id = $args['route_id'];

    $route_info = ORM::for_table('routes')
        ->select_many('route_long_name', 'route_color')
        ->where('route_id', $route_id)
        ->find_one();

    $route_stops = ORM::for_table('trips')
        ->select_many('trips.trip_id', 'stop_times.stop_id', 'stop_times.stop_sequence')
        ->join('stop_times', [
            'stop_times.trip_id',
            '=',
            'trips.trip_id'
        ])
        ->where([
            'trips.route_id' => $route_id,
            'trips.service_id' => 1
        ])
        ->order_by_asc('trip_id')
        ->order_by_asc('stop_sequence')
        ->find_many();

    $temp_stops = [];
    foreach ($route_stops as $stop) {
        array_push($temp_stops, [
            'trip_id' => $stop->trip_id,
            'stop_id' => $stop->stop_id,
            'stop_sequence' => $stop->stop_sequence
        ]);
    };

    $data = [];
    $data['route_name'] = $route_info->route_long_name;
    $data['route_color'] = $route_info->route_color;
    $data['route_stops'] = $temp_stops;

    $data = json_encode($data);

    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

// $app->get('/api/stop/{stop_id}/?', function ($request, $response) {


//     $response->getBody()->write($data);
//     return $response->withHeader('Content-Type', 'application/json');
// });

// $app->get('/api/all-stops/?', function ($request, $response) {


//     $response->getBody()->write($data);
//     return $response->withHeader('Content-Type', 'application/json');
// });
