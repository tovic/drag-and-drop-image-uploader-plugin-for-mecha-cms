<?php

// Begin variable(s)
define('DD_UPLOAD_DESTINATION', $config->url . '/' . $config->manager->slug . '/plugin/' . File::B(__DIR__) . '/upload');
define('DD_UPLOAD_TOKEN', Guardian::token());
define('DD_UPLOAD_FOLDER', ASSET . DS . 'object');
// End variable(s)

Config::merge('DASHBOARD.languages.MTE', array(
    'plugin_dd_upload' => Mecha::A($speak->plugin_dd_upload)
));

Weapon::add('shell_after', function() {
    echo Asset::stylesheet(__DIR__ . DS . 'assets' . DS . 'shell' . DS . 'button.css');
}, 20);

Weapon::add('SHIPMENT_REGION_BOTTOM', function() {
    echo Asset::javascript(__DIR__ . DS . 'assets' . DS . 'sword' . DS . 'button.js');
}, 20);

Route::post(DD_UPLOAD_DESTINATION, function() use($config, $speak) {
    Guardian::checkToken(DD_UPLOAD_TOKEN);
    HTTP::mime('application/json', $config->charset);
    $results = array();
    foreach($_FILES as $file) {
        $path = DD_UPLOAD_FOLDER . DS . Text::parse($file['name'], '->safe_file_name');
        $results[] = File::url($path);
        File::upload($file, File::path(Request::post('dd_path', DD_UPLOAD_FOLDER)));
    }
    Notify::clear(); // hide error messages
    echo json_encode($results);
    exit;
});