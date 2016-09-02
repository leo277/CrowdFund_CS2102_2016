<?php
//  Use a combination of dirname(__FILE__) and subsequent calls to itself until you reach to the home of your '/index.php'. Then, attach this variable (that contains the path) to your included files. 
/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/

defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));
defined("INCLUDES_PATH")
    or define("INCLUDES_PATH", realpath(dirname(__FILE__) . '/includes'));
/*
    config file should be included in every page of the project, or those need to access these settings
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/
 
// $config = array(
//     "urls" => array(
//         "baseUrl" => "http://example.com"
//     ),
//     "paths" => array(
//         "resources" => "/path/to/resources",
//         "images" => $_SERVER["DOCUMENT_ROOT"] . "/images"
//         )
// );





/*
    Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);
 
?>