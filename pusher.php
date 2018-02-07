<?php

    require 'vendor/autoload.php';

    $options = array(
        'cluster'   => 'us2',
        'encrypted' => true
    );
    $pusher  = new Pusher\Pusher(
        '7c379d1619b8599e7b23', '0e77d5930633660f2630', '470873', $options
    );

    $data['name']    = 'Jhon';
    $data['message'] = 'hello world';
    var_dump($pusher->trigger('viewlog-visualizations', 'new-visualization', $data));

    print_r($data);


