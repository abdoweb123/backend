<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CarCompanydrRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CarCompanydrCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CarCompanydrCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CarCompany::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/car-companis');
        CRUD::setEntityNameStrings('شركه', 'شركات السيارات الخاصه ');
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
                'label' => 'اسم الشركه',
                'type'  => 'text',
            ],
            [
                'name'      => 'logo', // The db column name
                'label'     => 'لوجو الشركه', // Table column heading
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
        CRUD::setValidation(CarCompanydrRequest::class);
        $this->crud->setOperationSetting('contentClass', 'col-md-12 bold-labels');

        $this->crud->addFields([
            [
                
                'name'  => 'name',
                'label' => 'اسم الشركه',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name'  => 'email',
                'label' => 'اميل الشركه',
                'type'  => 'email',
                'wrapper' => ['class' => 'form-group col-md-6'],
            ],
            [
                'label' => 'عنوان الشركه',
                'name' => "address",
                'type' => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'website',
                'label' => 'الموقع',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-6'],

            ],
            [
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'label' => 'لوجو الشركه',
                'name' => "logo",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imagecert",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'label' => 'اعتماد توقيع',
                'name' => "imagesignature",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],            
            [
                'label' => 'صورة المدنية',
                'name' => "ssidimage",
                'type' => 'image',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [ // Table
                'name'            => 'incharge',
                'label'           => 'اضافه مسئول',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'اسم المسؤول',
                    'desc'  => 'المسمي الوظيفي',
                    'price' => 'رقم الهاتف',
                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table
                'tab'             => 'المسؤولين',

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
                'name'         => 'name', // name of relationship method in the model
                'type'         => 'text',
                'label' => 'اسم الشركه',
            ],
            [
                'label' => 'لوجو الشركه',
                'name' => "logo",
                'type' => 'image',
                'prefix' => '/storage/public/',

            ],
            [
                'label' => 'عنوان الشركه',
                'name' => "address",
                'type' => 'text',
            ],
            [
                'name'  => 'email',
                'label' => 'البريد الالكترونى',
                'type'  => 'email',
                'wrapper' => ['class' => 'form-group col-md-3'],

            ],
            [
                'name'  => 'phone',
                'label' => 'رقم الهاتف',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],

            ],
            [
                'label' => 'صورة الرخصة',
                'name' => "imagecert",
                'type' => 'image',
                'prefix' => '/storage/public/',
            ],
            [
                'label' => 'اعتماد توقيع',
                'name' => "imagesignature",
                'type' => 'image',
                'prefix' => '/storage/public/',
            ],            
            [
                'label' => 'صورة المدنية',
                'name' => "ssidimage",
                'type' => 'image',
                'prefix' => '/storage/public/',            
            ],

            
            [ // Table
                'name'            => 'incharge',
                'label'           => 'المسؤولين',
                'type'            => 'table',
                'entity_singular' => 'جديده', // used on the "Add X" button
                'columns'         => [
                    'name'  => 'الاسم',
                    'desc'  => 'العنوان',
                    'price' => 'رقم الهاتف',
                ],
                'max' => 5, // maximum rows allowed in the table
                'min' => 0, // minimum rows allowed in the table

            ],
        ]);

    }
}
