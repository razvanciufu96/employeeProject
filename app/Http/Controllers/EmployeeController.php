<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Employee::query();
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('sort_by') && !empty($request->sort_by)) {
            $sortBy = $request->sort_by;
            $sortOrder = $request->has('sort_order') ? $request->sort_order : 'asc';
            $query->orderBy($sortBy, $sortOrder);
        }

        try {
            $employees = $query->get();

            return response()->json([
                'error' => false,
                'code' => 200,
                'message' => 'Employees fetched successfully',
                'data' => $employees
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 500,
                'message' => 'Something went wrong. Please try again later.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'phone_number' => 'required|string',
            'job_title' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
        try {
            $employee = Employee::create($request->all());

            return response()->json([
                'error' => false,
                'code' => 201, // Created
                'message' => 'Employee created successfully',
                'data' => $employee
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 500,
                'message' => 'Something went wrong. Please try again later.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::where('id', '=', $id)->first();

        if (!$employee) {
            return response()->json([
                'error' => true,
                'code' => 404,
                'message' => 'Employee not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $id,
            'phone_number' => 'required|string',
            'job_title' => 'required|string',
            'salary' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => true,
                'code' => 422, // Unprocessable Entity
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $employee->update($request->all());

            return response()->json([
                'error' => false,
                'code' => 200,
                'message' => 'Employee updated successfully',
                'data' => $employee
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 500,
                'message' => 'Something went wrong. Please try again later.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $employee = Employee::where('id', '=', $id)->first();
        if (!$employee) {
            return response()->json([
                'error' => true,
                'code' => 404,
                'message' => 'Employee not found',
            ], 404);
        }

        try {
            $employee->delete();

            return response()->json([
                'error' => false,
                'code' => 200,
                'message' => 'Employee deleted successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'code' => 500,
                'message' => 'Something went wrong. Please try again later.',
                'errors' => $e->getMessage()
            ], 500);
        }
    }
}
