<?php

namespace App\Models;


class ExperienceQuest extends BaseModel
{
    use HasUserTrait, HasCompanyTrait;

    const EXPERIENCE_USER_TYPES = [
        'USER',
        'MANAGER',
        'ADMIN'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
}
