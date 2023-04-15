<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarstypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarstypeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarstypeCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SpecialCar::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/carstype');
        CRUD::setEntityNameStrings('نوع', 'اداره انواع السيارات');
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
                'name'      => 'image', // The db column name
                'label'     => 'صوره', // Table column heading
                'type'      => 'image',
                'prefix' => '/storage/public/',
                // 'height' => '30px',
                // 'width'  => '30px',
            ],
            [
                'name'    => 'type',
                'label'   => 'النوع',
                'type'    => 'select_from_array',
                'options' => ['1' => 'ذوى الاحتياجات الخاصه', 
                '2' => 'اثاث',
                '3' => 'الاشتراكات',
            ],
            ],

        ]);

        $this->crud->addFilter([
            'name'  => 'type',
            'type'  => 'dropdown',
            'label' => 'اختر النوع'
          ], [
            1 => 'ذوى الاحتياجات الخاصه',
            2 => 'اثاث',
            3 => 'الاشتراكات',
          ], function($value) { // if the filter is active
            $this->crud->addClause('where', 'type', $value);
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
        CRUD::setValidation(CarstypeRequest::class);

        $this->crud->addFields([
            [
                
                'name'  => 'name',
                'label' => 'اسم النوع',
                'type'  => 'text'
            ],
            [
                'label' => 'لوجو النوع',
                'name' => "image",
                'type' => 'image',
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1,
            ],
            [
                'name'    => 'type',
                'label'   => 'النوع',
                'type'    => 'select_from_array',
                'options' => ['1' => 'ذوى الاحتياجات الخاصه', 
                '2' => 'اثاث',
                '3' => 'الاشتراكات',
            ],
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
