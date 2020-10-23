<?php

namespace AwsBucketTest;

use \Mockery;
use Aws\S3\S3Client;
use AwsBucket\AwsBucket;
use PHPUnit\Framework\TestCase;
use Ulid\Ulid;

class AwsBucketHelperTest extends TestCase
{
    /**
     * @covers AwsBucket\AwsBucket::__construct
     */
    public function testAwsBucketHelperCanBeInstanciated()
    {
        $configs = [];
        $awsBucket = new AwsBucket($configs);
        $this->assertInstanceOf(AwsBucket::class, $awsBucket);
    }

    /**
     * @covers AwsBucket\AwsBucket::putFile
     */
    public function testPutFile()
    {
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $ulidMock = Mockery::mock(Ulid::class);
        $ulidMock->shouldReceive('generate')
            ->once()
            ->andReturn('01E7110R431SMD2V7WGMSVHDVK');

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('putObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $awsBucketPartialMock->shouldReceive('newUlid')
            ->once()
            ->andReturn($ulidMock);

        $content = 'this is your file content';
        $name = 'sample';
        $extension = 'txt';

        $file = $awsBucketPartialMock->putFile($content, $name, $extension);
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers AwsBucket\AwsBucket::putFileOnPath
     */
    public function testPutFileOnPath()
    {
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('putObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $content = 'this is your file content';
        $path = 'data/sample';
        $extension = 'txt';

        $file = $awsBucketPartialMock->putFileOnPath($content, $path, $extension);
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers AwsBucket\AwsBucket::putFileOnPath
     */
    public function testPutFileOnPathWithAcl()
    {
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('putObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $content = 'this is your file content';
        $path = 'data/sample';
        $extension = 'txt';
        $acl = 'public-read';

        $file = $awsBucketPartialMock->putFileOnPath(
            $content,
            $path,
            $extension,
            $acl
        );
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers AwsBucket\AwsBucket::putFileOrigin
     */
    public function testPutFileOrigin()
    {
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $ulidMock = Mockery::mock(Ulid::class);
        $ulidMock->shouldReceive('generate')
            ->once()
            ->andReturn('01E7110R431SMD2V7WGMSVHDVK');

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('putObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $awsBucketPartialMock->shouldReceive('newUlid')
            ->once()
            ->andReturn($ulidMock);

        $origin = 'sample.txt';
        $name = 'sample';
        $extension = 'txt';
        $contentType = 'text/plain';

        $file = $awsBucketPartialMock->putFileOrigin($origin, $name, $extension, $contentType);
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers AwsBucket\AwsBucket::listFiles
     */
    public function testListFiles()
    {
        $result = [];

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('listObjects')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $list = $awsBucketPartialMock->listFiles();
        $this->assertEquals($list, []);
    }

    /**
     * @covers AwsBucket\AwsBucket::deleteFile
     */
    public function testDeleteFile()
    {
        $result = 'https://url/file.ext';

        $sqsClientMock = Mockery::mock(S3Client::class);
        $sqsClientMock->shouldReceive('deleteObject')
            ->once()
            ->withAnyArgs()
            ->andReturnSelf();

        $sqsClientMock->shouldReceive('toArray')
            ->once()
            ->withAnyArgs()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->once()
            ->andReturn($sqsClientMock);

        $deleted = $awsBucketPartialMock->deleteFile('file.ext');
        $this->assertEquals($deleted, $result);
    }

    public function tearDown()
    {
        Mockery::close();
    }
}
