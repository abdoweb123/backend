<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarsRegistrationRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarsRegistrationCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarsRegistrationCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CarsRegistration::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/carsregistration');
        CRUD::setEntityNameStrings('سياره', 'السيارات المسجله');
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
                'name'  => 'car_number',
                'label' => 'رقم السياره',
                'type'  => 'text',
            ],
            [
                'name'         => 'user', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم السائق', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'      => 'image', // The db column name
                'label'     => 'صوره السياره', // Table column heading
                'type'      => 'image',
                'prefix' => '/storage/public/',
                // 'height' => '30px',
                // 'width'  => '30px',
            ],
            [
                'name'         => 'CarsModel', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'موديل السياره', // Table column heading
                // OPTIONAL
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'         => 'cartype', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'نوع السياره', // Table column heading
                // OPTIONAL
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'         => 'CarsModel', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'موديل السياره', // Table column heading
                // OPTIONAL
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],            [
                'name'  => 'expiry_date',
                'label' => 'تاريخ الانتهاء',
                'type'  => 'date',
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
        CRUD::setValidation(CarsRegistrationRequest::class);

        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [
                
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار سائق",
                'entity' => 'user',
                'label' => 'اسم السائق',
                'name'      => 'user_id',
                'entity'    => 'user', 
                'model'     => "App\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'options'   => (function ($query) {
                     return $query->whereHas('DriverSpacliy')->get();
                 }), //  you can use this to filter the results show in the select
            ],
            [
                'name'  => 'car_number',
                'label' => 'رقم السياره',
                'type'  => 'text',
            ],
            [
                'label' => 'صوره السياره',
                'name' => "image",
                'type' => 'image',
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1,
            ],
            [
                'name'            => 'car_color',
                'label'           => "لون السياره",
                'type'            => 'select_from_array',
                'options'         => ['Blue' => 'أزرق',
                                      'Green' => 'أخضر',            
                                      'Yellow' => 'أصفر',            
                                      'Orange' => 'برتقالي',            
                                      'White' => 'أبيض',
                                      'Black' => 'اسود',   
                                      'Violet' => 'بنفسجي',
                                      'Red' => 'أحمر',  
                                      'Silver' => 'فضي',   
                                      'Gray' => 'رصاصي',
                                      'Brown' => 'بنّي',
                                    ],
                'allows_null'     => false,
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'label' => 'عدد السنلدر',
                'name' => "sanelder_number",
                'type' => 'number',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'type' => "relationship",
                'attribute' => 'name',
                'entity' => 'cartype',
                'label' => 'نوع السياره',
                'name'      => 'car_type_id',
                'entity'    => 'cartype', 
                'model'     => "App\Models\SpecialCar", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
            ],
            [
                'type' => "relationship",
                'attribute' => 'name',
            'entity' => 'CarsModel',
                'label' => 'موديل السياره',
                'name'      => 'car_model',
                'entity'    => 'CarsModel', 
                'model'     => "App\Models\CarModels", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
            ],
            // [

            //     'label'  => 'تاريخ انتهاء الرخصه',
            //     'name' => 'expiry_date',
            //     'type'  => 'date',
            //     'tab'             => 'تراخيص السياره',

            // ],
            [   // Table
                'name'            => 'laborers',
                'label'           => 'اضافه العمال والفنين',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اسم العامل',
                    'type'  => 'نوع',
                    'price'  => 'سعر العامل',

                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'             => 'العمال والفنين',
            ],
            [
                'name'            => 'license_type',
                'label' => 'نوع ترخيص السياره',
                'type'            => 'select_from_array',
                'options'         => ['Taxi' => 'تاكسي',
                'private_car' => 'اجرة تحت الطلب',
                'special_car' => 'خاصة',
                'delivery_car' => 'توصيل طلبات',
                'others' => 'آخري',
                ],
                'allows_null'     => false,
                'allows_multiple' => false,
                'tab'             => 'تراخيص السياره',
                'wrapper' => ['class' => 'form-group col-md-4'],

            ],
            [
                'label' => 'صوره تراخيص السياره',           
                'name' => "imagecert",
                'type' => 'image',
                'tab'             => 'تراخيص السياره',
                'wrapper' => ['class' => 'form-group col-md-4'],

            ],

            [
                'label' => 'دفتر السيارة',
                'name' => "carbook",
                'type' => 'image',
                'tab'             => 'تراخيص السياره',
                'wrapper' => ['class' => 'form-group col-md-4'],

            ],
        ]);
    }

    protected function setupShowOperation(){
        $this->crud->set('show.setFromDb', false);

        $this->crud->setColumns([
            [
                'name'  => 'car_number',
                'label' => 'رقم السياره',
                'type'  => 'text',
            ],
            [
                'name'         => 'user', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم السائق', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'      => 'image', // The db column name
                'label'     => 'صوره السياره', // Table column heading
                'type'      => 'image',
                'prefix' => '/storage/public/',
                // 'height' => '30px',
                // 'width'  => '30px',
            ],
            [
                'name'         => 'CarsModel', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'موديل السياره', // Table column heading
                // OPTIONAL
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'         => 'cartype', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'نوع السياره', // Table column heading
                // OPTIONAL
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'         => 'CarsModel', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'موديل السياره', // Table column heading
                // OPTIONAL
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],            [
                'name'  => 'expiry_date',
                'label' => 'تاريخ الانتهاء',
                'type'  => 'date',
            ],            
            [
                'name'      => 'imagecert', // The db column name
                'label'     => 'صوره رخصه السياره', // Table column heading
                'type'      => 'image',
                'prefix' => '/storage/public/',
                // 'height' => '30px',
                // 'width'  => '30px',
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
