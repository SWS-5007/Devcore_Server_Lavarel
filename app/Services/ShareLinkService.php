<?php

namespace App\Services;

use App\Lib\Models\Notification;
use App\Models\CompanyTool;
use App\Models\Idea;
use App\Models\ShareLink;
use App\Models\User;
use App\Validators\ProjectEvaluationRecordValidator;
use App\Validators\ShareLinkValidator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Nuwave\Lighthouse\Execution\Utils\Subscription;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShareLinkService extends HasCompanyService
{

    private static $_instance = null;

    protected function __construct()
    {
        parent::__construct(ShareLink::class, false);
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    protected function fillFromArray($option, $data, $instance)
    {
        $data = collect($data);
        if (!config("app.company_id")) {
            $instance->company_id = $data->get('company_id', $instance->company_id);
        }
        $instance->project_id = $data->get('project_id', $instance->project_id);
        $instance->company_role_ids = $data->get('company_role_ids', $instance->company_role_ids);
        $instance->url_name = $data->get('url_name', $instance->url_name);
        $instance->url_hash = base64_encode(Hash::make($instance->url_name.Carbon::now()));

        return $instance;
    }

    protected function getValidator($data, $object, $option)
    {
        return new ShareLinkValidator($data, $object, $option);
    }

    protected function created($data, $object)
    {
    }

    protected function updated($data, $object)
    {
    }

    protected function deleted($object)
    {
        return $object;
    }
}
