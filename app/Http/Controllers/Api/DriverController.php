<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DriverRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DriverCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DriverCrudController extends CrudController
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
        CRUD::setModel(\App\Models\User::class);
        $this->crud->addClause('where', 'is_driver', '=', '1');        
        CRUD::setRoute(config('backpack.base.route_prefix') . '/driver');
        CRUD::setEntityNameStrings('سائق', 'سائقين التاكسى');
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
                'label' => 'الاسم',
                'type'  => 'text',
            ],
            [
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
            ],
            [
                'name'  => 'birth_date',
                'label' => 'تاريخ الميلاد',
                'type'  => 'date',
            ],
            [
                'name'  => 'nationality',
                'label' => 'الجنسيه',
                'type'  => 'text',
            ],
            [
                'name'  => 'status',
                'label' => 'الحاله',
                'type'  => 'text',
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
        CRUD::setValidation(DriverRequest::class);

        $this->crud->addFields([
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
                'name'  => 'email',
                'label' => 'الايميل',
                'type'  => 'text',
            ],
            [
                'name'  => 'birth_date',
                'label' => 'تاريخ الميلاد',
                'type'  => 'date',
            ],
            [
                'name'            => 'status',
                'label'           => "الحاله",
                'type'            => 'select_from_array',
                'options'         => ['available' => 'Available', 'unavailable' => 'Unavailable'],
                'allows_null'     => false,
            ],
            [
                // 'name' => 'is_driver',
                // 'label' => 'تخصص السائق',
                // 'type'            => 'select_from_array',
                // 'options'         => ['1' => 'سائق سياره خاصه',
                //                      '2' => 'سائق سياره ذوى احتياجات خاصه',
                //                      '3' => 'سائق سياره اثاث',
                //                     ],
                // 'allows_null'     => false,
                // 'allows_multiple' => true,
                // 'tab'             => 'نوع السائق',   
                
                'name'            => 'is_driver',
                'label'           => "Select from array",
                'type'            => 'select_from_array',
                'options'         => ['1' => 'سائق سياره خاصه',
                                     '2' => 'سائق سياره ذوى احتياجات خاصه',
                                     '3' => 'سائق سياره اثاث',
                                    ],
                'allows_null'     => false,
                'allows_multiple' => true,
                'tab'             => 'Tab name here',

            ],
            

           
        ]);


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
