<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DriverKeloRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DriverKeloCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DriverKeloCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
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
        CRUD::setModel(\App\Models\DriverKelo::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/driverkelo');
        CRUD::setEntityNameStrings('اعداد', 'اعدادات نسعير السائقين');
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
                'name'  => 'name',
                'label' => 'اسم الخدمه',
                'type'  => 'text',
            ],
            [
            'label'     => 'سعر الكيلو', // Table column heading
            'type'      => 'text',
            'name'      => 'price', // the method that defines the relationship in your Model
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
        CRUD::setValidation(DriverKeloRequest::class);

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
        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => 'اسم الخدمه',
                'type'  => 'text',
            ],
            [
            'label'     => 'سعر الكيلو', // Table column heading
            'type'      => 'text',
            'name'      => 'price', // the method that defines the relationship in your Model
            ],
        ]);
    }

    protected function setupShowOperation(){
        $this->crud->set('show.setFromDb', false);
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => 'اسم الخدمه',
                'type'  => 'text',
            ],
            [
            'label'     => 'سعر الكيلو', // Table column heading
            'type'      => 'text',
            'name'      => 'price', // the method that defines the relationship in your Model
            ],

        ]);

    }
}
