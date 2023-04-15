<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RecuiredCarsFernRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RecuiredCarsFernCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RecuiredCarsFernCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SpecialCar::class);
        $this->crud->addClause('where', 'type', '=', '2');
        CRUD::setRoute(config('backpack.base.route_prefix') . '/recuiredcarsfern');
        CRUD::setEntityNameStrings('نوع', 'اداره سيارات الاثاث');
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
                'name'    => 'type',
                'label'   => 'النوع',
                'type'    => 'select_from_array',
                'options' => ['1' => 'ذوى الاحتياجات الخاصه',
                '2' => 'اثاث',
                '3' => 'الاشتراكات',
            ],
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
        CRUD::setValidation(RecuiredCarsFernRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [

                'name'  => 'name',
                'label' => 'اسم النوع',
                'type'  => 'text'
            ],
            [
                'label' => 'لوجو النوع',
                'name' => "image",
                'type' => 'image',
                'crop' => true, // set to true to allow cropping, false to disable
                'aspect_ratio' => 1,
            ],
            [
                'name'  => 'type',
                'type'  => 'hidden',
                'value' => 2,
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
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->setColumns([
            [
                'name'  => 'name',
                'label' => 'اسم النوع',
                'type'  => 'text'
            ],
            [
                'label' => 'لوجو النوع',
                'name' => "image",
                'type' => 'image',
                'prefix' => '/storage/public/',
            ],
        ]);

    }

}
