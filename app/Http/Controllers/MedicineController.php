<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $medicines = Medicine::all();
        return view('medicine.index', compact('medicines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return view('medicine.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
        ]);
        Medicine::create([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
            'stock' => $request->stock,
        ]);
        // atau jika seluruh data input akan dimasukkan langsung ke db bisa dengan perintah Medicine::create($request->all());
        return redirect()->back()->with('success', 'Berhasil menambahkan data obat!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicine $medicine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $medicine = Medicine::find($id);
        return view('medicine.edit', compact('medicine'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'name' => 'required|min:3',
            'type' => 'required',
            'price' => 'required|numeric',
        ]);

        Medicine::where('id', $id)->update([
            'name' => $request->name,
            'type' => $request->type,
            'price' => $request->price,
        ]);

        return redirect()->route('medicine.home')->with('success', 'Berhasil Mengubah Data!');
    }

    public function destroy($id)
    {
        //
        Medicine::where('id', $id)->delete();

        return redirect()->back()->with('deleted', 'Berhasil menghapus data');
    }

    public function stock()
    {
        $medicine = Medicine::orderBy('stock', 'ASC')->get();

        return view('medicine.stock', compact('medicine'));
    }

    public function stockEdit($id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404); // Handle the case if medicine not found
        }

        return response()->json($medicine); // Return the medicine data as JSON
    }

    public function stockUpdate(Request $request, $id)
    {
        $request->validate([
            'stock' => 'required|numeric', 
        ]);

        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json(['message' => 'Medicine not found'], 404);
        }
        if ($request->stock <= $medicine->stock) {
            return response()->json(["message" => "Stock yang diinput tidak boleh kurang dari stock sebelumnya"], 400); // Return error if stock is less than or equal to the current stock
        }
        $medicine->update([
            'stock' => $request->stock,
        ]);

        return response()->json(['message' => 'Stock updated successfully'], 200); // Return success message
    }
}
