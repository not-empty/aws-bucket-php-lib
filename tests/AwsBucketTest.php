<?php

namespace AwsBucket;

use Aws\S3\S3Client;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ulid\Ulid;

class AwsBucketTest extends TestCase
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
     * @covers AwsBucket\AwsBucket::fileExists
     */
    public function testFileExists()
    {
        $bucket = 'test';
        $path = 'folder/sample.txt';

        $s3ClientMock = Mockery::mock(S3Client::class);
        $s3ClientMock->shouldReceive('doesObjectExist')
            ->once()
            ->with($bucket, $path)
            ->andReturn(true)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->withNoArgs()
            ->once()
            ->andReturn($s3ClientMock)
            ->getMock();

        $result = $awsBucketPartialMock->fileExists(
            $bucket,
            $path
        );
        $this->assertEquals(true, $result);
    }

    /**
     * @covers AwsBucket\AwsBucket::putFile
     */
    public function testPutFile()
    {
        $bucket = 'test';
        $content = 'this is your file content';
        $name = 'sample';
        $extension = 'txt';
        $hash = '01E7110R431SMD2V7WGMSVHDVK';
        $putObject = [
            'Bucket' => $bucket,
            'Key' => $hash . '.' . $name . '.' . $extension,
            'Body' => $content,
            'ACL' => 'public-read',
        ];
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $ulidMock = Mockery::mock(Ulid::class);
        $ulidMock->shouldReceive('generate')
            ->withNoArgs()
            ->once()
            ->andReturn($hash);

        $s3ClientMock = Mockery::mock(S3Client::class);
        $s3ClientMock->shouldReceive('putObject')
            ->once()
            ->with($putObject)
            ->andReturnSelf()
            ->shouldReceive('toArray')
            ->withNoArgs()
            ->once()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->withNoArgs()
            ->once()
            ->andReturn($s3ClientMock)
            ->shouldReceive('newUlid')
            ->withNoArgs()
            ->once()
            ->andReturn($ulidMock)
            ->getMock();

        $file = $awsBucketPartialMock->putFile(
            $bucket,
            $content,
            $name,
            $extension
        );
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers AwsBucket\AwsBucket::putFileOnPath
     */
    public function testPutFileOnPath()
    {
        $bucket = 'test';
        $content = 'this is your file content';
        $path = 'folder_a/folder_b';
        $extension = 'txt';
        $acl = 'public-read';
        $putObject = [
            'Bucket' => $bucket,
            'Key' => $path . '.' . $extension,
            'Body' => $content,
            'ACL' => 'public-read',
        ];
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $s3ClientMock = Mockery::mock(S3Client::class);
        $s3ClientMock->shouldReceive('putObject')
            ->once()
            ->with($putObject)
            ->andReturnSelf()
            ->shouldReceive('toArray')
            ->withNoArgs()
            ->once()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->withNoArgs()
            ->once()
            ->andReturn($s3ClientMock)
            ->getMock();

        $file = $awsBucketPartialMock->putFileOnPath(
            $bucket,
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
        $bucket = 'test';
        $origin = 'test.txt';
        $content = 'this is your file content';
        $name = 'sample';
        $extension = 'txt';
        $hash = '01E7110R431SMD2V7WGMSVHDVK';
        $contentType = 'text';
        $putObject = [
            'Bucket' => $bucket,
            'Key' => $hash . '-' . $name . '.' . $extension,
            'SourceFile' => $origin,
            'ACL' => 'public-read',
            'ContentType' => $contentType,
        ];
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $ulidMock = Mockery::mock(Ulid::class);
        $ulidMock->shouldReceive('generate')
            ->withNoArgs()
            ->once()
            ->andReturn($hash);

        $s3ClientMock = Mockery::mock(S3Client::class);
        $s3ClientMock->shouldReceive('putObject')
            ->once()
            ->with($putObject)
            ->andReturnSelf()
            ->shouldReceive('toArray')
            ->withNoArgs()
            ->once()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->withNoArgs()
            ->once()
            ->andReturn($s3ClientMock)
            ->shouldReceive('newUlid')
            ->withNoArgs()
            ->once()
            ->andReturn($ulidMock)
            ->getMock();

        $file = $awsBucketPartialMock->putFileOrigin(
            $bucket,
            $origin,
            $name,
            $extension,
            $contentType
        );
        $this->assertEquals($file, 'https://url/file.ext');
    }

    /**
     * @covers AwsBucket\AwsBucket::listFiles
     */
    public function testListFiles()
    {
        $bucket = 'test';
        $listObjects = [
            'Bucket' => $bucket,
        ];
        $result = [];

        $s3ClientMock = Mockery::mock(S3Client::class);
        $s3ClientMock->shouldReceive('listObjects')
            ->with($listObjects)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('toArray')
            ->withNoArgs()
            ->once()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->withNoArgs()
            ->once()
            ->andReturn($s3ClientMock);

        $list = $awsBucketPartialMock->listFiles($bucket);
        $this->assertEquals($list, []);
    }

    /**
     * @covers AwsBucket\AwsBucket::deleteFile
     */
    public function testDeleteFile()
    {
        $bucket = 'test';
        $fileName = 'file.ext';
        $deleteObject = [
            'Bucket' => $bucket,
            'Key' => $fileName,
        ];
        $result = [
            'ObjectURL' => 'https://url/file.ext',
        ];

        $s3ClientMock = Mockery::mock(S3Client::class);
        $s3ClientMock->shouldReceive('deleteObject')
            ->with($deleteObject)
            ->once()
            ->andReturnSelf()
            ->shouldReceive('toArray')
            ->withNoArgs()
            ->once()
            ->andReturn($result)
            ->getMock();

        $awsBucketPartialMock = Mockery::mock(AwsBucket::class)
            ->makePartial();

        $awsBucketPartialMock->shouldReceive('newS3Client')
            ->withNoArgs()
            ->once()
            ->andReturn($s3ClientMock);

        $delete = $awsBucketPartialMock->deleteFile(
            $bucket,
            $fileName
        );
        $this->assertEquals($result, $delete);
    }

    protected function tearDown(): void
    {
        Mockery::close();
    }
}
