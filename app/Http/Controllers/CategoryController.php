<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use DB;
use Image;
use Carbon\Carbon;
use File;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.index', compact('categories'));        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            ['name' => 'required| unique:categories',
              'image' => 'mimes:jpeg,bmp,png'
            ] );
        $data = $request->except('_token', 'image');
        if($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/categories/' . $filename);
            Image::make($image)->save($location);
            $data['image'] = $filename;
        }
        $data['created_at'] = new Carbon();
        $data['updated_at'] = new Carbon();
        $success = Category::Insert($data);
        return redirect()->route('category.index')->with('success','Data Added');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category', 'id'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->hasFile('image')) {
            $this-> validate($request,[
                'name' => 'required| unique:categories,name,'. $id ,
                'image' => 'mimes:jpeg,bmp,png'
            ]);
        } else {
            $this-> validate($request,[
                'name' => 'required| unique:categories,name,'. $id ,
            ]);
        }

        $category = Category::findOrFail($id);
        $category->name = $request->get('name');
        if ($request->image) {
            if ($category->image) {
                $usersImage =  public_path('/images/categories/' . $category->image);
                if (File::exists($usersImage)) { // unlink or remove previous image from folder for modifying another image
                    unlink($usersImage);
                }                
            }
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('/images/categories/' . $filename);
            Image::make($image)->save($location);
            $category->image = $filename;
        }
        $category->updated_at = new Carbon();

        $category->save();
        return redirect()->route('category.index')->with('success','Data Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image) {
            $usersImage = public_path('/images/categories/' . $category->image);
            if (File::exists($usersImage)) { // unlink or remove previous image from folder for modifying another image
                unlink($usersImage);
            }                
        }
        $category = DB::table('categories')->where('id',$id)->delete();
        return back()->with('success','Data Deleted');

    }
}
