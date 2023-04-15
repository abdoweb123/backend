<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MainCategorysRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MainCategorysCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MainCategorysCrudController extends CrudController
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
        CRUD::setModel(\App\Models\MainCategories::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/maincategorys');
        CRUD::setEntityNameStrings('قسم', 'الاقسام العامه');
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
                'label' => 'اسم الصوره',
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
        CRUD::setValidation(MainCategorysRequest::class);

        $this->crud->addField([
            'label' => "اسم القسم",
            'name' => "name",
            'type' => 'text',
        ]);

        $this->crud->addField([
            'label' => "صوره القسم",
            'name' => "image",
            'type' => 'image',
            'crop' => true, // set to true to allow cropping, false to disable
            'aspect_ratio' => 1, // ommit or set to 0 to allow any aspect ratio
            // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
            // 'prefix'    => 'uploads/images/profile_pictures/'  in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
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

    protected function setupShowOperation()
    {
        $this->setupListOperation();
        // by default the Show operation will try to show all columns in the db table,
        // but we can easily take over, and have full control of what columns are shown,
        // by changing this config for the Show operation 
        $this->crud->set('show.setFromDb', false);

        CRUD::column('image')->type('image')->size(6);


        // // example logic
        // $this->crud->addColumn([
        //     'name' => 'table',
        //     'label' => 'Table',
        //     'type' => 'table',
        //     'columns' => [
        //         'name'  => 'Name',
        //         'desc'  => 'Description',
        //         'price' => 'Price',
        //     ]
        // ]);
        // $this->crud->addColumn([
        //     'name' => 'fake_table',
        //     'label' => 'Fake Table',
        //     'type' => 'table',
        //     'columns' => [
        //         'name'  => 'Name',
        //         'desc'  => 'Description',
        //         'price' => 'Price',
        //     ],
        // ]);
        // $this->crud->addColumn('text');
        // $this->crud->removeColumn('date');
        // $this->crud->removeColumn('extras');

        // Note: if you HAVEN'T set show.setFromDb to false, the removeColumn() calls won't work
        // because setFromDb() is called AFTER setupShowOperation(); we know this is not intuitive at all
        // and we plan to change behaviour in the next version; see this Github issue for more details
        // https://github.com/Laravel-Backpack/CRUD/issues/3108
    }

}
