@extends('layouts.dashboard')

@section('content')
<style type="text/css">
	.error {
		color : #FF0000;
	}
</style>
<h4>Categories</h4>
        <div class="box box-primary">
            <form role="form" action="{{ route('category.store') }}"  method="post" enctype="multipart/form-data">
                <div class="box-body">                
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{old('name')}}" class="" required>
                        <?php echo ($errors->first('name',"<li class='error'> :message</li>"));?>

                    </div>

                    <div class="form-group">
                        <label for="image">Image</label>
                        <input type="file" name="image" id="image" class="" required>
                    </div>
                    <?php echo ($errors->first('image',"<li class='error'> :message</li>"));?>

                {{ csrf_field() }}
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"  id="btn-submit" >Add</button>
                    <a href="" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>

@endsection