<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Product;
use App\Models\Slider;
use App\Models\MultiImg;
use App\Models\Brand;
use App\Models\OrderItem;
use App\Models\Order;
use App\Models\Blog\BlogPost;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Auth; 

 
class IndexController extends Controller 
{
    public function index(){

        $products = Product::where('status',1)->orderBy('id','DESC')->limit(8)->get();
        $sliders = Slider::where('status',1)->orderBy('id','DESC')->limit(3)->get();
        $categories = Category::orderBy('category_name_en','ASC')->get();
        $featured = Product::where('featured',1)->orderBy('id','DESC')->limit(6)->get();
        $hot_deals = Product::where('hot_deals',1)->where('discount_price','!=',NULL)->orderBy('id','DESC')->limit(3)->get();
        $special_offer = Product::where('special_offer',1)->orderBy('id','DESC')->limit(3)->get();
        $special_deals = Product::where('special_deals',1)->orderBy('id','DESC')->limit(3)->get();

        $skip_category_0 = Category::skip(0)->first();   //this is 1 no category
        $skip_product_0 = Product::where('status',1)->where('category_id',$skip_category_0->id)->orderBy('id','DESC')->get();

        $skip_category_1 = Category::skip(1)->first();   //this is 2 no category
        $skip_product_1 = Product::where('status',1)->where('category_id',$skip_category_1->id)->orderBy('id','DESC')->get();

        $skip_brand_1 = Brand::skip(1)->first();   //this is 2 no brand
        $skip_brand_product_1  = Product::where('status',1)->where('brand_id',$skip_brand_1->id)->orderBy('id','DESC')->get();
        //return $skip_category->id;
        //return $skip_category->id;
        //die();
 
        $blogpost  = BlogPost::latest()->get();
        
        //best selling
        $items = DB::table('order_items')->select('product_id',DB::raw('COUNT(product_id) as count'))->groupBy('product_id')->orderBy("count",'desc')->get();
        $product_ids =[];
        foreach($items as $item){
            array_push($product_ids,$item->product_id);
        }
        //return $product_ids; 
        $best_selling = Product::whereIn('id',$product_ids)->get();
        //return $best_selling;



        //top rated product
        $items_rated = DB::table('reviews')->select('product_id',DB::raw('AVG(rating) as count'))->groupBy('product_id')->orderBy("count",'desc')->get();
        $product_ids =[];
        foreach($items_rated as $item){
            array_push($product_ids,$item->product_id);
        }
        //return $product_ids;
        $idsImploded = implode(',',array_fill(0,count($product_ids),'?'));
        $best_rated = Product::whereIn('id',$product_ids)->orderByRaw("field(id,{$idsImploded})",$product_ids)->get();
        //return $best_rated;


        return view('frontend.index',compact('categories','sliders','products','featured','hot_deals',
        'special_offer','special_deals','skip_category_0','skip_product_0',
        'skip_category_1','skip_product_1','skip_brand_1','skip_brand_product_1','blogpost','best_selling','best_rated'));
    }

    public function UserLogout(){
        Auth::logout();
        return redirect()->route('login');  
    }

    public function UserProfile(){
        $id=Auth::user()->id;
        $user=User::find($id);
        return view('frontend.profile.user_profile',compact('user'));
    }

    public function UserProfileStore(Request $request){

        $data = User::find(Auth::user()->id);
        $data->name = $request->name;
        $data->email = $request->email;
        $data->phone = $request->phone;

        if($request->file('profile_photo_path')){
            $file=$request->file('profile_photo_path');
            @unlink(public_path('upload/user_images/'.$data->profile_photo_path));
            $filename=date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/user_images'),$filename);
            $data['profile_photo_path']=$filename;
        }
        $data->save();
        $notification = array(
			'message' => 'User Profile Updated Successfully',
			'alert-type' => 'success'
		);
        return redirect()->route('dashboard')->with($notification);
        
    }


    public function UserChangePassword(){
        $id=Auth::user()->id;
        $user=User::find($id);
        return view('frontend.profile.change_password',compact('user'));
    }

