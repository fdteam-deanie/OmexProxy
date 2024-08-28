<?php

namespace Proxy\ProxyHostingManagementTool\Http\Controllers;

use App\Models\Continent;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CountryController
{
    public function index()
    {
        $continents = Continent::with(['countries' => function(HasMany $hasMany) {
            $hasMany->withCount('ipProxies')->withCount('countryIps');
        }])->get();

        return response()->json($continents);
    }
}
