<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Code;
use App\Models\User;

class CodeController extends Controller
{
    // Don't get the wrong idea, but this creates a user per IP, senpai!
    private function getUser(Request $request)
    {
        $ip = $request->ip();
        $user = User::firstOrCreate(['ip' => $ip], [
            'is_admin' => false,
            'password' => null,
            'is_banned' => false,
        ]);

        // If you dare provide the secret password, you'll be marked admin, kyaa~!
        $adminPassword = env('ADMIN_PASSWORD');
        if ($request->filled('password') && $request->input('password') === $adminPassword) {
            $user->is_admin = true;
            $user->password = bcrypt($adminPassword);
            $user->save();
        }
        return $user;
    }

    // Anyone can view codes, but don't think I care about you!
    public function index(Request $request)
    {
        $this->getUser($request); // Just to ensure user creation, senpai.
        $codes = Code::all();
        return response()->json($codes);
    }

    // Adding a code is for everyone, you know!
    public function store(Request $request)
    {
        printf("Request: %s\n", json_encode($request->all()));
        $user = $this->getUser($request);
        $data = $request->validate([
            'code'       => 'required|string',
            'time'       => 'required|date',
            'is_active'  => 'required|boolean',
            'module_id'  => 'required|integer',
            'course_id'  => 'required|integer',
        ]);
        $data['user_id'] = $user->id;
        $code = Code::create($data);
        return response()->json($code, 201);
    }

    // Only an admin—so don't even try unless you're worthy, baka!
    public function update(Request $request, $id)
    {
        $user = $this->getUser($request);
        if (!$user->is_admin) {
            return response()->json(['error' => 'Unauthorized, baka!'], 403);
        }
        $code = Code::findOrFail($id);
        $data = $request->validate([
            'code'       => 'sometimes|required|string',
            'time'       => 'sometimes|required|date',
            'is_active'  => 'sometimes|required|boolean',
            'module_id'  => 'sometimes|required|integer',
            'course_id'  => 'sometimes|required|integer',
        ]);
        $code->update($data);
        return response()->json($code);
    }

    // Deleting a code? Only an admin can do that, senpai!
    public function destroy(Request $request, $id)
    {
        $user = $this->getUser($request);
        if (!$user->is_admin) {
            return response()->json(['error' => 'Unauthorized, senpai!'], 403);
        }
        $code = Code::findOrFail($id);
        $code->delete();
        return response()->json(['message' => 'Code deleted, ne~']);
    }
}
