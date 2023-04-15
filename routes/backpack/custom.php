<?php

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => [
        config('backpack.base.web_middleware', 'web'),
        config('backpack.base.middleware_key', 'admin'),
    ],
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('client', 'UserCrudController');
    Route::crud('driver', 'DriverCrudController');
    Route::get('test/ajax-category-options', 'DriverCrudController@categoryOptions');

    Route::crud('maincategorys', 'MainCategorysCrudController');
    Route::crud('driver-car', 'PrivteCrudController');
    Route::crud('driver-spcars', 'SpcarsCrudController');
    Route::crud('restumancat', 'RestumancatCrudController');
    Route::crud('subscriptions', 'SubscrCrudController');
    Route::crud('RoadServices', 'RoadServCrudController');
    Route::crud('roadserviceoptions', 'ServOptionCrudController');
    Route::crud('restaurants', 'RestCrudController');
    Route::crud('restaurant-category', 'RestCatsCrudController');
    Route::crud('restmenus', 'RestmenusCrudController');
    Route::crud('restitems', 'RestItemCrudController');
    Route::crud('cafeitems', 'CafeItemCrudController');
    Route::crud('cafes', 'CafeCrudController');
    Route::crud('cafemenus', 'CafemenusCrudController');
    Route::crud('cafe-category', 'CafecatCrudController');
    Route::crud('markets', 'MarketsCrudController');
    Route::crud('marketsmenus', 'MarketsmenusCrudController');
    Route::crud('marketitems', 'MarketitemsCrudController');
    Route::crud('groups', 'GroupsCrudController');
    Route::crud('orderslist', 'OrdersListCrudController');
    Route::crud('ridelist', 'RideListCrudController');
    Route::crud('carcompany', 'CarCompanyCrudController');
    Route::crud('carmodels', 'CarModelsCrudController');
    Route::crud('carlist', 'CarlistCrudController');
    Route::crud('carstype', 'CarstypeCrudController');
    Route::crud('requerdequipment', 'RequerdEquipmentCrudController');
    Route::crud('carsregistration', 'CarsRegistrationCrudController');
    Route::crud('car-companis', 'CarCompanydrCrudController');
    Route::crud('driverhistory', 'DriverhistoryCrudController');
    Route::crud('deliverycars', 'DeliverycarsCrudController');
    Route::crud('roadsevcars', 'RoadsevcarsCrudController');
    Route::crud('furniturecars', 'FurniturecarsCrudController');
    Route::crud('attrbutes', 'AttrbutesCrudController');
    Route::crud('cities', 'CitiesCrudController');
    Route::crud('districts', 'DistrictsCrudController');
    Route::crud('additionalitem', 'AdditionalItemCrudController');
    Route::crud('manageplace', 'ManageplaceCrudController');
    Route::crud('country', 'CountryCrudController');
    Route::crud('carsetting', 'CarSettingCrudController');
    Route::crud('servcrsetting', 'ServcrSettingCrudController');
    Route::crud('shera', 'SheraCrudController');
    Route::crud('sheramenus', 'SheramenusCrudController');
    Route::crud('sheraitems', 'SheraitemsCrudController');
    Route::crud('adddriver', 'AddDriverCrudController');
    Route::crud('recuiredcarssp', 'RecuiredCarsspCrudController');
    Route::crud('recuiredcarsfern', 'RecuiredCarsFernCrudController');
    Route::crud('requerdequi', 'RequerdEquiCrudController');
    Route::crud('requerdequifern', 'RequerdEquiFernCrudController');
    Route::crud('restadmin', 'RestAdminCrudController');
    Route::crud('reportdriver', 'ReportdriverCrudController');
    Route::crud('reportclient', 'ReportClientCrudController');
    Route::crud('reportsections', 'ReportSectionsCrudController');
    Route::crud('reportorders', 'ReportOrdersCrudController');
    Route::crud('reporttrips', 'ReportTripsCrudController');
    Route::crud('driverkelo', 'DriverKeloCrudController');
    Route::post('savetdriverMoney', 'ServcrSettingCrudController@savedrivermoney');

    Route::crud('resturantcut', 'ResturantCutCrudController');
    Route::crud('places', 'PlacesCrudController');
    Route::crud('placetype', 'PlaceTypeCrudController');
    Route::crud('placemenu', 'PlaceMenuCrudController');
Route::crud('placeproducts', 'PlaceProductsCrudController');
    Route::crud('workers', 'WorkersCrudController');
    Route::crud('technician', 'TechnicianCrudController');
}); // this should be the absolute last line of this file