<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Others;

use ClassyPOS\Models\Others\Media;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * Get the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function get($id)
    {
        $media = Media::find($id);
        // Get file path
        if (!$path = $this->getPath($media)) {
            return false;
        }
        return response()->file($path);
    }
    /**
     * Download the specified resource.
     *
     * @param  $id
     * @return mixed
     */
    public function download($id)
    {
        $media = Media::find($id);
        // Get file path
        if (!$path = $this->getPath($media)) {
            return false;
        }
        return response()->download($path);
    }
    /**
     * Destroy the specified resource.
     *
     * @param  $id
     * @return callable
     */
    public function destroy($id, Request $request)
    {
        $media = Media::find($id);
        // Get file path
        if (!$path = $this->getPath($media)) {
            $message = trans('messages.warning.deleted', ['name' => $media->basename, 'text' => $media->basename]);
            flash($message)->warning();
            return back();
        }
        $media->delete(); //will not delete files
        File::delete($path);
        if (!empty($request->input('page'))) {
            switch ($request->input('page')) {
                case 'setting':
                    setting()->set($request->input('key'), '');
                    setting()->save();
                    break;
                default;
            }
        }
        return back();
    }
    /**
     * Get the full path of resource.
     *
     * @param  $media
     * @return boolean|string
     */
    protected function getPath($media)
    {
        $path = $media->basename;
        if (!empty($media->directory)) {
            $folders = explode('/', $media->directory);
            // Check if company can access media
            if ($folders[0] != session('company_id')) {
                return false;
            }
            $path = $media->directory . '/' . $media->basename;
        }
        if (!Storage::exists($path)) {
            return false;
        }
        $full_path = Storage::path($path);
        return $full_path;
    }

    public function store(Request $request)
    {
        $file = new Media();
        $media = $file->upload($request->file('Image'));

        return response()->json($media);
    }
}
