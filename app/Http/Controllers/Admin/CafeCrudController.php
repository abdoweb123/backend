<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CafeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CafeCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CafeCrudController extends CrudController
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
        $this->crud->addClause('where', 'type_id', '=', '2');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/cafes');
        CRUD::setEntityNameStrings('كافيه', 'اداره الكافيهات');
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
        CRUD::setValidation(CafeRequest::class);

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
                    return $query->orderBy('name', 'ASC')->where('type_id',2)->get();
                }),  //  you can use this to filter the results show in the select

                    'inline_create' => true,
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
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1,
                'wrapper' => ['class' => 'form-group col-md-6'],

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
                'name'  => 'country',
                'label' => 'بلد',
                'type'  => 'text'
            ],
            [
                'name'  => 'government',
                'label' => 'المحافظه',
                'type'  => 'text'
            ],
            [
                'name'  => 'district',
                'label' => 'مدينه',
                'type'  => 'text'
            ],
            [
                'name'            => 'type_id',
                'label'           => "نوع المنشئه",
                'type'            => 'select_from_array',
                'options'         => ['2' => 'كافيه'],
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
                'wrapper' => ['class' => 'form-group col-md-4'],

                'tab'   => 'بيانات المطعم',

            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imgcert",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-4'],
                'tab'   => 'بيانات المطعم',

            ],    
            [      
                'label' => 'صورة اعتماد توقيع',
                'name' => "signatureimage",
                'type' => 'image',

                'wrapper' => ['class' => 'form-group col-md-4'],

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
                'max' => 5, // maximum rows allowed in the table
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
    protected function setupShowOperation(){
        $this->crud->set('show.setFromDb', false);
        $this->crud->setColumns([
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
                'name'  => 'country',
                'label' => 'بلد',
                'type'  => 'text'
            ],
            [
                'name'  => 'government',
                'label' => 'المحافظه',
                'type'  => 'text'
            ],
            [
                'name'  => 'district',
                'label' => 'مدينه',
                'type'  => 'text'
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
                'max' => 5, // maximum rows allowed in the table
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
