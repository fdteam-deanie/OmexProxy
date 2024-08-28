<?php

namespace Database\Seeders;

use App\Models\Proxy;
use App\Models\ProxyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProxyTypesSeeder extends Seeder
{
    private string $proxyTypesTable;
    private string $proxiesTable;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->initData();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table($this->proxyTypesTable)->truncate();
        DB::table($this->proxyTypesTable)->insert([
            [ 'id' => ProxyType::PROXY_TYPE_STATIC_ID, 'name' => ProxyType::PROXY_TYPE_STATIC_NAME, 'active' => true ],
            [ 'id' => ProxyType::PROXY_TYPE_ROTATION_ID, 'name' => ProxyType::PROXY_TYPE_ROTATION_NAME, 'active' => true ],
        ]);

        $proxies = DB::table($this->proxiesTable)->get();
        foreach ($proxies as $proxy) {
            $newProxyTypeId = ($proxy->is_static == 1) ? 1 : 2;
            DB::table($this->proxiesTable)->where('id', $proxy->id)->update(['type_id' => $newProxyTypeId]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

    }

    private function initData(): void
    {
        $proxyTypesOrm = new ProxyType();
        $proxiesOrm = new Proxy();
        $this->proxyTypesTable = $proxyTypesOrm->getTable();
        $this->proxiesTable = $proxiesOrm->getTable();
    }
}
