<?php

namespace App\Services;

use App\Models\ManagingComittee;
use Illuminate\Support\Facades\DB;

final class GoverningBodyChairmanProfile
{
    private const DESIGNATIONS = ['chairman', 'president'];

    public function read(): array
    {
        $candidates = ManagingComittee::query()
            ->where(function ($query) {
                $query->whereNull('status')
                    ->orWhereRaw("LOWER(TRIM(status)) = ''")
                    ->orWhereRaw("LOWER(TRIM(status)) = 'active'");
            })
            ->whereIn(DB::raw('LOWER(TRIM(designation))'), self::DESIGNATIONS)
            ->orderBy('id')
            ->limit(2)
            ->get();

        $member = $candidates->count() === 1 ? $candidates->first() : null;
        $designation = $member?->designation;
        $person = $member ? (object) [
            'id' => $member->id,
            'name' => $member->fullName,
            'fullName' => $member->fullName,
            'designation' => $designation,
            'avatar' => $member->avatar,
            'photoUrl' => app(PublicMediaUrl::class)->principal($member->avatar),
            'jobDetails' => $member->jobDetails,
            'message' => $member->message,
            'validYear' => $member->validYear,
        ] : null;

        return [
            'person' => $person,
            'ambiguous' => $candidates->count() > 1,
            'candidateCount' => $candidates->count(),
        ];
    }
}
