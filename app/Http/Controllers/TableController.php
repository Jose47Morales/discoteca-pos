<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Table;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::orderBy('name')->paginate(10);
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:tables,name,'. ($table->id ?? 'NULL') . ',id',
        ],
            'status' => [
                'required',
                'in:disponible,ocupado,reservado',
            ]
        ]);

        Table::create($request->all());
        return redirect()->route('tables.index')->with('success', 'Mesa creada con éxito.');
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:tables,name,'. ($table->id ?? 'NULL') . ',id',
        ],
            'status' => [
                'required',
                'in:disponible,ocupado,reservado',
            ]
        ]);

        $table->update($request->all());
        return redirect()->route('tables.index')->with('success', 'Mesa actualizada con éxito.');
    }

    public function destroy(Table $table)
    {
        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Mesa eliminada con éxito.');
    }
}
