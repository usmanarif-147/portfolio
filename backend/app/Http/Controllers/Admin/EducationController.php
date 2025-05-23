<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::orderBy('start_year', 'desc')->get();

        return view('admin.education.index', ['educations' => $educations]);
    }


    public function create()
    {
        return view('admin.education.create');
    }

    // Store a new education entry
    public function store(Request $request)
    {
        $payload = $request->validate([
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_year' => 'required|integer|min:1900|max:' . date('Y'),
            'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'description' => 'nullable|string',
        ]);

        Education::create($payload);

        return redirect()->back()->with('message', 'Education Created successfully');
    }

    public function edit($id)
    {
        $education = Education::where('id', $id)->first();

        return view('admin.education.edit', ['education' => $education]);
    }

    // Update existing education entry
    public function update(Request $request, $id)
    {
        $education = Education::find($id);

        if (!$education) {
            return response()->json([
                'status' => false,
                'message' => 'Education record not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'institution' => 'required|string|max:255',
            'degree' => 'required|string|max:255',
            'field_of_study' => 'nullable|string|max:255',
            'start_year' => 'required|integer|min:1900|max:' . date('Y'),
            'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (
            $request->start_year &&
            $request->end_year &&
            $request->start_year > $request->end_year
        ) {
            return response()->json([
                'status' => false,
                'errors' => ['start_year' => ['Start year must be less than or equal to end year']]
            ], 422);
        }

        $education->update($request->only([
            'institution',
            'degree',
            'field_of_study',
            'start_year',
            'end_year',
            'description'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Education updated successfully',
            'data' => $education
        ]);
    }

    // Delete education entry
    public function delete($id)
    {
        $education = Education::find($id);

        if (!$education) {
            return response()->json([
                'status' => false,
                'message' => 'Education record not found'
            ], 404);
        }

        $education->delete();

        return response()->json([
            'status' => true,
            'message' => 'Education deleted successfully'
        ]);
    }
}
