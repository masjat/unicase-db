<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index()
    {
        return response()->json(Status::all());
    }

    public function store(Request $request)
    {
        // Validasi data
        $request->validate([
            'status_name' => 'required|string|max:50', // Validasi kolom status_name
        ]);

        // Simpan data ke tabel status
        $status = Status::create($request->only('status_name'));
        return response()->json($status, 201);
    }

    public function show($id)
    {
        return response()->json(Status::findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        // Validasi data
        $request->validate([
            'status_name' => 'required|string|max:50',
        ]);

        // Update data
        $status = Status::findOrFail($id);
        $status->update($request->only('status_name'));
        return response()->json($status);
    }

    public function destroy($id)
    {
        Status::destroy($id);
        return response()->json(null, 204);
    }
}
