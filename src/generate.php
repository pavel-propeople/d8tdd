#!/usr/bin/env php
<?php

$vendor_dir = dirname(__DIR__, 3);
require_once $vendor_dir . '/autoload.php';

use Symfony\Component\Console\Application;
use MPNDEV\D8TDD\Commands\GenerateKernelTestCommand;

$application = new Application();
$application->add(new GenerateKernelTestCommand());
$application->run();

