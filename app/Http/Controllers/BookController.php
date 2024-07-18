<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Book;
use App\Models\Category;

class BookController extends Controller
{
    public function index(){
        try{
            $categories = Category::all();
            $books = Book::with('category')->get();
            return view('user.index', compact('books','categories'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    public function search(Request $request){
        try{
            $categories = Category::all();
            $search_text = $request->get('search-text');
            $category_select = $request->get('category-select');

            if($category_select != null){
                $books = Book::whereHas('category', function($q) use($category_select){
                    $q->where('name', $category_select);
                })->get();
            }
            
            if($search_text != null){
                $books = Book::where('title', 'like', '%'.$search_text.'%')
                ->orWhere('author' ,'like', '%'.$search_text.'%')
                ->orWhere('description', 'like', '%'.$search_text.'%')->get();
            }
            
            return view('user.index', compact('books','categories'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    public function bookList(){
        try{
            $books = Book::with('category')->get();
            return view('admin.index', compact('books'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    public function createBookPage(){
        try{
            $categories = Category::all();
            return view('admin.createbook', compact('categories'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    public function create(Request $request){
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'author' => 'required',
                'price' => 'required|numeric',
                'category' => 'required',
                'description' => 'required',
            ],
            [
                'title.required'=> 'Title is required',
                'author.required'=> 'Author is required',
                'price.required'=> 'Price is required',
                'price.numeric'=> 'Price should be numeric',
                'category.required'=> 'Category is required',
                'description.required'=> 'Description is required',
            ]);
        
            

            $inputs = [
                'title' => $request->get('title'),
                'author' => $request->get('author'),
                'price' => $request->get('price'),
                'category' =>$request->get('category-select'),
                'description' => $request->get('description')
            ];

            $category = Category::where('name', $inputs['category'])->first();

            Book::create([
                'title' => $inputs['title'],
                'author' => $inputs['author'],
                'description' => $inputs['description'],
                'price' => $inputs['price'],
                'category_id' => $category->id
            ]);

            return redirect()->route('admin-dashboard');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    public function edit($id){
        try{
            $categories = Category::all();
            $book = Book::where('id', $id)->with('category')->first();
            return view('admin.updatebook', compact('book', 'categories'));
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }

    public function update(Request $request, $id){
        try{
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'author' => 'required',
                'price' => 'required|numeric',
                'category' => 'required',
                'description' => 'required',
            ],
            [
                'title.required'=> 'Title is required',
                'author.required'=> 'Author is required',
                'price.required'=> 'Price is required',
                'price.numeric'=> 'Price should be numeric',
                'category.required'=> 'Category is required',
                'description.required'=> 'Description is required',
            ]);
        
            

            $inputs = [
                'title' => $request->get('title'),
                'author' => $request->get('author'),
                'price' => $request->get('price'),
                'category' =>$request->get('category-select'),
                'description' => $request->get('description')
            ];

            $category = Category::where('name', $inputs['category'])->first();

            $book = Book::find($id);

            $book->title = $inputs['title'];
            $book->author = $inputs['author'];
            $book->description = $inputs['description'];
            $book->price = $inputs['price'];
            $book->category_id = $category->id;
            $book->save();

            return redirect()->route('admin-dashboard');
        }
        catch(\Exception $e){
            return redirect()->back()->with('error', 'Something Went Wrong');
        }
    }
}
