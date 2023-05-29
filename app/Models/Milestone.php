<?php

namespace App\Models;

use App\Lib\Models\HasPropertiesColumnTrait;
use App\Lib\Models\Resources\HasResourcesTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\Log;

class Milestone extends Model
{
    use HasCompanyTrait;
    protected $fillable = ['title', 'description'];

    public function users()
    {
        return $this->hasMany(MilestoneUser::class, 'milestone_id', 'id');
    }

    public function activeUsers()
    {
        return $this->users()->where("status", MilestoneUser::MILESTONE_USER_TYPES[0]);
    }
}
