<?php

namespace App\Models;

use App\Lib\Models\HasDisplayOrderFieldTrait;
use App\Lib\Models\HasFileTrait;
use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Log;
use Mpociot\Versionable\VersionableTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class IdeaContent extends BaseModel
{
    use SoftDeletes, HasCompanyRolesTrait; #HasResourcesTrait;

    protected $casts = [
        'markup' => 'array'
    ];

     protected $fillable = ['content_type', 'markup', 'idea_id', 'version'];

    public function idea()
    {
        return $this->belongsTo(Idea::class, 'idea_id', 'id');
    }

//    public function getResourcesSection($type = null)
//    {
//        return 'idea-contents';
//    }
//
//    public function resourcesOwnerId()
//    {
//        return $this->contentType;
//    }

    public function getDefaultImageText()
    {
        $reportColor = $this->getReportColorAttribute();
        return urlencode(preg_replace("/[^a-zA-Z0-9 ]+/", "", $this->name)) . "&color=" . $reportColor['contrast'] . '&background=' . $reportColor['color'];
    }

    public function companyRoles()
    {
        return $this->hasManyThrough(CompanyRole::class, ModelHasCompanyRole::class, 'model_id', 'id', 'id', 'company_role_id')->where('model_type', array_search(static::class, Relation::morphMap()) ?: static::class);
    }

}
