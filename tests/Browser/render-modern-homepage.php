<?php

// Read-only local presentation capture; never changes homepage configuration.
require __DIR__.'/../../vendor/autoload.php';
$app = require __DIR__.'/../../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$db = $app['db']->connection();
if (!$app->environment('local') || $db->getDatabaseName() !== 'cultivation'
    || !in_array($db->getConfig('host'), ['127.0.0.1', 'localhost'], true)) {
    throw new RuntimeException('Read-only homepage capture requires local cultivation.');
}
$output = realpath(getenv('HOMEPAGE_QA_DIR') ?: '');
if (!$output || !str_starts_with($output, realpath(sys_get_temp_dir()).DIRECTORY_SEPARATOR)) {
    throw new RuntimeException('Capture output must be under system temp.');
}
Illuminate\Support\Facades\URL::forceRootUrl('http://localhost/cultivationweb-V2.0');
$selected = app(App\Http\Controllers\FrontController::class)->homePage();
$data = $selected->getData();
if ($selected->name() !== 'frontend.cultivation-v2.homepage') {
    $data = [
        'config' => App\Models\ServerConfig::latest('id')->first(),
        'insData' => App\Models\InstituteDetails::first(),
        'sliderData' => App\Models\HomeSlider::latest('id')->limit(5)->get(),
        'noticeBoard' => App\Models\Notice::latest('id')->limit(5)->get(),
        'gallery' => App\Models\PhotoGallery::all(),
        'overviewMetrics' => $data['metrics'],
        'facultyPreview' => App\Models\TeacherManagement::orderByRaw('CAST(rank AS UNSIGNED) IS NULL, CAST(rank AS UNSIGNED), id')->limit(8)->get(),
        'principalSpeechModel' => App\Models\PrincipalSpeech::first(),
        'studentCount' => App\Models\newAdmission::query()->withValidClass()->count(),
        'teacherCount' => App\Models\TeacherManagement::count(),
        'staffCount' => App\Models\StaffManagement::count(),
        'classCount' => App\Models\classManage::count(),
    ];
    $chairman = app(App\Services\GoverningBodyChairmanProfile::class)->read();
    $data['chairman'] = $chairman['person'];
    $data['chairmanAmbiguous'] = $chairman['ambiguous'];
}
$classic = view('frontend.cultivation-v2.homepage', $data);
$presentation = app(App\Services\ModernHomepagePresentation::class)->build(
    $data['config'], $data['insData'], $data['sliderData'], $data['noticeBoard'], $data['gallery'],
    $data['overviewMetrics'], $data['facultyPreview'], app(App\Services\GoverningBodyChairmanProfile::class)->read()
);
file_put_contents($output.'/modern.html', view('frontend.cultivation-v2.homepage-modern', $presentation)->render());
file_put_contents($output.'/classic.html', $classic->render());
echo 'Read-only Classic and Modern captures complete.'.PHP_EOL;
