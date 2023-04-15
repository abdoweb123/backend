<?php
    namespace App\Http\Controllers\Admin;
    use App\Http\Requests\ServOptionRequest;
    use Backpack\CRUD\app\Http\Controllers\CrudController;
    use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

    /**
     * Class ServOptionCrudController
     * @package App\Http\Controllers\Admin
     * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
     */
    class ServOptionCrudController extends CrudController
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
            CRUD::setModel(\App\Models\RoadServiceOption::class);
            CRUD::setRoute(config('backpack.base.route_prefix') . '/roadserviceoptions');
            CRUD::setEntityNameStrings('خيار', 'خيارات خدمه الطريق');
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

                    'name'         => 'name', // name of relationship method in the model
                    'type'         => 'text',
                    'label'        => 'اسم الخيار', // Table column heading

                ],
                [

                    'name'         => 'RoadService', // name of relationship method in the model
                    'type'         => 'relationship',
                    'label'        => 'نوع الخدمه', // Table column heading
                    // OPTIONAL
                    // 'entity'    => 'tags', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    // 'model'     => App\Models\Category::class, // foreign key model
                ],
                [
                    'name'      => 'image', // The db column name
                    'label'     => 'صوره الخدمه', // Table column heading
                    'type'      => 'image',
                    'prefix' => '/storage/public/',

                ],
                [
                    'name'         => 'price', // name of relationship method in the model
                    'type'         => 'text',
                    'label'        => 'السعر', // Table column heading
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
            CRUD::setValidation(ServOptionRequest::class);
            $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
            $this->crud->addFields([
                [

                    'type' => "relationship",
                    'attribute' => 'name',
                    'placeholder' => "اختار الخدمه",
                    'entity' => 'RoadService',
                    'label' => 'اسم الرئيسيه',
                    'name'      => 'road_services_id',
                    'entity'    => 'RoadService',
                    'model'     => "App\Models\RoadService", // related model
                    'attribute' => 'name', // foreign key attribute that is shown to user

                ],
                [

                    'name'  => 'name',
                    'label' => 'اسم الفرعيه',
                    'type'  => 'text',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
                [
                    'name'         => 'price', // name of relationship method in the model
                    'type'         => 'text',
                    'label'        => 'السعر', // Table column heading
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
                [
                    'name'  => 'description',
                    'type'  => 'ckeditor',
                    'label' => ' الوصف بالغه العربيه',
                    'wrapper' => ['class' => 'form-group col-md-6'],

                ],
                [
                    'name'  => 'description_en',
                    'type'  => 'ckeditor',
                    'label' => ' الوصف بالغه الانجليزيه',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
                [
                    'label'     => 'صوره الخدمه', // Table column heading
                    'name' => "image",
                    'type' => 'image',
                    // 'disk'      => 's3_bucket', // in case you need to show images from a different disk
                    // 'prefix'    => 'uploads/images/profile_pictures/' // in case your db value is only the file name (no path), you can use this to prepend your path to the image src (in HTML), before it's shown to the user;
                    'prefix' => '/storage/public/',

                ],

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
        protected function setupShowOperation(){
            $this->crud->set('show.setFromDb', false);
            $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');
            $this->crud->setColumns([
                [

                    'name'         => 'name', // name of relationship method in the model
                    'type'         => 'text',
                    'label'        => 'اسم الخيار', // Table column heading

                ],
                [

                    'name'         => 'RoadService', // name of relationship method in the model
                    'type'         => 'relationship',
                    'label'        => 'نوع الخدمه', // Table column heading
                    // OPTIONAL
                    // 'entity'    => 'tags', // the method that defines the relationship in your Model
                    'attribute' => 'name', // foreign key attribute that is shown to user
                    // 'model'     => App\Models\Category::class, // foreign key model
                ],
                [
                    'name'      => 'image', // The db column name
                    'label'     => 'صوره الخدمه', // Table column heading
                    'type'      => 'image',
                    'prefix' => '/storage/public/',

                ],
                [
                    'name'  => 'description',
                    'type'  => 'ckeditor',
                    'label' => ' الوصف بالغه العربيه',
                    'wrapper' => ['class' => 'form-group col-md-6'],

                ],
                [
                    'name'  => 'description_en',
                    'type'  => 'ckeditor',
                    'label' => ' الوصف بالغه الانجليزيه',
                    'wrapper' => ['class' => 'form-group col-md-6'],
                ],
                [
                    'name'         => 'price', // name of relationship method in the model
                    'type'         => 'text',
                    'label'        => 'السعر', // Table column heading
                ],
            ]);

        }
    }
