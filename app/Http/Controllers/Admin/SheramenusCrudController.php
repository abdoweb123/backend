<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SheramenusRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SheramenusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SheramenusCrudController extends CrudController
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
        $this->crud->addClause('where', 'cat_id', '=', '20');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/sheramenus');
        CRUD::setEntityNameStrings('قائمه', 'اداره قوائم الشيرا');

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
                'label'        => 'اسم المطعم', // Table column heading
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
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        CRUD::setValidation(SheramenusRequest::class);
        $this->crud->addFields([
            [
                
                'name'  => 'name',
                'label' => 'اسم القائمه',
                'type'  => 'text'
            ],
            [
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار الشيرا",
                'entity' => 'restaurants',
                'label' => 'اسم المطعم',
                'name'      => 'restaurant_id',
                'entity'    => 'restaurants', 
                'model'     => "App\Models\Restaurants", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'options'   => (function ($query) {
                     return $query->orderBy('name', 'ASC')->where('type_id','88')->get();
                 }), //  you can use this to filter the results show in the select
                
            ],
            [
                'name'  => 'cat_id', 
                'type'  => 'hidden', 
                'value' => '20',
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
