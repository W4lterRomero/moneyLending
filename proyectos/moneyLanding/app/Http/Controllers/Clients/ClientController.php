<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\ClientDocument;
use App\Services\FilterBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct(private readonly FilterBuilder $filters)
    {
        $this->authorizeResource(Client::class, 'client');
    }

    public function index(Request $request)
    {
        $query = Client::query()->withCount('loans');

        if ($term = $request->get('search')) {
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            });
        }

        if ($request->filled('filters')) {
            $this->filters->apply($query, $request->input('filters', []));
        }

        $clients = $query->latest()->paginate(15)->withQueryString();

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        return view('clients.create');
    }

    public function store(ClientRequest $request)
    {
        $data = $request->validated();
        $data['tags'] = $this->parseTags($request);

        $documentFields = [
            'dui_front' => 'dui_front',
            'dui_back' => 'dui_back',
            'selfie_with_id' => 'selfie_with_id',
            'proof_of_income' => 'proof_of_income',
            'utility_bill' => 'utility_bill',
        ];

        $client = DB::transaction(function () use ($data, $request, $documentFields) {
            $payload = collect($data)->except(array_keys($documentFields))->except(['photo'])->toArray();

            $client = Client::create($payload);

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store("clients/{$client->id}/photo", 'public');
                $client->update(['photo_path' => $path]);
            }

            foreach ($documentFields as $field => $type) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $path = $file->store("clients/{$client->id}/documents", 'public');

                    ClientDocument::create([
                        'client_id' => $client->id,
                        'type' => $type,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => $request->user()?->id,
                    ]);
                }
            }

            return $client;
        });

        return redirect()->route('clients.show', $client)->with('success', 'Cliente creado');
    }

    public function show(Client $client)
    {
        $client->load([
            'loans.payments',
            'documents',
            'references',
        ]);

        return view('clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    public function update(ClientRequest $request, Client $client)
    {
        $data = $request->validated();
        $data['tags'] = $this->parseTags($request, $client->tags);

        $documentFields = [
            'dui_front' => 'dui_front',
            'dui_back' => 'dui_back',
            'selfie_with_id' => 'selfie_with_id',
            'proof_of_income' => 'proof_of_income',
            'utility_bill' => 'utility_bill',
        ];

        DB::transaction(function () use ($client, $data, $request, $documentFields) {
            $payload = collect($data)->except(array_keys($documentFields))->except(['photo'])->toArray();
            $client->update($payload);

            if ($request->hasFile('photo')) {
                if ($client->photo_path) {
                    Storage::disk('public')->delete($client->photo_path);
                }
                $path = $request->file('photo')->store("clients/{$client->id}/photo", 'public');
                $client->update(['photo_path' => $path]);
            }

            foreach ($documentFields as $field => $type) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $path = $file->store("clients/{$client->id}/documents", 'public');

                    $old = $client->documents()->where('type', $type)->first();
                    if ($old) {
                        Storage::disk('public')->delete($old->file_path);
                        $old->delete();
                    }

                    ClientDocument::create([
                        'client_id' => $client->id,
                        'type' => $type,
                        'file_path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => $request->user()?->id,
                    ]);
                }
            }
        });

        return redirect()->route('clients.show', $client)->with('success', 'Cliente actualizado');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado');
    }

    public function archive(Client $client)
    {
        $client->update(['archived_at' => now()]);

        return back()->with('success', 'Cliente archivado');
    }

    public function restore(Client $client)
    {
        $client->update(['archived_at' => null]);

        return back()->with('success', 'Cliente restaurado');
    }

    public function destroyDocument(Client $client, ClientDocument $document)
    {
        $this->authorize('update', $client);
        if ($document->client_id !== $client->id) {
            abort(404);
        }

        if ($document->file_path) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return back()->with('success', 'Documento eliminado');
    }

    protected function parseTags(Request $request, $current = null): array
    {
        if ($request->filled('tags')) {
            $tags = $request->input('tags');
            if (is_string($tags)) {
                return collect(explode(',', $tags))
                    ->map(fn ($t) => trim($t))
                    ->filter()
                    ->values()
                    ->toArray();
            }
            return (array) $tags;
        }

        return $current ?? [];
    }
}
