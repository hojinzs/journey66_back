<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\ProviderToken;

class ProviderController extends Controller
{

    public function show(Request $request)
    {
        return $this->getToken($request);
    }

    //
    public function update(Request $request)
    {
        $token = $this->getToken($request);
        $driver = $request->header('Provider');

        $newToken = $this->setNewProviderToken($driver,$token);

        return response()->json($newToken);
    }

    /**
     * @param Request $request
     * @return ProviderToken
     */
    private function getToken(Request $request)
    {
        return $request->user()->getProvider($request->header('Provider'));
    }

    private function setNewProviderToken(string $provider,ProviderToken $token)
    {
        $client = new Client();
        $url = config('services.'.$provider.'.refreash_url');
        $query = [
            'client_id' => config('services.'.$provider.'.client_id'),
            'client_secret' => config('services.'.$provider.'.client_secret'),
            'grant_type' => 'refresh_token',
            'refresh_token' => $token->refresh_token,
        ];

        $response = $client->request('POST',$url,['query' => $query]);
        $response->getStatusCode();
        $contents = $response->getBody();

        $new_token = json_decode($contents);
        $token->updateToken($new_token);
        return $token;
    }


}
