<?php

namespace ClassyPOS\Jobs;

use ClassyPOS\Notifications\TenantCreated;
use ClassyPOS\Models\Users\User;
use Hyn\Tenancy\Contracts\Repositories\CustomerRepository;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Environment;
use Hyn\Tenancy\Models\Customer;
use Hyn\Tenancy\Models\Hostname;
use Hyn\Tenancy\Models\Website;
use Hyn\Tenancy\Queue\TenantAwareJob;
use Illuminate\Support\Facades\Hash;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class CreateTenantJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, TenantAwareJob;

    protected $name;
    protected $email;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($name, $email)
    {
        $this->name = $name;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $name = $this->name;
        $email = $this->email;

        $hostname = $this->registerTenant($name, $email);
        app(Environment::class)->hostname($hostname);

        // we'll create a random secure password for our to-be admin
//        $password = str_random();
        $password = $name . '@123';
        $this->addAdmin($name, $email, $password)
            ->notify(new TenantCreated($hostname));

    }

    private function registerTenant($name, $email)
    {
        // create a customer
        $customer = new Customer;
        $customer->name = $name;
        $customer->email = $email;

        app(CustomerRepository::class)->create($customer);

        // associate the customer with a website
        $website = new Website;
        $website->customer()->associate($customer);

        app(WebsiteRepository::class)->create($website);

        // associate the website with a hostname
        $hostname = new Hostname;
        $baseUrl = config('app.url_base');
        $hostname->fqdn = "{$name}.{$baseUrl}";
        $hostname->customer()->associate($customer);

        app(HostnameRepository::class)->attach($hostname, $website);

        return $hostname;
    }

    private function addAdmin($name, $email, $password)
    {
        $admin = User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        $admin->guard_name = 'api';
        $admin->assignRole('admin');

        return $admin;
    }
}
