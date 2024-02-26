<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFileRequest;
use App\Http\Requests\StoreFolderRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class FileController extends Controller
{
    public function myFiles(string $folder = null): Response
    {
        if ($folder) {
            $folder = File:: query()
                ->where('created_by', Auth:: id())
                ->where('path', $folder)
                ->firstOrFail();
        }

        if (!$folder) {
            $folder = $this->getRoot();
        }

        $files = File::query()
            ->where('parent_Id', $folder->id)
            ->where('created_by', Auth::id())
            ->whereNotNull('parent_id')
            ->orderByDesc('is_folder')
            ->orderByDesc('created_at')
            ->paginate(10);

        $files = FileResource::collection($files);
        $ancestors = FileResource::collection([...$folder->ancestors, $folder]);
        $folder = new FileResource($folder);

        return Inertia::render('MyFiles', compact('files', 'folder', 'ancestors'));
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

    public function store(StoreFileRequest $request)
    {
        $data = $request->validated();
        $parent = $request->parent;
        $user = $request->user();
        $fileTree = $request->file_tree;


        if (!$parent) {
            $parent = $this->getRoot();
        }

        if (!empty($fileTree)) {
            $this->saveFileTree($fileTree, $parent, $user);
        } else {
            foreach ($data['files'] as $file) {
                /** @var UploadedFile $file */

                $this->saveFile($file, $user, $parent);
            }
        }
    }

    private function getRoot()
    {
        return File::query()->whereIsRoot()->where("created_by", Auth::id())->firstOrFail();
    }

    public function saveFileTree($fileTree, $parent, $user)
    {
        foreach ($fileTree as $name => $file) {
            if (is_array($file)) {
                $folder = new File();
                $folder->is_folder = 1;
                $folder->name = $name;

                $parent->appendNode($folder);
                $this->saveFileTree($file, $folder, $user);
            } else {

                $this->saveFile($file, $user, $parent);
            }
        }
    }

    /**
     * @param UploadedFile $file
     * @param mixed $user
     * @param File|null $parent
     * @return void
     */
    public function saveFile(UploadedFile $file, mixed $user, ?File $parent): void
    {
        $path = $file->store('/files/' . $user->id);

        $model = new File();
        $model->storage_path = $path;
        $model->is_folder = false;
        $model->name = $file->getClientOriginalName();
        $model->mime = $file->getMimeType();
        $model->size = $file->getSize();
        $parent->appendNode($model);
    }
}
