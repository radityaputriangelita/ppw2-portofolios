<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;


class GalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();
        return view('auth.gallery', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(){
        return view('auth.create');
    }

    /**
     * Store a newly created resource in storage.
     */

     public function store(Request $request)
     {
         $request->validate([
             'title' => 'required|max:255',
             'description' => 'required',
             'picture' => 'image|nullable|max:1999',
             'link' => 'required|string'
         ]);
     
         $post = new Post;
         $post->title = $request->input('title');
         $post->description = $request->input('description');
         $post->link = $request->input('link');
     
         if ($request->hasFile('picture')) {
            $image = $request->file('picture');
     
            $filenameWithExt = $image->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;

            $folderPath = public_path('storage/original');
            if (!File::isDirectory($folderPath)) {
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $resize = public_path('storage/resize');
            if (!File::isDirectory($resize)) {
                File::makeDirectory($resize, 0777, true, true);
            }
            
            
     
            $path = $request->file('picture')->storeAs( 'original/', $filenameSimpan);
     
            if ($request->input('size') === 'thumbnail') {
                $imageResized = Image::make($image)
                    ->fit(375, 235)
                    ->save(public_path('storage/resize/' . $filenameSimpan), 80);
            }
     
            $post->picture = $filenameSimpan;
         } else {
             $post->picture = 'noimage.png';
         }
     
         $post->save();
     
        //  return redirect('Porto')->with('success', 'Berhasil menambahkan data baru');
        return redirect()->route('Porto')->with('success', 'Data telah berhasil disimpan');
     }
     
     



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $post = Post::find($id); 
        return view('auth.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:255',
            'description' => 'required',
            'picture' => 'image|nullable|max:1999',
            'link' => 'required|string'
        ]);
    
        $post = Post::find($id);
    
        if (!$post) {
            return redirect()->route('pageporto')->with('error', 'Data not found');
        }
    
        if ($request->hasFile('picture')) {
            $image = $request->file('picture');
     
            $filenameWithExt = $image->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $image->getClientOriginalExtension();
            $filenameSimpan = $filename . '_' . time() . '.' . $extension;
    
            $path = $request->file('picture')->storeAs( 'original/', $filenameSimpan);
     
            if ($request->input('size') === 'thumbnail') {
                $imageResized = Image::make($image)
                    ->fit(375, 235)
                    ->save(public_path('storage/resize/' . $filenameSimpan), 80);
            }
            // Hapus gambar lama jika perlu
            if ($post->picture !== 'noimage.png') {
                $oldImagePath = public_path('storage/posts_image/' . $post->picture);
                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }
    
            // Perbarui nama gambar dalam database
            $post->picture = $filenameSimpan;
        }else {
            // Proses jika tidak ada gambar yang diunggah baru, namun ingin meresize gambar yang sudah ada
            if ($request->input('size') === 'thumbnail') {
                $oldImagePath = public_path('storage/original/' . $post->picture);
        
                $image = Image::make($oldImagePath)
                    ->fit(375, 235)
                    ->save(public_path('storage/resize/' . $post->picture), 80);
            }
        }
    
        // Update data post
        $post->title = $request->input('title');
        $post->description = $request->input('description');
        $post->link = $request->input('link');
        $post->save();
    
        return redirect()->route('Porto')->with('success', 'Berhasil memperbarui data');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);
    
        if ($post->picture !== 'noimage.png') {
            $imagePath = public_path('storage/posts_image/' . $post->picture);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }
        $post->delete();
    
        return redirect()->route('Porto')->with('success', 'Berhasil menghapus data');
    }
    
}
