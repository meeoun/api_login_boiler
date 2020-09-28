<?php

namespace App\Jobs;

use App\Models\Design;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class UploadImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $design;
    /**
     * Create a new job instance.
     *
     * @param Design $design
     */
    public function __construct(Design $design)
    {
       $this->design = $design;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       $disk = $this->design->disk;
       $original_file = storage_path(). '/uploads/original/'. $this->design->image;
        try {
            Image::make($original_file)
                ->fit(800, 600, function ($constraint){
                    $constraint->aspectRatio();
                })->save($large = storage_path('uploads/large/' . $this->design->image));

            Image::make($original_file)
                ->fit(150, 100, function ($constraint){
                    $constraint->aspectRatio();
                })->save($thumbnail = storage_path('uploads/thumbnail/' . $this->design->image));

            if(Storage::disk($disk)->put('uploads/designs/original/'.$this->design->image, fopen($original_file, 'r+')))
            {
                File::delete($original_file);
            }

            if(Storage::disk($disk)->put('uploads/designs/thumbnail/'.$this->design->image, fopen($thumbnail, 'r+')))
            {
                File::delete($thumbnail);
            }

            if(Storage::disk($disk)->put('uploads/designs/large/'.$this->design->image, fopen($large, 'r+')))
            {
                File::delete($large);
            }

            $this->design->update([
                'upload_successful'=> true
            ]);



        }catch (\Exception $e)
        {
            Log::error($e->getMessage());
        }

    }
}
