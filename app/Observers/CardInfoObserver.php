<?php

namespace App\Observers;

use App\Models\CardInfo;

class CardInfoObserver
{
    /**
     * Handle the CardInfo "created" event.
     */
    public function created(CardInfo $cardInfo): void
    {
        //
    }

    /**
     * Handle the CardInfo "updated" event.
     */
    public function updated(CardInfo $cardInfo): void
    {
        //
    }

    /**
     * Handle the CardInfo "deleted" event.
     */
    public function deleted(CardInfo $cardInfo): void
    {
        //
    }

    /**
     * Handle the CardInfo "restored" event.
     */
    public function restored(CardInfo $cardInfo): void
    {
        //
    }

    /**
     * Handle the CardInfo "force deleted" event.
     */
    public function forceDeleted(CardInfo $cardInfo): void
    {
        //
    }
}
