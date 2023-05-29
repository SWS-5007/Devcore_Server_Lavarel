<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportSerivce
{
    private static $_instance = null;

    protected function __construct()
    {
    }


    public static function instance()
    {
        if (!static::$_instance) {
            static::$_instance = new static();
        }
        return static::$_instance;
    }

    public function peopleReport($data)
    {
        $input = collect($data);
        $ret = [
            'data' => []
        ];

        $usersBuilder = UserService::instance()->find()->with(['companyRole', 'projects' => function ($q) use ($input) {
            if ($input->get('from')) {
                $q->where('started_at', '>=', $input->get('from'));
            }
            if ($input->get('to')) {
                $q->where('started_at', '<=', $input->get('to'));
            }
            if ($input->get('only_active_projects')) {
                $q->whereNull('finished_at');
            }
        }, 'projects.stages', 'projects.stages.evaluationRecords'])->withCount(['projects']);

        if ($input->get('company_role')) {
            $usersBuilder->where('company_role_id', $input->get('company_role'));
        }

        $usersData = $usersBuilder->get();

        $data = [];
        foreach ($usersData as $user) {
            $current = [
                'userId' => $user->id,
                'user' => $user,
                'totalValue' => 0,
                'userValue' => 0,
                'ideas' => 0,
                'companyRole' => $user->companyRole,
                'projects' => [],
            ];

            $totalValue = 0;
            $totalEvaluations = 0;
            $userTotalValue = 0;
            $userTotalEvaluations = 0;
            $totalIdeas = 0;
            $totalUsers = 0;
            $otherTotalEvaluations = 0;
            $otherTotalValue = 0;
            $otherValue = 0;
            foreach ($user->projects as $project) {

                $currentProject = [
                    'projectId' => $project->id,
                    'project' => $project,
                    'stages' => []
                ];
                //check if the project is valid for user
                $validForUser = false;

                foreach ($project->stages as $stage) {


                    //evaluations records with user access to the idea
                    $availableIdeasForUser = $stage->evaluationRecords->where('author_id', $user->id)->unique('project_idea_id');
                    if ($availableIdeasForUser->count() > 0) {
                        $validForUser = true;
                        //all evaluations records
                        $evaluationRecords = $stage->evaluationRecords->where('status', 'COMPLETED')->whereIn('project_idea_id', $availableIdeasForUser->pluck(['project_idea_id']));
                        $otherEvaluationRecords = $evaluationRecords->where('author_id', '!=', $user->id);
                        $myEvaluationRecords = $evaluationRecords->where('author_id', $user->id);


                        $totalValue += $evaluationRecords->sum('total_value');
                        $totalEvaluations += $evaluationRecords->count();
                        $currentTotalUsers = $evaluationRecords->unique('author_id')->count();
                        $totalUsers += $currentTotalUsers;

                        $userTotalValue += $myEvaluationRecords->sum('total_value');
                        $userTotalEvaluations += $myEvaluationRecords->count();

                        $otherEvaluations = $otherEvaluationRecords->unique('author_id')->count();
                        $otherValue = $otherEvaluationRecords->sum('total_value') > 0 ? $otherEvaluationRecords->sum('total_value') : 1;

                        $otherTotalEvaluations += $otherEvaluations;
                        $otherTotalValue += $otherValue;

                        $calcOtherAverage = round($otherValue / ($otherEvaluations > 0 ? $otherEvaluations : 1));

                        $currentProject['stages'][] = [
                            'stageId' => $stage->id,
                            'stage' => $stage,
                            'userTotalEvaluations' => $myEvaluationRecords->count(),
                            'totalValue' => $evaluationRecords->sum('total_value'),
                            'userValue' => $myEvaluationRecords->sum('total_value'),
                            'userAverage' => $myEvaluationRecords->sum('total_value') / ($myEvaluationRecords->count() > 0 ? $myEvaluationRecords->count() : 1),
                            'otherValue' => $otherValue,
                            'totalIdeas' => $availableIdeasForUser->count(),
                            'totalEvaluations' => $evaluationRecords->count(),
                            'totalAverage' => round($evaluationRecords->sum('total_value') / ($currentTotalUsers > 0 ? $currentTotalUsers : 1)),
                            'otherAverage' => $calcOtherAverage,
                            'totalUsers' => $currentTotalUsers,
                        ];
                    }
                }
                if ($validForUser) {
                    $current['projects'][] = $currentProject;
                }
            }
            //((int) $otherValue / (((int) $otherTotalEvaluations > 0) ?
            // (int) $otherTotalEvaluations :
            // 1))
            $current['userValue'] = (int)  $userTotalValue;
            $current['totalValue'] = (int)  $totalValue;
            $current['userAverage'] = (int) $userTotalValue / (((int) $userTotalEvaluations > 0) ? (int) $userTotalEvaluations : 1);
            $current['userTotalEvaluations'] = (int) $userTotalEvaluations;
            $current['totalEvaluations'] = (int) $totalEvaluations;
            $current['totalUsers'] = (int) $totalUsers;
            $current['totalAverage'] = (int)  $totalValue / (((int) $totalUsers > 0) ? (int) $totalUsers : 1);
            $current['otherTotalEvaluations'] = (int)  $otherTotalEvaluations;
            $current['otherTotalValue'] = (int) $otherTotalValue;
            $current['otherAverage'] = ((int) $otherValue / (((int) $otherTotalEvaluations > 0) ? (int) $otherTotalEvaluations : 1));
            $data[] = $current;
        }

        $ret['data'] = $data;

        return $ret;
    }

    public function userProjectReport($data)
    {
        $input = collect($data);
        $user = UserService::instance()->findByPrimaryKey($input->get('id'));
        $ret = [
            'id' => $input->get('id'),
            'project_id' => $input->get('project_id'),
            'user' => $user,
            'currency' => $user->company->currency,
            'from' => $input->get('from', Carbon::now()->addYears(-1)),
            'to' => $input->get('to', Carbon::now()),
            'interval' => $input->get('interval', 'month'),
            'data' => []
        ];

        if ($user) {
            $data = [];

            $to = $ret['to']->copy()->endOfDay();
            $from = $ret['from']->copy()->startOfDay();

            //get the ideas valid for the user
            $project = ProjectService::instance()->findByPrimaryKey($ret['project_id']);
            $validIdeas = $project->userIdeas($user)->pluck('idea_id')->toArray();
            $ideaIds = join(',', $validIdeas);

            $dateFormat = "DATE_FORMAT(completed_at, '%Y-%m-%d')";
            switch (strtolower($ret['interval'])) {
                case 'month':
                    $dateFormat = "DATE_FORMAT(completed_at, '%Y-%m')";
                    break;
                case 'year':
                    $dateFormat = "DATE_FORMAT(completed_at, '%Y')";
                    break;
                case 'quarter':
                    $dateFormat = "CONCAT(YEAR(completed_at), '-',LPAD(QUARTER(completed_at), 2, '0'))";
                    break;
            }

            $rawData = []; //calculate the idea loss
            $rawData['user_loss']  = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total, count(DISTINCT(author_id)) as users_count, count(id) as count FROM project_evaluation_records WHERE status='COMPLETED'
            AND author_id = :user_id AND project_id = :project_id AND company_id = :company_id AND total_value<0 AND completed_at >= :from AND completed_at <= :to GROUP BY date_key ORDER BY date_key
            "), [
                'user_id' => $ret['id'],
                'project_id' => $ret['project_id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });
            //calculate the idea gain
            $rawData['user_gain'] = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total, count(DISTINCT(author_id)) as users_count, count(id) as count FROM project_evaluation_records WHERE status='COMPLETED'
            AND author_id = :user_id AND project_id = :project_id  AND company_id = :company_id AND total_value>=0 AND completed_at >= :from AND completed_at <= :to GROUP BY date_key ORDER BY date_key
            "), [
                'user_id' => $ret['id'],
                'project_id' => $ret['project_id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });

            //calculate other ideas loss
            $rawData['other_gain'] = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total, count(DISTINCT(author_id)) as users_count, count(id) as count FROM project_evaluation_records WHERE status='COMPLETED'
            AND project_id = :project_id  AND company_id = :company_id AND idea_id IN ($ideaIds) AND total_value>=0 AND completed_at >= :from AND completed_at <= :to GROUP BY date_key ORDER BY date_key
            "), [
                //'user_id' => $ret['id'],
                'project_id' => $ret['project_id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'project_id' => $ret['project_id'],
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });
            //calculate other ideas gain
            $rawData['other_loss'] = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total, count(DISTINCT(author_id)) as users_count, count(id) as count FROM project_evaluation_records WHERE status='COMPLETED'
            AND project_id = :project_id AND author_id != :user_id  AND idea_id IN ($ideaIds) AND company_id = :company_id  AND total_value<0 AND completed_at >= :from AND completed_at <= :to GROUP BY date_key ORDER BY date_key
            "), [
                'user_id' => $ret['id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'project_id' => $ret['project_id'],
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });

            $rawData['totals'] = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total, count(DISTINCT(author_id)) as users_count, count(id) as count FROM project_evaluation_records WHERE status='COMPLETED'
            AND project_id = :project_id AND company_id = :company_id AND idea_id IN ($ideaIds) AND completed_at >= :from AND completed_at <= :to GROUP BY date_key ORDER BY date_key
            "), [
                'company_id' => $input->get('company_id', config('app.company_id')),
                'project_id' => $ret['project_id'],
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });


            while ($to >= $from) {
                $item = [
                    'from' => $from->format('Y-m-d'),
                    'to' => $to->format('Y-m-d'),
                    'loss' => 0,
                    'gain' => 0,
                    'total_value' => 0,
                    'other_loss' => 0,
                    'other_gain' => 0,
                    'other_total_value' => 0,
                ];
                $dateStr = "";
                switch ($ret['interval']) {
                    case 'month':
                        $currentFrom = $to->copy()->startOfMonth()->startOfDay();
                        $currentTo = $to->copy()->endOfMonth()->endOfDay();
                        $dateStr = $to->format('Y-m');
                        break;
                    case 'quarter':
                        $currentTo = $to->copy()->lastOfQuarter()->endOfDay();
                        $dateStr = $to->format('Y') . '-' . str_pad($to->quarter, 2, '0', STR_PAD_LEFT);
                        $currentFrom = $to->copy()->firstOfQuarter()->startOfDay();
                        break;
                    case 'year':
                        $currentTo = $to->copy()->endOfYear()->endOfDay();
                        $dateStr = $to->format('Y');
                        $currentFrom = $to->copy()->startOfYear()->startOfDay();
                        break;
                    case 'day':
                        $currentTo = $to->copy()->endOfDay();
                        $dateStr = $to->format('Y-m-d');
                        $currentFrom = $to->copy()->startOfDay();
                        break;
                }
                $currentFrom->startOfDay();
                $item['from'] = $currentFrom->format('Y-m-d H:i:s');
                $item['to'] = $currentTo->format('Y-m-d H:i:s');


                //current idea
                $currentGain = $rawData['user_gain']->where('date_key', $dateStr)->first();
                if (!$currentGain) {
                    $currentGain = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['gain'] = [
                    'total' => (int) $currentGain['total'],
                    'count' => (int) $currentGain['count'],
                    'average' => (int) $currentGain['total'] / ((int) $currentGain['count'] > 0 ? (int) $currentGain['count'] : 1)
                ];

                $currentLoss = $rawData['user_loss']->where('date_key', $dateStr)->first();
                if (!$currentLoss) {
                    $currentLoss = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['loss'] = [
                    'total' => (int) $currentLoss['total'],
                    'count' => (int) $currentLoss['count'],
                    'average' => (int) $currentLoss['total'] / ((int) $currentLoss['count'] > 0 ? (int) $currentLoss['count'] : 1)
                ];



                $item['total_value'] = [
                    'total' => ((int) $currentGain['total'] + (int) $currentLoss['total']),
                    'count' => (int) $currentGain['count'] + (int) $currentLoss['count'],
                    'average' => ((int) $currentGain['total'] + (int) $currentLoss['total']) / (((int) $currentGain['count'] + (int) $currentLoss['count']) > 0 ? ((int) $currentGain['count'] + (int) $currentLoss['count']) : 1)
                ];


                //other ideas
                $currentGain = $rawData['other_gain']->where('date_key', $dateStr)->first();
                if (!$currentGain) {
                    $currentGain = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['other_gain'] = [
                    'total' => (int) $currentGain['total'],
                    'count' => (int) $currentGain['count'],
                    'average' => (int) $currentGain['total'] / ((int) $currentGain['count'] > 0 ? (int) $currentGain['count'] : 1)
                ];

                $currentLoss = $rawData['other_loss']->where('date_key', $dateStr)->first();
                if (!$currentLoss) {
                    $currentLoss = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['other_loss'] = [
                    'total' => (int) $currentLoss['total'],
                    'count' => (int) $currentLoss['count'],
                    'average' => (int) $currentLoss['total'] / ((int) $currentLoss['count'] > 0 ? (int) $currentLoss['count'] : 1)
                ];

                $item['other_total_value'] = [
                    'total' => (int) $currentGain['total'] + (int) $currentLoss['total'],
                    'count' => (int) $currentGain['count'] + (int) $currentLoss['count'],
                    'average' => ((int) $currentGain['total'] + (int) $currentLoss['total']) / (((int) $currentGain['count'] + (int) $currentLoss['count']) > 0 ? ((int) $currentGain['count'] + (int) $currentLoss['count']) : 1)
                ];

                $currentTotals = $rawData['totals']->where('date_key', $dateStr)->first();
                if (!$currentTotals) {
                    $currentTotals = [
                        'total' => 0,
                        'count' => 0,
                        'users_count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['general_total_Value'] = [
                    'total' => (int) $currentTotals['total'],
                    'count' => (int) $currentTotals['count'],
                    'users_count' => (int) $currentTotals['users_count'],
                    'average' => ((int) $currentTotals['total']) / (((int) $currentTotals['users_count']) > 0 ? ((int) $currentTotals['users_count']) : 1),
                ];


                $data[$dateStr] = $item;
                switch ($ret['interval']) {
                    case 'month':
                        $to->addMonths(-1);
                        break;
                    case 'quarter':
                        $to->lastOfQuarter()->addMonths(-4);
                        break;
                    case 'year':
                        $to->addYears(-1);
                        break;
                    case 'day':
                        $to->addDays(-1);
                        break;
                }
            }
            ksort($data);
            $ret['data'] = $data;
        }
        return $ret;
    }

    public function ideaReport($data)
    {
        $input = collect($data);
        $idea = IdeaService::instance()->findByPrimaryKey($input->get('id'));
        $ret = [
            'id' => $input->get('id'),
            'idea' => $idea,
            'currency' => $idea->company->currency,
            'from' => $input->get('from', Carbon::now()->addYears(-1)),
            'to' => $input->get('to', Carbon::now()),
            'interval' => $input->get('interval', 'month'),
            'data' => []
        ];

        if ($idea) {
            $data = [];

            $to = $ret['to']->copy()->endOfDay();
            $from = $ret['from']->copy()->startOfDay();

            $dateFormat = "DATE_FORMAT(completed_at, '%Y-%m-%d')";
            switch (strtolower($ret['interval'])) {
                case 'month':
                    $dateFormat = "DATE_FORMAT(completed_at, '%Y-%m')";
                    break;
                case 'year':
                    $dateFormat = "DATE_FORMAT(completed_at, '%Y')";
                    break;
                case 'quarter':
                    $dateFormat = "CONCAT(YEAR(completed_at), '-',LPAD(QUARTER(completed_at), 2, '0'))";
                    break;
            }

            $rawData = [];

            //calculate the idea loss
            $rawData['idea_loss']  = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total,
            count(id) as count
            FROM project_evaluation_records
            WHERE status='COMPLETED'
            AND idea_id = :idea_id
            AND company_id = :company_id AND total_value<0
            AND completed_at >= :from AND completed_at <= :to
            GROUP BY date_key ORDER BY date_key
            "), [
                'idea_id' => $ret['id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });

            //calculate the idea gain
            $rawData['idea_gain'] = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total,
            count(id) as count
            FROM project_evaluation_records
            WHERE status='COMPLETED'
            AND idea_id = :idea_id
            AND company_id = :company_id
            AND total_value>=0
            AND completed_at >= :from AND completed_at <= :to
            GROUP BY date_key ORDER BY date_key
            "), [
                'idea_id' => $ret['id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });

            //calculate other ideas loss
            $rawData['other_gain'] = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total,
            count(id) as count
            FROM project_evaluation_records
            WHERE status='COMPLETED'
            AND idea_id != :idea_id
            AND company_id = :company_id
            AND idea_id IN (SELECT id FROM ideas WHERE parent_type = :parent_type AND parent_id = :parent_id)
            AND total_value>=0 AND completed_at >= :from AND completed_at <= :to
            GROUP BY date_key ORDER BY date_key
            "), [
                'idea_id' => $ret['id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'parent_type' => $idea->parent_type,
                'parent_id' => $idea->parent_id,
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });

            //calculate other ideas gain
            $rawData['other_loss'] = collect(DB::select(DB::raw("
            SELECT $dateFormat as date_key, sum(total_value) as total,
            count(id) as count FROM project_evaluation_records
            WHERE status='COMPLETED'
            AND idea_id != :idea_id   AND company_id = :company_id
            AND idea_id IN (SELECT id FROM ideas
            WHERE parent_type = :parent_type AND parent_id = :parent_id)
            AND total_value<0 AND completed_at >= :from
            AND completed_at <= :to GROUP BY date_key
            ORDER BY date_key
            "), [
                'idea_id' => $ret['id'],
                'company_id' => $input->get('company_id', config('app.company_id')),
                'parent_type' => $idea->parent_type,
                'parent_id' => $idea->parent_id,
                'from' => $ret['from']->startOfDay(),
                'to' => $ret['to']->endOfDay(),
            ]))->map(function ($item) {
                return (array) $item;
            });


            while ($to >= $from) {
                $item = [
                    'from' => $from->format('Y-m-d'),
                    'to' => $to->format('Y-m-d'),
                    'loss' => 0,
                    'gain' => 0,
                    'total_value' => 0,
                    'other_loss' => 0,
                    'other_gain' => 0,
                    'other_total_value' => 0,
                ];
                $dateStr = "";
                switch ($ret['interval']) {
                    case 'month':
                        $currentFrom = $to->copy()->startOfMonth()->startOfDay();
                        $currentTo = $to->copy()->endOfMonth()->endOfDay();
                        $dateStr = $to->format('Y-m');
                        break;
                    case 'quarter':
                        $currentTo = $to->copy()->lastOfQuarter()->endOfDay();
                        $dateStr = $to->format('Y') . '-' . str_pad($to->quarter, 2, '0', STR_PAD_LEFT);
                        $currentFrom = $to->copy()->firstOfQuarter()->startOfDay();
                        break;
                    case 'year':
                        $currentTo = $to->copy()->endOfYear()->endOfDay();
                        $dateStr = $to->format('Y');
                        $currentFrom = $to->copy()->startOfYear()->startOfDay();
                        break;
                    case 'day':
                        $currentTo = $to->copy()->endOfDay();
                        $dateStr = $to->format('Y-m-d');
                        $currentFrom = $to->copy()->startOfDay();
                        break;
                }
                $currentFrom->startOfDay();
                $item['from'] = $currentFrom->format('Y-m-d H:i:s');
                $item['to'] = $currentTo->format('Y-m-d H:i:s');


                //current idea
                $currentGain = $rawData['idea_gain']->where('date_key', $dateStr)->first();
                if (!$currentGain) {
                    $currentGain = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['gain'] = [
                    'total' => (int) $currentGain['total'],
                    'count' => (int) $currentGain['count'],
                    'average' => (int) $currentGain['total'] / ((int) $currentGain['count'] > 0 ? (int) $currentGain['count'] : 1)
                ];

                $currentLoss = $rawData['idea_loss']->where('date_key', $dateStr)->first();
                if (!$currentLoss) {
                    $currentLoss = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['loss'] = [
                    'total' => (int) $currentLoss['total'],
                    'count' => (int) $currentLoss['count'],
                    'average' => (int) $currentLoss['total'] / ((int) $currentLoss['count'] > 0 ? (int) $currentLoss['count'] : 1)
                ];

                $item['total_value'] = [
                    'total' => (int) $currentGain['total'] + (int) $currentLoss['total'],
                    'count' => (int) $currentGain['count'] + (int) $currentLoss['count'],
                    'average' => ((int) $currentGain['total'] + (int) $currentLoss['total']) / (((int) $currentGain['count'] + (int) $currentLoss['count']) > 0 ? ((int) $currentGain['count'] + (int) $currentLoss['count']) : 1)
                ];


                //other ideas
                $currentGain = $rawData['other_gain']->where('date_key', $dateStr)->first();
                if (!$currentGain) {
                    $currentGain = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['other_gain'] = [
                    'total' => (int) $currentGain['total'],
                    'count' => (int) $currentGain['count'],
                    'average' => (int) $currentGain['total'] / ((int) $currentGain['count'] > 0 ? (int) $currentGain['count'] : 1)
                ];

                $currentLoss = $rawData['other_loss']->where('date_key', $dateStr)->first();
                if (!$currentLoss) {
                    $currentLoss = [
                        'total' => 0,
                        'count' => 0,
                        'date_key' => $dateStr
                    ];
                }

                $item['other_loss'] = [
                    'total' => (int) $currentLoss['total'],
                    'count' => (int) $currentLoss['count'],
                    'average' => (int) $currentLoss['total'] / ((int) $currentLoss['count'] > 0 ? (int) $currentLoss['count'] : 1)
                ];

                $item['other_total_value'] = [
                    'total' => (int) $currentGain['total'] + (int) $currentLoss['total'],
                    'count' => (int) $currentGain['count'] + (int) $currentLoss['count'],
                    'average' => ((int) $currentGain['total'] + (int) $currentLoss['total']) / (((int) $currentGain['count'] + (int) $currentLoss['count']) > 0 ? ((int) $currentGain['count'] + (int) $currentLoss['count']) : 1)
                ];



                $data[$dateStr] = $item;
                switch ($ret['interval']) {
                    case 'month':
                        $to->addMonths(-1);
                        break;
                    case 'quarter':
                        $to->lastOfQuarter()->addMonths(-4);
                        break;
                    case 'year':
                        $to->addYears(-1);
                        break;
                    case 'day':
                        $to->addDays(-1);
                        break;
                }
            }
            ksort($data);
            $ret['data'] = $data;
        }

        return $ret;
    }
}
