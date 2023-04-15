<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarModelsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarModelsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarModelsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CarModels::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/carmodels');
        CRUD::setEntityNameStrings('موديل', 'اداره موديلات السيارات');
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

                'name'         => 'CarCompany', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم الشركه', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
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
        CRUD::setValidation(CarModelsRequest::class);

        $this->crud->addFields([
            [
                
                'name'  => 'name',
                'label' => 'اسم الموديل',
                'type'  => 'text'
            ],
            [
                
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار الشركه المصنعه",
                'entity' => 'CarCompany',
                'label' => 'الشركه المصنعه',
                'name'      => 'car_factories_id',
                'entity'    => 'CarCompany', 
                'model'     => "App\Models\CarFactories", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user

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
