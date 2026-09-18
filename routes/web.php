<?php

use App\Models\Rsvp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/imagen/{image}', function (string $image) {
    abort_unless(in_array($image, ['cris', 'fondo', 'logo', 'pozole', 'duende'], true), 404);

    $files = [
        'cris' => ['1.jpeg', 'image/jpeg'],
        'fondo' => ['spider-man-brand-5433x2160-26134.jpg', 'image/jpeg'],
        'logo' => ['pngegg.png', 'image/png'],
        'pozole' => ['pozole.png', 'image/png'],
        'duende' => ['duendeverde.png', 'image/png'],
    ];

    return response()->file(
        storage_path('app/public/'.$files[$image][0]),
        ['Content-Type' => $files[$image][1], 'Cache-Control' => 'public, max-age=604800']
    );
})->name('event.image');

Route::get('/media/spiderman-3.mp3', function () {
    return response()->file(
        storage_path('app/public/spiderman-3.mp3'),
        ['Content-Type' => 'audio/mpeg', 'Cache-Control' => 'public, max-age=604800']
    );
})->name('event.music');

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/protocolo-traje', function () {
    if (session('rsvp_admin')) {
        return to_route('rsvp.admin');
    }

    return view('rsvp-login');
})->name('rsvp.login');

Route::post('/protocolo-traje', function (Request $request) {
    $data = $request->validate(['key' => ['required', 'string', 'max:100']]);

    if (! hash_equals((string) config('services.rsvp.admin_key'), $data['key'])) {
        return back()->withErrors(['key' => 'Acceso denegado. La identidad del héroe no coincide.']);
    }

    $request->session()->regenerate();
    $request->session()->put('rsvp_admin', true);

    return to_route('rsvp.admin');
})->middleware('throttle:5,1')->name('rsvp.login.store');

Route::get('/protocolo-traje/registros', function () {
    abort_unless(session('rsvp_admin'), 403);

    $rsvps = Rsvp::latest()->paginate(20);
    $confirmedGuests = Rsvp::where('attending', true)->sum('guests');
    $confirmedHosts = Rsvp::where('attending', true)->count();

    return view('rsvp-admin', compact('rsvps', 'confirmedGuests', 'confirmedHosts'));
})->name('rsvp.admin');

Route::post('/protocolo-traje/salir', function (Request $request) {
    $request->session()->forget('rsvp_admin');
    $request->session()->regenerateToken();

    return to_route('rsvp.login');
})->name('rsvp.logout');

Route::post('/confirmacion', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:100'],
        'attending' => ['required', 'boolean'],
        'guests' => ['required', 'integer', 'min:0', 'max:10'],
        'phone' => ['nullable', 'string', 'max:30'],
        'message' => ['nullable', 'string', 'max:500'],
    ]);

    Rsvp::create($data);

    return to_route('home')->with('confirmed', true);
})->name('rsvp.store');
