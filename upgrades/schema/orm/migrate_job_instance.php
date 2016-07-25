<?php

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

require_once __DIR__ . '/../../../app/bootstrap.php.cache';
require_once __DIR__ . '/../../../app/AppKernel.php';
require_once __DIR__ . '/../../SchemaHelper.php';
require_once __DIR__ . '/../../UpgradeHelper.php';
require_once __DIR__ . '/JobInstanceParametersMigration.php';

// TO BE LAUNCHED AFTER HAVING UPDATED THE DATABASE SCHEMA

/**********************************************
 * USAGE
 * migrate_job_instance.php [--env environment]
 * with:
 *      environment: your application environment (dev, prod...), default is dev
 **********************************************/

$migration = new JobInstanceParametersMigration(new ConsoleOutput(), new ArgvInput($argv));

$migration->updateJobInstanceParameters();
