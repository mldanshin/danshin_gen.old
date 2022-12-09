<?php

use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\EventSubscriptionController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\TreeController;
use App\Http\Controllers\Events\EventsController as EventPartialController;
use App\Http\Controllers\Events\SubscriptionController as SubscriptionPartialController;
use App\Http\Controllers\Tree\TreeController as TreePartialController;
use App\Http\Controllers\People\FilterOrderingController as PeopleFilterOrderingController;
use App\Http\Controllers\Person\ResourceController as PersonResourceController;
use App\Http\Controllers\Person\PartialController as PersonPartialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/', IndexController::class)->name("index");

    Route::prefix("/person/")->name("person.")->group(function () {
        Route::get('create', [PersonController::class, "create"])->name("create");
        Route::get('{id}', [PersonController::class, "show"])->name("show");
        Route::get('{id}/edit', [PersonController::class, "edit"])->name("edit");
    });

    Route::get("/tree/image/{id}/{parentId?}", [TreeController::class, "showImage"])->name("tree.image");
    Route::get("/tree/{id}/{parentId?}", [TreeController::class, "show"])->name("tree");

    Route::prefix("/download/")->name("download.")->group(function () {
        Route::get("people/{type}", [DownloadController::class, "downloadPeople"])->name("people");
        Route::get("person/{id}/{type}", [DownloadController::class, "downloadPerson"])->name("person");
        Route::get("tree/{id}/{parentId?}", [DownloadController::class, "downloadTree"])->name("tree");
        Route::get("db", [DownloadController::class, "downloadDataBase"])->name("data_base");
        Route::get("photo", [DownloadController::class, "downloadPhoto"])->name("photo");
    });

    Route::prefix("/events/")->name("events.")->group(function () {
        Route::get("show", [EventController::class, "show"])->name("show");
        Route::get("subscription/create", [EventSubscriptionController::class, "create"])->name("subscription.create");
        Route::get("subscription/edit", [EventSubscriptionController::class, "edit"])->name("subscription.edit");
    });

    Route::post('/partials/people/filter-ordering', PeopleFilterOrderingController::class)->name("partials.people.filter_ordering");

    Route::prefix("/partials/person/")->name("partials.person.")->group(function () {
        Route::get('close', [PersonResourceController::class, "close"])->name("close");
        Route::get('list-input/{name}', [PersonPartialController::class, "getListInput"])->name("list_input");
        Route::post('marriage', [PersonPartialController::class, "getMarriage"])->name("marriage");
        Route::post('parent', [PersonPartialController::class, "getParent"])->name("parent");
        Route::post('photo', [PersonPartialController::class, "getPhoto"])->name("photo");
    });

    Route::prefix("/partials/")->name("partials.")->group(function () {
        Route::resource('person', PersonResourceController::class)->except("index");
    });

    Route::prefix("/partials/tree/")->name("partials.tree.")->group(function () {
        Route::post("index", [TreePartialController::class, "index"])->name("index");
        Route::post("show", [TreePartialController::class, "show"])->name("show");
    });

    Route::prefix("/partials/events/")->name("partials.events.")->group(function () {
        Route::get("show", [EventPartialController::class, "show"])->name("show");
        Route::get("subscription/create", [SubscriptionPartialController::class, "create"])->name("subscription.create");
        Route::get("subscription/edit", [SubscriptionPartialController::class, "edit"])->name("subscription.edit");
        Route::post("subscription/store", [SubscriptionPartialController::class, "store"])->name("subscription.store");
        Route::post("subscription/delete", [SubscriptionPartialController::class, "delete"])->name("subscription.delete");
    });

    Route::post("/log", LogController::class)->name("log");
});
