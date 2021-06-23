<?php
// This is the entrypoint into the app, all paths will be redirected here unless another file exists at the path.

// Composer autoload:
use GuzzleHttp\Psr7\ServerRequest;

require_once "../vendor/autoload.php";

$request = ServerRequest::fromGlobals();

// Classes can be autoloaded from the src/ directory under the "Task" namespace.
$test = new \Task\TestClass($request);
echo $test->helloWorld();
