<?php

namespace App\Http\Controllers;

use App\Models\CustomCase;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomCaseController extends Controller
{
    public function index()
    {
        return CustomCase::with(['user', 'brand', 'brandType'])->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'case_type' => ['required', 'in:softcase,hardcase,premium_anti_crack'],
            'print_effect' => ['required', 'in:glossy,doff,glow_effect'],
            'brand_id' => ['required', 'exists:brands,id'],
            'brand_type_id' => ['required', 'exists:brand_types,id'],
            'description' => ['nullable'],
            'image_file' => ['required', 'image', 'mimes:png,jpg', 'max:5120'], // PNG hasil desain
        ]);

        // Mapping harga
        $casePrices = [
            'softcase' => 10000,
            'hardcase' => 30000,
            'premium_anti_crack' => 50000,
        ];

        $effectPrices = [
            'glossy' => 8000,
            'doff' => 8000,
            'glow_effect' => 15000,
        ];

        $case_type = $request->case_type;
        $print_effect = $request->print_effect;

        $price_case = $casePrices[$case_type] ?? 0;
        $price_print = $effectPrices[$print_effect] ?? 0;
        $total_price = $price_case + $price_print;

        // Upload file gambar
        $image_url = null;
        if ($request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $filename = 'custom_' . time() . '.' . $image->getClientOriginalExtension();
            $path = $image->storeAs('public/custom_cases', $filename);
            $image_url = Storage::url($path); // menghasilkan /storage/custom_cases/...
        }
        
        $customCategory = \App\Models\Category::where('name', 'Custom')->first();
        if (!$customCategory) {
            return response()->json(['message' => 'Kategori "Custom" tidak ditemukan'], 400);
        }


        // Simpan custom case
        $customCase = CustomCase::create([
            'user_id' => $request->user_id,
            'case_type' => $case_type,
            'print_effect' => $print_effect,
            'brand_id' => $request->brand_id,
            'brand_type_id' => $request->brand_type_id,
            'description' => $request->description,
            'image_url' => $image_url,
            'price_case' => $price_case,
            'price_print' => $price_print,
            'total_price' => $total_price,
        ]);

        // Simpan otomatis ke tabel produk
        Product::create([
            'type' => 'custom',
            'is_active' => true,
            'user_id' => $customCase->user_id,
            'custom_case_id' => $customCase->id,
            'name' => 'Custom Case by User ' . $customCase->user_id,
            'description' => $customCase->description ?? 'Custom case',
            'price' => $total_price,
            'stock' => 1,
            'weight' => 200, // default berat 200 gram
            'image' => $image_url,
            'category_id' => $customCategory->id, 
        ]);

        return response()->json([
            'message' => 'Custom case created successfully',
            'data' => $customCase
        ], 201);
    }

    public function show($id)
    {
        return CustomCase::with(['user', 'brand', 'brandType'])->findOrFail($id);
    }

    public function destroy($id)
    {
        $customCase = CustomCase::findOrFail($id);
        $customCase->delete();

        return response()->json(['message' => 'Custom case deleted']);
    }
}

