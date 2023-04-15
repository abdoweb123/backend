<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
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
        CRUD::setModel(\App\User::class);
        $this->crud->addClause('where', 'is_driver', '=', '0');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/client');
        CRUD::setEntityNameStrings('عميل', 'العملاء');
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
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
            ],
            [
                // 'label'        => 'الجنسيه', // Table column heading
                // 'type'         => 'relationship',
                // 'name'         => 'Country', // name of relationship method in the model
                'name'  => 'Country.name',
                'label' => 'الجنسيه',
                'type'  => 'test',

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
        CRUD::setValidation(UserRequest::class);
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
            ],            
            [
                'name'  => 'email',
                'label' => 'الايميل',
                'type'  => 'text',
            ],
            [
                'name'  => 'birth_date',
                'label' => 'تاريخ الميلاد',
                'type'  => 'date',
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
            'name'  => 'email',
            'label' => 'الايميل',
            'type'  => 'text',
        ],
        [
            'name'  => 'birth_date',
            'label' => 'تاريخ الميلاد',
            'type'  => 'date',
            'wrapper' => ['class' => 'form-group col-md-6'],

        ],
        [  
        // any type of relationship
        'name'         => 'Country', // name of relationship method in the model
        'type'         => 'relationship',
        'label' => 'الجنسيه',
        ],
         
        ]);

    }
}
