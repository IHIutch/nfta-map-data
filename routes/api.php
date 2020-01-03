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

    $data = [];
    foreach ($routes as $route) {
        $route_stops = ORM::for_table('trips')
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
        foreach ($route_stops as $stop) {
            array_push($temp_stops, [
                'trip_id' => $stop->trip_id,
                'stop_id' => $stop->stop_id,
                'stop_sequence' => $stop->stop_sequence
            ]);
        };

        $temp_route = [];
        $temp_route['route_long_name'] = $route->route_long_name;
        $temp_route['route_id'] = $route->route_id;
        $temp_route['stops'] = $temp_stops;
        array_push($data, $temp_route);
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

    $data = [];
    foreach ($routes as $route) {
        $route_path = ORM::for_table('trips')
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

        $temp_path = [];
        foreach ($route_path as $path) {
            array_push($temp_path, [
                'trip_id' => $path->trip_id,
                'shape_id' => $path->shape_id,
                'shape_pt_sequence' => $path->shape_pt_sequence,
                'shape_pt_lat' => $path->shape_pt_lat,
                'shape_pt_lon' => $path->shape_pt_lon
            ]);
        };

        $temp_route = [];
        $temp_route['route_long_name'] = $route->route_long_name;
        $temp_route['route_id'] = $route->route_id;
        $temp_route['shapes'] = $temp_path;
        array_push($data, $temp_route);
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

    $route_path = ORM::for_table('trips')
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

    $temp_path = [];
    foreach ($route_path as $path) {
        array_push($temp_path, [
            'trip_id' => $path->trip_id,
            'shape_id' => $path->shape_id,
            'shape_pt_sequence' => $path->shape_pt_sequence,
            'shape_pt_lat' => $path->shape_pt_lat,
            'shape_pt_lon' => $path->shape_pt_lon
        ]);
    };

    $data = [];
    $data['route_name'] = $route_info->route_long_name;
    $data['route_color'] = $route_info->route_color;
    $data['route_path'] = $temp_path;

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

$app->get('/api/stop/{stop_id}', function ($request, $response, $args) {
    $stop_id = $args['stop_id'];

    $stop_info = ORM::for_table('stops')
        ->select_many('stop_code', 'stop_name', 'stop_lat', 'stop_lon')
        ->where('stop_id', $stop_id)
        ->find_one();

    $stop_times = ORM::for_table('stop_times')
        ->select_many('stop_times.trip_id', 'stop_times.arrival_time', 'stop_times.departure_time', 'stop_times.stop_sequence', 'trips.route_id')
        ->join('trips', [
            'trips.trip_id',
            '=',
            'stop_times.trip_id'
        ])
        ->where('stop_id', $stop_id)
        ->order_by_asc('trip_id')
        ->order_by_asc('stop_sequence')
        ->find_many();

    $temp_times = [];
    foreach ($stop_times as $times) {
        array_push($temp_times, [
            'trip_id' => $times->trip_id,
            'arrival_time' => $times->arrival_time,
            'departure_time' => $times->departure_time,
            'stop_sequence' => $times->stop_sequence,
            'route_id' => $times->route_id,
        ]);
    };

    $data = [];
    $data['stop_code'] = $stop_info->stop_code;
    $data['stop_name'] = $stop_info->stop_name;
    $data['stop_lat'] = $stop_info->stop_lat;
    $data['stop_lon'] = $stop_info->stop_lon;
    $data['stop_times'] = $temp_times;

    $data = json_encode($data);

    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});

$app->get('/api/all-stops', function ($request, $response) {
    $stop_info = ORM::for_table('stops')
        ->select_many('stop_id', 'stop_code', 'stop_name', 'stop_lat', 'stop_lon')
        ->find_many();

    $temp_stops = [];
    foreach ($stop_info as $stop) {
        array_push($temp_stops, [
            'stop_id' => $stop->stop_id,
            'stop_code' => $stop->stop_code,
            'stop_name' => $stop->stop_name,
            'stop_lat' => $stop->stop_lat,
            'stop_lon' => $stop->stop_lon,
        ]);
    };

    $data = json_encode($temp_stops);

    $response->getBody()->write($data);
    return $response->withHeader('Content-Type', 'application/json');
});
