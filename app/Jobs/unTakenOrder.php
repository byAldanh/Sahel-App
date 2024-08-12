<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;
use Illuminate\Support\Facades\Log;


class unTakenOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order; // only used in this class 
    /**
     * Create a new job instance.
     */
    public function __construct(Order $order)
    {
        $this->order=$order;
    }//the end of the method 

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        
        Log::warning('Non-successful response');        // delete the order if it still pending
        if ($this->order->status=='pending')
        {
            $this->order->delete();
        }
    }//the end of the method 
}
