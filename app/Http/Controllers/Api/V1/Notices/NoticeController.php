<?php

namespace ClassyPOS\Http\Controllers\Api\V1\Notices;

use ClassyPOS\Models\Notices\Notices;
use Illuminate\Http\Request;
use ClassyPOS\Http\Controllers\Controller;

class NoticeController extends Controller
{
    /**
     * Notices manipulation
     * Create, Update, Find Notices
     * return @void
     *
     * */

    public function listNotice()
    {
        return Notices::paginate(10);
    }

    public function listTrash()
    {
        // view only trashed items
        return Notices::onlyTrashed()->paginate(10);
    }

    public function restoreTrash($NoticeID)
    {
        // Recover soft deleted items back to list
        Notices::withTrashed()->find($NoticeID)->restore();
    }

    public function clearTrash($NoticeID)
    {
        // Permanently Delete
        Notices::withTrashed()->find($NoticeID)->forceDelete();
    }


    public function showNotice(Notices $NoticeID)
    {
        // View specific Notices @param Notices $NoticeID.

        return response()->json($NoticeID, 200);
    }

    public function storeNotice(Request $request)
    {
        $Notice = new Notices();
        $Notice->UserID         = $request->UserID;
        $Notice->ShopID         = $request->ShopID;
        $Notice->ToUserID       = $request->ToUserID;
        $Notice->ShowDate       = $request->ShowDate;
        $Notice->Title          = $request->Title;
        $Notice->Message        = $request->Message;
        $Notice->IsDismissed    = $request->IsDismissed;

        $Notice->save();

        return response()->json($Notice, 201);
    }

    public function updateNotice(Request $request, Notices $NoticeID)
    {
        $NoticeID->update($request->all());

        return response()->json($NoticeID, 200);
    }

    public function destroyNotice(Notices $NoticeID)
    {
        $NoticeID->delete();

        return response()->json(null, 204);
    }
}
