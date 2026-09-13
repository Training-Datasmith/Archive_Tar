--TEST--
tests basic creation of tarballs
--SKIPIF--
--FILE--
<?php
require_once dirname(__FILE__) . '/setup.php.inc';

$self = basename(__FILE__);
$tar = new Archive_Tar(__DIR__ . '/no_compression.tar');
$tar->addModify(array(__DIR__ . '/' . $self), '', __DIR__);
$content = $tar->listContent();
$discovered_self = false;
foreach ($content as $item) {
  if ($item['filename'] === $self) {
    $discovered_self = true;
    break;
  }
}
$phpunit->assertTrue($discovered_self, 'found the file in the uncompressed archive');
unlink(__DIR__ . '/no_compression.tar');

$tar_gz = new Archive_Tar(__DIR__ . '/gzip.tar.gz', 'gz');
$tar_gz->addModify(array(__DIR__ . '/' . $self), '', __DIR__);
$content = $tar_gz->listContent();
$discovered_self = false;
foreach ($content as $item) {
  if ($item['filename'] === $self) {
    $discovered_self = true;
    break;
  }
}
$phpunit->assertTrue($discovered_self, 'found the file in the gzipped archive');
unlink(__DIR__ . '/gzip.tar.gz');

echo 'tests done';
?>
--CLEAN--
--EXPECT--
tests done
