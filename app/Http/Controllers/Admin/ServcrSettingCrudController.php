<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ServcrSetting;
use App\Http\Requests\ServcrSettingRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ServcrSettingCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ServcrSettingCrudController extends CrudController
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
        CRUD::setModel(\App\Models\ServcrSetting::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/servcrsetting');
        CRUD::setEntityNameStrings('تسعير', 'تسعير انواع الخدمات');
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
                'name'         => 'user', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم السائق', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'  => 'byrequest',
                'label' => 'عدد الطلابات',
                'type'  => 'text',
            ],
            [
                'name'  => 'hourly',
                'label' => 'عدد الساعات',
                'type'  => 'text',
            ],
            [
                'name'         => 'DriverSpacliy', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'التخصص',
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
        $this->crud->setCreateView('admin.index');

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

    public function savedrivermoney(Request $request){
        $allre=$request->all();
        $bykelp=$request->kelo;
        $bytrip=$request->bytrip;
        $bypercentage=$request->bypercentage;
        $bysalary=$request->bysalary;
        $user_id=$request->user_id;

        $NewItem= new ServcrSetting;
        $NewItem->user_id = $user_id;
        $NewItem->other = $bykelp;
        $NewItem->commission = $bypercentage;
        $NewItem->salary = $bysalary;
        $NewItem->byrequest = $bytrip;
        $NewItem->save();
        \Alert::success('عمليه ناجحه')->flash();
        return \Redirect::to($this->crud->route);




    }
    protected function setupShowOperation(){
        $this->crud->set('show.setFromDb', false);
        $this->crud->setColumns([
            [
                'name'         => 'user', // name of relationship method in the model
                'type'         => 'relationship',
                'label'        => 'اسم السائق', // Table column heading
                // OPTIONAL
                // 'entity'    => 'tags', // the method that defines the relationship in your Model
                'attribute' => 'name', // foreign key attribute that is shown to user
                // 'model'     => App\Models\Category::class, // foreign key model
            ],
            [
                'name'  => 'byrequest',
                'label' => 'عدد الطلابات',
                'type'  => 'text',
            ],
            [
                'name'  => 'hourly',
                'label' => 'عدد الساعات',
                'type'  => 'text',
            ],
            [
                'name'         => 'DriverSpacliy', // name of relationship method in the model
                'type'         => 'relationship',
                'label' => 'التخصص',
            ],
            [
                'name'  => 'commission',
                'label' => 'عموله',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
                'prefix' =>'%'
            ],
            [
                'name'  => 'salary',
                'label' => 'راتـــب',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'salary_cut',
                'label' => 'مبلغ المستقطع',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],
            [
                'name'  => 'other',
                'label' => 'اخرى',
                'type'  => 'text',
                'wrapper' => ['class' => 'form-group col-md-3'],
            ],



        ]);
    }
}
