<?php

namespace Vortechron\Essentials\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Twilio\Rest\Client;
use Authy\AuthyApi;

class PhoneVerificationController extends Controller
{
    protected $authy;
    protected $sid;
    protected $authToken;
    protected $twilioFrom;

    protected $fileName = 'phone_number_verifications';

    public function __construct()
    {
        // Initialize the Authy API and the Twilio Client
        $this->authy = new AuthyApi(config('laravel-essentials.twilio')['AUTHY_API_KEY']);
        // Twilio credentials
        $this->sid = config('laravel-essentials.twilio')['TWILIO_ACCOUNT_SID'];
        $this->authToken = config('laravel-essentials.twilio')['TWILIO_AUTH_TOKEN'];
        $this->twilioFrom = config('laravel-essentials.twilio')['TWILIO_PHONE'];
    }

    public function check(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|min:1',
            'number' => 'required|string|min:5',
        ]);

        $colls = $this->getCollections();
        $verified = $colls->get(user()->id) ?: [];
        $toBeCheck = $this->serializePhoneNumber($request);

        return [
            'status' => in_array($toBeCheck, $verified)
        ];
    }

    public function send(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|min:1',
            'number' => 'required|string|min:5',
        ]);

        $response = $this->authy->phoneVerificationStart($request->number, $request->code   , 'sms');

        return [
            'status' => $response->ok()
        ];
    }

    public function code(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|string|min:1',
            'number' => 'required|string|min:5',
        ]);

        $response = $this->authy->phoneVerificationCheck($request->number, $request->code, $request->verification_code);

        if ($response->ok()) {
            $colls = $this->getCollections();
            $verified = $colls->get(user()->id) ?: [];
            $toBeCheck = $this->serializePhoneNumber($request);

            if (! in_array($toBeCheck, $verified)) {
                $verified[] = $toBeCheck;
                $colls->put(user()->id, $verified);
                $collName = $this->fileName;
                
                \Storage::put($collName, json_encode($colls->toArray()));
            }
        }

        return [
            'status' => $response->ok()
        ];
    }

    protected function getCollections()
    {
        $collName = $this->fileName;
        return \Storage::exists($collName) ? collect(json_decode(\Storage::get($collName))) : collect([]);
    }

    protected function serializePhoneNumber($request)
    {
        return $request->code . $request->number;
    }
}

