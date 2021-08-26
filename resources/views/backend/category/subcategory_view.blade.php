@extends('admin.admin_master')
@section('admin')

<!-- Content Wrapper. Contains page content -->
<div class="container-full">
		
	<!-- Main content -->
	<section class="content">
	   <div class="row">	  
		  <div class="col-8">

			<div class="box">
			   <div class="box-header with-border">
				  <h3 class="box-title">SubCategory List <span class="badge badge-pill badge-danger"> {{ count($subcategory) }} </span></h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				   <div class="table-responsive">
					 <table id="example1" class="table table-bordered table-striped">
					    <thead>
						   <tr>
							  <th width="15%">Category </th>
							  <th width="20%">SubCategory En</th>
							  <th width="20%">SubCategory Hin</th>
							  <th width="20%">Action</th>	
						   </tr>
						</thead>
<tbody>

	@foreach($subcategory as $item)
	<tr>
	 <td>{{ $item['category']['category_name_en'] }}</td>
	<td>{{ $item->subcategory_name_en  }}</td>
	<td>{{ $item->subcategory_name_hin  }}</td>
	<td>
		<a href="{{ route('subcategory.edit',$item->id) }}" class="btn btn-info" title="Edit Data"><i class="fa fa-pencil"></i> </a>
		<a href="{{ route('subcategory.delete',$item->id) }}" class="btn btn-danger" title="Delete Data" id="delete"><i class="fa fa-trash"></i></a>

	</td>	
	</tr>
	
    @endforeach
</tbody>
						
					  </table>
					</div>
				</div>
				<!-- /.box-body -->
			  </div>
			  <!-- /.box -->

			  
			  <!-- /.box -->          
			</div>
			<!-- /.col -->

<!--  -----------------Add Category page -----------------------  -->
           <div class="col-4">

            <div class="box">
               <div class="box-header with-border">
	            <h3 class="box-title">Add SubCategory</h3> 
	        </div>
	       <!-- /.box-header -->
	          <div class="box-body">
	            <div class="table-responsive">
		             
    <form method="POST" action="{{ route('subcategory.store') }}" >
        @csrf
        
        <div class="form-group">
			<h5>Category Select <span class="text-danger">*</span></h5>
			<div class="controls">
			  <select name="category_id" id=""  class="form-control">

				 <option value="" selected="" disabled="">Select Category</option>

                  @foreach($categories as $category)
			     <option value="{{ $category->id }}"> {{ $category->category_name_en }} </option>
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
                <input type="text" name="subcategory_name_en" class="form-control" > 
                @error('subcategory_name_en') 
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            
            </div>
        </div>


        <div class="form-group">
            <h5> SubCategory Hindi <span class="text-danger">*</span></h5>
            <div class="controls">
                <input type="text"  name="subcategory_name_hin" class="form-control" > 
                @error('subcategory_name_hin') 
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            
                </div>
        </div>
        
        <div class="text-xs-right">
            <input type="submit" class="btn btn-rounded btn-primary mb-5" value="Add New">					 
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