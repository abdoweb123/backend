<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\WorkersRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class WorkersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class WorkersCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Workers::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/workers');
        CRUD::setEntityNameStrings('عامل', 'العمال');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->setColumns([
            [
                'name'  => 'username',
                'label' => 'اسم العامل',
                'type'  => 'text'
            ],
            [
                'name'         => 'DriverSpacliy',
                'type'         => 'relationship',
                'label' => 'تخصص العامل',
            ],
            [
                'name'  => 'phone',
                'label' => 'رقم هاتف العامل',
                'type'  => 'text'
            ],
            [
                'name'  => 'byhour',
                'label' => 'بالساعه',
                'type'  => 'text'
            ],
            [
                'name'  => 'byday',
                'label' => 'باليوم',
                'type'  => 'text'
            ],
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(WorkersRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [
                'name'  => 'username',
                'label' => 'اسم العامل',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'label' => 'تخصص العامل',
                'type'      => 'select2_multiple',
                'name'      => 'DriverSpacliy', // the method that defines the relationship in your Model

                // optional
                'entity'    => 'DriverSpacliy', // the method that defines the relationship in your Model
                'model'     => "App\Models\DriversSpecialty", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user

                // also optional
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                }),
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'phone',
                'label' => 'رقم هاتف العامل',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'byhour',
                'label' => 'بالساعه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name'  => 'byday',
                'label' => 'باليوم',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
        ]);
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
