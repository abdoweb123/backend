<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ReportSectionsRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ReportSectionsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ReportSectionsCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Restaurants::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/reportsections');
        CRUD::setEntityNameStrings('reportsections', 'تقارير الاقسام');
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
                'name' => "OrderItem",
                'label' => "عدد الطلابات", // Table column heading
                'type' => "model_function",
                'function_name' => 'getOrderItemCount', // the method in your Model
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
        CRUD::setValidation(ReportSectionsRequest::class);

        CRUD::setFromDb(); // fields

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number']));
         */
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
