<?php

namespace LaravelBox\Commands\Streams;

use GuzzleHttp\Client;
use function GuzzleHttp\Psr7\stream_for;
use LaravelBox\Commands\AbstractCommand;
use LaravelBox\Factories\ApiResponseFactory;

class DownloadStreamCommand extends AbstractCommand
{
    public function __construct(string $token, string $path)
    {
        $this->token = $token;
        $this->fileId = parent::getFileId($path);
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $fileId = $this->fileId;

        $url = 'https://api.box.com/2.0/files/' . $fileId . '/content';
        $options = [
            'stream' => true,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->token,
            ],
        ];

        try {
            $client = new Client();
            $resp = $client->get($url, $options);

            return ApiResponseFactory::build($resp, $resp->getBody());
        } catch (ClientException $e) {
            return ApiResponseFactory::build($e);
        } catch (ServerException $e) {
            return ApiResponseFactory::build($e);
        } catch (TransferException $e) {
            return ApiResponseFactory($e);
        } catch (RequestException $e) {
            return ApiResponseFactory($e);
        }
    }
}
