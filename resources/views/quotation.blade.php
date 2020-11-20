@extends('layouts.dashboard')

@section('content')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<style type="text/css">
		#customers td, #customers th {
	  border: 1px solid #ddd;
	  padding: 8px;
	}

	#customers tr:nth-child(even){background-color: #f2f2f2;}

	#customers tr:hover {background-color: #ddd;}

	#customers th {
	  padding-top: 12px;
	  padding-bottom: 12px;
	  text-align: left;
	  background-color: #4CAF50;
	  color: white;
	}

</style>

<form>
  <div class="form-group">
    <label for="category">Select Category</label>
    <select class="form-control" id="category" onchange="select_category()">
      <option value="">Select Category</option>
	  @foreach ($categories as $category)
	      <option value="{{$category['id']}}">{{$category['name']}}</option>
	  @endforeach
    </select>
  </div>

  <div id="showdata"></div>
</form>


<div id="itemModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" id="item_form">
                <div class="modal-header">
                   <h4 class="modal-title"><i class="fas fa-plus"></i>Add Item</h4>
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {{csrf_field()}}
                    <span id="form_output"></span>
                    <div class="form-group">
                        <label>Item Name</label>
                        <input type="text" name="name" id="name" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Price</label>
                        <input type="text" name="price" id="price" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="id" id="id" value="" />
                    <input type="hidden" name="category_id" id="category_id" value="" />
                    <input type="hidden" name="button_action" id="button_action" value="insert" />
                    <input type="submit" name="submit" id="action" value="Add" class="btn btn-info" />
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="emailModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
                <div class="modal-header">
                   <h4 class="modal-title_email">Email</h4>
                   <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <span id="form_output_email"></span>
                    <div class="form-group">
                        <label>Email Address</label>
                        <input type="email" name="email" id="email" class="form-control" required />
                    </div>
                </div>
                <div class="modal-footer">
                	<input type="hidden" name="category_id_email" id="category_id_email" value="" />
                    <a id="action_email" class="btn btn-info" onclick="send_the_mail()" >Send Mail</a>
                    <button type="button" class="btn btn-default" id="close_email" data-dismiss="modal">Close</button>
                </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
	function select_category() {
	  var category_id = $('#category option:selected').val();
	  display_category(category_id);
	}
	function display_category(category_id) {
	  $.ajax({    
	    type:"POST",
	    url: '{{ url("get_items_from_the_category") }}',
	    data: {id : category_id, _token:'{{ csrf_token()}}'},
	    dataType: 'json',
	    success: function(result) {
	        var content = '';
	        content += '<a onclick="add_data('+result.category_id+')" class="btn btn-success"><i class="fas fa-plus"></i> Add items</a> <br> <br>';
	 		content += '<table style="width:100%" id="customers">';
		  	content += '<thead>';
		    content += '<tr>';
		    content += '<th>Item</th><th>Price</th><th>Action</th></tr></thead><tbody>';
		    if (result.items.length == 0) {
		    	content += '<tr><td colspan="3"><center>No items</center></td></tr>';
		    } else {
	          	$.each(result.items, function(k,v) {
	               content += '<tr><td>'+ v.name +'</td><td>' + v.price + '</td><td><a class="btn btn-danger"  onclick="delete_item('+v.id+', ' + result.category_id+ ')" >Delete</td></tr>';
	          	});
		    }
		    content += '<tr><td>Total</td><td>';
		    if (result.items.length) {
		    	content += result.total;
		    }
		    content += '</td><td></td></tr></tbody></table>';
			content += '<br><a link="" onclick="open_mail(' + result.category_id + ', ' + result.items.length +')" class="btn btn-success">SUBMIT</a>';
			if (!category_id) {
				content = '';
			}
	        $('#showdata').html(content);
	    },
	  });

	}
	   $('#item_form').on('submit', function(event) {
        event.preventDefault();
        var form_data = $(this).serialize();
        $.ajax({
            url:"{{ route('add_data') }}",
            method:"POST",
            data:form_data,
            dataType:"json",
            success:function(data)
            {
                if(data.error.length > 0) {
                    var error_html = '';
                    for(var count = 0; count < data.error.length; count++)
                    {
                        error_html += '<div class="alert alert-danger">'+data.error[count]+'</div>';
                    }
                    $('#form_output').html(error_html);
                } else {
                    $('#form_output').html(data.success);
                    $('#item_form')[0].reset();
                    $('#action').val('Add');
                    $('.modal-title').text('Add Data');
                    $('#button_action').val('insert');
                    display_category(data.category_id);
                }
            }
        })
    });


	function add_data(category_id) {
        $('#itemModal').modal('show');
        $('#item_form')[0].reset();
		$('#category_id').val(category_id);
        $('#form_output').html('');
        $('#button_action').val('insert');
        $('#action').val('Add');
        $('.modal-title').text('Add Data');
	}

	function delete_item(item_id, category_id) {
        var id = item_id;
        if(confirm("Are you sure you want to Delete this item?")) {
            $.ajax({
                url:"{{route('delete_item_from_the_category')}}",
                method:"get",
                data:{id:id, category_id: category_id},
                success:function(data)
                {
	                display_category(data.category_id);
                }
            })
        }

	}

	function send_the_mail() {
		var email_value = $('#email').val();
		var category_id = $('#category_id_email').val();
        if (!email_value) {
			alert("Please fill in the email field");
			return false;
		}
		var filter = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
		if (!filter.test(email_value)) {
		    alert('Please provide a valid email address');
		    return false;
		} else {
			send_mail(category_id,email_value);			
	    }
	}

	function open_mail(category_id, items_length) {
		if (items_length == 0) {
			alert('There are no items of this category');
			return false;
		}
		$('#email').val('');
		$('#emailModal').modal('show');
        $('#category_id_email').val(category_id);
        return false;
	}

	function send_mail(category_id,email_value) {
		$.ajax({
	     url:"{{route('send_mail')}}",
	     method:"get",
	     data:{category_id: category_id, email_value : email_value},
	     success:function(data) {
	     	$('#category_id_email').val('');
	     	$('#email').val('');
	     	alert('Mail Sent');
	     	$("#close_email").trigger('click'); 
	     	display_category(data.category_id);
	     }
	 	})
	}

</script>

@endsection