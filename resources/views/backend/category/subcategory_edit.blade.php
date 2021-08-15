@extends('admin.admin_master')
@section('admin')

<!-- Content Wrapper. Contains page content  -->
<div class="container-full">
		
	<!-- Main content -->
	<section class="content">
	   <div class="row">	  
		 


<!--  -----------------Add Category page -----------------------   -->
           <div class="col-12">

            <div class="box">
               <div class="box-header with-border">
	            <h3 class="box-title">Edit SubCategory</h3> 
	        </div>
	       <!-- /.box-header -->
	          <div class="box-body">
	            <div class="table-responsive">
		             
    <form method="POST" action="{{ route('subcategory.update',$subcategory->id) }}" >
        @csrf
        
        <input type="hidden" name="id" value="{{ $subcategory->id }}">
        
        <div class="form-group">
			<h5>Category Select <span class="text-danger">*</span></h5>
			<div class="controls">
			  <select name="category_id" id=""  class="form-control">

				 <option value="" selected="" disabled="">Select Category</option>

                  @foreach($categories as $category)
			     <option value="{{ $category->id }}" {{ $category->id == $subcategory->category_id ? 'selected': ''}}> {{ $category->category_name_en }} </option>
				 @endforeach

			 </select>

             @error('category_id') 
	           <span class="text-danger">{{ $message }}</span>
	         @enderror 

		    </div>
		</div>



        <div class="form-group">
            <h5>SubCategory English <span class="text-danger">*</span></h5>
            <div class="controls">
                <input type="text" name="subcategory_name_en" value="{{ $subcategory->subcategory_name_en }}"  class="form-control" > 
                @error('subcategory_name_en') 
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            
            </div>
        </div>


        <div class="form-group">
            <h5> SubCategory Hindi <span class="text-danger">*</span></h5>
            <div class="controls">
                <input type="text"  name="subcategory_name_hin" class="form-control" value="{{ $subcategory->subcategory_name_hin }}"> 
                @error('subcategory_name_hin') 
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            
                </div>
        </div>
        
        <div class="text-xs-right">
            <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Update">					 
        </div>


    </form>  






		       </div>
	     </div>
 <!-- /.box-body -->
  </div>
  <!-- /.box -->

  
  <!-- /.box -->          
</div>
<!-- /.col -->







		  </div>
		  <!-- /.row -->
		</section>
		<!-- /.content -->
	  
	  </div>
 
  <!-- /.content-wrapper -->





@endsection