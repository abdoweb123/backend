<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReportClientRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReportClientCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReportClientCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reportclient');
        CRUD::setEntityNameStrings('reportclient', 'تقارير العملاء');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('whereDoesntHave', 'DriverSpacliy');
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => 'الاسم',
                'type'  => 'text',
            ],
            [
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
            ],
            [
                'name' => "ClientTrip",
                'label' => "عدد الرحلات", // Table column heading
                'type' => "model_function",
                'function_name' => 'getClientTripCount', // the method in your Model
            ],
            [
                'name' => "TotalTrip",
                'label' => "المبلغ الاجمالى", // Table column heading
                'type' => "model_function",
                'function_name' => 'getTotalTripSum', // the method in your Model
            ],


        ]);
        $this->crud->addFilter([
            'type'  => 'date',
            'name'  => 'created_at',
            'label' => 'الوقت'
          ],
            false,
          function ($value) { // if the filter is active, apply these constraints
             $this->crud->addClause('where', 'created_at', $value);
        });

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ReportClientRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
