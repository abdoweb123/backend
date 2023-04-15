<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CafeItemRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CafeItemCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CafeItemCrudController extends CrudController
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
        CRUD::setModel(\App\Models\RestaurantMenu::class);
        $this->crud->addClause('where', 'cat_id', '=', '2');       
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cafeitems');
        CRUD::setEntityNameStrings('منتج', 'اداره منتجات الكافيهات');
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
            ],
            [

                'name'         => 'Restaurants', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم الكافيه', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [

                'name'         => 'menucategory', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم القائمه', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            // [
            //     'name'  => 'description',
            //     'label' => 'الوصف',
            //     'type'  => 'text',
            // ],

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
        CRUD::setValidation(CafeItemRequest::class);

        $this->crud->addFields([
            [
                
                'name'  => 'name',
                'label' => 'الاسم',
                'type'  => 'text',
                
            ],
            [
                'name'  => 'description',
                'label' => 'الوصف',
                'type'  => 'textarea'
            ],
            [
                
                'label' => "صوره",
                'name' => "image",
                'type' => 'image',
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
                // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
                // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
            ],
            [
                
                'label' => "اختار الكافيه",
                'type'          => 'select',
                'name'          => 'restaurant_id',
                'entity'        => 'Restaurants',
                'attribute'     => 'name',
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->where('type_id',2)->get();
                }), 
            ],            
            [

                'label'                => 'اسم القائمه',
                'type'                 => 'select2_from_ajax',
                'name'                 => 'menu_category_id',  //the column that contains the ID of that connected entity;
                'entity'               => 'menucategory', //the method that defines the relationship in your Model
                'attribute'            => 'name', // foreign key attribute that is shown to user
                'data_source'          => url('api/indexmenecats'), // url to controller search function (with /{id} should return model)
                'placeholder'          => 'Select an Menu', // placeholder for the select
                'include_all_form_fields' => true, //sends the other form fields along with the request so it can be filtered.
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies'         => ['restaurant_id'], 
                
                // 'type' => "relationship",
                // 'attribute' => 'name',
                // 'placeholder' => "اختار الخدمه",
                // 'entity' => 'menucategory',
                // 'label' => 'اسم القائمه',
                // 'name'      => 'menu_category_id',
                // 'entity'    => 'menucategory', 
                // 'model'     => "App\Models\MenuCategory", // related model
                // 'attribute' => 'name', 
                // 'options'   => (function ($query) {
                //     return $query->orderBy('name', 'ASC')->where('cat_id',2)->get();
                // }), 


            ],
            [
                'name'  => 'price',
                'label' => 'السعر',
                'type'  => 'number'
            ],
            [
                'name'            => 'cat_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['2' => 'كافيه'],
                'allows_null'     => false,
            ],
            [
                'name'            => 'attribute_title',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['Size' => 'Size'],
                'allows_null'     => false,
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
