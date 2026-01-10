<?php

namespace App\Services;

use App\Models\Company;
use Carbon\Carbon;

class TimezoneService
{
    /**
     * Convert a datetime from UTC to company timezone
     *
     * @param Company $company
     * @param Carbon $dateTime
     * @return Carbon
     */
    public function convertToCompanyTimezone(Company $company, Carbon $dateTime): Carbon
    {
        return $dateTime->copy()->setTimezone($this->getCompanyTimezone($company));
    }

    /**
     * Convert a datetime from company timezone to UTC
     *
     * @param Company $company
     * @param Carbon $dateTime
     * @return Carbon
     */
    public function convertFromCompanyTimezone(Company $company, Carbon $dateTime): Carbon
    {
        return $dateTime->copy()->setTimezone('UTC');
    }

    /**
     * Format a datetime for display in company timezone
     *
     * @param Company $company
     * @param Carbon|null $dateTime
     * @param string $format
     * @return string
     */
    public function formatForCompany(?Company $company, ?Carbon $dateTime, string $format = 'H:i'): string
    {
        if (!$dateTime) {
            return '-';
        }

        if (!$company) {
            return $dateTime->format($format);
        }

        $converted = $this->convertToCompanyTimezone($company, $dateTime);
        return $converted->format($format);
    }

    /**
     * Get the timezone for a company
     *
     * @param Company|null $company
     * @return string
     */
    public function getCompanyTimezone(?Company $company): string
    {
        if (!$company) {
            return 'America/Argentina/Buenos_Aires';
        }

        return $company->getTimezone();
    }

    /**
     * Get current time in company timezone
     *
     * @param Company|null $company
     * @return Carbon
     */
    public function now(?Company $company): Carbon
    {
        return Carbon::now($this->getCompanyTimezone($company));
    }

    /**
     * Get today's date in company timezone
     *
     * @param Company|null $company
     * @return Carbon
     */
    public function today(?Company $company): Carbon
    {
        return Carbon::today($this->getCompanyTimezone($company));
    }
}
