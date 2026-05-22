<?php

use App\Models\Collection;

if (! function_exists('waShareUrl')) {
    function waShareUrl(Collection $collection): string
    {
        $url  = route('collection.show', $collection->slug);
        $text = urlencode(
            "Hi, I've started a collection for *{$collection->recipient_name}* — {$collection->occasionLabel()}.\n" .
            "Please contribute here: {$url}\n" .
            "Organised by {$collection->organiser_name}."
        );
        return "https://wa.me/?text={$text}";
    }
}
