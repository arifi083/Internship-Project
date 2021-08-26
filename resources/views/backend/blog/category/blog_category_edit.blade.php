@extends('admin.admin_master')
@section('admin')

<!-- Content Wrapper. Contains page content -->
<div class="container-full">
		
	<!-- Main content -->
	<section class="content">
	   <div class="row">	  
		 

<!--  -----------------edit category page -----------------------  -->
           <div class="col-12">

            <div class="box">
               <div class="box-header with-border">
	            <h3 class="box-title">Edit Blog Category</h3>
	        </div>
	       <!-- /.box-header -->
	          <div class="box-body">
	            <div class="table-responsive">
		             
				    <form method="POST" action="{{ route('blogcategory.update',$blogcategory->id) }}">
					    @csrf
                        
                        
                        <div class="form-group">
		                    <h5>Blog Category English<span class="text-danger">*</span></h5>
		                    <div class="controls">
	                           <input type="text"  name="blog_category_name_en" value="{{ $blogcategory->blog_category_name_en }}" class="form-control" > 
							   @error('blog_category_name_en') 
	                               <span class="text-danger">{{ $message }}</span>
	                            @enderror
	                        </div>
	                    </div>


                        <div class="form-group">
		                    <h5>Blog Category Hindi<span class="text-danger">*</span></h5>
		                    <div class="controls">
	                           <input type="text" name="blog_category_name_hin" value="{{ $blogcategory->blog_category_name_hin }}" class="form-control" > 
							   @error('blog_category_name_hin') 
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