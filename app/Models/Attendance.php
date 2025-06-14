<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    /**
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('attendance_date', now());
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeLate(Builder $query): Builder
    {
        return $query->where('timing_status', 'late');
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeOnTime(Builder $query): Builder
    {
        return $query->where('timing_status', 'on_time');
    }

    /**
     * @param Builder $builder
     * @param string $periodStart
     * @param string $periodEnd
     *
     * @return Builder
     */
    public function scopePeriod(Builder $builder, string $periodStart, string $periodEnd): Builder
    {
        return $builder->whereBetween('attendance_date', [$periodStart, $periodEnd]);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeEarly(Builder $query): Builder
    {
        return $query->where('timing_status', 'early');
    }

    /**
     * @return bool
     */
    public function isOnTime(): bool
    {
        return $this->timing_status === 'on_time';
    }

    /**
     * @return bool
     */
    public function isLate(): bool
    {
        return $this->timing_status === 'late';
    }

    /**
     * @return bool
     */
    public function isEarly(): bool
    {
        return $this->timing_status === 'early';
    }

    /**
     * @return bool
     */
    public function isPresent(): bool
    {
        return $this->status === 'present';
    }

    /**
     * @return bool
     */
    public function isAbsent(): bool
    {
        return $this->status === 'absent';
    }

    /**
     * @return bool
     */
    public function isLeave(): bool
    {
        return $this->status === 'leave';
    }

    /**
     * @return string
     */
    public function getLateInHumanAttribute(): string
    {
        $minutes = abs($this->late_in_min);
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        $result = '';

        if ($hours > 0) {
            $result .= $hours . ' hr' . ($hours > 1 ? 's ' : ' ');
        }

        if ($remainingMinutes > 0 || $result === '') {
            $result .= $remainingMinutes . ' min' . ($remainingMinutes > 1 ? 's' : '');
        }

        return trim($result);
    }

}
