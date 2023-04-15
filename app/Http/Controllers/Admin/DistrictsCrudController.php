<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\DistrictsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class DistrictsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DistrictsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Districts::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/districts');
        CRUD::setEntityNameStrings('مقاطعه', 'اداره المقاطعات');
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
                'label' => 'اسم المقاطعه',
                'type'  => 'text',
            ],
            [
                'name'         => 'City', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'اسم المحافظه',
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
        CRUD::setValidation(DistrictsRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [   // relationship
                'type' => "relationship",
                'name' => 'city_id', // the method on your model that defines the relationship
                'label' => "اسم المحافظه",
                'attribute' => "name", // foreign key attribute that is shown to user (identifiable attribute)
                'entity' => 'City', // the method that defines the relationship in your Model
                'model' => "App\Models\Cities", // foreign key Eloquent model
                'placeholder' => "اختار المحافظه", // placeholder for the select2 input
             ],
             [
                
                'name'  => 'name',
                'label' => 'اسم المحافظه',
                'type'  => 'text',

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
