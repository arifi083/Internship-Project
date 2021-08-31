@extends('admin.admin_master')
@section('admin')

<!-- Content Wrapper. Contains page content  -->
<div class="container-full">
		
	<!-- Main content -->
	<section class="content">
	   <div class="row">	  
		  <div class="col-12">

			<div class="box">
			   <div class="box-header with-border">
				  <h3 class="box-title">Total Admin User</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
				   <div class="table-responsive">
					 <table id="example1" class="table table-bordered table-striped">
					    <thead>
						   <tr>
							  <th>Image</th>
							  <th>Name</th>
							  <th>Email</th>
                              <th>Access </th>
							  <th>Action</th>	
						   </tr>
						</thead>
<tbody>

	@foreach($adminuser as $item)
	<tr>
	<td> <img src="{{ asset($item->profile_photo_path) }}"></td>
	<td>{{ $item->name }}</td>
    <td>${{ $item->email  }}</td>
    
    <td></td>
    <td></td>
	
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






		  </div>
		  <!-- /.row -->
		</section>
		<!-- /.content -->
	  
	  </div>
 
  <!-- /.content-wrapper -->





@endsection