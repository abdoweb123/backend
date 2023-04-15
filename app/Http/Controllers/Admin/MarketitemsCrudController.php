<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MarketitemsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MarketitemsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MarketitemsCrudController extends CrudController
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
        $this->crud->addClause('where', 'cat_id', '=', '8');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/marketitems');
        CRUD::setEntityNameStrings('منتج', 'اداره المنتجات');
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
                'label'        => 'اسم المطعم', // Table column heading
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

        $this->addCustomCrudFilters();

    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MarketitemsRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [
                'label'         => 'اسم المطعم',
                'type'          => 'select',
                'name'          => 'restaurant_id',
                'entity'        => 'Restaurants',
                'attribute'     => 'name',
                'options'   => (function ($query) {
                        return $query->orderBy('name', 'ASC')->where('type_id',11)->get();
                    }),

                    // 'type' => "select",
                    // 'attribute' => 'name',
                    // 'placeholder' => "اختار الخدمه",
                    // 'entity' => 'Restaurants',
                    // 'label' => 'اسم المطعم',
                    // 'name'      => 'restaurant_id',
                    // 'entity'    => 'Restaurants',
                    // 'model'     => "App\Models\Restaurants", // related model
                    // 'attribute' => 'name', // foreign key attribute that is shown to user
                    // 'options'   => (function ($query) {
                    //     return $query->orderBy('name', 'ASC')->where('type_id',1)->get();
                    // }),
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
                    //     return $query->orderBy('name', 'ASC')->where('cat_id',1)->get();
                    // }),


                ],
            [

                'name'  => 'name',
                'label' => 'اسم المنتج بالعربى',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],


            ],
            [
                'name'  => 'name_en',
                'label' => 'اسم المنتج بالانجليزى',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'description',
                'label' => 'الوصف باللغه العربيه',
                'type'  => 'ckeditor',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],

            [
                'name'  => 'description_en',
                'label' => 'الوصف بالغه الانجليزيه',
                'type'  => 'ckeditor',
                'wrapper' => ['class' => 'form-group col-md-6'],


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
                'name'  => 'price',
                'label' => 'السعر',
                'type'  => 'number',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name'            => 'cat_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['1' => 'مطعم'],
                'allows_null'     => false,
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'label' => 'الزيادات',
                'type' => 'relationship',
                'name' => 'AdditionalItems', // the method that defines the relationship in your Model
                'entity' => 'AdditionalItems', // the method that defines the relationship in your Model
                'attribute' => 'name_ar', // foreign key attribute that is shown to user
                'pivot' => true, // on create&update, do you need to add/delete pivot table entries?
                'inline_create' => ['entity' => 'additionalitem'],
                'ajax' => true,
                'tab'   => 'الزيادات',

            ],
            [
                'name'            => 'attribute_title',
                'label'           => "اسم المتغير عربي",
                'type'            => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'tab'   => 'المتغيرات',


            ],
            [
                'name'            => 'attribute_title_en',
                'label'           => "اسم المتغير انجليزى",
                'type'            => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'tab'   => 'المتغيرات',
            ],
            [ // Table
                'name'            => 'attribute_body',
                'label'           => 'اضافه العناصر',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name_ar'  => 'اسم العنصر بالعربى',
                    'name_en'  => 'اسم العنصر بالانجليزى',
                    'price' => 'السعر',
                ],
                'max' => 10, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'المتغيرات',

            ],
            [
                'name'            => 'attribute_title_two',
                'label'           => "اسم المتغير عربي",
                'type'            => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'tab'   => 'المتغير الثانى',
            ],
            [
                'name'            => 'attribute_title_en_two',
                'label'           => "اسم المتغير انجليزى",
                'type'            => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'tab'   => 'المتغير الثانى',
            ],
            [ // Table
                'name'            => 'attribute_body_two',
                'label'           => 'اضافه العناصر',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name_ar'  => 'اسم العنصر بالعربى',
                    'name_en'  => 'اسم العنصر بالانجليزى',
                    'price' => 'السعر',
                ],
                'max' => 10, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'المتغير الثانى',

            ],
            [
                'name'            => 'attribute_title_three',
                'label'           => "اسم المتغير عربي",
                'type'            => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'tab'   => 'المتغير الثالث',
            ],
            [
                'name'            => 'attribute_title_en_three',
                'label'           => "اسم المتغير انجليزى",
                'type'            => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
                'tab'   => 'المتغير الثالث',
            ],
            [ // Table
                'name'            => 'attribute_body_three',
                'label'           => 'اضافه العناصر',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name_ar'  => 'اسم العنصر بالعربى',
                    'name_en'  => 'اسم العنصر بالانجليزى',
                    'price' => 'السعر',
                ],
                'max' => 10, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'المتغير الثالث',

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
    protected function addCustomCrudFilters()
    {
        CRUD::filter('date_range')
                ->type('date_range')
                ->label('البحث بالوقت')
                ->whenActive(function ($value) {
                    $dates = json_decode($value);
                    CRUD::addClause('where', 'created_at', '>=', $dates->from);
                    CRUD::addClause('where', 'created_at', '<=', $dates->to);
        });
    }
}
