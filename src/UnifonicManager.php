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
     */
    public function __construct()
    {
        $this->with();
    }

    /**
     * Create new Unifonic client instance.
     *
     *
     * @return $this
     * @throws \Exception
     */
    public function with()
    {
        $appSid = config('services.unifonic.app_id');
        if (is_null($appSid)) {
            throw new \Exception('Invalid APP ID, please make sure to set the value of APP ID within your `config/services.php`');
        }
        $this->client = new UnifonicClient($appSid);

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
