--TEST--
test files that happen to contain the endblock
--SKIPIF--
--FILE--
<?php
require_once __DIR__ . '/setup.php.inc';
$tar = new Archive_Tar(__DIR__ . '/testblock.tar.gz');
$tar->addModify(__DIR__ . '/testblock1', '', __DIR__);
$tar->addModify(__DIR__ . '/testblock2', '', __DIR__);
$tar = new Archive_Tar(__DIR__ . '/testblock.tar.gz');
$tar->listContent();
$phpunit->assertNoErrors('after');
echo 'tests done';
?>
--CLEAN--
<?php
@unlink(dirname(__FILE__) . '/testblock.tar.gz');
?>
--EXPECT--
tests done
