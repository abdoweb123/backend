<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarlistRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarlistCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarlistCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Carslist::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/carlist');
        CRUD::setEntityNameStrings('موديل', 'سنة موديل السيارة');
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
                'attribute' => 'name', 
            ],
            [

                'name'         => 'CarModel', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم الموديل', // Table column heading
                'attribute' => 'name', 
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
        CRUD::setValidation(CarlistRequest::class);

        $this->crud->addFields([            
            [
                
                'type' => "select",
                'attribute' => 'name',
                'placeholder' => "اختار الشركه المصنعه",
                'entity' => 'CarCompany',
                'label' => 'الشركه المصنعه',
                'name'      => 'car_factories_id',
                'entity'    => 'CarCompany', 
                'model'     => "App\Models\CarFactories", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
            ],

            [
                
                'type' => 'select2_from_ajax',
                'attribute' => 'name',
                'placeholder' => "اختار الموديل",
                'entity' => 'CarCompany',
                'label' => 'اختار الموديل',
                'name'      => 'car_models_id',
                'entity'    => 'CarModel', 
                'data_source' => url('api/indexcars'), // url to controller search function (with /{id} should return model)
                'attribute' => 'name', // foreign key attribute that is shown to user
                'include_all_form_fields' => true, //sends the other form fields along with the request so it can be filtered.
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies'         => ['car_factories_id'], 
            ],
            [
                
                'name'  => 'name',
                'label' => 'سنه الموديل',
                'type'  => 'text'
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
