<?php

declare(strict_types=1);

/**
 * Example: Create a gzipped TAR archive and then extract it.
 *
 * Requires the Archive_Tar package to be autoloaded (e.g. via PEAR or Composer).
 */

require_once 'Archive/Tar.php';

$archivePath = sys_get_temp_dir() . '/example.tar.gz';

// --- Create ---
$tar = new Archive_Tar($archivePath, 'gz');

// Add individual files or entire directories.
$tar->create([__FILE__]);   // adds this example file

echo "Created: {$archivePath}" . PHP_EOL;

// --- List contents ---
$contents = $tar->listContent();
foreach ($contents as $entry) {
    printf("  %s (%d bytes)\n", $entry['filename'], $entry['size']);
}

// --- Extract ---
$extractDir = sys_get_temp_dir() . '/archive_tar_example';
@mkdir($extractDir, 0755, true);

$success = $tar->extract($extractDir);
if ($success) {
    echo "Extracted to: {$extractDir}" . PHP_EOL;
} else {
    echo "Extraction failed." . PHP_EOL;
}
