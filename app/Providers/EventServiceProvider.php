<?php

namespace App\Providers;

use App\Lib\Context;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Lang;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        /**
         * @param \Nuwave\Lighthouse\Events\ManipulateResult $result
         */
        Event::listen(\Nuwave\Lighthouse\Events\ManipulateResult::class, function (\Nuwave\Lighthouse\Events\ManipulateResult $event) {
            $context = Context::get();
            try {
                $metadata = [
                    'serverTime' => Carbon::now(),
                    'locale' => $context->getLocale(),
                    'currentUserId' => $context->getUserId(),
                    'currentCompanyId' => $context->getCompanyId(),
                    'companyRoleId'=>$context->getCompanyRoleId(),
                    'companyRoleName'=>$context->getCompanyRole()->name,
                    'context' => $context->name,
                ];

                if ($context->request && $context->request->has('_metadata')) {
                    $metadata = array_merge($metadata, $context->request->get('_metadata', []));
                }

                $event->result->extensions['_metadata'] = $metadata;
            } catch (\Exception $ex) {
            }
        });

        //
    }
}
