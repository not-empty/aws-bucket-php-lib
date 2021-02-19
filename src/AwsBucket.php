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
     * Check whether file exists in bucket or not.
     * @param string $bucket bucket name
     * @param string $path file path in bucket
     * @return bool
     */
    public function fileExists(
        string $bucket,
        string $path
    ): bool {
        $s3Client = $this->newS3Client();
        
        return $s3Client->doesObjectExist(
            $bucket,
            $path
        );
    }

    /**
     * PutFile into AwsBucket based on a content
     * @param string $bucket bucket name
     * @param string $content content of file
     * @param string $name file name
     * @param string $extension file extension
     * @return string
     */
    public function putFile(
        string $bucket,
        string $content,
        string $name,
        string $extension
    ): string {
        $ulid = $this->newUlid();
        $hash =  $ulid->generate();

        $s3Client = $this->newS3Client();

        $result = $s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $hash . '.' . $name . '.' . $extension,
            'Body' => $content,
            'ACL' => 'public-read',
        ])->toArray();

        return $result['ObjectURL'];
    }

    /**
     * PutFile into AwsBucket based on a content and path
     * @param string $bucket bucket name
     * @param string $content content of file
     * @param string $path file path
     * @param string $extension file extension
     * @param string $acl file acl
     * @return string
     */
    public function putFileOnPath(
        string $bucket,
        string $content,
        string $path,
        string $extension,
        string $acl = null
    ): string {
        $s3Client = $this->newS3Client();

        $s3Config = [
            'Bucket' => $bucket,
            'Key' => $path . '.' . $extension,
            'Body' => $content,
        ];

        if (!empty($acl)) {
            $s3Config['ACL'] = $acl;
        }

        $result = $s3Client->putObject($s3Config)->toArray();

        return $result['ObjectURL'];
    }

    /**
     * PutFile into AwsBucket based on uploaded file
     * @param string $bucket bucket name
     * @param string $origin origin file
     * @param string $name file name
     * @param string $extension file extension
     * @param string $contentType file content type
     * @return string
     */
    public function putFileOrigin(
        string $bucket,
        string $origin,
        string $name,
        string $extension,
        string $contentType = null
    ): string {
        $ulid = $this->newUlid();
        $hash =  $ulid->generate();

        $s3Client = $this->newS3Client();

        $s3Config = [
            'Bucket' => $bucket,
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
     * @param string $bucket bucket name
     * @param string $prefix bucket prefix to access a specific folder
     * @return array
     */
    public function listFiles(
        string $bucket,
        string $prefix = ''
    ): array {
        $s3Client = $this->newS3Client();

        return $s3Client->listObjects([
            'Bucket' => $bucket,
            'Prefix' => $prefix,
        ])->toArray();
    }

    /**
     * Delete files of AwsBucket
     * @param string $bucket bucket name
     * @param string $fileName key of the file
     * @return array
     */
    public function deleteFile(
        string $bucket,
        string $fileName
    ): array {
        $s3Client = $this->newS3Client();

        return $s3Client->deleteObject([
            'Bucket' => $bucket,
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
