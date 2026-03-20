# Archive_Tar Architecture

## Purpose

A PEAR-based PHP library for creating and extracting compressed TAR archives.
Supports gzip (`.tar.gz`), bzip2 (`.tar.bz2`), and LZMA2 (`.tar.xz`) compression
as well as plain (`.tar`) archives.

## Directory Structure

```
Archive/
  Tar.php          — the single public class (Archive_Tar extends PEAR)
docs/
  Archive_Tar.txt  — original PEAR documentation
tests/             — .phpt test cases runnable via pear/PHPUnit
scripts/
  phptar.in        — command-line wrapper script template
```

## Key Design Decisions

- **PEAR base class**: extends `PEAR` for legacy error handling via
  `PEAR::raiseError()`; modern code should check return values rather than
  catching exceptions.
- **Streaming I/O**: reads and writes archives in 512-byte blocks so that very
  large archives can be processed without loading the entire file into memory.
- **Multiple compression drivers**: at construction time the compression codec
  is selected (none / gz / bz2 / lzma2) and the appropriate `fopen`-compatible
  wrapper or PHP extension is used transparently.
- **Security**: path traversal protection prevents extraction of entries whose
  resolved path escapes the target directory.

## Extension Points

- Pass a compression type as the second constructor argument: `'gz'`, `'bz2'`,
  `'lzma2'`, or `null` for uncompressed.
- Use `set_ignore_regexp()` to skip files matching a pattern during extraction.
- Override `_error()` in a subclass to customise error reporting.

## Dependency Flow

```
Consumer → Archive_Tar
               ├── PEAR           (error handling)
               ├── ext/zlib       (gz compression)
               ├── ext/bz2        (bz2 compression)
               └── ext/xz / lzma  (lzma2 compression)
```
