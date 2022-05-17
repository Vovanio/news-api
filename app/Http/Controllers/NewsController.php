<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\News;

class NewsController extends Controller
{
    public function UpdateNews(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'title' => 'nullable|string|max:255',
            'text' => 'nullable|string|max:255',
            'img' => 'nullable|image'
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => [
                    'code' => 422,
                    'message' => 'validation error',
                    'errors' => $validator->errors()
                ]
            ],422);
        }

        $fullpathtoimg = null;
        if($request->has('img')){
            $path = $request->file('img')
            ->store('media/images/uploads', 'public');

            $fullpathtoimg = env('APP_DOMAIN_STORAGE') . $path;
        }
        $news = News::find($request->id);
        $news->title = $request->title || null;
        $news->text = $request->text || null;
        $news->img_src = $fullpathtoimg || null;
        $news->save();
    }

}
