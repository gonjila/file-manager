<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFolderRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FileController extends Controller
{
    public function myFiles(): Response
    {
        $files = File::query()
            ->where('created_by', Auth::id())
            ->whereNotNull('parent_id')
            ->orderByDesc('is_folder')
            ->orderByDesc('created_at')
            ->paginate(10);

        $files = FileResource::collection($files);
        
        return Inertia::render('MyFiles', compact('files'));
    }

    public function createFolder(StoreFolderRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;

        if (!$parent) {
            $parent = $this->getRoot();
        }

        $file = new File();
        $file->is_folder = 1;
        $file->name = $data['name'];

        $parent->appendNode($file);
    }

    private function getRoot()
    {
        return File::query()->whereIsRoot()->where("created_by", Auth::id())->firstOrFail();
    }
}
