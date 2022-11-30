<?php

namespace LaravelBox\Commands\Folders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use LaravelBox\Factories\ApiResponseFactory;

class GetFolderInformationCommand extends AbstractFolderCommand
{
    public function __construct(string $token, string $path)
    {
        $this->token = $token;
        $this->folderId = parent::getFolderId(dirname($path));
    }

    public function execute()
    {
        $folderId = $this->fileId;
        $url = 'https://api.box.com/2.0/folders/' . $folderId;
        $options = [
        'headers' => [
            'Authorization' => 'Bearer ' . $this->token,
        ],
        ];

        try {
            $client = new Client();
            $req = $client->get($url, $options);

            return ApiResponseFactory::build($req);
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
