<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReportdriverRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReportdriverCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReportdriverCrudController extends CrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reportdriver');
        CRUD::setEntityNameStrings('reportdriver', 'تقارير السائقين');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('whereHas', 'DriverSpacliy');

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
                'name'         => 'MyCar', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'رقم السياره', // Table column heading
                'attribute' => 'car_number', // foreign key attribute that is shown to user
            ],
            [
                'name'         => 'DriverSpacliy', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'تخصص السائق',
            ],
            [
                'name' => "mYTrip",
                'label' => "عدد الرحلات", // Table column heading
                'type' => "model_function",
                'function_name' => 'getMyTripCount', // the method in your Model
            ],
            [
                'name'         => 'Trip', // name of relationship method in the model
                'type'         => 'relationship_count',
                'label'        => 'اجمالي المبلغ', // Table column heading
                'suffix' => 'دينار', // to show "123 tags" instead of "123 items"
            ],
        ]);

        $this->crud->addFilter([
            'name'        => 'DriverSpacliy',
            'type'        => 'select2_ajax',
            'label'       => 'اختر تخصص السائق',
            'placeholder' => 'Pick a category'
          ],
          url('admin/test/ajax-category-options'), // the ajax route
          function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'DriverSpacliy', function($query) use($value) {
                $query->where('drivers_specialty_id', '=', $value);
            });
        });

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
        CRUD::setValidation(ReportdriverRequest::class);

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
