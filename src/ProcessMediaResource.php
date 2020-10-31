<?php

namespace EdgarMendozaTech\MediaResource;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMediaResource implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $mediaResource;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MediaResource $mediaResource)
    {
        $this->mediaResource = $mediaResource;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(ImageProcessor $imageProcessor)
    {
        $imageProcessor->process($this->mediaResource);
    }
}
