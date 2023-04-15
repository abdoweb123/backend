<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\OrdersListRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class OrdersListCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class OrdersListCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
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
        CRUD::setModel(\App\Models\Order::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/orderslist');
        CRUD::setEntityNameStrings('طلب', 'الطلابات');
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
                'name'  => 'id',
                'label' => '#',
                'type'  => 'number',
            ],
            [
                'name'  => 'username',
                'label' => 'اسم العميل',
                'type'  => 'text',
            ],
            [
                'name'      => 'phone', // The db column name
                'label'     => 'هاتف العميل', // Table column heading
                'type'      => 'text',
            ],
            [
                'label'     => 'عنوان العميل', // Table column heading
                'type'      => 'text',
                'name'      => 'address', // the method that defines the relationship in your Model
            ],
            [
                'name'      => 'status', // The db column name
                'label'     => 'حاله الاوردر', // Table column heading
                'type'      => 'text',
            ],
            [
                'label'     => 'الثمن', // Table column heading
                'type'      => 'number',
                'name'      => 'total', // the method that defines the relationship in your Mo
            ],


        ]);

        $this->crud->addFilter([
            'name'  => 'status',
            'type'  => 'dropdown',
            'label' => 'نوع الطلب'
          ], [
            1 => 'مطعم',
            2 => 'كافيه',
            11 => 'الاسواق',
            12 => 'الجمعيات',

          ], function($value) { // if the filter is active
            $this->crud->addClause('where', 'cat_id', $value);
          });

        $this->addCustomCrudFilters();


    }

    /**
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void


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
