<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MarketsmenusRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MarketsmenusCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MarketsmenusCrudController extends CrudController
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
        $this->crud->addClause('where', 'cat_id', '=', '8');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/marketsmenus');
        CRUD::setEntityNameStrings('قائمه', 'اداره قوائم الاسواق');
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
                'label' => 'اسم السوق',
                'type'  => 'text',
            ],
            [

                'name'         => 'restaurants', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم السوق', // Table column heading
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
        CRUD::setValidation(MarketsmenusRequest::class);
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
                'placeholder' => "اختار السوق",
                'entity' => 'restaurants',
                'label' => 'اسم السوق',
                'name'      => 'restaurant_id',
                'entity'    => 'restaurants',
                'model'     => "App\Models\Restaurants", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'options'   => (function ($query) {
                     return $query->orderBy('name', 'ASC')->where('type_id',8)->get();
                 }), //  you can use this to filter the results show in the select

            ],
            [
                'name'            => 'cat_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['8' => 'سوق'],
                'allows_null'     => false,
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
