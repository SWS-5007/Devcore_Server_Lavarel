<?php

namespace App\Lib\Validators\Rules;

use Illuminate\Contracts\Validation\Rule;
use Twilio\Rest\Client as Twilio;

class TwilioPhoneValidator implements Rule
{

    private $client;

    public function __construct()
    {
        try {
            // fetch the api key from the config - which allows the config to be cached
            $twilioSID = config('twilio.sid');
            $twilioToken = config('twilio.token');
            // throw exception if the twilio credentials are missing from the env
            if ($twilioSID == null || $twilioToken == null) {
                throw new \Exception('The phone cannot be validated!');
            }
            $this->client = new Twilio($twilioSID, $twilioToken);
        } catch (\Exception $ex) {
           
        }
    }

    public function passes($attribute, $value)
    {
        if (!$this->client instanceof Twilio) {
            return false;
        }

        if(!preg_match("#^(\+\d{1,3}\s?)?1?\-?\.?\s?\(?\d{3}\)?[\s.-]?\d{2,3}[\s.-]?\d{4}$#", $value)){
            return false;
        }

        try {
            // attempt to look up a phone number
            // if an exception is thrown, no phone number was found
            $this->client->lookups->phoneNumbers($value)->fetch();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message()
    {
        return trans()->get("validation.phone");
    }
}
