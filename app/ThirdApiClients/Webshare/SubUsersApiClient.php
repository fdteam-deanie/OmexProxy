<?php

namespace App\ThirdApiClients\Webshare;

class SubUsersApiClient extends BaseWebshareApiClient
{
    public function getSubUsersList(int $page): array
    {
        $response = $this->client->get(
            "{$this->baseUrl}/subuser?page=$page",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

//    public function getSubusersInChunks(callable $callback, int $chunkSize = 50): void
//    {
//        $page = 1;
//        $data = $this->getSubusersList($page, $chunkSize);
//        $count = $data['count'] ?? 0;
//        $subusers = $data['results'];
//        $callback($subusers);
//        while ($count > 0) {
//            $page++;
//            $data = $this->getSubusersList($page, $chunkSize);
//            $count = $data['count'] ?? 0;
//            $subusers = $data['results'];
//            $callback($subusers);
//        }
//    }

    public function getSubUserById(string $id): array
    {
        $response = $this->client->get(
            "{$this->baseUrl}/subuser/$id/",
            $this->getHeaders()
        );

        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

    public function createSubUser(array $data): array
    {
        $response = $this->client->post(
            "{$this->baseUrl}/subuser/", [
                    ...$this->getHeaders(),
                    "json" => $data
                ]
        );

        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }

    public function deleteSubUser(string $id): void
    {
        $this->client->delete(
            "{$this->baseUrl}/subuser/$id/",
            $this->getHeaders()
        );
    }

    public function updateSubUser(string $id, array $data): array
    {
        $response = $this->client->patch(
            "{$this->baseUrl}/subuser/$id/", [
                    ...$this->getHeaders(),
                    "json" => $data
                ]
        );

        $data = json_decode($response->getBody()->getContents(), true);
        return $data;
    }
}
