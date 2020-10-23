<?php

namespace AwsBucket;

use Aws\S3\S3Client;
use Ulid\Ulid;

class AwsBucket
{
    public $s3Config;

    /**
     * Constructor
     * @param array $s3Config
     */
    public function __construct(array $s3Config)
    {
        $this->s3Config = $s3Config;
    }

    /**
     * PutFile into AwsBucket based on a content
     * @param string $content content of file
     * @param string $name file name
     * @param string $extension file extension
     * @return string
     */
    public function putFile($content, $name, $extension)
    {
        $ulid = $this->newUlid();
        $hash =  $ulid->generate();

        $s3Client = $this->newS3Client();

        $result = $s3Client->putObject([
            'Bucket' => $this->s3Config['bucket'],
            'Key' => $hash . '.' . $name . '.' . $extension,
            'Body' => $content,
            'ACL' => 'public-read',
        ])->toArray();

        return $result['ObjectURL'];
    }

    /**
     * PutFile into AwsBucket based on uploaded file
     * @param string $oringin origin file
     * @param string $name file name
     * @param string $extension file extension
     * @return string
     */
    public function putFileOrigin($origin, $name, $extension, $contentType = null)
    {
        $ulid = $this->newUlid();
        $hash =  $ulid->generate();

        $s3Client = $this->newS3Client();

        $s3Config = [
            'Bucket' => $this->s3Config['bucket'],
            'Key' => $hash . '-' . $name . '.' . $extension,
            'SourceFile' => $origin,
            'ACL' => 'public-read',
        ];

        if ($contentType) {
            $s3Config['ContentType'] = $contentType;
        }

        $result = $s3Client->putObject($s3Config)->toArray();

        return $result['ObjectURL'];
    }

    /**
     * List all file of AwsBucket
     * @return array
     */
    public function listFiles()
    {
        $s3Client = $this->newS3Client();

        return $s3Client->listObjects([
            'Bucket' => $this->s3Config['bucket'],
        ])->toArray();
    }

    /**
     * Delete files of AwsBucket
     * @param string $fileName key of the file
     * @return array
     */
    public function deleteFile(string $fileName)
    {
        $s3Client = $this->newS3Client();

        return $s3Client->deleteObject([
            'Bucket' => $this->s3Config['bucket'],
            'Key' => $fileName,
        ])->toArray();
    }

    /**
     * @codeCoverageIgnore
     * method newS3Client
     * create S3 Client instance
     * @return \Aws\S3\S3Client
     */
    public function newS3Client()
    {
        return new S3Client($this->s3Config);
    }

    /**
     * @codeCoverageIgnore
     * method newS3Client
     * create Ulid instance
     * @return \Ulid\Ulid
     */
    public function newUlid()
    {
        return new Ulid();
    }
}
