<?php

namespace App\Services\Telegram;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

class WebAppInitData implements Arrayable, Jsonable
{
    protected string $queryId;
    protected Carbon $authDate;

    protected WebAppUser $user;

    public function __construct(string $queryId, Carbon $authDate, WebAppUser $user)
    {
        $this->queryId = $queryId;
        $this->authDate = $authDate;
        $this->user = $user;
    }

    public function getQueryId(): string
    {
        return $this->queryId;
    }

    public function setQueryId(string $queryId): void
    {
        $this->queryId = $queryId;
    }

    public function getAuthDate(): Carbon
    {
        return $this->authDate;
    }

    public function setAuthDate(Carbon $authDate): void
    {
        $this->authDate = $authDate;
    }

    public function getUser(): WebAppUser
    {
        return $this->user;
    }

    public function setUser(WebAppUser $user): void
    {
        $this->user = $user;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['query_id'],
            Carbon::createFromTimestamp($data['auth_date']),
            WebAppUser::fromJson($data['user'])
        );
    }

    public function toArray(): array
    {
        return [
            'query_id' => $this->queryId,
            'auth_date' => $this->authDate->timestamp,
            'user' => $this->user->toArray()
        ];
    }

    public function toJson($options = 0): string
    {
        return json_encode($this->toArray());
    }
}