    public function UserPasswordUpdate(Request $request){
        $validateData = $request->validate([
			'oldpassword' => 'required',
			'password' => 'required|confirmed',
		]);

        $hashedPassword=Auth::user()->password;
        if(Hash::check($request->oldpassword,$hashedPassword)){
            $user = User::find(Auth::id());
            $user->password = Hash::make($request->password);
            $user->save();
            Auth::logout();
            return redirect()->route('user.logout');
        }
        else{
            return redirect()->back();
        }
    }


    
    public function ProductDetails($id,$slug){
       
        $product = Product::findOrFail($id);

        $color_en = $product->product_color_en;
        $product_color_en = explode(',',$color_en);

        $color_hin = $product->product_color_hin;
        $product_color_hin = explode(',',$color_hin);

        $size_en = $product->product_size_en;
        $product_size_en = explode(',',$size_en);

        $size_hin = $product->product_size_hin;
        $product_size_hin = explode(',',$size_hin);

        $mulitImg = MultiImg::where('product_id',$id)->get(); 

        $cat_id = $product->category_id;
        $reletedProduct = Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->get();

        return view('frontend.product.product_details',compact('product','mulitImg','product_color_en','product_color_hin',
        'product_size_en','product_size_hin','reletedProduct'));
    }


    public function TagWiseProduct($tag){
        $products = Product::where('status',1)->where('product_tags_en',$tag)->where('product_tags_hin',$tag)->orderBy('id','DESC')->paginate(3);
        $categories = Category::orderBy('category_name_en','ASC')->get();
        return view('frontend.tags.tags_view',compact('products','categories'));
    }

    
    
  // Subcategory wise product data

    public function SubCatWiseProduct(Request $request,$subcat_id,$slug){
        $products = Product::where('status',1)->where('subcategory_id',$subcat_id)->orderBy('id','DESC')->paginate(3);
        $categories = Category::orderBy('category_name_en','ASC')->get();
        $breadsubcat = SubCategory::with(['category'])->where('id',$subcat_id)->get();

        
        ///  Load More Product with Ajax
        if ($request->ajax()) {
            $grid_view = view('frontend.product.grid_view_product',compact('products'))->render();
         
            $list_view = view('frontend.product.list_view_product',compact('products'))->render();
             return response()->json(['grid_view' => $grid_view,'list_view' => $list_view]);	
         
         }
         ///  End Load More Product with Ajax 
        return view('frontend.product.subcategory_view',compact('products','categories','breadsubcat'));
    }


    
    // Sub-Subcategory wise product data

    public function SubSubCatWiseProduct($subsubcat_id,$slug){
        $products = Product::where('status',1)->where('subsubcategory_id',$subsubcat_id)->orderBy('id','DESC')->paginate(3);
        $categories = Category::orderBy('category_name_en','ASC')->get();
        $breadsubsubcat = SubSubCategory::with(['category','subcategory'])->where('id',$subsubcat_id)->get();

        return view('frontend.product.sub_subcategory_view',compact('products','categories','breadsubsubcat'));
    }


    // product view with ajax
    public function ProductViewAjax($id){

        $product = Product::with('category','brand')->findOrFail($id);

        $color = $product->product_color_en;
        $product_color = explode(',',$color);

        
        $size = $product->product_size_en;
        $product_size = explode(',',$size);

        return response()->json(array(
            'product' => $product,
            'color' => $product_color,
            'size' => $product_size,
        ));

    } //end method



    // product search 
    
    public function ProductSearch(Request $request){
        $request->validate(["search" => "required"]);
        $item = $request->search;
		//echo "$item";
        $categories = Category::orderBy('category_name_en','ASC')->get();
        $products = Product::where('product_name_en','LIKE',"%$item%")->get();
        return view('frontend.product.search',compact('products','categories'));

    }//end method 



    public function SearchProduct(Request $request){
        
        $request->validate(["search" => "required"]);

		$item = $request->search;		 
        
		$products = Product::where('product_name_en','LIKE',"%$item%")->select('product_name_en','product_thambnail','selling_price','id','product_slug_en')->limit(5)->get();
		return view('frontend.product.search_product',compact('products'));

	} // end method 





}
 