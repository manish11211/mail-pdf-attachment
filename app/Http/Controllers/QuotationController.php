<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use App\Item;
use DB;
use Validator;
use PDF;
use Mail;

class QuotationController extends Controller
{
    public function index()
    {
    	$categories = Category::all()->toArray();
    	return view('quotation', compact('categories'));
    }

    public function get_items_from_the_category(Request $request)
    {
    	$items = DB::table('items')->where('category_id', $request->id)->get();
    	$total = DB::table('items')->where('category_id', $request->id)->get()->sum("price");
    	return response()->json(['items' => $items, 'category_id' => $request->id, 'total' => $total]);
    }

    public function delete_item_from_the_category(Request $request)
    {
    	$items = DB::table('items')->where('id', $request->id)->delete();
    	return response()->json(['items' => $items, 'category_id' => $request->category_id]);
    }

    public function send_mail(Request $request) 
    {
    	$data["email"] = $request->email_value;
    	$data["title"] = "Quotation";
        $data["category"] = DB::table('categories')->where('id', $request->category_id)->first();
    	$data["items"] = DB::table('items')->where('category_id', $request->category_id)->get();
    	$data["total"] = DB::table('items')->where('category_id', $request->category_id)->get()->sum("price");
        $pdf = PDF::loadView('emails.sendmail', $data);

        Mail::send('emails.sendmail', $data, function($message)use($data, $pdf) {
            $message->to($data["email"], $data["email"])
                    ->subject($data["title"])
                    ->attachData($pdf->output(), "file.pdf");
        });
        return response()->json(['success' => true, 'category_id' => $request->category_id]);
    }

    public function add_items_from_ajax(Request $request) 
    {
        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'price'  => 'required|numeric',
        ]);
        $error_array = array();
        $success_output = '';
        if ($validation->fails()) {
            foreach ($validation->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages; 
            }
        } else {
            if ($request->get('button_action') == 'insert') {
                $item = new Item([
                    'name'    =>  $request->get('name'),
                    'price'     =>  $request->get('price'),
                    'category_id'     =>  $request->get('category_id')
                ]);
                $item->save();
                $success_output = '<div class="alert alert-success">Item Inserted</div>';
            }

            if($request->get('button_action') == 'update') {
                $item = Item::find($request->get('item_id'));
                $item->name = $request->get('name');
                $item->price = $request->get('price');
                $item->save();
                $success_output = '<div class="alert alert-success">Item Updated</div>';
            }
            
        }        
        $output = array(
            'error'     =>  $error_array,
            'success'   =>  $success_output,
            'category_id'   =>  $request->get('category_id')
        );
        echo json_encode($output);
    }


}
