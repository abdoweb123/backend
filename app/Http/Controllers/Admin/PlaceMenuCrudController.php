<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PlaceMenuRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PlaceMenuCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PlaceMenuCrudController extends CrudController
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
        CRUD::setModel(\App\Models\MenuCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/placemenu');
        CRUD::setEntityNameStrings('قائمه', 'اداره قوائم الاماكن');
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
            'label' => 'اسم المطعم',
            'type'  => 'text',
        ],
        [

            'name'         => 'restaurants', // name of relationship method in the model
            'type'         => 'relationship',
            'label'        => 'اسم المكان', // Table column heading
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
        CRUD::setValidation(PlaceMenuRequest::class);

        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [

                'name'  => 'name',
                'label' => 'اسم القائمه',
                'type'  => 'text'
            ],
            [
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار المكان",
                'entity' => 'restaurants',
                'label' => 'اسم المكان',
                'name'      => 'restaurant_id',
                'entity'    => 'restaurants',
                'model'     => "App\Models\Restaurants", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'options'   => (function ($query) {
                     return $query->orderBy('name', 'ASC')->get();
                 }), //  you can use this to filter the results show in the select

            ],
            [   // relationship
                'label'     => 'نوع المكان', // Table column heading
                'type'      => 'select',
                'name'      => 'cat_id', // the db column for the foreign key
                'entity'    => 'MainCategories',
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
