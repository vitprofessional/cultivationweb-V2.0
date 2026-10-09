<?php

namespace App\Services;

use App\Models\ServerConfig;
use App\Models\TeacherManagement;
use Illuminate\Support\Collection;

final class ModernHomepagePresentation
{
    public function build(?ServerConfig $config, $institution, Collection $sliders, Collection $notices, Collection $gallery, Collection $metrics, Collection $teachers, array $chairman): array
    {
        $media = app(PublicMediaUrl::class);
        $principal = app(PrincipalProfile::class)->read();
        $leaders = collect();
        if ($chairman['person'] && ! $chairman['ambiguous']) {
            $person = $chairman['person'];
            $leaders->push(['name' => $person->name, 'role' => $person->designation, 'photo' => $person->photoUrl, 'url' => route('chairmanMessagePage')]);
        }
        if ($principal['name'] && ! $principal['identityAmbiguous']) {
            $leaders->push(['name' => $principal['name'], 'role' => $principal['designation'], 'photo' => $principal['photoUrl'], 'url' => route('headOfInstituteMessagePage')]);
        }
        // Resolve names and designation lookups before rendering, not inside Blade.
        $faculty = $teachers->take(4)->map(function ($teacher) use ($media) {
            $first = trim((string) $teacher->firstName);
            $last = trim((string) $teacher->lastName);
            $name = trim((string) $teacher->fullName);
            if ($name === '') $name = $last === '' || preg_match('/(?:^|\s)'.preg_quote($last, '/').'$/iu', $first) ? $first : trim($first.' '.$last);
            return ['name' => $name, 'role' => TeacherManagement::getDesignationName($teacher->designation_id ?: $teacher->designation), 'photo' => $media->teacherPortrait($teacher->avatar), 'url' => route('teacher.show', $teacher->id)];
        })->filter(fn ($person) => filled($person['name']));
        // Known imported/demo copy is not approved institution content. Keep records intact.
        $slides = $sliders->reject(fn ($slide) => preg_match('/sonar\s+bangla\s+college|lorem\s+ipsum|demo\s+slide/iu', (string) $slide->headLine.' '.(string) $slide->detail))->map(fn ($slide) => [
            'image' => $media->slider($slide->avatar), 'title' => trim((string) $slide->headLine),
            'description' => trim(strip_tags((string) ($slide->supporting_text ?: $slide->description ?: $slide->detail))),
        ])->filter(fn ($slide) => $slide['image'])->values();
        $photos = $gallery->map(fn ($photo) => [
            'image' => $media->galleryPhoto($photo->avatar), 'title' => trim((string) ($photo->title ?: $photo->headline)),
        ])->filter(fn ($photo) => $photo['image'])->take(5)->values();
        $noticeRows = $notices->map(function ($notice) use ($media) {
            $date = $notice->created_at;
            return ['id' => $notice->id, 'title' => $notice->headline, 'day' => $date?->format('d') ?? '--', 'month' => $date?->format('M') ?? '---',
                'url' => route('notice.show', $notice), 'file' => $media->notice($notice->attachment), 'filename' => basename((string) $notice->attachment)];
        });
        $link = fn ($title, $route, $icon, $text) => ['title' => $title, 'url' => route($route), 'icon' => $icon, 'text' => $text];
        $quickActions = [
            $link('Notice Board', 'allNotices', 'file-text', 'View latest notices'), $link('Result', 'internalResult', 'users', 'Examination results'),
            $link('Exam Routine', 'newExamSchedule', 'calendar', 'View routine'), $link('Gallery', 'imagePage', 'picture-o', 'Photos and moments'),
        ];
        $usefulLinks = [
            $link('Admission Information', 'supportPage', 'file-text', 'Contact for admission guidance'),
            $link('Institute Information', 'institutePage', 'university', 'About our institution'),
            $link('Academic Information', 'newSyllabus', 'book', 'Syllabus and routines'),
            $link('Student Corner', 'student', 'users', 'Browse student information'),
        ];
        $academicLinks = [
            $link('Syllabus', 'newSyllabus', 'book', 'Academic resources'), $link('Class Routine', 'newClassSchedule', 'calendar', 'Class schedules'),
            $link('Result', 'internalResult', 'graduation-cap', 'Examination results'), $link('Student Corner', 'student', 'users', 'Student information'),
        ];
        return [
            'homepageLayout' => 'modern', 'config' => $config ?? new ServerConfig(), 'institutionName' => trim((string) $config?->instituteName),
            'aboutTitle' => trim((string) $institution?->insHeadline), 'aboutText' => \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', html_entity_decode(strip_tags((string) $institution?->insDetails), ENT_QUOTES, 'UTF-8'))), 420),
            'aboutImage' => $media->institutionAboutImage($institution?->heroImg), 'slides' => $slides, 'leaders' => $leaders,
            'metrics' => $metrics->filter(fn ($metric) => $metric['value'] !== '—')->map(function ($metric) {
                if ($metric['label'] === 'Classes & Programs') $metric['label'] = 'Classes';
                return $metric;
            }), 'faculty' => $faculty, 'photos' => $photos,
            'noticeRows' => $noticeRows, 'noticeBoard' => $notices, 'quickActions' => $quickActions, 'usefulLinks' => $usefulLinks, 'academicLinks' => $academicLinks,
        ];
    }
}
