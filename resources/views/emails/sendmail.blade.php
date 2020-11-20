<!DOCTYPE html>
<html>
<head>
    <title>ItsolutionStuff.com</title>
</head>
<body>
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


    <h1>{{ $title }}</h1><br>
    <h2>Category : {{$category->name}}</h2>
    <p>
    	<img src="{{ public_path('images/ecommerce.png') }}" style="width: 200px; height: 200px">
    	
		   <table style="width:100%" id="customers">
		  	<thead>
		    <tr>
		    <th>Item</th><th>Price</th></tr></thead><tbody>
			    @if(count($items) == 0)
			    	<tr><td colspan="2"><center>No items</center></td></tr>
			    @endif		    
		        
	        @foreach($items as $item)
                <tr><td>{{$item->name}}</td><td>{{$item->price}}</td></tr>
	        @endforeach 
		    <tr><td>Total</td><td>
		    @if(count($items) == 0)
		    	0.00
		    @else
		    	{{$total}}
		    @endif
		    </td></tr></tbody></table>
    </p>
     
</body>
</html>