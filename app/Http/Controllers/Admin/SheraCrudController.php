<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SheraRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SheraCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SheraCrudController extends CrudController
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
        $this->crud->addClause('where', 'type_id', '=', '26');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/shera');
        CRUD::setEntityNameStrings('شيرا', 'اداره الشيرا');
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
        CRUD::setValidation(SheraRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');


        $this->crud->addFields([
            [

                'name'  => 'name',
                'label' => 'اسم الشيرا بالغه العربيه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6 checkRTL'],

            ],
            [

                'name'  => 'name_en',
                'label' => 'اسم الشيرا بالغه الانجليزىه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6 checkRTL'],

            ],
            [
                'label' => 'لوجو الشيرا',
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
                'label' => 'وصف الشيرا بالغه العربيه',
                'type'  => 'summernote'
            ],
            [
                'name'  => 'description_en',
                'label' => 'وصف الشيرا بالغه الانجليزىه',
                'type'  => 'summernote'
            ],

            [
                'name'  => 'address',
                'label' => 'عنوان الشيرا بالغه العربيه',
                'type'  => 'text'
            ],
            [
                'name'  => 'address_en',
                'label' => 'عنوان الشيرا بالغه الانجليزيه',
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
                'label'       => 'حاله الشيرا', // the input label
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
                'options'         => ['26' => 'شيرا'],
                'allows_null'     => false,
            ],
            [
                'name'  => 'place_phone',
                'label' => 'رقم تلفون الشيرا',
                'type'  => 'text',
                'tab'   => 'بيانات الشيرا',
            ],
            [
                'name'  => 'place_email',
                'label' => 'اميل الشيرا',
                'type'  => 'email',
                'tab'   => 'بيانات الشيرا',
            ],
            [
                'label' => 'صورة المدنية',
                'name' => "ownerimage",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-3'],

                'tab'   => 'بيانات الشيرا',

            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imgcert",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-3'],
                'tab'   => 'بيانات الشيرا',

            ],
            [
                'label' => 'صورة اعتماد توقيع',
                'name' => "signatureimage",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-3'],

                'tab'   => 'بيانات الشيرا',

            ],
            [
                'label' => 'اخرى',
                'name' => "otherimage",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'tab'   => 'بيانات الشيرا',

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
                'tab'   => 'فروع الشيرا',

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
                'label'           => 'حساب الشيرا البنكى',
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
