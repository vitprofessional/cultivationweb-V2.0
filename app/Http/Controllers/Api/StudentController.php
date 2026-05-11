<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StudentManagement;
use App\Http\Resources\StudentResource;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Schema;

class StudentController extends Controller
{
    /**
     * GET /api/students?search=&page=&limit=
     */
    public function index(Request $request)
    {
        // Optional: protect reads if configured
        if(!$this->checkReadGuard($request)){
            return response()->json(['message'=>'Unauthorized'], 401);
        }

        $query = StudentManagement::query();
        $this->applyFilters($request, $query);

        $limit = (int) $request->query('limit', 15);
        $limit = $limit > 0 && $limit <= 100 ? $limit : 15;

        // Sorting: allow ?sort=class|name|roll|email|mobile|session|department&dir=asc|desc
        [$sortCol, $sortDir] = $this->resolveSort($request);
        try {
            $query->orderBy($sortCol, $sortDir);
        } catch (\Throwable $e) {
            // Fallback if invalid column
            $query->orderBy('id', 'desc');
        }

        $students = $query->paginate($limit);
        return StudentResource::collection($students)->additional([
            'meta' => [
                'search' => trim($request->query('search','')),
                'limit'  => $limit,
                'total'  => $students->total(),
                'page'   => $students->currentPage(),
                'pages'  => $students->lastPage(),
                'filters'=> [
                    'roll'       => $request->query('roll'),
                    'email'      => $request->query('email'),
                    'session'    => $request->query('session'),
                    'department' => $request->query('department'),
                    'name'       => $request->query('name')
                ],
                'sort'   => [
                    'by'  => $request->query('sort', 'classid'),
                    'dir' => strtolower($request->query('dir','desc'))
                ]
            ]
        ]);
    }

    /**
     * POST /api/students
     */
    public function store(Request $request)
    {
        if(!$this->checkApiKey($request)){
            return response()->json(['message'=>'Unauthorized'], 401);
        }
        $data = $this->validateData($request);
        $student = new StudentManagement();
        $student->fullName     = $data['name'];
        $student->rollNumber   = $data['roll'];
        $student->email        = $data['email'] ?? null;
        $student->mobile       = $data['mobile'] ?? null;
        $student->sessionYear  = $data['session'] ?? null;
        $student->department   = $data['department'] ?? null;

        $student->save();
        return (new StudentResource($student))->additional([
            'message' => 'Student created successfully'
        ])->response()->setStatusCode(201);
    }

    /**
     * GET /api/students/{student}
     */
    public function show(StudentManagement $student)
    {
        $request = request();
        if(!$this->checkReadGuard($request)){
            return response()->json(['message'=>'Unauthorized'], 401);
        }
        return new StudentResource($student);
    }

    /**
     * PUT/PATCH /api/students/{student}
     */
    public function update(Request $request, StudentManagement $student)
    {
        if(!$this->checkApiKey($request)){
            return response()->json(['message'=>'Unauthorized'], 401);
        }
        $data = $this->validateData($request, $student->id);
        // Map back to underlying columns
        if(isset($data['name'])) $student->fullName = $data['name'];
        if(isset($data['roll'])) $student->rollNumber = $data['roll'];
        if(array_key_exists('email',$data)) $student->email = $data['email'];
        if(array_key_exists('mobile',$data)) $student->mobile = $data['mobile'];
        if(array_key_exists('session',$data)) $student->sessionYear = $data['session'];
        if(array_key_exists('department',$data)) $student->department = $data['department'];

        $student->save();
        return (new StudentResource($student))->additional([
            'message' => 'Student updated successfully'
        ]);
    }

    /**
     * DELETE /api/students/{student}
     */
    public function destroy(StudentManagement $student)
    {
        // No body on delete normally, but still enforce API key
        $request = request();
        if(!$this->checkApiKey($request)){
            return response()->json(['message'=>'Unauthorized'], 401);
        }
        $student->delete();
        return response()->json([
            'message' => 'Student deleted successfully'
        ], 200);
    }

    /**
     * Shared validation rules
     */
    protected function validateData(Request $request, $id = null): array
    {
        return $request->validate([
            'name'       => ['required','string','min:2','max:120'],
            'roll'       => ['required','string','min:1','max:50'],
            'email'      => ['nullable','email:rfc,dns','max:150'],
            'mobile'     => ['nullable','regex:/^\+?[0-9]{10,15}$/'],
            'session'    => ['nullable','string','max:50'],
            'department' => ['nullable','string','max:100'],
        ],[
            'mobile.regex' => 'Mobile must be 10-15 digits (optional leading +).'
        ]);
    }

