<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RoadServRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class RoadServCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class RoadServCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
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
        CRUD::setModel(\App\Models\RoadService::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/RoadServices');
        CRUD::setEntityNameStrings('خدمه', 'خدمات الطريق');
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
                'label' => 'الاسم باللغه العربيه',
                'type'  => 'text',
            ],
            [
                'name'  => 'name_en',
                'label' => 'الاسم باللغه الانجليزيه',
                'type'  => 'text',
            ],
            [
                'name'      => 'image', // The db column name
                'label'     => 'صوره', // Table column heading
                'type'      => 'image',
                'prefix' => '/storage/public/',
                // image from a different disk (like s3 bucket)
                // 'disk'   => 'disk-name', 
                // optional width/height if 25px is not ok with you
                // 'height' => '30px',
                // 'width'  => '30px',
            ],


        ]);
        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(RoadServRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [
                'name'  => 'name',
                'label' => 'الاسم باللغه العربيه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
        ]);

        $this->crud->addFields([
            [
                'name'  => 'name_en',
                'label' => 'الاسم باللغه الانجليزيه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
        ]);
        $this->crud->addField([
            'label' => "صوره",
            'name' => "image",
            'type' => 'image',
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
            // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
        ]);
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
