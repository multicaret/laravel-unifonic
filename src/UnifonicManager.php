<?php

namespace Multicaret\Unifonic;


class UnifonicManager
{

    /**
     * @var UnifonicClient
     */
    public $client;

    /**
     * UnifonicManager constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->withDefaultConfiguration();
    }

    /**
     * Create new Unifonic client instance.
     *
     *
     * @return $this
     * @throws \Exception
     */
    public function withDefaultConfiguration()
    {
        $appSid = config('services.unifonic.app_id');
        $email = config('services.unifonic.account_email');
        $password = config('services.unifonic.account_password');

        if (is_null($appSid)) {
            throw new \Exception('Invalid APP ID, please make sure to set the value of APP ID within your `config/services.php`');
        }

        if (is_null($email)) {
            throw new \Exception('Empty Account Email provided, please make sure to set the value of Email within your `config/services.php`');
        }

        if (is_null($password)) {
            throw new \Exception('Empty Account Password provided, please make sure to set the value of Account Password within your `config/services.php`');
        }
        
        $this->client = new UnifonicClient($appSid, $email, $password);

        return $this;
    }


    /**
     * Dynamically call methods on the Unifonic client.
     *
     * @param $method
     * @param $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if ( ! method_exists($this->client, $method)) {
            abort(500, "Method $method does not exist");
        }

        return call_user_func_array([$this->client, $method], $parameters);
    }

}
