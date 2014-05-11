<?php

ini_set('display_errors', true);
error_reporting(E_ALL);
set_time_limit(0);

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

use Symfony\Component\Process\ProcessBuilder;

$processes = [];

// php server
$processes[] =
    ProcessBuilder::create([
        PHP_BINARY,
        '-S', '0.0.0.0:8888',
        '-t', 'src/ResqueBoard/webroot/',
    ])
    ->setTimeout(null)
    ->getProcess()
;

// cube collector
$processes[] =
    ProcessBuilder::create([
        'node', 'bin/collector.js',
    ])
    ->setTimeout(null)
    ->setWorkingDirectory('node_modules/cube/')
    ->getProcess()
;

// cube evaluator
$processes[] =
    ProcessBuilder::create([
        'node', 'bin/evaluator.js',
    ])
    ->setTimeout(null)
    ->setWorkingDirectory('node_modules/cube/')
    ->getProcess()
;

while (count($processes) > 0) {
    pcntl_signal_dispatch();

    foreach ($processes as $i => $process) {
        if (!$process->isStarted()) {
            $process->start();
            continue;
        }

        fwrite(STDOUT, $process->getIncrementalOutput());
        fwrite(STDERR, $process->getIncrementalErrorOutput());

        if (!$process->isRunning()) {
            unset($processes[$i]);
        }
    }

    usleep(0.200 * 1000000);
}
