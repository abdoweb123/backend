@extends(backpack_view('blank'))

<@php
    use App\User;

    $users = User::whereHas('DriverSpacliy')->get();
@endphp

@section('content')
<div class="card"align="center">

    <div class="card-body">
    <h4>حساب السائقين</h4>
<hr>

<form method="post" action="/admin/savetdriverMoney" enctype="multipart/form-data">
    @csrf
    <div class="form-row">
      <div class="form-group col-md-6">
        <label for="inputState">ارسال لعضو</label>
        <select id="inputState" class="form-control" name="user_id">
          @foreach ($users as $user)
          <option value="{{ $user->id }}">{{ $user->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="form-group col-md-6">
        <label for="inputState">اختر طريقه الحساب</label>
        <select id="select-box" class="form-control">
          <option selected>اختر الطريقه...</option>
          <option value="1">الحساب بالكيلو</option>
          <option value="2">الحساب بالمشوار</option>
          <option value="3">الحساب بالنسبه</option>
          <option value="4">الحساب بالراتب</option>
          <option>...</option>
        </select>
      </div>
    </div>
    <div class="form-row">
      <div class="tab-container">

        <div id="tab-1" class="tab-content">
            <div class="form-group">
                <label for="exampleFormControlInput1">ادخل سعر الكيلو</label>
                <input type="number" class="form-control" name="kelo" id="exampleFormControlInput1" placeholder="سعر الكيلو">
            </div>
        </div>
        <div id="tab-2" class="tab-content">
            <div class="form-group">
                <label for="exampleFormControlInput1">ادخل سعر المشوار</label>
                <input type="number" class="form-control" name="bytrip"  id="exampleFormControlInput1" placeholder="سعر المشوار">
            </div>
        </div>
        <div id="tab-3" class="tab-content">
            <div class="form-group">
                <label for="exampleFormControlInput1">ادخل النسبه</label>
                <input type="number" class="form-control" name="bypercentage" id="exampleFormControlInput1" placeholder="ادخل النسبه">
            </div>
        </div>
       <div id="tab-4" class="tab-content">
        <div class="form-group">
            <label for="exampleFormControlInput1">ادخل الراتب</label>
            <input type="number" class="form-control" name="bysalary" id="exampleFormControlInput1" placeholder="ادخل الراتب الشهرى">
        </div>
        </div>


      </div>
    </div>

    <button type="submit" class="btn btn-primary">ارسال</button>



  </form>


</div>
</div>

<script type="text/javascript" src="https://code.jquery.com/jquery.js"></script>
<script type="text/javascript">
  //hide all tabs first
  $('.tab-content').hide();
//show the first tab content
$('#tab-1').show();

$('#select-box').change(function () {
   dropdown = $('#select-box').val();
  //first hide all tabs again when a new option is selected
  $('.tab-content').hide();
  //then show the tab content of whatever option value was selected
  $('#' + "tab-" + dropdown).show();
});
</script>



@endsection



@section('after_styles')
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/crud.css') }}">
	<link rel="stylesheet" href="{{ asset('packages/backpack/crud/css/show.css') }}">
@endsection

@section('after_scripts')
	<script src="{{ asset('packages/backpack/crud/js/crud.js') }}"></script>
	<script src="{{ asset('packages/backpack/crud/js/show.js') }}"></script>
@endsection

