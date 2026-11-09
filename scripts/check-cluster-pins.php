<?php

/**
 * Fail if any cluster package is not installed at the expected index SHA and root alias.
 */

$expected = array(
    'nikic/php-parser' => array(
        'alias' => '5.7.0',
        'reference' => '50f0d9c9d0e3cff1163c959c50aaaaa4a7115f08',
    ),
);

$demoWrongSha = false;
if (isset($argv) && is_array($argv)) {
    foreach ($argv as $arg) {
        if ($arg === '--demo-wrong-sha') {
            $demoWrongSha = true;
            break;
        }
    }
}

$installedPhp = dirname(__FILE__) . '/../vendor/composer/installed.php';
if (!is_readable($installedPhp)) {
    fwrite(STDERR, "check-cluster-pins: vendor/composer/installed.php not found; run composer update first.\n");
    exit(1);
}

$installed = require $installedPhp;
$versions = isset($installed['versions']) ? $installed['versions'] : array();

/**
 * @param array<string, array{alias: string, reference: string}> $map
 * @param array $versions
 * @return bool
 */
function check_cluster_pins_run($map, $versions)
{
    $failed = false;
    foreach ($map as $name => $want) {
        if (!isset($versions[$name])) {
            fwrite(STDERR, sprintf("check-cluster-pins: %s is not installed.\n", $name));
            $failed = true;
            continue;
        }

        $pkg = $versions[$name];
        $aliases = isset($pkg['aliases']) ? $pkg['aliases'] : array();
        if (!in_array($want['alias'], $aliases, true)) {
            fwrite(STDERR, sprintf(
                "check-cluster-pins: %s alias mismatch (expected %s in aliases %s).\n",
                $name,
                $want['alias'],
                json_encode($aliases)
            ));
            $failed = true;
        }

        $reference = isset($pkg['reference']) ? $pkg['reference'] : null;
        if (!is_string($reference) || $reference === '') {
            fwrite(STDERR, sprintf("check-cluster-pins: %s has no reference.\n", $name));
            $failed = true;
            continue;
        }

        if (strcasecmp($want['reference'], $reference) !== 0) {
            fwrite(STDERR, sprintf(
                "check-cluster-pins: %s reference mismatch (expected %s, got %s).\n",
                $name,
                $want['reference'],
                $reference
            ));
            $failed = true;
        }
    }

    return !$failed;
}

if ($demoWrongSha) {
    $wrongMap = $expected;
    $wrongMap['nikic/php-parser']['reference'] = '0000000000000000000000000000000000000000';
    if (check_cluster_pins_run($wrongMap, $versions)) {
        fwrite(STDERR, "check-cluster-pins: --demo-wrong-sha did not fail as expected.\n");
        exit(1);
    }
    fwrite(STDOUT, "check-cluster-pins: --demo-wrong-sha failed on wrong SHA as expected.\n");
}

if (!check_cluster_pins_run($expected, $versions)) {
    exit(1);
}

fwrite(STDOUT, "check-cluster-pins: all cluster packages match expected SHAs and aliases.\n");
exit(0);
