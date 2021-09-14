<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\BlogPostCategory;
use App\Models\Blog\BlogPost;
use App\Models\Blog\BlogComment;
use Carbon\Carbon;

class HomeBlogController extends Controller 
{
    public function AddBlogPost(){
        
        $blogcategory = BlogPostCategory::latest()->get();
        $blogpost  = BlogPost::latest()->get();
        return view('frontend.blog.blog_list',compact('blogpost','blogcategory'));
    }


    public function DetailsBlogPost($id){
        
        $blogcategory = BlogPostCategory::latest()->get();
        $blogpost  = BlogPost::findOrFail($id);
        return view('frontend.blog.blog_details',compact('blogpost','blogcategory'));
    }


    public function HomeBlogCatPost($category_id){
        
        $blogcategory = BlogPostCategory::latest()->get();
        $blogpost  = BlogPost::where('category_id',$category_id)->orderBy('id','DESC')->get();
        return view('frontend.blog.blog_cat_list',compact('blogpost','blogcategory'));
    }//end method




    //blog user comment
    public function BlogCommentStore(Request $request){

        $request->validate([
    		'name' => 'required',
            'email' => 'required',
    		
    	]); 
        BlogComment::insert([
            'name' => $request->name,
            'email' => $request->email,
            'title' => $request->title,
            'comment' => $request->comment,  
            'created_at' => Carbon::now(),
            
        ]);
        $notification = array(
            'message' => 'Blog Comment Inserted Successfully',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);
    }


}
