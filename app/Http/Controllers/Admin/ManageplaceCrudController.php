<?php

namespace App\Http\Controllers\Admin;

use App\Models\MainCategories;
use App\Http\Requests\ManageplaceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ManageplaceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ManageplaceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Restaurants::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/manageplace');
        CRUD::setEntityNameStrings('جديده', 'اداره المنشئات');
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
            'label'     => 'عدد القوائم', // Table column heading
            'type'      => 'relationship_count',
            'name'      => 'MenuesCategories', // the method that defines the relationship in your Model
            'suffix'    => 'قائمه    ',
            ],
            [
                'label'     => 'عدد المنتجات', // Table column heading
                'type'      => 'relationship_count',
                'name'      => 'RestaurantMenu', // the method that defines the relationship in your Model
                'suffix'    => 'منتج',
            ],


        ]);
        $this->crud->addFilter([ // select2 filter
            'name' => 'category_id',
            'type' => 'select2',
            'label'=> 'نوع المنشئه',
        ], function () {
            return MainCategories::all()->keyBy('id')->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'type_id', $value);
        });

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
        CRUD::setValidation(ManageplaceRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        
        $this->crud->addFields([
            [
                'type' => "select2_from_ajax_multiple",
                'attribute' => 'name',
                'placeholder' => "اختار نوع المطعم",
                'entity' => 'RestaurantCategory',
                'data_source' => url("api/indexRestaurantCategory"),
                'label' => 'نوع المطعم',
                'minimum_input_length' => 2, // minimum characters to type before querying results
                'name'      => 'category_id',
                'entity'    => 'RestaurantCategory', 
                'model'     => "App\Models\RestaurantCategory", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'options'   => (function ($query) {
                     return $query->orderBy('name', 'ASC')->where('type_id',1)->get();
                    }), //  you can use this to filter the results show in the select
                    'inline_create' => true,
            ],
            [
                
                'name'  => 'name',
                'label' => 'اسم المنشئه بالغه العربيه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                
                'name'  => 'name_en',
                'label' => 'اسم المنشئه بالغه الانجليزىه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'label' => 'لوجو المنشئه',
                'name' => "image",
                'type' => 'image',
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1,
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'label' => 'الوقت المستغرق',
                'name' => "time_frame",
                'type' => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'label' => 'الحد الادنى للطلب',
                'name' => "order_limit",
                'type' => 'number',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'description',
                'label' => 'وصف المنشئه بالغه العربيه',
                'type'  => 'summernote'
            ],
            [
                'name'  => 'description_en',
                'label' => 'وصف المنشئه بالغه الانجليزىه',
                'type'  => 'summernote'
            ],

            [
                'name'  => 'address',
                'label' => 'عنوان المنشئه بالغه العربيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'address_en',
                'label' => 'عنوان المنشئه بالغه الانجليزيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'delivery_price',
                'label' => 'سعر التوصيل',
                'type'  => 'number'
            ],
            [
                'name'  => 'lat',
                'label' => 'خط عرض',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name'  => 'lng',
                'label' => 'خط طول',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [               
                'label'         => 'المحافظه',
                'type'          => 'select2',
                'name'          => 'government',
                'entity'        => 'City',
                'attribute'     => 'name',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],            
                [
                    'label'                => 'اسم المقاطعه',
                    'type'                 => 'select2',
                    'name'                 => 'district',  //the column that contains the ID of that connected entity;
                    'entity'               => 'Districts', //the method that defines the relationship in your Model
                    'wrapper' => ['class' => 'form-group col-md-6'],

                ],
            [
                'name'            => 'type_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['1' => 'مطعم',
                                      '2' => 'كافيه',
                                      '3' => 'ورود',
                                      '23' => 'صيدليات',
                                      '17' => 'الكترونيات',
            ],
                'allows_null'     => false,
            ],
            [
                'name'  => 'place_phone',
                'label' => 'رقم تلفون المنشئه',
                'type'  => 'text',
                'tab'   => 'بيانات المنشئه',
            ],
            [
                'name'  => 'place_email',
                'label' => 'اميل المنشئه',
                'type'  => 'email',
                'tab'   => 'بيانات المنشئه',
            ],
            [
                'label' => 'صورة المدنية',
                'name' => "ownerimage",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-4'],

                'tab'   => 'بيانات المنشئه',

            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imgcert",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-4'],
                'tab'   => 'بيانات المنشئه',

            ],    
            [      
                'label' => 'صورة اعتماد توقيع',
                'name' => "signatureimage",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-4'],

                'tab'   => 'بيانات المنشئه',

            ],
            [ // Table
                'name'            => 'branches',
                'label'           => 'اضافه فروع',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اسم مسئول فرع',
                    'job'  => 'وظيفته',
                    'phone' => 'رقم الهاتف',
                    'address_en' => 'عنوان فرع انجليزي',
                    'address_ar' => 'عنوان فرع عربى',


                ],
                'max' => 35, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'فروع المنشئه',

            ],
            [ // Table
                'name'            => 'working_hours',
                'label'           => 'مواعيد العمل',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اليوم',
                    'desc'  => 'من',
                    'price' => 'الى',
                ],
                'max' => 7, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'مواعيد العمل',

            ],
            [ // Table
                'name'            => 'responsibles',
                'label'           => 'اسماء المسؤلين',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اسم المسئول',
                    'job'  => 'وظيفته',
                    'phone' => 'رقمه',
                ],
                'max' => 7, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'بيانات المسئولين',

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
