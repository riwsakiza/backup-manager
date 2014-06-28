<?php

namespace spec\BigName\BackupManager\Compressors;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GzipCompressorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('BigName\BackupManager\Compressors\GzipCompressor');
    }

    function it_should_handle_filetype_strings_case_insensitively()
    {
        foreach (['gzip', 'GZIP', 'GZip'] as $type) {
            $this->handles($type)->shouldBe(true);
        }

        foreach ([null, 'foo'] as $type) {
            $this->handles($type)->shouldBe(false);
        }
    }

    function it_should_generate_valid_compression_commands()
    {
        $this->getCompressCommandLine('foo')->shouldBe("gzip 'foo'");
        $this->getCompressCommandLine('../foo')->shouldBe("gzip '../foo'");
        $this->getCompressCommandLine('../foo.sql')->shouldBe("gzip '../foo.sql'");
    }

    function it_should_generate_valid_decompression_commands()
    {
        $this->getDecompressCommandLine('foo')->shouldBe("gunzip 'foo'");
        $this->getDecompressCommandLine('../foo.gz')->shouldBe("gunzip '../foo.gz'");
        $this->getDecompressCommandLine('../foo.sql.gz')->shouldBe("gunzip '../foo.sql.gz'");
    }

    function it_can_generate_compressed_paths_from_filename()
    {
        $this->getCompressedPath('a')->shouldBe('a.gz');
        $this->getCompressedPath('/a')->shouldBe('/a.gz');
        $this->getCompressedPath('/a.sql')->shouldBe('/a.sql.gz');
    }

    function it_can_generate_decompressed_paths_from_filename()
    {
        $this->getDecompressedPath('a.gz')->shouldBe('a');
        $this->getDecompressedPath('/a.gz')->shouldBe('/a');
        $this->getDecompressedPath('/a.sql.gz')->shouldBe('/a.sql');
    }
}
