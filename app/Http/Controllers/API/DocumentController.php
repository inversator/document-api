<?php

namespace App\Http\Controllers\API;

use App\Document;
use App\File as FileList;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected $stroragePath = 'public/img/';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Document::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $data = $request->validate([
            'title' => 'required|unique:documents|max:255|min:3|alpha_dash',
            'description' => 'required|max:1000|min:3',
            'file' => 'required|file|mimes:jpeg,jpg,png|max:10000|dimensions:max_width=2500,max_height=2500'
        ]);

        $file = $request->file('file');
        $newFileName = uniqid();
        $ext = $file->getClientOriginalExtension();

        // Save to storage
        $res = $file->storeAs($this->stroragePath, $newFileName . '.' . $ext);

        // Creating entries
        if ($res) {
            $item = Document::create($data);

            FileList::create([
                'filename' => $newFileName . '.' . $ext,
                'path' => $this->stroragePath,
                'size' => $file->getSize(),

                'document_id' => $item->id
            ]);

            return $item;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Document::findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $item = Document::findOrFail($id);

        $rules = [
            'title' => 'max:255|min:3|alpha_dash',
            'description' => 'max:1000|min:3',
            'file' => 'file|mimes:jpeg,jpg,png|max:10000|dimensions:max_width=2500,max_height=2500'
        ];

        if ($request->get('title') != $item->title)
            $rules['title'] .= '|unique:documents';

        $data = $request->validate($rules);

        // If exist new file set it instead of the old
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $newFileName = uniqid();
            $ext = $file->getClientOriginalExtension();

            // Save to storage
            $res = $file->storeAs($this->stroragePath, $newFileName . '.' . $ext);

            if ($res) {
                $oldFile = $item->file;

                if (Storage::delete($this->stroragePath . $oldFile->filename))
                    $oldFile->delete();

                FileList::create([
                    'filename' => $newFileName . '.' . $ext,
                    'path' => $this->stroragePath,
                    'size' => $file->getSize(),

                    'document_id' => $item->id
                ]);
            }
        }

        if ($item->update($data))
            return ['item' => $item, 'res' => 1];
        else
            return ['res' => 0];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Document::findOrFail($id);
        $file = $item->file;

        if ($item->delete())
            Storage::delete('api/img/' . $file->filename);

        return response(null, 204);
    }
}
