<?php

namespace App\Services;

use App\Models\PrincipalSpeech;
use App\Models\ServerConfig;

/** One identity store; no HomeInfo, teacher, or demo identity fallback. */
final class PrincipalProfile
{
    public function read(): array
    {
        $configs = ServerConfig::query()->orderBy('id')->limit(2)->get();
        $speeches = PrincipalSpeech::query()->orderBy('id')->limit(2)->get();
        $identity = $configs->count() === 1 ? $configs->first() : null;
        $speech = $speeches->count() === 1 ? $speeches->first() : null;

        return [
            'identity' => $identity,
            'speech' => $speech,
            'identityAmbiguous' => $configs->count() > 1,
            'speechAmbiguous' => $speeches->count() > 1,
            'name' => $identity?->principalName,
            'designation' => $identity?->principalDesignation,
            'photoUrl' => app(PublicMediaUrl::class)->principal($identity?->avatar),
            'headline' => $speech?->importantSpeech,
            'message' => $speech?->generalSpeech,
        ];
    }
}
