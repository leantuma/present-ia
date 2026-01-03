<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Attendance;
use App\Models\Alert;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AIService
{
    /**
     * Analyze attendance patterns and generate alerts
     *
     * @param Company $company
     * @return void
     */
    public function analyzePatterns(Company $company): void
    {
        $this->detectLatePatterns($company);
        $this->detectAbnormalBehavior($company);
    }

    /**
     * Detect late patterns for employees
     */
    private function detectLatePatterns(Company $company): void
    {
        $threshold = 3; // Number of late occurrences to trigger alert
        $daysToCheck = 14; // Check last 14 days

        $startDate = Carbon::now()->subDays($daysToCheck);
        
        $lateCounts = Attendance::where('company_id', $company->id)
            ->where('date', '>=', $startDate)
            ->where('status', 'late')
            ->select('user_id', DB::raw('COUNT(*) as late_count'))
            ->groupBy('user_id')
            ->having('late_count', '>=', $threshold)
            ->get();

        foreach ($lateCounts as $lateCount) {
            $user = User::find($lateCount->user_id);
            if (!$user) continue;

            // Check if alert already exists
            $existingAlert = Alert::where('company_id', $company->id)
                ->where('user_id', $user->id)
                ->where('type', 'late_pattern')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->first();

            if (!$existingAlert) {
                Alert::create([
                    'company_id' => $company->id,
                    'user_id' => $user->id,
                    'type' => 'late_pattern',
                    'title' => 'Frequent Late Arrivals Detected',
                    'message' => "{$user->name} has been late {$lateCount->late_count} times in the last {$daysToCheck} days.",
                    'severity' => 'medium',
                    'metadata' => [
                        'late_count' => $lateCount->late_count,
                        'days_checked' => $daysToCheck,
                    ],
                ]);
            }
        }
    }

    /**
     * Detect abnormal behavior patterns
     */
    private function detectAbnormalBehavior(Company $company): void
    {
        $daysToCheck = 7;
        $startDate = Carbon::now()->subDays($daysToCheck);

        // Detect employees with unusual check-in times
        $attendances = Attendance::where('company_id', $company->id)
            ->where('date', '>=', $startDate)
            ->whereNotNull('check_in_at')
            ->with('user', 'schedule')
            ->get();

        foreach ($attendances->groupBy('user_id') as $userId => $userAttendances) {
            $user = $userAttendances->first()->user;
            if (!$user) continue;

            // Check for very early or very late check-ins
            foreach ($userAttendances as $attendance) {
                if ($attendance->schedule) {
                    $expectedTime = Carbon::parse($attendance->date->format('Y-m-d') . ' ' . $attendance->schedule->start_time->format('H:i:s'));
                    $actualTime = Carbon::parse($attendance->check_in_at);
                    
                    // Check if check-in is more than 2 hours early or 4 hours late
                    $diffHours = $actualTime->diffInHours($expectedTime, false);
                    
                    if ($diffHours < -2 || $diffHours > 4) {
                        Alert::create([
                            'company_id' => $company->id,
                            'user_id' => $user->id,
                            'attendance_id' => $attendance->id,
                            'type' => 'abnormal_behavior',
                            'title' => 'Unusual Check-in Time Detected',
                            'message' => "{$user->name} checked in at {$actualTime->format('H:i')} on {$attendance->date->format('Y-m-d')}, which is significantly different from the expected time.",
                            'severity' => 'low',
                            'metadata' => [
                                'expected_time' => $expectedTime->format('H:i:s'),
                                'actual_time' => $actualTime->format('H:i:s'),
                                'difference_hours' => $diffHours,
                            ],
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Generate weekly summary for company admins
     *
     * @param Company $company
     * @return array
     */
    public function generateWeeklySummary(Company $company): array
    {
        $startDate = Carbon::now()->startOfWeek();
        $endDate = Carbon::now()->endOfWeek();

        $attendances = Attendance::where('company_id', $company->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        $totalEmployees = $company->users()->where('role', 'employee')->count();
        $totalCheckIns = $attendances->whereNotNull('check_in_at')->count();
        $totalLate = $attendances->where('status', 'late')->count();
        $totalAbsent = $attendances->where('status', 'absent')->count();
        $averageMinutesLate = $attendances->where('status', 'late')->avg('minutes_late') ?? 0;
        $totalHoursWorked = $attendances->sum('total_minutes_worked') / 60;

        $topLateEmployees = Attendance::where('company_id', $company->id)
            ->whereBetween('date', [$startDate, $endDate])
            ->where('status', 'late')
            ->select('user_id', DB::raw('COUNT(*) as late_count'))
            ->groupBy('user_id')
            ->orderByDesc('late_count')
            ->limit(5)
            ->with('user')
            ->get();

        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'overview' => [
                'total_employees' => $totalEmployees,
                'total_check_ins' => $totalCheckIns,
                'total_late' => $totalLate,
                'total_absent' => $totalAbsent,
                'attendance_rate' => $totalEmployees > 0 ? ($totalCheckIns / ($totalEmployees * 5)) * 100 : 0, // Assuming 5 working days
                'average_minutes_late' => round($averageMinutesLate, 2),
                'total_hours_worked' => round($totalHoursWorked, 2),
            ],
            'top_late_employees' => $topLateEmployees->map(function ($item) {
                return [
                    'user' => $item->user->name,
                    'late_count' => $item->late_count,
                ];
            }),
            'insights' => $this->generateInsights($company, $attendances),
        ];
    }

    /**
     * Generate AI insights based on attendance data
     */
    private function generateInsights(Company $company, $attendances): array
    {
        $insights = [];

        $lateRate = $attendances->whereNotNull('check_in_at')->count() > 0
            ? ($attendances->where('status', 'late')->count() / $attendances->whereNotNull('check_in_at')->count()) * 100
            : 0;

        if ($lateRate > 20) {
            $insights[] = [
                'type' => 'warning',
                'message' => "High late arrival rate detected ({$lateRate}%). Consider reviewing schedule flexibility or tolerance settings.",
            ];
        }

        $absentRate = $attendances->count() > 0
            ? ($attendances->where('status', 'absent')->count() / $attendances->count()) * 100
            : 0;

        if ($absentRate > 10) {
            $insights[] = [
                'type' => 'warning',
                'message' => "Elevated absence rate ({$absentRate}%). May indicate scheduling or engagement issues.",
            ];
        }

        if (empty($insights)) {
            $insights[] = [
                'type' => 'positive',
                'message' => 'Attendance patterns are within normal ranges. Keep up the good work!',
            ];
        }

        return $insights;
    }
}

