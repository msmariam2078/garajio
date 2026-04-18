<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait UploadTrait{

    public function verifyAndStoreFile(Request $request, $inputname , $foldername , $disk) {

        if( $request->hasFile( $inputname ) ) {

            // Check img
            if (!$request->file($inputname)->isValid()) {
                flash('Invalid Image!')->error()->important();
                return redirect()->back()->withInput();
            }

            $photo = $request->file($inputname);
            $name = \Str::slug($request->input('name'));
            $filename = $name. '.' . $photo->getClientOriginalExtension();

            // insert Image
       
            return $request->file($inputname)->storeAs($foldername, $filename, $disk);
        }

        return null;

    }

   
    public function Delete_attachment($disk,$path){

        Storage::disk($disk)->delete($path);

        

    }
  





}
