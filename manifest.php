<?php declare(strict_types=1);


$src_glob = 'Z:/iiif/bcec/*/';
// $src_glob = 'Z:/iiif/bcec/bcec195105/';
$dst_dir = 'C:/code/ddr_iiif/';
$bcec_data = json_decode(file_get_contents(__DIR__ . '/bcec_toc.json'), true);

/*
foreach($bcec_data as $key => $manifest) {
    if (!isset($bcectoc_data[$key])) continue;
    $bcec_data[$key]["structures"] = [
        "id" => $bcec_data[$key]["id"] . "toc",
        "type" => "Range",
        "label" => ["fr" => ["Sommaire"]],
        "items" => $bcectoc_data[$key]
    ];
}

$json = json_encode($bcec_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents(__DIR__ . '/bcec-new.json', $json);
*/


// bcec collection
function collection()
{
    global $bcec_data, $dst_dir;
    $dst_file = "$dst_dir/bcec.json";
    $collection = $bcec_data[""];
    $collection["@context"] = "http://iiif.io/api/presentation/3/context.json";
    $collection["type"] = "Collection";
    $collection["behavior"] = ["multi-part"];
    $collection["items"] = [];
    foreach($bcec_data as $key => $manifest) {
        if (!$key) continue;
        $collection["items"][] = [
            "id" => "https://oeuvres.unige.ch/testiiif/$key.json",
            "label" => $manifest['label'],
            "type" => "Manifest",
        ];
    }
    $collection_json = json_encode($collection, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (json_last_error() != JSON_ERROR_NONE) {
        die("Error encoding JSON: " . json_last_error_msg());
    }
    file_put_contents($dst_file, $collection_json);
}
collection();

// put a manifest in each folder
foreach (glob($src_glob) as $src_dir) {
    if (!is_dir($src_dir)) continue;
    $name = basename($src_dir);
    if (!isset($bcec_data[$name])) continue;
    $dst_file = "$dst_dir/$name.json";
    if (file_exists($dst_file)) continue;
    echo "$src_dir\n";
    $bcec_data[$name]["@context"] = "http://iiif.io/api/presentation/3/context.json";
    $bcec_data[$name]["type"] = "Manifest";
    $bcec_data[$name]["behavior"] = ["paged"];
    $glob = glob("$src_dir/{$name}_*.jpg");
    $bcec_data[$name]["items"] = [];

    $page_canvasid = [];
    foreach($glob as $jpg_file) {
        $jpg_name = pathinfo($jpg_file, PATHINFO_FILENAME);
        $jpg_filename = basename($jpg_file);
        $canvas_data = [];
        $canvas_data['id'] = $bcec_data[$name]['id'] . $jpg_name;
        $canvas_data['type'] = "Canvas";
        preg_match('/_(\d+)\./', $jpg_filename, $matches);
        $jpg_no = intval($matches[1]);
        if ($jpg_no < $bcec_data[$name]['page1']) {
            $label = "n. p.";
        }
        else {
            $n = (1 + $jpg_no - $bcec_data[$name]['page1']);
            $label = "p. " . $n;
            $page_canvasid[$n] = $canvas_data['id'];
        }
        $canvas_data['label'] = ["none" => [$label]];
        $jpg_info = getimagesize($jpg_file);
        $canvas_data["width"] = $jpg_info[0];
        $canvas_data["height"] = $jpg_info[1];
        $canvas_data["items"] = [];
        $annotation_data = [
            "id" => $bcec_data[$name]['id'].$jpg_filename,
            "type" => "AnnotationPage",
            "items" => [
                [
                    "id" => $bcec_data[$name]['iiif'].$jpg_filename,
                    "target" => $canvas_data['id'],
                    "type" => "Annotation",
                    "motivation" => "painting",
                    "body" => [
                        "id" => $bcec_data[$name]['iiif'].$jpg_filename.'/full/full/0/default.jpg',
                        "type" => "Image",
                        "format" => "image/jpeg",
                        "width" => $canvas_data["width"],
                        "height" => $canvas_data["height"],
                        "service" => [
                            [
                                "@id" => $bcec_data[$name]['iiif'].$jpg_filename,
                                "@type" => "ImageService2",
                                "profile" => "http://iiif.io/api/image/2/level2.json",
                            ]
                        ],
                    ]
                ]
            ],
        ];
        $canvas_data["items"][] = $annotation_data;
        $bcec_data[$name]["items"][] = $canvas_data;
    }

    // populate toc with canvas id
    if (
        isset($bcec_data[$name]["structures"]) 
        && count($bcec_data[$name]["structures"])
    ) {
        foreach($bcec_data[$name]["structures"] as &$range) {
        // get range by reference '&'
        tocpages($range, $page_canvasid);
        }
    }

    unset($bcec_data[$name]['page1']);
    unset($bcec_data[$name]['iiif']);
    $manifest_json = json_encode($bcec_data[$name], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (json_last_error() != JSON_ERROR_NONE) {
        die("Error encoding JSON: " . json_last_error_msg());
    }
    file_put_contents($dst_file, $manifest_json);
}

function tocpages(&$range, &$page_canvasid)
{
    if (!isset($range['items'])) {
        // what ?
    }
    // items are toc items
    if (count($range['items'])) {
        // get items by reference '&'
        foreach($range['items'] as &$child) {
            tocpages($child, $page_canvasid);
        }
    }
    // sould be a leave to populate with pages
    else {
        $label = current(current($range['label']));
        if (!preg_match('/\[p. (\d+)\-?(\d+)?/u', $label, $matches)) {
            // no pages go 
            return;
        }
        $from = intval($matches[1]);
        if (isset($matches[2]) && $matches[2]) {
            $to = intval($matches[2]);
        }
        else {
            $to = $from;
        }
        for ($page = $from; $page < $to + 1; $page++) {
            if (!isset($page_canvasid[$page])) continue;
            $range['items'][] = [
                "id" => $page_canvasid[$page],
                "type" => "Canvas",
            ];
        }
    }
}
/*

if (!isset($argv[1])) {
    die("Pas de dossier passé");
}

$glob = glob($argv[1]);
if (count($glob) > 1) {
    echo("=== " . $argv[1] . " ===\n");
}
$dir_last = null;
foreach ($glob as $src_file) {
    $dir = basename(dirname($src_file));
    if ($dir != $dir_last) {
        echo "$dir\n";
        $dir_last = $dir;
    }
    $src_file = realpath($src_file);
    $basename = basename($src_file);
    preg_match('/_(\d\d\d\d)(.jp[g2])$/', $basename, $matches);
    $dst_file = dirname($src_file) . "/" . $dir . '_' . $matches[1] . $matches[2];
    rename($src_file, $dst_file);
}
*/