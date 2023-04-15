<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use App\Models\DriversSpecialty;
use App\Http\Requests\DriverRequest;
use Illuminate\Support\Facades\Hash;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Intervention\Image\ImageManagerStatic as Image;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;


/**
 * Class DriverCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class DriverCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;


    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/driver');
        CRUD::setEntityNameStrings('سائق', 'السائقين ');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addClause('whereHas', 'DriverSpacliy');



        $this->crud->addFilter([
            'name'        => 'DriverSpacliy',
            'type'        => 'select2_ajax',
            'label'       => 'اختر تخصص السائق',
            'placeholder' => 'Pick a category'
          ],
          url('admin/test/ajax-category-options'), // the ajax route
          function($value) { // if the filter is active
            $this->crud->addClause('whereHas', 'DriverSpacliy', function($query) use($value) {
                $query->where('drivers_specialty_id', '=', $value);
            });
          });
        
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => 'الاسم',
                'type'  => 'text',
            ],
            [
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
            ],
            [
                'name'  => 'status',
                'label' => 'الحاله',
                'type'  => 'text',
            ],
            [

                'name'         => 'MyCar', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'رقم السياره', // Table column heading
                'attribute' => 'car_number', // foreign key attribute that is shown to user
            ],

            [

                'name'         => 'Trip', // name of relationship method in the model
                'type'         => 'relationship_count',
                'label'        => 'اجمالي المبلغ', // Table column heading
                'suffix' => 'دينار', // to show "123 tags" instead of "123 items"

            ],
            [
                'name'         => 'DriverSpacliy', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'تخصص السائق',            
            ],
            [
                'name'     => 'created_at',
                'label'    => 'تاريخ التسجيل',
                'type'     => 'closure',
                'function' => function($entry) {
                    return 'سجل فى'.$entry->created_at;
                }           
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
        CRUD::setValidation(DriverRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');


        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => 'الاسم',
                'type'  => 'text',
            ],
            [
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],      
            [
                'name'  => 'phone_intreal',
                'label' => 'رقم هاتف السستم',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],        
            [
                'name'  => 'email',
                'label' => 'الايميل',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [   // Password
                'name'  => 'password',
                'label' => 'كلمه المرور',
                'type'  => 'password',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],            
            [
                'name'  => 'birth_date',
                'label' => 'تاريخ الميلاد',
                'type'  => 'date',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name'            => 'status',
                'label'           => "الحاله",
                'type'            => 'select_from_array',
                'options'         => ['available' => 'Available', 'unavailable' => 'Unavailable'],
                'allows_null'     => false,
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [   // date_range
                'name'  => ['start_blocked_at', 'end_blocked_at'], // db columns for start_date & end_date
                'label' => 'البلوك',
                'type'  => 'date_range',
                'wrapper' => ['class' => 'form-group col-md-6'],

            
                // OPTIONALS
                // default values for start_date & end_date
                'default'            => ['2019-03-28 01:01', '2019-04-05 02:00'], 
                // options sent to daterangepicker.js
                'date_range_options' => [
                    'timePicker' => true,
                    'locale' => ['format' => 'DD/MM/YYYY HH:mm']
                ]
            ],
            [
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار شركه",
                'entity' => 'CompanyOwner',
                'label' => 'اختر الشركه (للفرد بدون شركه اتركها فارغه)',
                'name'      => 'car_company_id',
                'entity'    => 'CompanyOwner', 
                'model'     => "App\Models\CarCompany", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'inline_create' => true,
                // 'inline_create' => [ 'entity' => 'car-companis' ], // specify the entity in singular
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار البلد",
                'entity' => 'Country',
                'label' => 'الجنسيه',
                'name'      => 'country_id',
                'entity'    => 'Country', 
                'model'     => "App\Models\Country", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [

                
                'label' => 'تخصص السائق',
                'type'      => 'select2_multiple',
                'name'      => 'DriverSpacliy', // the method that defines the relationship in your Model

                // optional
                'entity'    => 'DriverSpacliy', // the method that defines the relationship in your Model
                'model'     => "App\Models\DriversSpecialty", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user

                // also optional
                'options'   => (function ($query) {
                    return $query->orderBy('name', 'ASC')->get();
                }),
                'wrapper' => ['class' => 'form-group col-md-6'],



            ],
            [
                'label' => 'صوره السائق',
                'name' => "image",
                'type' => 'image',
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1,
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'label' => 'رقم البطاقة',
                'name' => "ssid_driver",
                'type' => 'number',
                'tab'  => 'تراخيص السائق',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'label' => 'عنوان السائق',
                'name' => "address",
                'type' => 'text',
                'tab'  => 'تراخيص السائق',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'label' => 'صوره الترخيص',
                'name' => "imgcert",
                'type' => 'image',
                'tab'  => 'تراخيص السائق',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],

            [
                'label' => 'صوره الجواز',
                'name' => "passport",
                'type' => 'image',
                'tab'  => 'تراخيص السائق',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],           
            [
                'label' => 'صوره البطاقة الاماميه',
                'name' => "ssidfront",
                'type' => 'image',
                'tab'  => 'تراخيص السائق',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],              
            [
                'label' => 'صوره البطاقة الخلفيه',
                'name' => "ssidback",
                'type' => 'image',
                'tab'  => 'تراخيص السائق',
                'wrapper' => ['class' => 'form-group col-md-6'],

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
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
        $this->crud->set('show.setFromDb', false);
        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => 'الاسم',
                'type'  => 'text',
            ],
            [
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
            ],
            [
                'name'  => 'phone_intreal',
                'label' => 'رقم هاتف السستم',
                'type'  => 'text',
            ],
            [
                'name'  => 'birth_date',
                'label' => 'تاريخ الميلاد',
                'type'  => 'date',
            ],
            [
                'name'  => 'email',
                'label' => 'الايميل',
                'type'  => 'text',

            ],
            [  
                // any type of relationship
                'name'         => 'Country', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'الجنسيه',
             ],
             [  
                // any type of relationship
                'name'         => 'CompanyOwner', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'اختر الشركه (للفرد بدون شركه اتركها فارغه)',
             ],

             [  
                // any type of relationship
                'name'         => 'CompanyOwner', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'تخصص السائق',
             ],

            [
                'name'  => 'status',
                'label' => 'الحاله',
                'type'  => 'text',
            ],
            [
                'label' => 'صوره السائق',
                'name' => "image",
                'type' => 'image',
                'prefix' => '/storage/public/',

            ],
            [
                'label' => 'البطاقة',
                'name' => "ssid_driver",
                'type' => 'text',
                'tab'  => 'تراخيص السائق',
            ],
            [
                'label' => 'صوره البطاقة الاماميه',
                'name' => "ssidfront",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'tab'  => 'تراخيص السائق',
            ],            
            [
                'label' => 'صوره البطاقة الخلفيه',
                'name' => "ssidback",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'tab'  => 'تراخيص السائق',
            ],
            [
                'label' => 'صوره الترخيص',
                'name' => "imgcert",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'tab'  => 'تراخيص السائق',
            ],
            [
                'label' => 'صوره الجواز',
                'name' => "passport",
                'type' => 'image',
                'prefix' => '/storage/public/',
                'tab'  => 'تراخيص السائق',
            ],

            [
                'label' => 'عنوان السائق',
                'name' => "address",
                'type' => 'text',
                'tab'  => 'تراخيص السائق',
            ],
        ]);

    }

    public function categoryOptions(Request $request) {
        $term = $request->input('term');
        $options = DriversSpecialty::where('name', 'like', '%'.$term.'%')->get()->pluck('name', 'id');
        return $options;
    }

    public function store()
    {
        $this->crud->setRequest($this->crud->validateRequest());

        /** @var \Illuminate\Http\Request $request */
        $request = $this->crud->getRequest();

        // Encrypt password if specified.
        if ($request->input('password')) {
            $request->request->set('password', Hash::make($request->input('password')));
        } else {
            $request->request->remove('password');
        }

        $this->crud->setRequest($request);
        $this->crud->unsetValidation(); // Validation has already been run

        return $this->traitStore();
    }


}
