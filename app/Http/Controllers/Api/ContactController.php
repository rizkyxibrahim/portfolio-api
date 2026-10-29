<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ContactResource;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::create($validated);

        return response()->json([
            'message' => 'Pesan Anda telah terkirim. Kami akan menanggapi segera.',
            'data' => new ContactResource($contact),
        ], 201);
    }

    public function indexAdmin(Request $request)
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(15);

        return ContactResource::collection($contacts);
    }

    public function showAdmin($id)
    {
        $contact = Contact::findOrFail($id);

        return new ContactResource($contact);
    }

    public function markAsRead(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['is_read' => true]);

        return response()->json([
            'message' => 'Pesan ditandai telah dibaca.',
            'data' => new ContactResource($contact->fresh()),
        ]);
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json([
            'message' => 'Pesan berhasil dihapus.',
        ]);
    }
}