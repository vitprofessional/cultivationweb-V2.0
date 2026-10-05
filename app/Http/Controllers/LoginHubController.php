<?php
namespace App\Http\Controllers;

final class LoginHubController extends Controller
{
    public function __invoke()
    {
        $definitions = [
            'admin' => ['Administration', 'Manage institution operations and website content.', '/login'],
            'teacher' => ['Teacher', 'Access your teaching workspace and academic tools.', '/teacher/login'],
            'student' => ['Student', 'Access your student portal and academic information.', '/portal/login'],
            'guardian' => ['Guardian', 'Access your linked children’s information.', '/portal/login'],
        ];
        $portals = [];
        foreach ($definitions as $key => [$name, $description, $path]) {
            $base = rtrim((string) config('portals.base_url'), '/');
            $url = config('portals.'.$key) ?: ($base !== '' ? $base.$path : null);
            $parts = $url ? parse_url($url) : false;
            $valid = $parts && filter_var($url, FILTER_VALIDATE_URL)
                && in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
                && (strtolower($parts['scheme'] ?? '') === 'https' || app()->environment(['local', 'testing']))
                && !isset($parts['user']) && !isset($parts['pass']) && !isset($parts['query']) && !isset($parts['fragment']);
            $portals[] = compact('key', 'name', 'description') + ['url' => $valid ? $url : null];
        }
        return view('frontend.cultivation-v2.login', compact('portals'));
    }
}
