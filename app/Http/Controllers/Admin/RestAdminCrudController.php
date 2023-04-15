<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RestAdminRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RestAdminCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RestAdminCrudController extends CrudController
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
        $this->crud->addClause('where', 'parent_user', '!=',null);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/restadmin');
        CRUD::setEntityNameStrings('مدير', 'مديرين المطاعم');
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
                'label' => 'اسم المطعم',
                'type'  => 'text',
            ],
            [
                'name'  => 'address',
                'label' => 'العنوان',
                'type'  => 'text',
            ],
            [
                // any type of relationship
                'name'         => 'Owner', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم صحاب المطععم', // Table column heading
                'attribute' => 'name', // foreign key attribute that is shown to user
             ],
            [
                // any type of relationship
                'name'         => 'Owner', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'ايميل صحاب المطععم', // Table column heading
                'attribute' => 'email', // foreign key attribute that is shown to user
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
        CRUD::setValidation(RestAdminRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
        $this->crud->addFields([
            [   // relationship
                'type' => "relationship",
                'name' => 'Owner', // the method on your model that defines the relationship

                // OPTIONALS:
                // 'label' => "Category",
                'attribute' => "email", // foreign key attribute that is shown to user (identifiable attribute)
                // 'entity' => 'category', // the method that defines the relationship in your Model
                // 'model' => "App\Models\Category", // foreign key Eloquent model
                // 'placeholder' => "Select a category", // placeholder for the select2 input
             ],
            [
                'name'  => 'name',
                'label' => 'الاسم',
                'type'  => 'text',
            ],
            [
                'name'  => 'price',
                'label' => 'الثمن',
                'type'  => 'text',
            ],
            [
                'name'  => 'type',
                'type'  => 'hidden',
                'value' => 1,
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
