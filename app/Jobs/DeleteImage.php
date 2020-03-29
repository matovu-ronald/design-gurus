<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class DeleteImage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $disk;
    protected $image;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($disk, $image)
    {
        $this->disk = $disk;
        $this->image = $image;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Delete the files associated with the record.
        foreach (['thumbnail', 'large', 'original'] as $size) {
            // Check if the file exists on the disk
            if (Storage::disk($this->disk)->exists("uploads/designs/{$size}/".$this->image)) {
                Storage::disk($this->disk)->delete("uploads/designs/{$size}/".$this->image);
            }
        }
    }
}
