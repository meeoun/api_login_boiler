<?php

namespace App\Http\Controllers\Designs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Designs\UploadRequest;
use App\Jobs\UploadImage;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    public function upload(UploadRequest $request)
    {

        //get image
        $image = $request->file('image');
        $image_path = $image->getPathname();

        $fileName = time()."_". strtolower(str_replace('_',' ', $image->getClientOriginalName()));

        $tmp = $image->storeAs('uploads/original',$fileName,'tmp');

        $design = auth()
            ->user()
            ->designs()
            ->create([
               'image' => $fileName,
               'disk' => config('site.upload_disk')
            ]);

        $this->dispatch(new UploadImage($design));


        return response()->json($design, 200);
    }
}
