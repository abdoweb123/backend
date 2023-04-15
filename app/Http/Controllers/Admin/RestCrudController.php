<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RestRequest;
use App\Models\RestaurantCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RestCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RestCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
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
        $this->crud->addClause('where', 'type_id', '=', '1');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/restaurants');
        CRUD::setEntityNameStrings('مطعم', 'ادراه المطاعم');
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
            [
                'name'  => 'show',
                'label' => 'الحاله',
                'type'  => 'boolean',
                'options' => [0 => 'متاح',
                 1 => 'غير متاح']
            ],
        ]);

        $this->crud->addFilter([ // select2 filter
            'name' => 'category_id',
            'type' => 'select2',
            'label'=> 'النوع',
        ], function () {
            return RestaurantCategory::all()->keyBy('id')->pluck('name', 'id')->toArray();
        }, function ($value) { // if the filter is active
            $this->crud->addClause('where', 'category_id', $value);
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
        CRUD::setValidation(RestRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');


        $this->crud->addFields([
            [
                'label' => 'نوع المطعم',
                'type'        => "select2_from_ajax_multiple",
                'name'        => 'RestaurantCategory', // a unique identifier (usually the method that defines the relationship in your Model) 
                'entity' => 'RestaurantCategory',
                'attribute'   => "name", // foreign key attribute that is shown to user
                'data_source' => url("api/indexRestaurantCategory"),
                'pivot'       => true, // on create&update, do you need to add/delete pivot table entries?
            
                // OPTIONAL
                'model'     => "App\Models\RestaurantCategory", // related model
                'placeholder' => "اختار نوع المطعم",
                'minimum_input_length' => 2, // minimum characters to type before querying results
                // 'include_all_form_fields'  => false, // optional - only send the current field through AJAX (for a smaller payload if you're not using multiple chained select2s)
            ],
            [

                'name'  => 'name',
                'label' => 'اسم المطعم بالغه العربيه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6 checkRTL'],

            ],
            [

                'name'  => 'name_en',
                'label' => 'اسم المطعم بالغه الانجليزىه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6 checkRTL'],

            ],
            [
                'label' => 'لوجو المطعم',
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
                'type' => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'description',
                'label' => 'وصف المطعم بالغه العربيه',
                'type'  => 'summernote'
            ],
            [
                'name'  => 'description_en',
                'label' => 'وصف المطعم بالغه الانجليزىه',
                'type'  => 'summernote'
            ],

            [
                'name'  => 'address',
                'label' => 'عنوان المطعم بالغه العربيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'address_en',
                'label' => 'عنوان المطعم بالغه الانجليزيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'delivery_price',
                'label' => 'سعر التوصيل',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [   // select_from_array
                'name'        => 'show',
                'label'       => 'حاله المطعم', // the input label
                'type'        => 'select_from_array',
                'options'     => ['0' => 'متاح',
                '1' => 'غير متاح'],
                'allows_null' => false,
                'default'     => '0',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
                'wrapper' => ['class' => 'form-group col-md-6'],

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
                'label'         => 'اسم المحافظه',
                'type'          => 'select2',
                'name'          => 'government',
                'entity'        => 'City',
                'attribute'     => 'name',
            ],
            [
                'label'                => 'اسم المنطقه',
                'type'                 => 'select2_from_ajax',
                'name'                 => 'district',  //the column that contains the ID of that connected entity;
                'entity'               => 'Districts', //the method that defines the relationship in your Model
                'attribute'            => 'name', // foreign key attribute that is shown to user
                'data_source'          => url('api/indexctiys'), // url to controller search function (with /{id} should return model)
                'placeholder'          => 'Select an Menu', // placeholder for the select
                'include_all_form_fields' => true, //sends the other form fields along with the request so it can be filtered.
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies'         => ['government'],
            ],
            [
                'name'            => 'type_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['1' => 'مطعم'],
                'allows_null'     => false,
            ],
            [
                'name'  => 'place_phone',
                'label' => 'رقم تلفون المطعم',
                'type'  => 'text',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'name'  => 'place_email',
                'label' => 'اميل المطعم',
                'type'  => 'email',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'label' => 'صورة المدنية',
                'name' => "ownerimage",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-3'],

                'tab'   => 'بيانات المطعم',

            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imgcert",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-3'],
                'tab'   => 'بيانات المطعم',

            ],
            [
                'label' => 'صورة اعتماد توقيع',
                'name' => "signatureimage",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-3'],

                'tab'   => 'بيانات المطعم',

            ],
            [
                'label' => 'اخرى',
                'name' => "otherimage",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'tab'   => 'بيانات المطعم',

            ],
            [ // Table
                'name'            => 'branches',
                'label'           => 'اضافه فروع',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اسم الفرع',
                    'address_en' => 'عنوان فرع انجليزي',
                    'address_ar' => 'عنوان فرع عربى',
                    'Longitude' => 'خط الطول',
                    'latitude' => 'خط العرض',
                ],
                'max' => 35, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'فروع المطعم',

            ],
            [ // Table
                'name'            => 'working_hours',
                'label'           => 'مواعيد العمل',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اليوم',
                    'from_day'  => 'من',
                    'to_day' => 'الى',
                    'from_night'  => 'من',
                    'to_night' => 'الى',
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
                    'address' => 'اسم الفرع',
                    'name'  => 'اسم المسئول',
                    'job'  => 'وظيفته',
                    'phone' => 'رقمه',
                ],
                'max' => 10, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'بيانات المسئولين',

            ],
            [
                'name'            => 'bank_info',
                'label'           => 'حساب المطعم البنكى',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'acount_name' => 'اسم صاحب الحساب',
                    'bank_name'  => 'اسم البنك',
                    'credit_number' => 'رقم الحساب',
                ],
                'max' => 1, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'المعاملات الماليه',

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
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
        
        // $this->setupCreateOperation();

        $this->crud->addFields([
            [
                'label' => 'نوع المطعم',
                'type'        => "select2_from_ajax_multiple",
                'name'        => 'RestaurantCategory', // a unique identifier (usually the method that defines the relationship in your Model) 
                'entity' => 'RestaurantCategory',
                'attribute'   => "name", // foreign key attribute that is shown to user
                'data_source' => url("api/indexRestaurantCategory"),
                'pivot'       => true, // on create&update, do you need to add/delete pivot table entries?
                // OPTIONAL
                'model'     => "App\Models\RestaurantCategory", // related model
                'placeholder' => "اختار نوع المطعم",
                'minimum_input_length' => 2, // minimum characters to type before querying results
                // 'include_all_form_fields'  => false, // optional - only send the current field through AJAX (for a smaller payload if you're not using multiple chained select2s)
            ],
            [

                'name'  => 'name',
                'label' => 'اسم المطعم بالغه العربيه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6 checkRTL'],

            ],
            [

                'name'  => 'name_en',
                'label' => 'اسم المطعم بالغه الانجليزىه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6 checkRTL'],

            ],
            [
                'label' => 'لوجو المطعم',
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
                'type' => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'description',
                'label' => 'وصف المطعم بالغه العربيه',
                'type'  => 'summernote'
            ],
            [
                'name'  => 'description_en',
                'label' => 'وصف المطعم بالغه الانجليزىه',
                'type'  => 'summernote'
            ],

            [
                'name'  => 'address',
                'label' => 'عنوان المطعم بالغه العربيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'address_en',
                'label' => 'عنوان المطعم بالغه الانجليزيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'delivery_price',
                'label' => 'سعر التوصيل',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [   // select_from_array
                'name'        => 'show',
                'label'       => 'حاله المطعم', // the input label
                'type'        => 'select_from_array',
                'options'     => ['0' => 'متاح',
                '1' => 'غير متاح'],
                'allows_null' => false,
                'default'     => '0',
                // 'allows_multiple' => true, // OPTIONAL; needs you to cast this to array in your model;
                'wrapper' => ['class' => 'form-group col-md-6'],

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
                'label'         => 'اسم المحافظه',
                'type'          => 'select2',
                'name'          => 'government',
                'entity'        => 'City',
                'attribute'     => 'name',
            ],
            [
                'label'                => 'اسم المنطقه',
                'type'                 => 'select2_from_ajax',
                'name'                 => 'district',  //the column that contains the ID of that connected entity;
                'entity'               => 'Districts', //the method that defines the relationship in your Model
                'attribute'            => 'name', // foreign key attribute that is shown to user
                'data_source'          => url('api/indexctiys'), // url to controller search function (with /{id} should return model)
                'placeholder'          => 'Select an Menu', // placeholder for the select
                'include_all_form_fields' => true, //sends the other form fields along with the request so it can be filtered.
                'minimum_input_length' => 0, // minimum characters to type before querying results
                'dependencies'         => ['government'],
            ],
            [
                'name'            => 'type_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['1' => 'مطعم'],
                'allows_null'     => false,
            ],
            [
                'name'  => 'place_phone',
                'label' => 'رقم تلفون المطعم',
                'type'  => 'text',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'name'  => 'place_email',
                'label' => 'اميل المطعم',
                'type'  => 'email',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'label' => 'صورة المدنية',
                'name' => "ownerimage",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-3'],

                'tab'   => 'بيانات المطعم',

            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imgcert",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-3'],
                'tab'   => 'بيانات المطعم',

            ],
            [
                'label' => 'صورة اعتماد توقيع',
                'name' => "signatureimage",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-3'],

                'tab'   => 'بيانات المطعم',

            ],
            [
                'label' => 'اخرى',
                'name' => "otherimage",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'tab'   => 'بيانات المطعم',

            ],
            [ // Table
                'name'            => 'branches',
                'label'           => 'اضافه فروع',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اسم الفرع',
                    'address_en' => 'عنوان فرع انجليزي',
                    'address_ar' => 'عنوان فرع عربى',
                    'Longitude' => 'خط الطول',
                    'latitude' => 'خط العرض',
                ],
                'max' => 35, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'فروع المطعم',

            ],
            [ // Table
                'name'            => 'working_hours',
                'label'           => 'مواعيد العمل',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اليوم',
                    'from_day'  => 'من',
                    'to_day' => 'الى',
                    'from_night'  => 'من',
                    'to_night' => 'الى',
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
                    'address' => 'اسم الفرع',
                    'name'  => 'اسم المسئول',
                    'job'  => 'وظيفته',
                    'phone' => 'رقمه',
                ],
                'max' => 10, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'بيانات المسئولين',

            ],
            [
                'name'            => 'bank_info',
                'label'           => 'حساب المطعم البنكى',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'acount_name' => 'اسم صاحب الحساب',
                    'bank_name'  => 'اسم البنك',
                    'credit_number' => 'رقم الحساب',
                ],
                'max' => 1, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'المعاملات الماليه',

            ],

        ]);
        }

    protected function setupShowOperation(){
        $this->crud->set('show.setFromDb', false);
        $this->crud->setColumns([
            [
                'label' => 'نوع المطعم',
                'type'        => "select2_from_ajax_multiple",
                'name'        => 'RestaurantCategory', // a unique identifier (usually the method that defines the relationship in your Model) 
                'entity' => 'RestaurantCategory',
                'attribute'   => "name", // foreign key attribute that is shown to user
                'data_source' => url("api/indexRestaurantCategory"),
                'pivot'       => true, // on create&update, do you need to add/delete pivot table entries?
                // OPTIONAL
                'model'     => "App\Models\RestaurantCategory", // related model
                'placeholder' => "اختار نوع المطعم",
                'minimum_input_length' => 2, // minimum characters to type before querying results
                // 'include_all_form_fields'  => false, // optional - only send the current field through AJAX (for a smaller payload if you're not using multiple chained select2s)
            ],
            [
                
                'name'         => 'RestaurantCategory', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'نوع المطعم',
            ],
            [

                'name'  => 'name',
                'label' => 'اسم المطعم بالغه العربيه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [

                'name'  => 'name_en',
                'label' => 'اسم المطعم بالغه الانجليزىه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'label' => 'لوجو المطعم',
                'name' => "image",
                'type' => 'image',
                'prefix' => '/storage/public/',

            ],
            [
                'name'  => 'description',
                'label' => 'وصف المطعم بالغه العربيه',
                'type'  => 'summernote'
            ],
            [
                'name'  => 'description_en',
                'label' => 'وصف المطعم بالغه الانجليزىه',
                'type'  => 'summernote'
            ],

            [
                'name'  => 'address',
                'label' => 'عنوان المطعم بالغه العربيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'address_en',
                'label' => 'عنوان المطعم بالغه الانجليزيه',
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
                'label'         => 'اسم المطعم',
                'type'          => 'select',
                'name'          => 'government',
                'entity'        => 'City',
                'attribute'     => 'name',
                'options'   => (function ($query) {
                        return $query->orderBy('name', 'ASC')->where('type_id',1)->get();
                    }),
                ],
                [
                    'label'                => 'اسم القائمه',
                    'type'                 => 'select2_from_ajax',
                    'name'                 => 'district',  //the column that contains the ID of that connected entity;
                    'entity'               => 'menucategory', //the method that defines the relationship in your Model
                    'attribute'            => 'name', // foreign key attribute that is shown to user
                    'data_source'          => url('api/indexctiys'), // url to controller search function (with /{id} should return model)
                    'placeholder'          => 'Select an Menu', // placeholder for the select
                    'include_all_form_fields' => true, //sends the other form fields along with the request so it can be filtered.
                    'minimum_input_length' => 0, // minimum characters to type before querying results
                    'dependencies'         => ['government'],
                ],
            // [
            //     'name'  => 'country',
            //     'label' => 'بلد',
            //     'type'  => 'text'
            // ],
            // [
            //     'name'  => 'government',
            //     'label' => 'المحافظه',
            //     'type'  => 'text'
            // ],
            // [
            //     'name'  => 'district',
            //     'label' => 'مدينه',
            //     'type'  => 'text'
            // ],
            [
                'name'            => 'type_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['1' => 'مطعم'],
                'allows_null'     => false,
            ],
            [
                'name'  => 'place_phone',
                'label' => 'رقم تلفون المطعم',
                'type'  => 'text',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'name'  => 'place_email',
                'label' => 'اميل المطعم',
                'type'  => 'email',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'label' => 'صورة المدنية',
                'name' => "ownerimage",
                'type' => 'image',
                'prefix' => '/storage/public/',
            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imgcert",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'label' => 'صورة اعتماد توقيع',
                'name' => "signatureimage",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'tab'   => 'بيانات المطعم',
            ],
            [
                'label' => 'اخرى',
                'name' => "otherimage",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'tab'   => 'بيانات المطعم',
            ],
            [ // Table
                'name'            => 'branches',
                'label'           => 'اضافه فروع',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'الاسم',
                    'desc'  => 'العنوان',
                    'price' => 'رقم الهاتف',

                ],
                'max' => 10, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'فروع المطعم',

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

        ]);

    }

    public function fetchRestaurantcategory()
    {
        return $this->fetch(RestaurantCategory::class);
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