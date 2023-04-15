<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RideListRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RideListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RideListCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    // use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Trip::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/ridelist');
        CRUD::setEntityNameStrings('توصيل', 'حركه التعاملات على التطبيق');
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
                'label' => '#',
                'type' => "number",
                'name' => 'id', // the method on your model that defines the relationship
            
            ],
            [
                'label' => 'اسم العميل',
                'type' => "relationship",
                'name' => 'user', // the method on your model that defines the relationship
            
            ],
            [
                'name'      => 'driver', // The db column name
                'label'     => 'اسم السائق', // Table column heading
                'type'      => 'relationship',
            ],
            [
            'label'     => 'عنوان العميل', // Table column heading
            'type'      => 'text',
            'name'      => 'address_from', // the method that defines the relationship in your Model
            ],
            [
                'label'     => 'وجهه العميل', // Table column heading
                'type'      => 'text',
                'name'      => 'address_to', // the method that defines the relationship in your Model
            ],  
            [
                'label'     => 'الثمن', // Table column heading
                'type'      => 'number',
                'name'      => 'total', // the method that defines the relationship in your Mo
            ],
            [
                'label'     => 'طريقه الدفع', // Table column heading
                'type'      => 'text',
                'name'      => 'payment_method', // the method that defines the relationship in your Model
            ],


        ]);

        $this->crud->addFilter([
            'name'  => 'status',
            'type'  => 'dropdown',
            'label' => 'نوع الطلب'
          ], [
            1 => 'تاكسى',
            2 => 'خاصه',
            3 => 'ذوى الاحتياجات',
            4 => 'اثاث',

          ], function($value) { // if the filter is active
            $this->crud->addClause('where', 'ride_type', $value);
          });

        $this->addCustomCrudFilters();
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupShowOperation()
    {
        CRUD::setValidation(RideListRequest::class);
        $this->crud->set('show.setFromDb', false);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
        $this->crud->setColumns([
            [
                'label' => '#',
                'type' => "number",
                'name' => 'id', // the method on your model that defines the relationship
            
            ],
            [
                'label' => 'اسم العميل',
                'type' => "relationship",
                'name' => 'user', // the method on your model that defines the relationship
            
            ],
            [
                'label'     => 'رقم هاتف العميل', // Table column heading
                'type'  => 'text',
                'name'      => 'user.phone', // The db column name
             ],
            [
                'name'      => 'driver', // The db column name
                'label'     => 'اسم السائق', // Table column heading
                'type'      => 'relationship',
            ],
            [
                'label'     => 'رقم هاتف السائق', // Table column heading
                'type'  => 'text',
                'name'      => 'driver.phone', // The db column name
             ],
            [
            'label'     => 'عنوان العميل', // Table column heading
            'type'      => 'text',
            'name'      => 'address_from', // the method that defines the relationship in your Model
            ],
            [
                'label'     => 'وجهه العميل', // Table column heading
                'type'      => 'text',
                'name'      => 'address_to', // the method that defines the relationship in your Model
            ],  
            [
                'label'     => 'الحاله', // Table column heading
                'type'      => 'text',
                'name'      => 'status', // the method that defines the relationship in your Model
            ],
            [
                'label'     => 'الثمن', // Table column heading
                'type'      => 'number',
                'name'      => 'total', // the method that defines the relationship in your Mo
            ],
            [
                'label'     => 'طريقه الدفع', // Table column heading
                'type'      => 'text',
                'name'      => 'payment_method', // the method that defines the relationship in your Model
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