    /**
     * Minimal API key check via X-API-KEY header vs env('API_KEY')
     */
    protected function checkApiKey(Request $request): bool
    {
        $expected = env('API_KEY');
        if(!$expected){
            // If no key configured, allow for now
            return true;
        }
        $provided = $request->header('X-API-KEY');
        return is_string($provided) && hash_equals($expected, $provided);
    }

    /**
     * If API_PROTECT_READS=true, require API key for reads too
     */
    protected function checkReadGuard(Request $request): bool
    {
        $protect = filter_var(env('API_PROTECT_READS', false), FILTER_VALIDATE_BOOLEAN);
        if(!$protect){
            return true;
        }
        return $this->checkApiKey($request);
    }

    /**
     * Apply common filtering to queries based on request params
     */
    protected function applyFilters(Request $request, $query): void
    {
        if($search = trim($request->query('search',''))){
            $query->where(function($q) use ($search){
                $like = '%'.$search.'%';
                $q->where('fullName','like',$like)
                  ->orWhere('rollNumber','like',$like)
                  ->orWhere('email','like',$like)
                  ->orWhere('mobile','like',$like)
                  ->orWhere('sessionYear','like',$like)
                  ->orWhere('department','like',$like);
            });
        }
        if($name = trim($request->query('name',''))){
            $query->where('fullName','like','%'.$name.'%');
        }
        if($roll = trim($request->query('roll',''))){
            $query->where('rollNumber','like','%'.$roll.'%');
        }
        if($email = trim($request->query('email',''))){
            $query->where('email','like','%'.$email.'%');
        }
        if($session = trim($request->query('session',''))){
            $query->where('sessionYear','like','%'.$session.'%');
        }
        if($dept = trim($request->query('department',''))){
            $query->where('department','like','%'.$dept.'%');
        }
    }

    /**
     * GET /api/students/export.csv
     * Streams CSV with same filters as index.
     */
    public function exportCsv(Request $request)
    {
        // Protect export as a read endpoint; honor API_PROTECT_READS
        if(!$this->checkReadGuard($request)){
            return response()->json(['message'=>'Unauthorized'], 401);
        }

        $query = StudentManagement::query();
        $this->applyFilters($request, $query);
        [$sortCol, $sortDir] = $this->resolveSort($request);
        try {
            $query->orderBy($sortCol, $sortDir);
        } catch (\Throwable $e) {
            $query->orderBy('id', 'asc');
        }

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="students_export.csv"',
            'Cache-Control'       => 'no-store, no-cache, must-revalidate',
            'Pragma'              => 'no-cache'
        ];

        $callback = function() use ($query){
            $out = fopen('php://output', 'w');
            // Header row
            fputcsv($out, ['ID','Name','Roll','Email','Mobile','Session','Department','Created At']);
            $query->chunk(200, function($rows) use ($out){
                foreach($rows as $r){
                    fputcsv($out, [
                        $r->id,
                        $r->fullName,
                        $r->rollNumber,
                        $r->email,
                        $r->mobile,
                        $r->sessionYear,
                        $r->department,
                        optional($r->created_at)->toDateTimeString(),
                    ]);
                }
            });
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Resolve sorting column and direction from request with safe mapping.
     * Supports sort values: id,name,roll,email,mobile,session,department,class
     * 'class' maps to 'className' if present, else falls back to 'id'.
     */
    protected function resolveSort(Request $request): array
    {
    // default to class id DESC if not provided
    $dir = strtolower($request->query('dir','desc'));
    $dir = in_array($dir, ['asc','desc'], true) ? $dir : 'desc';

        $key = strtolower($request->query('sort','classid'));

        // Select best-fit column for class id/name based on existing columns
        $table = (new \App\Models\StudentManagement())->getTable();
        $firstExisting = function(array $candidates) use ($table){
            foreach($candidates as $c){
                if(Schema::hasColumn($table, $c)) return $c;
            }
            return null;
        };

        // Map sort keys to underlying columns (with fallbacks)
        $map = [
            'id'          => ['id'],
            'name'        => ['fullName','name'],
            'roll'        => ['rollNumber','roll','roll_no'],
            'email'       => ['email'],
            'mobile'      => ['mobile','phone'],
            'session'     => ['sessionYear','session'],
            'department'  => ['department','department_id'],
            'class'       => ['className','class_id','classId'],
            'classid'     => ['class_id','classId','className'],
        ];

        $candidates = $map[$key] ?? ['id'];
        $col = $firstExisting($candidates) ?? 'id';
        return [$col, $dir];
    }
}
