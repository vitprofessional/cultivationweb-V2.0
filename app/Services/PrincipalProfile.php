<?php

namespace App\Services;

use App\Models\PrincipalSpeech;
use App\Models\ServerConfig;
use App\Models\TeacherManagement;
use Illuminate\Support\Facades\DB;

/** Canonical Principal identity from the existing Teacher people master. */
final class PrincipalProfile
{
    private const DESIGNATIONS = [
        'principal', 'principal(incharge)', 'principal(in-charge)',
        'head master', 'head master(incharge)', 'head master(in-charge)', 'head teacher',
    ];

    public function read(): array
    {
        $candidates = $this->candidateQuery()->orderBy('id')->limit(2)->get();
        $configs = ServerConfig::query()->orderBy('id')->limit(2)->get();
        $speeches = PrincipalSpeech::query()->orderBy('id')->limit(2)->get();
        $identity = $candidates->count() === 1 ? $candidates->first() : null;
        $speech = $speeches->count() === 1 ? $speeches->first() : null;
        $designation = $identity ? TeacherManagement::getDesignationName($identity->designation_id ?: $identity->designation) : null;
        $name = trim((string) ($identity?->fullName ?? ''));
        if ($name === '') {
            $first = trim((string) ($identity?->firstName ?? ''));
            $last = trim((string) ($identity?->lastName ?? ''));
            $name = $last === '' || preg_match('/(?:^|\s)'.preg_quote($last, '/').'$/iu', $first)
                ? $first : trim($first.' '.$last);
        }
        $configIdentity = $configs->count() === 1 ? $configs->first() : null;
        $legacyConflict = $identity && $configIdentity && (
            (filled($configIdentity->principalName) && strtolower(trim($configIdentity->principalName)) !== strtolower($name))
            || (filled($configIdentity->avatar) && trim($configIdentity->avatar) !== trim((string) $identity->avatar))
            || (filled($configIdentity->principalDesignation) && strtolower(trim($configIdentity->principalDesignation)) !== strtolower(trim((string) $designation)))
        );

        return [
            'identity' => $identity,
            'speech' => $speech,
            'identityAmbiguous' => $candidates->count() > 1,
            'identityIssue' => $candidates->isEmpty()
                ? 'No Principal-designated Teacher is present in People.'
                : ($candidates->count() > 1
                    ? 'More than one Principal-designated Teacher is present in People.'
                    : ($name === '' ? 'The selected Principal Teacher record has no name.' : null)),
            'speechAmbiguous' => $speeches->count() > 1,
            'legacyIdentityConflict' => (bool) $legacyConflict,
            'legacyIdentityAmbiguous' => $configs->count() > 1,
            'name' => $name !== '' ? $name : null,
            'designation' => $designation,
            'photoUrl' => app(PublicMediaUrl::class)->teacherPortrait($identity?->avatar),
            'headline' => $speech?->importantSpeech,
            'message' => $speech?->generalSpeech,
        ];
    }

    private function candidateQuery()
    {
        $roleIds = DB::table('designations')
            ->where('type', 'teacher')
            ->where('is_active', true)
            ->whereIn(DB::raw('LOWER(TRIM(name))'), self::DESIGNATIONS)
            ->select('id');
        $roleNames = DB::table('designations')
            ->where('type', 'teacher')
            ->where('is_active', true)
            ->whereIn(DB::raw('LOWER(TRIM(name))'), self::DESIGNATIONS)
            ->selectRaw('LOWER(TRIM(name))');

        return TeacherManagement::query()
            ->where(function ($query) use ($roleIds, $roleNames) {
                $query->whereIn('designation_id', $roleIds)
                    ->orWhere(function ($legacy) use ($roleIds, $roleNames) {
                        $legacyRoleIds = DB::table('designations')
                            ->where('type', 'teacher')
                            ->where('is_active', true)
                            ->whereIn(DB::raw('LOWER(TRIM(name))'), self::DESIGNATIONS)
                            ->select('id');
                        $legacy->whereNull('designation_id')->where(function ($legacyDesignation) use ($legacyRoleIds, $roleNames) {
                            $legacyDesignation->whereIn(DB::raw('LOWER(TRIM(designation))'), $roleNames)
                                ->orWhereIn(DB::raw('CAST(designation AS UNSIGNED)'), $legacyRoleIds);
                        });
                    });
            });
    }
}
