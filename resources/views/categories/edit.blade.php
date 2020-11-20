@extends('layouts.dashboard')

@section('content')
<style type="text/css">
    .error {
        color : #FF0000;
    }
</style>

<h4>Categories</h4>
        <div class="box box-primary">
            <form role="form" action="{{ route('category.update',$category->id) }}"  method="post" enctype="multipart/form-data">
                <div class="box-body">                
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" value="{{$category->name}}" class="" required>
                        <?php echo ($errors->first('name',"<li class='error'> :message</li>"));?>
                    </div>
                    <div class="form-group" id="image">
                        <label for="image">Image</label>
                        <br>
                        <div id="imagepart">
                            <left><img src="{{ asset('images/categories/' . $category->image) }}" height="150" width="200"><a href="#" onclick="delete_image()">  <img src="{{asset('images/delete.png') }}" border="0"/></a></left>
                        </div>
                        <div id="theimagepart" style="display:none;">
                            <input type="file" name="image">
                        </div>
                    </div>
                {{ csrf_field() }}
                {{ method_field('PATCH') }}
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary"  id="btn-submit">Update</button>
                    <a href="" class="btn btn-danger">Cancel</a>
                </div>
            </form>
        </div>


        <script type="text/javascript">
          $("#theimagepart").css('display:block');
          function delete_image() {
              $("#imagepart").html("");
              $("#theimagepart").show();
            }
        </script>


@endsection