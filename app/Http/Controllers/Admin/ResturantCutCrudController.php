<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ResturantCutRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ResturantCutCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ResturantCutCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ResturantCut::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/resturantcut');
        CRUD::setEntityNameStrings('تسعير', 'تسعير المطاعم');
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
                'name'         => 'Restaurants', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم المطعم', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'  => 'persentage',
                'label' => 'نسبة',
                'type'  => 'text',

            ],
            [
                'name'  => 'fixed',
                'label' => 'ثابت شهري',
                'type'  => 'text',
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
        CRUD::setValidation(ResturantCutRequest::class);

        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [
                'name'         => 'Restaurants', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم المطعم', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model

            ],
            [
                'name'  => 'persentage',
                'label' => 'نسبة',
                'type'  => 'text',
                'prefix'     => '%',

            ],
            [
                'name'  => 'fixed',
                'label' => 'ثابت شهري',
                'type'  => 'text',
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
