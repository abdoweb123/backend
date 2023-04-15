<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SubscrRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SubscrCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SubscrCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Subscription::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/subscriptions');
        CRUD::setEntityNameStrings('اشتراك', 'الاشتراكات الشهريه');
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

                'name'         => 'user', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم العميل', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],

            [

                'name'         => 'driver', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم السائق', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
                'options'   => (function ($query) {
                    return $query->whereHas('DriverSpacliy')->get();
                }), //  you can use this to filter the results show in the select
            ],
            [
                'name'  => 'from_date',
                'label' => 'من تاريخ',
                'type'  => 'date',
            ],
            [
                'name'  => 'to_date',
                'label' => 'تاريخ الانتهاء',
                'type'  => 'date',
            ],
            
            
            [
                'name'  => 'going_coming',
                'label' => 'ذهاب وعوده',
                'type'  => 'boolean',
                'options' => [0 => 'لا',
                             1 => 'نعم']
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
        CRUD::setValidation(SubscrRequest::class);

        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [
                
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار عميل",
                'entity' => 'user',
                'label' => 'اسم العميل',
                'name'      => 'user_id',
                'entity'    => 'user', 
                'model'     => "App\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'options'   => (function ($query) {
                     return $query->orderBy('name', 'ASC')->where('is_driver',0)->get();
                 }), //  you can use this to filter the results show in the select
                 'wrapper' => ['class' => 'form-group col-md-6'],

            ],

            [
                'type' => "relationship",
                'attribute' => 'name',
                'placeholder' => "اختار عميل",
                'entity' => 'user',
                'label' => 'اسم السائق',
                'name'      => 'driver_id',
                'entity'    => 'user', 
                'model'     => "App\User", // related model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'options'   => (function ($query) {
                     return $query->orderBy('name', 'ASC')->where('is_driver',1)->get();
                 }), //  you can use this to filter the results show in the select
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                
                'name'  => 'from_date',
                'label' => 'من تاريخ',
                'type'  => 'date',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                
                'name'  => 'to_date',
                'label' => 'الى تاريخ',
                'type'  => 'date',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name'  => 'from_address',
                'label' => 'من العنوان',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'from_lng',
                'label' => 'من خط طول',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'from_lat',
                'label' => 'من خط عرض',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'to_address',
                'label' => 'الى العنوان',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'name'  => 'to_lng',
                'label' => 'الى خط طول',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'to_lat',
                'label' => 'الى خط عرض',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [  
                'name'  => 'from_time',
                'label' => 'من الساعه',
                'type'  => 'time',
                'wrapper' => ['class' => 'form-group col-md-3'],

            ],

            [   
                'name'  => 'to_time',
                'label' => 'الى الساعه',
                'type'  => 'time',
                'wrapper' => ['class' => 'form-group col-md-3'], 

            ],
            [
                'label'     => "نوع السياره",
                'type'      => 'select2',
                'name'      => 'driver_spacliy_id', // the db column for the foreign key
             
                // optional
                'entity'    => 'DriverSpacliy', // the method that defines the relationship in your Model
                'model'     => "App\Models\DriversSpecialty", // foreign key model
                'attribute' => 'name', // foreign key attribute that is shown to user
                'default'   => 2, // set the default value of the select2
             
                 // also optional
                'options'   => (function ($query) {
                     return $query->whereIn('id', [2,4, 3])->get();
                 }), // force the related options to be a custom query, instead of all(); you can use this to filter the results show in the select
                 'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            
            [
                
                'name'        => 'going_coming',
                'label'       => "ذهاب وعوده",
                'type'        => 'select_from_array',
                'options'     => ['0' => 'لا', 
                                '1' => 'نعم'],
                'allows_null' => false,

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

                'name'         => 'user', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم العميل', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],

            [

                'name'         => 'driver', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم السائق', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
                'options'   => (function ($query) {
                    return $query->whereHas('DriverSpacliy')->get();
                }), //  you can use this to filter the results show in the select
            ],
            [
                'name'  => 'from_date',
                'label' => 'من تاريخ',
                'type'  => 'date',
            ],
            [
                'name'  => 'to_date',
                'label' => 'تاريخ الانتهاء',
                'type'  => 'date',
            ],
            [
                
                'name'  => 'from_date',
                'label' => 'من تاريخ',
                'type'  => 'date',

            ],
            [
                
                'name'  => 'to_date',
                'label' => 'الى تاريخ',
                'type'  => 'date',

            ],
            [
                'name'  => 'from_address',
                'label' => 'من العنوان',
                'type'  => 'text',
            ],
            [
                'name'  => 'from_lng',
                'label' => 'من خط طول',
                'type'  => 'text',
            ],
            [
                'name'  => 'from_lat',
                'label' => 'من خط عرض',
                'type'  => 'text',
            ],
            [
                'name'  => 'to_address',
                'label' => 'الى العنوان',
                'type'  => 'text',
            ],
            [
                'name'  => 'to_lng',
                'label' => 'الى خط طول',
                'type'  => 'text',
            ],
            [
                'name'  => 'to_lat',
                'label' => 'الى خط عرض',
                'type'  => 'text',
            ],
            [  
                'name'  => 'from_time',
                'label' => 'من الساعه',
                'type'  => 'time',

            ],

            [   
                'name'  => 'to_time',
                'label' => 'الى الساعه',
                'type'  => 'time',

            ],
            
            
            [
                'name'  => 'going_coming',
                'label' => 'ذهاب وعوده',
                'type'  => 'boolean',
                'options' => [0 => 'لا',
                             1 => 'نعم']
            ],

            [ 
                'name'            => 'working_days',
                'label'           => 'ايام العمل',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'acount_name' => 'اليوم',
                    'bank_name'  => 'من الساعه',
                    'credit_number' => 'الى الساعه',
                ],
                'max' => 7, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'   => 'ايام العمل',

            ],




        ]);

    }
}
