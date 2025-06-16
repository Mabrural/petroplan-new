<?php

namespace App\Http\Controllers;

use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocumentTypeController extends Controller
{
    public function index()
    {
        $documentTypes = DocumentType::with('creator')->latest()->get();
        return view('document_types.index', compact('documentTypes'));
    }

    public function create()
    {
        return view('document_types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
        ]);

        DocumentType::create([
            'document_name' => $request->document_name,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('document-types.index')->with('success', 'Document type created successfully.');
    }

    public function edit(DocumentType $documentType)
    {
        return view('document_types.edit', compact('documentType'));
    }

    public function update(Request $request, DocumentType $documentType)
    {
        $request->validate([
            'document_name' => 'required|string|max:255',
        ]);

        $documentType->update([
            'document_name' => $request->document_name,
        ]);

        return redirect()->route('document-types.index')->with('success', 'Document type updated successfully.');
    }

    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();
        return redirect()->route('document-types.index')->with('success', 'Document type deleted.');
    }
}

