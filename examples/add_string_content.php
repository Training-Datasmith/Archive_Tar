<?php

declare(strict_types=1);

/**
 * Example: Add in-memory string content directly into a TAR archive
 * without writing temporary files to disk.
 */

require_once 'Archive/Tar.php';

$archivePath = sys_get_temp_dir() . '/strings.tar';
$tar = new Archive_Tar($archivePath);

// Add a virtual file with arbitrary string content.
$tar->addString('README.txt', "Hello from Archive_Tar!\n");
$tar->addString('data/config.json', json_encode(['key' => 'value'], JSON_PRETTY_PRINT));

echo "Archive created with in-memory entries at: {$archivePath}" . PHP_EOL;

// Retrieve a single file's content without full extraction.
$content = $tar->extractInString('README.txt');
echo "README.txt content: {$content}";
