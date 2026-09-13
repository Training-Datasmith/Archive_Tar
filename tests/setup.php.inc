<?php
error_reporting(E_ALL);
$repoRoot = dirname(dirname(__FILE__));
$autoload = $repoRoot . '/vendor/autoload.php';
if (is_file($autoload)) {
    require_once $autoload;
}
$pearSrc = $repoRoot . '/vendor/pear/pear-core-minimal/src';
if (is_dir($pearSrc)) {
    set_include_path($pearSrc . PATH_SEPARATOR . get_include_path());
}
// Legacy system PEAR tree (Linux package installs), when present.
$legacyPear = '/usr/local/php/' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION . '/share/pear';
if (is_dir($legacyPear)) {
    set_include_path(get_include_path() . PATH_SEPARATOR . $legacyPear);
}
require_once dirname(__FILE__) . '/phpt_test.php.inc';
$phpunit = new PEAR_PHPTest(true);
require_once dirname(dirname(__FILE__)) . '/Archive/Tar.php';
