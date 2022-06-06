<?php

namespace ClassyPOS\Http\Controllers\Api\V1\System;

use ClassyPOS\Jobs\CreateTenantJob;
use Hyn\Tenancy\Contracts\Repositories\CustomerRepository;
use Hyn\Tenancy\Contracts\Repositories\HostnameRepository;
use Hyn\Tenancy\Contracts\Repositories\WebsiteRepository;
use Hyn\Tenancy\Models\Customer;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class RegisterCustomerController extends Controller
{
    public function createTenant(Request $request)
    {
        $name = $request->name;
        $email = $request->email;

        if ($this->tenantExists($name, $email)) {
            return back()->with('error', "A tenant with name '{$name}' and/or '{$email}' already exists.");
        }

        CreateTenantJob::dispatch($name, $email);

//        return redirect('/home');
        return response('Status: Tenant Created.', 201);
    }


    private function tenantExists($name, $email)
    {
        return Customer::where('name', $name)->orWhere('email', $email)->exists();
    }


    public function deleteTenant(Request $request)
    {
        $name = $request->name;

        if ($customer = Customer::where('name', $name)->with(['websites', 'hostnames'])->firstOrFail()) {
            $hostname = $customer->hostnames->first();
            $website = $customer->websites->first();

            app(HostnameRepository::class)->delete($hostname, true);
            app(WebsiteRepository::class)->delete($website, true);
            app(CustomerRepository::class)->delete($customer, true);

        }

//        return redirect('/');
        return response()->json('Status: OK', 204);
    }
}
