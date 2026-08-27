<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Http\Request;

class EventCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['venue.seatCategories', 'eventSessions'])
            ->whereIn('status', ['coming_soon', 'registration', 'ongoing', 'published']);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $events = $query->orderBy('created_at', 'desc')->paginate(9);

        // Attach automatic price range to each event
        foreach ($events as $event) {
            $categories = $event->venue?->seatCategories;
            if ($categories && $categories->count() > 0) {
                $event->min_price = $categories->min('price');
                $event->max_price = $categories->max('price');
            } else {
                $event->min_price = 0;
                $event->max_price = 0;
            }
        }

        return view('events.index', compact('events'));
    }

    public function show($slug)
    {
        $event = Event::with(['venue.seatCategories', 'eventSessions' => function ($q) {
            $q->orderBy('session_date', 'asc')->orderBy('start_time', 'asc');
        }])
        ->where('slug', $slug)
        ->whereIn('status', ['coming_soon', 'registration', 'ongoing', 'published'])
        ->firstOrFail();

        $categories = $event->venue?->seatCategories;
        if ($categories && $categories->count() > 0) {
            $event->min_price = $categories->min('price');
            $event->max_price = $categories->max('price');
        } else {
            $event->min_price = 0;
            $event->max_price = 0;
        }

        return view('events.show', compact('event'));
    }
}
