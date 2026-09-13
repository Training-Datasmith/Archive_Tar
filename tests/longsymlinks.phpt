--TEST--
test trimming of characters in long symbolic link targets
--SKIPIF--
--FILE--
<?php
require_once __DIR__ . '/setup.php.inc';
$dirname = __DIR__ . '/longsymlink/';
$longfilename = $dirname . str_repeat("b", 120) . " ";
$symlinkfilename = $dirname . "a";
@mkdir($dirname, 0777, true);
@touch($longfilename);
@symlink($longfilename, $symlinkfilename);
$tar = new Archive_Tar(__DIR__ . '/testlongsymlink.tar');
$tar->addModify(array($longfilename, $symlinkfilename), '', __DIR__);
$tar = new Archive_Tar(__DIR__ . '/testlongsymlink.tar');
$files = $tar->listContent();
$file = end($files);
$lastChar = $file['link'][strlen($file['link']) - 1];
$phpunit->assertEquals(' ', $lastChar, 'should contain space as last character');
echo 'tests done';
?>
--CLEAN--
<?php
$dirname = __DIR__;
unlink($dirname . '/testlongsymlink.tar');
system("rm -rf " . escapeshellarg($dirname . '/longsymlink'));
?>
--EXPECT--
tests done
