@extends('layouts.dashboard')

@section('content')
<div class="row">
    <!-- left column -->
    <div class="col-md-12">
      <!-- general form elements -->
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Categories &nbsp; &nbsp;   <a href="{{route('category.create')}}" class="btn btn-info">Create</a>
       </h3>
        </div>
        <div class="box-body">
        <div class="form-group">
          <div class="col-md-12">
            <!-- Horizontal Form -->

              <!-- /.box-header -->
              <!-- /.row -->
      <!-- <div class="box"> -->
        <div class="box-header">
          <h3 class="box-title">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
          </h3>
        </div>
        <!-- /.box-header -->
        @if(isset($categories))
	        <div class="box-body table-responsive no-padding">
	          <table class="table table-hover">
	            <thead>
	              <th>S.No</th>
	              <th>Name</th>
	              <th>Image</th>
	              <th>Action</th>
	            </thead>
	            <tbody>

	            @foreach ($categories as $k => $category)
		            <tr>
		              <td>{{$k+1}}</td>
		              <td>{{$category->name}}</td>
		              <td>
		                  @if ($category->image) 
		                    <left><img src="{{ asset('images/categories/' . $category->image) }}" height="150" width="200"></left>
		                  @else 
		                    No Image
		                  @endif
		              </td>
		              <td>&nbsp;&nbsp;<a href="{{ route('category.edit', $category->id)}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>&nbsp;&nbsp;<form action="{{route('category.destroy',$category->id)}}" method="post">
		                  <input type="hidden" name="_token" value="{{csrf_token()}}" >
		                  <input type="hidden" name="_method" value="DELETE" >
		                  <button onclick="return confirm('Are you sure want to delete?')" type="submit"><i class="fa fa-trash"></i></button>
		                </form>
		              </td>
		              
		            </tr>
            	@endforeach
            	</tbody>

          	 </table>
        </div>
       
        @else
        	No Results found...
        @endif
     </div>
      <!-- /.box -->
                <!-- /.box-footer -->
              </form>
            </div>
      </div>
    </div>
</div>
</div>
</div>


@endsection