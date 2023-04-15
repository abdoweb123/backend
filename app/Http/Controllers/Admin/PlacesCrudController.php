<?php

namespace App\Http\Controllers\Admin;

use App\Models\MainCategories;
use App\Http\Requests\PlacesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class PlacesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PlacesCrudController extends CrudController
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
        CRUD::setRoute(config('backpack.base.route_prefix') . '/places');
        CRUD::setEntityNameStrings('مكان', 'ادراه الاماكن');
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
                'label'     => 'نوع المكان', // Table column heading
                'type'      => 'relationship',
                'name'      => 'MainCategories', // the method that defines the relationship in your Model
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
                'name' => 'type_id',
                'type' => 'select2',
                'label'=> 'نوع القائمه',
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
        CRUD::setValidation(PlacesRequest::class);

        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');


        $this->crud->addFields([

            [   // relationship
                'label'     => 'نوع المكان', // Table column heading
                'type' => "relationship",
                'name'      => 'MainCategories', // the method that defines the relationship in your Model
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
