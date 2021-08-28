<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog\BlogPostCategory;
use App\Models\Blog\BlogPost;
use Carbon\Carbon;
use Image;

class BlogController extends Controller
{
    public function BlogCategory(){
        $blogcategory = BlogPostCategory::latest()->get();
        return view('backend.blog.category.blog_category_view',compact('blogcategory'));
    }  //end method


    public function BlogCategoryStore(Request $request){
        $request->validate([
    		'blog_category_name_en' => 'required',
            'blog_category_name_hin' => 'required',
            
    		
    	],[
    		'blog_category_name_en.required' => 'Input Blog category English Name',
            'blog_category_name_hin.required' => 'Input Blog category Hindi Name'
    		
    	]);

        BlogPostCategory::insert([
            'blog_category_name_en' => $request->blog_category_name_en,
            'blog_category_name_hin' => $request->blog_category_name_hin,
            'blog_category_slug_en' => strtolower(str_replace(' ', '-',$request->blog_category_name_en)),
            'blog_category_slug_hin' => str_replace(' ', '-',$request->blog_category_name_hin),
            'created_at' => Carbon::now(),
            
        ]);
        $notification = array(
            'message' => 'Blog Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return Redirect()->back()->with($notification);

    } //end method



    public function BlogCategoryEdit($id){
        $blogcategory = BlogPostCategory::findOrFail($id);
        return view('backend.blog.category.blog_category_edit',compact('blogcategory'));
    }



    public function BlogCategoryUpdate(Request $request,$id){
        

        BlogPostCategory::findOrFail($id)->update([
            'blog_category_name_en' => $request->blog_category_name_en,
            'blog_category_name_hin' => $request->blog_category_name_hin,
            'blog_category_slug_en' => strtolower(str_replace(' ', '-',$request->blog_category_name_en)),
            'blog_category_slug_hin' => str_replace(' ', '-',$request->blog_category_name_hin),

        ]);
        $notification = array(
            'message' => 'Blog Category Updated Successfully',
            'alert-type' => 'success'
        );         
        return Redirect()->route('blog-category')->with($notification);

    }


    public function BlogCategoryDelete($id){

        BlogPostCategory::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Blog Category Delete Successfully',
            'alert-type' => 'info'
        );   
        return Redirect()->back()->with($notification);
        
    }




    ///////////////////////////// Blog Post ALL Methods //////////////////

    public function AddBlogPost(){
       
        $blogcategory = BlogPostCategory::latest()->get();
        return view('backend.blog.post.add_post',compact('blogcategory'));


    }//end method




    public function BlogPostStore(Request $request){
        $request->validate([
    		'post_title_en' => 'required',
            'post_title_hin' => 'required',
            'post_image' => 'required',
            
    		
    	],[
    		'post_title_en.required' => 'Input Post Title English Name',
            'post_title_hin.required' => 'Input  Post Title Hindi Name'
    		
    	]);

        $image =  $request->file('post_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        Image::make($image)->resize(780,433)->save('upload/post/'.$name_gen);
        $save_url = 'upload/post/'.$name_gen;


        BlogPost::insert([
            'category_id' => $request->category_id,
            'post_title_en' => $request->post_title_en,
            'post_title_hin' => $request->post_title_hin,
            'post_slug_en' => strtolower(str_replace(' ', '-',$request->post_title_en)),
            'post_slug_hin' => str_replace(' ', '-',$request->post_title_hin),
            'post_image' => $save_url,
            'post_details_en' => $request->post_details_en,
            'post_details_hin' => $request->post_details_hin,
            'created_at' => Carbon::now(),
            
        ]);
        $notification = array(
			'message' => 'Blog Post Inserted Successfully',
			'alert-type' => 'success'
		);
        return redirect()->route('post-list')->with($notification);

    }//end method



    public function BlogPostList(){
       
        $blogpost  = BlogPost::with('category')->latest()->get();
        return view('backend.blog.post.post_list',compact('blogpost'));


    }//end method



    public function BlogPostEdit($id){
        $blogcategory = BlogPostCategory::orderBy('blog_category_name_en','ASC')->get();
        $blogpost = BlogPost::findOrFail($id);
        return view('backend.blog.post.post_edit',compact('blogcategory','blogpost'));
    }



   
    public function BlogPostUpdate(Request $request,$id){ 
        
        $old_image=$request->old_image;

        if($request->file('post_image')){

            unlink($old_image);
            $image =  $request->file('post_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(780,430)->save('upload/post/'.$name_gen);
            $save_url = 'upload/post/'.$name_gen;

            BlogPost::findOrFail($id)->update([
                'category_id' => $request->category_id,
                'post_title_en' => $request->post_title_en,
                'post_title_hin' => $request->post_title_hin,
                'post_slug_en' => strtolower(str_replace(' ', '-',$request->post_title_en)),
                'post_slug_hin' => str_replace(' ', '-',$request->post_title_hin),
                'post_image' => $save_url,
                'post_details_en' => $request->post_details_en,
                'post_details_hin' => $request->post_details_hin,

            ]);
            $notification = array(
                'message' => 'Blog Post Updated Successfully',
                'alert-type' => 'info'
            );         
            return Redirect()->route('post-list')->with($notification);
    
  
        }
        else{

            BlogPost::findOrFail($id)->update([
                'category_id' => $request->category_id,
                'post_title_en' => $request->post_title_en,
                'post_title_hin' => $request->post_title_hin,
                'post_slug_en' => strtolower(str_replace(' ', '-',$request->post_title_en)),
                'post_slug_hin' => str_replace(' ', '-',$request->post_title_hin),
                'post_details_en' => $request->post_details_en,
                'post_details_hin' => $request->post_details_hin,


            ]);
            $notification = array(
                'message' => 'Without Image Post Updated Successfully',
                'alert-type' => 'info'
            );         
            return Redirect()->route('post-list')->with($notification);

        }

       
    } //end method


    public function BlogPostDelete($id){
        
        $post = BlogPost::findOrFail($id);
        $img=$post->post_image;
        unlink($img);

        BlogPost::findOrFail($id)->delete();
        $notification = array(
            'message' => 'Post Delete Successfully',
            'alert-type' => 'error'
        );   
        return Redirect()->back()->with($notification);
        
    }



}
