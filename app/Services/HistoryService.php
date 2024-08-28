<?php

namespace App\Services;

use App\Http\Requests\History\GetProxiesRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class HistoryService
{
    protected User $user;

    protected $query;

    protected int $pagination;


    public function __construct(?User $user)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;

        $this->query = $this->user->proxies();
    }

    public function preparedCollection(GetProxiesRequest $request): self
    {

        $this->setFilters($request->get('filters', []));

        $this->setPagination($request->get('count', 25));

        $this->query->orderBy('paid_at', 'desc');

        return $this;
    }

    private function setFilters(array $filters): void
    {
        if(isset($filters['ip'])) {
            $this->query->where('ip', 'LIKE', '%'.$filters['ip'].'%');
        }
        if(isset($filters['is_online'])) {
            $this->query->where('is_online', $filters['is_online']);
        }
        if(isset($filters['is_paid'])) {
            $this->query->wherePivot('is_paid', $filters['is_paid']);
        }
        if(isset($filters['country'])) {
            $this->query->whereHas('country', function($query) use ($filters) {
                $query->where('name', 'LIKE', '%'.$filters['country'].'%');
            });
        }
        if(isset($filters['state'])) {
            $this->query->whereHas('state', function($query) use ($filters) {
                $query->where('name', 'LIKE', '%'.$filters['state'].'%');
            });
        }
        if(isset($filters['city'])) {
            $this->query->whereHas('city', function($query) use ($filters) {
                $query->where('name', 'LIKE', '%'.$filters['city'].'%');
            });
        }
        if(isset($filters['zip'])) {
            $this->query->whereHas('zip', function($query) use ($filters) {
                $query->where('name', 'LIKE', '%'.$filters['zip'].'%');
            });
        }
        if(isset($filters['isp'])) {
            $this->query->whereHas('isp', function($query) use ($filters) {
                $query->where('name', 'LIKE', '%'.$filters['isp'].'%');
            });
        }
        if(isset($filters['type'])) {
            $this->query->whereHas('type', function($query) use ($filters) {
                $query->where('id', $filters['type']);
            });
        }
        if(isset($filters['price'])) {
            $this->query->where('price', '<=', $filters['price']);
        }
    }

    /**
     * @param int $pagination
     */
    public function setPagination(int $pagination): void
    {
        $this->pagination = $pagination;
    }

    public function getCollection()
    {
        return $this->query->get();
    }

    public function getPaginated()
    {
        return $this->query->paginate($this->pagination);
    }

}
