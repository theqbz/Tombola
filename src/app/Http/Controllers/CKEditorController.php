<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class CKEditorController extends Controller
{
    public function upload(Request $request)
    {
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
            $path = public_path('documents/'.Auth::user()->id.'/');
            if(!File::exists($path)) {
                File::makeDirectory($path, 0755, true, true);
            }

            $request->file('upload')->move($path, $fileName);
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('documents/'.Auth::user()->id.'/'.$fileName);
            $msg = __('Image successfully uploaded');
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";

            @header('Content-type: text/html; charset=utf-8');
            echo $response;
        }
    }
}
