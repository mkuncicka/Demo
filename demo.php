#!/usr/bin/env php
<?php
require_once 'classes.php';

$application = new \Demo\Application\Application();
$application->handleCommand($argv);

?>