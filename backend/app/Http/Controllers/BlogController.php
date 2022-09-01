<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class BlogController extends Controller
{
    public function index()
    {
        //return response()->json(Blog::latest()->get());
        $blogs = Blog::all();
        return response()->json([
            'status'=> 200,
            'bloglists'=>$blogs,
        ]);
    }

    public function store(Request $request)
    { 
        //$img_tmp =$request->file('image')->store('blog');
        $data = $request->all();
        $validator = Validator::make($request->all(),[
            'title'=>'required|max:191',
            'description'=>'required',
            'image'=>'required|image|mimes:jpeg,jpg,png'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'status'=> 422,
                'validate_err'=> $validator->messages(),
            ]);
        }
        else
        {
            $blog = new Blog;
            $blog->title = $request->title;
            $blog->description = $request->description;
            
         if($request->hasFile('image')){
            $img_tmp =$request->file('image');
            $filename =time() . '.' .$img_tmp->getClientOriginalExtension();
            //$path = $img_tmp->store('public');
            $location=('blog/' .$filename);
            //$img_tmp->move($location);
            $request->image->move(public_path('blog'), $filename);
            //$request->file->move(public_path('blog/'). $filename);
            $blog->image = $location ;
         }

            $blog->save();

            return response()->json([
                'status'=> 200,
                'blog'=>$blog,
                'message'=>'Blog Added Successfully',
            ]);
        }

    }

    public function edit($id)
    {
        $blog = Blog::find($id);
        if($blog)
        {
            return response()->json([
                'status'=> 200,
                'blog' => $blog,
            ]);
        }
        else
        {
            return response()->json([
                'status'=> 404,
                'message' => 'blog notFound',
            ]);
        }

    }


    
}
