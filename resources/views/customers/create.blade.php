<x-app-layout>
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-header card-header-icon card-header-theme">
          <div class="card-icon">
            <i class="material-icons">perm_identity</i>
          </div>
          <h4 class="card-title ">{!! trans('panel.customers.create_title') !!}
            <span class="pull-right">
              <div class="btn-group">
                @if(auth()->user()->can(['customer_access']))
                <a href="{{ url('customers') }}" class="btn btn-just-icon btn-theme" title="{!! trans('panel.lead.title_singular') !!}{!! trans('panel.global.list') !!}"><i class="material-icons">next_plan</i></a>
                @endif
              </div>
            </span>
          </h4>
        </div>
        <div class="card-body">
          @if(count($errors) > 0)
          <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <i class="material-icons">close</i>
            </button>
            <span>
              @foreach($errors->all() as $error)
              <li>{{$error}}</li>
              @endforeach
            </span>
          </div>
          @endif
          {!! Form::model($customers,[
          'route' => $customers->exists ? ['customers.update', $customers->id] : 'customers.store',
          'method' => $customers->exists ? 'PUT' : 'POST',
          'id' => 'storeCustomerData',
          'files'=>true
          ]) !!}
          <input type="hidden" name="id" id="customer_id" value="{!! $customers['id'] !!}">
          <div class="first-box">
            <div class="row">
              <div class="col-md-3 ml-auto mr-auto">
                <div class="fileinput fileinput-new" data-provides="fileinput">

                  <div class="fileinput-new thumbnail">
                    <img src="{!! !empty($customers['profile_image']) ? $customers['profile_image'] : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview7">
                    <div class="selectThumbnail">
                      <span class="btn btn-just-icon btn-round btn-file">
                        <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="image" class="getimage7" accept="image/*">
                      </span>
                      <br>
                      <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                    </div>
                  </div>
                  <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                  <label class="bmd-label-floating">{!! trans('panel.customers.fields.shop_image') !!}</label>
                  @if ($errors->has('image'))
                  <div class="error col-lg-12">
                    <p class="text-danger">{{ $errors->first('image') }}</p>
                  </div>
                  @endif
                </div>
              </div>
              <div class="col-md-3 ml-auto mr-auto">
                <div class="fileinput fileinput-new" data-provides="fileinput">

                  <div class="fileinput-new thumbnail">
                    <img src="{!! !empty($customers['shop_image']) ? $customers['shop_image'] : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview8">
                    <div class="selectThumbnail">
                      <span class="btn btn-just-icon btn-round btn-file">
                        <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                        <span class="fileinput-exists">Change</span>
                        <input type="file" name="profileImage" class="getimage8" accept="image/*">
                      </span>
                      <br>
                      <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                    </div>
                  </div>
                  <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
                  <label class="bmd-label-floating">{!! trans('panel.customers.fields.profile_image') !!}</label>
                  @if ($errors->has('profileImage'))
                  <div class="error col-lg-12">
                    <p class="text-danger">{{ $errors->first('profileImage') }}</p>
                  </div>
                  @endif
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">{!! trans('panel.customers.fields.name') !!}<span class="text-danger"> *</span></label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="name" class="form-control" value="{!! old( 'name', $customers['name']) !!}" maxlength="200" required>
                    @if ($errors->has('name'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('name') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Customer ID </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="customer_code" id="customer_code" class="form-control" value="{!! old( 'customer_code', $customers['customer_code']) !!}" maxlength="200">
                    @if ($errors->has('customer_code'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('customer_code') }}</p>
                    </div>
                    @endif
                  </div>

                </div>
              </div>

              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">{!! trans('panel.customers.fields.first_name') !!} <span class="text-danger"> *</span></label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="first_name" class="form-control" value="{!! old( 'first_name', $customers['first_name']) !!}" maxlength="200" required>
                    @if ($errors->has('first_name'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('first_name') }}</p>
                    </div>
                    @endif
                  </div>

                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">{!! trans('panel.customers.fields.last_name') !!}</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="last_name" class="form-control" value="{!! old( 'last_name', $customers['last_name']) !!}" maxlength="200">
                    @if ($errors->has('last_name'))
                    <div class="error col-lg-12">
                      <p class="text-danger">{{ $errors->first('last_name') }}</p>
                    </div>
                    @endif
                  </div>

                </div>
              </div>

              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">{!! trans('panel.customers.fields.customertype') !!}<span class="text-danger"> *</span></label>

                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control select2" name="customertype" style="width: 100%;" required id="type">
                      <option value="">Select {!! trans('panel.customers.fields.customertype') !!}</option>
                      @if(@isset($customertype ))
                      @foreach($customertype as $type)
                      <option value="{!! $type['id'] !!}" {{ old( 'customertype' , (!empty($customers->customertype))?($customers->customertype):('') ) == $type['id'] ? 'selected' : '' }}>{!! $type['customertype_name'] !!}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>
                  @if ($errors->has('customertype'))
                  <div class="error">
                    <p class="text-danger">{{ $errors->first('customertype') }}</p>
                  </div>
                  @endif

                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Contact Number 1<span class="text-danger"> *</span></label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="mobile" pattern="[0-9]{10}" id="mobile" class="form-control" value="{!! old( 'mobile', $customers['mobile']) !!}" required>
                  </div>
                  @if ($errors->has('mobile'))
                  <label class="error">{{ $errors->first('mobile') }}</label>
                  @endif
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Mail ID</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="email" name="email" id="email" class="form-control" value="{!! old( 'email', $customers['email']) !!}" maxlength="200">
                    @if ($errors->has('email'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('email') }}</p>
                    </div>
                    @endif
                  </div>

                </div>
              </div>


              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Sales Person

                    <?php
                    $userarray = array();
                    ?>
                    @foreach($customers->getemployeedetail as $key_new => $datas)
                    <?php $userarray[] = $datas->user_id; ?>
                    @endforeach

                  </label>

                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control select2" name="executive_id[]" style="width: 100%;" multiple>
                      <!-- <option value="">Select Employee</option> -->
                      @if(@isset($users ))

                      @foreach($users as $user)
                      <option value="{!! $user['id'] !!}" <?php if (in_array($user->id, $userarray)) {
                                                            echo "selected";
                                                          } ?>>{!! $user['name'] !!}</option>
                      @endforeach
                      @endif
                    </select>
                  </div>
                  @if ($errors->has('executive_id'))
                  <div class="error">
                    <p class="text-danger">{{ $errors->first('executive_id') }}</p>
                  </div>
                  @endif

                </div>
              </div>

              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">{!! trans('panel.global.contact_number_two') !!}</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="contact_number" id="contact_number" class="form-control" value="{!! old( 'contact_number', $customers['contact_number']) !!}" maxlength="13" minlength="10">
                  </div>
                  @if ($errors->has('contact_number'))
                  <label class="error">{{ $errors->first('contact_number') }}</label>
                  @endif

                </div>
              </div>


              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Discom<span class="text-danger"> *</span></label>

                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control select2" name="working_status" style="width: 100%;" required id="type">
                      <option value="">Select Discom</option>
                      <option value="MPCZ" {{($customers && $customers['working_status'] == 'MPCZ')? 'selected':''}}>MPCZ</option>
                      <option value="MPEZ" {{($customers && $customers['working_status'] == 'MPEZ')? 'selected':''}}>MPEZ</option>
                      <option value="MPWZ" {{($customers && $customers['working_status'] == 'MPWZ')? 'selected':''}}>MPWZ</option>
                      <option value="DHBVN" {{($customers && $customers['working_status'] == 'DHBVN')? 'selected':''}}>DHBVN</option>
                      <option value="BRPL" {{($customers && $customers['working_status'] == 'BRPL')? 'selected':''}}>BRPL</option>
                      <option value="TPDDL" {{($customers && $customers['working_status'] == 'TPDDL')? 'selected':''}}>TPDDL</option>
                      <option value="BYPL" {{($customers && $customers['working_status'] == 'BYPL')? 'selected':''}}>BYPL</option>
                      <option value="NDMC" {{($customers && $customers['working_status'] == 'NDMC')? 'selected':''}}>NDMC</option>
                      <option value="Uttar Pradesh" {{($customers && $customers['working_status'] == 'Uttar Pradesh')? 'selected':''}}>Uttar Pradesh</option>
                    </select>
                  </div>
                  @if ($errors->has('working_status'))
                  <div class="error col-lg-12">
                    <p class="text-danger">{{ $errors->first('working_status') }}</p>
                  </div>
                  @endif

                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Creation Date</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="creation_date" id="creation_date" class="form-control datepicker" value="{!! old( 'contact_number', $customers['creation_date']) !!}" autocomplete="off">
                  </div>
                  @if ($errors->has('creation_date'))
                  <div class="error">
                    <p class="text-danger">{{ $errors->first('creation_date') }}</p>
                  </div>
                  @endif
                </div>

              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Capacity (in KW)</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="sap_code" id="sap_code" class="form-control" value="{!! old( 'contact_number', $customers['sap_code']) !!}" autocomplete="off">
                  </div>
                  @if ($errors->has('sap_code'))
                  <div class="error">
                    <p class="text-danger">{{ $errors->first('sap_code') }}</p>
                  </div>
                  @endif
                </div>

              </div>

              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label" for="latitude">Latitude</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="latitude" id="latitude" class="form-control" value="{!! old( 'latitude', $customers['latitude']) !!}" autocomplete="off">
                  </div>
                  @if ($errors->has('latitude'))
                  <div class="error">
                    <p class="text-danger">{{ $errors->first('latitude') }}</p>
                  </div>
                  @endif
                </div>

              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label" for="longitude">Longitude</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="longitude" id="longitude" class="form-control" value="{!! old( 'longitude', $customers['longitude']) !!}" autocomplete="off">
                  </div>
                  @if ($errors->has('longitude'))
                  <div class="error">
                    <p class="text-danger">{{ $errors->first('longitude') }}</p>
                  </div>
                  @endif
                </div>

              </div>

              
              {{--<div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Change The Password</label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="checkbox" id="change_password" name="pass_check"
                      style="width: 16px; height: 16px; margin-left: 12;" value="" />
                  </div>
                </div>
              </div> --}}             


              <div class="col-md-6" id="parentcustomer" style="display:none;">
                <div class="input_section">
                  <label class="col-form-label">{!! trans('panel.global.parentcustomer') !!}</label>

                  <?php
                  $parentarray = array();
                  ?>
                  @foreach($customers->getparentdetail as $key => $parentdetail)
                  <?php $parentarray[] = $parentdetail->parent_id;
                  ?>
                  @endforeach



                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control select2 customer_parent" name="parent_id[]" style="width: 100%;" multiple>
                      <!-- <option value="">Select {!! trans('panel.global.parentcustomer') !!}</option> -->
                      @if(@isset($parentcustomers ))
                      @foreach($parentcustomers as $parentcustomer)
                      <option value="{!! $parentcustomer['id'] !!}" <?php if (in_array($parentcustomer->id, $parentarray)) {
                                                                      echo "selected";
                                                                    } ?>>{!! $parentcustomer['name'] !!}</option>
                      @endforeach
                      @endif

                    </select>
                  </div>
                  @if ($errors->has('parent_id'))
                  <div class="error col-lg-12">
                    <p class="text-danger">{{ $errors->first('parent_id') }}</p>
                  </div>
                  @endif

                </div>
              </div>
              <!-- row -->
            </div>




            <!-- <div class="col-md-6">
              <div class="row">
                <label class="col-md-3 col-form-label">{!! trans('panel.customers.fields.firmtype') !!}</label>
                <div class="col-md-9">
                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control select2" name="firmtype" style="width: 100%;">
                        <option value="">Select {!! trans('panel.customers.fields.firmtype') !!}</option>
                        @if(@isset($firmtype ))
                        @foreach($firmtype as $firm)
                        <option value="{!! $firm['id'] !!}" {{ old( 'firmtype' , (!empty($customers->firmtype))?($customers->firmtype):('') ) == $firm['id'] ? 'selected' : '' }}>{!! $firm['firmtype_name'] !!}</option>
                        @endforeach
                        @endif
                     </select>
                  </div>
                  @if ($errors->has('firmtype'))
                   <div class="error col-lg-12">
                      <p class="text-danger">{{ $errors->first('firmtype') }}</p>
                   </div>
                  @endif
                </div>
              </div>
            </div> -->
            <!--             <div class="col-md-6">
              <div class="row">
                <label class="col-md-3 col-form-label">Employee</label>
                <div class="col-md-9">
                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control select2" name="executive_id" style="width: 100%;">
                        <option value="">Select Employee</option>
                        @if(@isset($users ))
                        @foreach($users as $user)
                        <option value="{!! $user['id'] !!}" {{ old( 'executive_id' , (!empty($customers->executive_id))?($customers->executive_id):('') ) == $user['id'] ? 'selected' : '' }}>{!! $user['name'] !!}</option>
                        @endforeach
                        @endif
                     </select>
                  </div>
                  @if ($errors->has('executive_id'))
                   <div class="error col-lg-12">
                      <p class="text-danger">{{ $errors->first('executive_id') }}</p>
                   </div>
                  @endif
                </div>
              </div>
            </div> -->




            <!-- new field -->





            <!--             <div class="col-md-6" id="parentcustomer" style="display:none;">
                <div class="row">
                  <label class="col-md-3 col-form-label">{!! trans('panel.global.parentcustomer') !!}</label>

                  <div class="col-md-9">
                    <div class="form-group has-default bmd-form-group">
                      <select class="form-control select2 customer_parent" name="parent_id"  style="width: 100%;">
                         <option value="">Select {!! trans('panel.global.parentcustomer') !!}</option>
                          @if(@isset($parentcustomers ))
                            @foreach($parentcustomers as $parentcustomer)
                            <option value="{!! $parentcustomer['id'] !!}" {{ old( 'parent_id' , (!empty($customers->parent_id))?($customers->parent_id):('') ) == $parentcustomer['id'] ? 'selected' : '' }}>{!! $parentcustomer['first_name'] !!}{!! $parentcustomer['last_name'] !!}</option>
                            @endforeach
                          @endif

                      </select>
                    </div>
                    @if ($errors->has('parent_id'))
                     <div class="error col-lg-12">
                        <p class="text-danger">{{ $errors->first('parent_id') }}</p>
                     </div>
                    @endif
                  </div>
                </div>
              </div> -->



            <!-- end new feld -->

          </div>
          <hr class="my-3">
          <div class="row">
            <div class="col-md-6" id="billing_address">
              <h4 class="section-heading mb-3  h4 mt-0 text-center text-theme2">Billing Address</h4>
              <div class="second-box">
                <div class="row">
                  <div class="col-md-12">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.address.address1') !!} <span class="text-danger"> *</span></label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="address1" class="form-control" value="{!! old( 'address1', isset($customers['customeraddress']['address1']) ? $customers['customeraddress']['address1'] :'' ) !!}" maxlength="200" required>
                        @if ($errors->has('address1'))
                        <div class="error col-lg-12">
                          <p class="text-danger">{{ $errors->first('address1') }}</p>
                        </div>
                        @endif
                      </div>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.address.address2') !!} </label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="address2" class="form-control" value="{!! old( 'address2', isset($customers['customeraddress']['address2']) ? $customers['customeraddress']['address2'] :'' ) !!}" maxlength="200">
                        @if ($errors->has('address2'))
                        <div class="error">
                          <p class="text-danger">{{ $errors->first('address2') }}</p>
                        </div>
                        @endif

                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">Area </label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="landmark" class="form-control" value="{!! old( 'address1', isset($customers['customeraddress']['landmark']) ? $customers['customeraddress']['landmark'] :'' ) !!}" maxlength="200">
                        @if ($errors->has('landmark'))
                        <div class="error">
                          <p class="text-danger">{{ $errors->first('landmark') }}</p>
                        </div>
                        @endif

                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.address.locality') !!} </label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="locality" class="form-control" value="{!! old( 'locality', isset($customers['customeraddress']['locality']) ? $customers['customeraddress']['locality'] :'' ) !!}" maxlength="200">
                        @if ($errors->has('locality'))
                        <div class="error">
                          <p class="text-danger">{{ $errors->first('locality') }}</p>
                        </div>
                        @endif
                      </div>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input_section">
                      <label class=" col-form-label">{!! trans('panel.global.country') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 country" name="country_id" onchange="getStateList()" style="width: 100%;">
                          <option value="">Select {!! trans('panel.global.country') !!}</option>
                          @if(@isset($countries ))
                          @foreach($countries as $country)
                          <option value="{!! $country['id'] !!}" {{ old( 'country_id' , (!empty($customers['customeraddress']['country_id']))?($customers['customeraddress']['country_id']):('') ) == $country['id'] ? 'selected' : '' }}>{!! $country['country_name'] !!}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('country_id'))
                      <div class="error col-lg-12">
                        <p class="text-danger">{{ $errors->first('country_id') }}</p>
                      </div>
                      @endif

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.state') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 state" name="state_id" onchange="getDistrictList()" style="width: 100%;">
                          @if($customers->exists && isset($customers['customeraddress']['state_id']))
                          <option value="{!! $customers['customeraddress']['state_id'] !!}">{!! $customers['customeraddress']['statename']['state_name'] !!}</option>
                          @else
                          <option value="">Select {!! trans('panel.global.state') !!}</option>
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('state_id'))
                      <div class="error">
                        <p class="text-danger">{{ $errors->first('state_id') }}</p>
                      </div>
                      @endif
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.district') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 district" name="district_id" onchange="getCityList()" style="width: 100%;">
                          @if($customers->exists && isset($customers['customeraddress']['district_id']))
                          <option value="{!! $customers['customeraddress']['district_id'] !!}">{!! $customers['customeraddress']['districtname']['district_name'] !!}</option>
                          @else
                          <option value="">Select {!! trans('panel.global.district') !!}</option>
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('country_id'))
                      <div class="error">
                        <p class="text-danger">{{ $errors->first('country_id') }}</p>
                      </div>
                      @endif

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.city') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 city" name="city_id" onchange="getPincodeList()" style="width: 100%;">
                          @if($customers->exists && isset($customers['customeraddress']['city_id']))
                          <option value="{!! $customers['customeraddress']['city_id'] !!}">{!! $customers['customeraddress']['cityname']['city_name'] !!}</option>
                          @else
                          <option value="">Select {!! trans('panel.global.city') !!}</option>
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('city_id'))
                      <div class="error col-lg-12">
                        <p class="text-danger">{{ $errors->first('city_id') }}</p>
                      </div>
                      @endif

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.pincode') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control pincode select2" name="pincode_id" onchange="getAddressData()" style="width: 100%;">
                          <option value="">Select {!! trans('panel.global.pincode') !!}</option>
                          @if(@isset($pincodes ))
                          @foreach($pincodes as $pincode)
                          <option value="{!! $pincode['id'] !!}" {{ old( 'pincode_id' , (!empty($customers['customeraddress']['pincode_id']))?($customers['customeraddress']['pincode_id']):('') ) == $pincode['id'] ? 'selected' : '' }}>{!! $pincode['pincode'] !!}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('pincode_id'))
                      <div class="error">
                        <p class="text-danger">{{ $errors->first('pincode_id') }}</p>
                      </div>
                      @endif

                    </div>
                    <input type="checkbox" name="same_address" id="same_address" {{ old( 'same_address' , (!empty($customers['same_address']))?($customers['same_address']):('') ) == 1 ? 'checked' : '' }}> <span class="text-theme2">Same Shipping Address</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6" id="shipping_address">
              <h4 class="section-heading mb-3  h4 mt-0 text-center text-theme2">Shipping Address</h4>
              <div class="second-box">
                <div class="row">
                  <div class="col-md-12">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.address.address1') !!} <span class="text-danger"> *</span></label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="shipping_address1" class="form-control" value="{!! old( 'shipping_address1', isset($customers['customershippingaddress']['address1']) ? $customers['customershippingaddress']['address1'] :'' ) !!}" maxlength="200" required>
                        @if ($errors->has('shipping_address1'))
                        <div class="error col-lg-12">
                          <p class="text-danger">{{ $errors->first('shipping_address1') }}</p>
                        </div>
                        @endif
                      </div>

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.address.address2') !!} </label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="shipping_address2" class="form-control" value="{!! old( 'shipping_address2', isset($customers['customershippingaddress']['address2']) ? $customers['customershippingaddress']['address2'] :'' ) !!}" maxlength="200">
                        @if ($errors->has('shipping_address2'))
                        <div class="error">
                          <p class="text-danger">{{ $errors->first('shipping_address2') }}</p>
                        </div>
                        @endif

                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">Area </label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="shipping_landmark" class="form-control" value="{!! old( 'address1', isset($customers['customershippingaddress']['landmark']) ? $customers['customershippingaddress']['landmark'] :'' ) !!}" maxlength="200">
                        @if ($errors->has('shipping_landmark'))
                        <div class="error">
                          <p class="text-danger">{{ $errors->first('shipping_landmark') }}</p>
                        </div>
                        @endif

                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.address.locality') !!} </label>

                      <div class="form-group has-default bmd-form-group">
                        <input type="text" name="shipping_locality" class="form-control" value="{!! old( 'shipping_locality', isset($customers['customershippingaddress']['locality']) ? $customers['customershippingaddress']['locality'] :'' ) !!}" maxlength="200">
                        @if ($errors->has('aadhar_no'))
                        <div class="error">
                          <p class="text-danger">{{ $errors->first('aadhar_no') }}</p>
                        </div>
                        @endif
                      </div>

                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input_section">
                      <label class=" col-form-label">{!! trans('panel.global.country') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 shipping_country" name="shipping_country_id" onchange="getShippingStateList()" style="width: 100%;">
                          <option value="">Select {!! trans('panel.global.country') !!}</option>
                          @if(@isset($countries ))
                          @foreach($countries as $country)
                          <option value="{!! $country['id'] !!}" {{ old( 'shipping_country_id' , (!empty($customers['customershippingaddress']['country_id']))?($customers['customershippingaddress']['country_id']):('') ) == $country['id'] ? 'selected' : '' }}>{!! $country['country_name'] !!}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('shipping_country_id'))
                      <div class="error col-lg-12">
                        <p class="text-danger">{{ $errors->first('shipping_country_id') }}</p>
                      </div>
                      @endif

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.state') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 shipping_state" name="shipping_state_id" onchange="getShippingDistrictList()" style="width: 100%;">
                          @if($customers->exists && isset($customers['customershippingaddress']['state_id']))
                          <option value="{!! $customers['customershippingaddress']['state_id'] !!}">{!! $customers['customershippingaddress']['statename']['state_name'] !!}</option>
                          @else
                          <option value="">Select {!! trans('panel.global.state') !!}</option>
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('shipping_state_id'))
                      <div class="error">
                        <p class="text-danger">{{ $errors->first('shipping_state_id') }}</p>
                      </div>
                      @endif
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.district') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 shipping_district" name="shipping_district_id" onchange="getShippingCityList()" style="width: 100%;">
                          @if($customers->exists && isset($customers['customershippingaddress']['district_id']))
                          <option value="{!! $customers['customershippingaddress']['district_id'] !!}">{!! $customers['customershippingaddress']['districtname']['district_name'] !!}</option>
                          @else
                          <option value="">Select {!! trans('panel.global.district') !!}</option>
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('country_id'))
                      <div class="error">
                        <p class="text-danger">{{ $errors->first('country_id') }}</p>
                      </div>
                      @endif

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.city') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control select2 shipping_city" name="shipping_city_id" onchange="getShippingPincodeList()" style="width: 100%;">
                          @if($customers->exists && isset($customers['customershippingaddress']['city_id']))
                          <option value="{!! $customers['customershippingaddress']['city_id'] !!}">{!! $customers['customershippingaddress']['cityname']['city_name'] !!}</option>
                          @else
                          <option value="">Select {!! trans('panel.global.city') !!}</option>
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('shipping_city_id'))
                      <div class="error col-lg-12">
                        <p class="text-danger">{{ $errors->first('shipping_city_id') }}</p>
                      </div>
                      @endif

                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="input_section">
                      <label class="col-form-label">{!! trans('panel.global.pincode') !!}</label>

                      <div class="form-group has-default bmd-form-group">
                        <select class="form-control shipping_pincode select2" name="shipping_pincode_id" onchange="getShippingAddressData()" style="width: 100%;">
                          <option value="">Select {!! trans('panel.global.pincode') !!}</option>
                          @if(@isset($pincodes ))
                          @foreach($pincodes as $pincode)
                          <option value="{!! $pincode['id'] !!}" {{ old( 'shipping_pincode_id' , (!empty($customers['customershippingaddress']['pincode_id']))?($customers['customershippingaddress']['pincode_id']):('') ) == $pincode['id'] ? 'selected' : '' }}>{!! $pincode['pincode'] !!}</option>
                          @endforeach
                          @endif
                        </select>
                      </div>
                      @if ($errors->has('shipping_pincode_id'))
                      <div class="error">
                        <p class="text-danger">{{ $errors->first('shipping_pincode_id') }}</p>
                      </div>
                      @endif

                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <hr class="my-3">
          <h4 class="section-heading mb-3  h4 mt-0 text-center text-theme2">Customer Details</h4>
          <div class="last-box">
            <div class="row">
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">CA No </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="gstin_no" id="gstin_no" class="form-control" value="{!! old( 'gstin_no', isset($customers['customerdetails']['gstin_no']) ? $customers['customerdetails']['gstin_no'] :'' ) !!}">
                    @if ($errors->has('gstin_no'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('gstin_no') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Application No</label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="pan_no" id="pan_no" class="form-control" value="{!! old( 'pan_no', isset($customers['customerdetails']['pan_no']) ? $customers['customerdetails']['pan_no'] :'' ) !!}" maxlength="200">
                    @if ($errors->has('pan_no'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('pan_no') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Meter No </label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="aadhar_no" id="aadhar_no" class="form-control" value="{!! old( 'aadhar_no', isset($customers['customerdetails']['aadhar_no']) ? $customers['customerdetails']['aadhar_no'] :'' ) !!}" maxlength="200">
                    @if ($errors->has('aadhar_no'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('aadhar_no') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">IVRS No </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="ivrs_no" id="ivrs_no" class="form-control" value="{!! old( 'ivrs_no', isset($customers['ivrs_no']) ? $customers['ivrs_no'] :'' ) !!}">
                    @if ($errors->has('ivrs_no'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('ivrs_no') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Invoice No </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="account_holder" id="account_holder" class="form-control" value="{!! old( 'account_holder', isset($customers['customerdetails']['account_holder']) ? $customers['customerdetails']['account_holder'] :'' ) !!}" maxlength="200">
                    @if ($errors->has('account_holder'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('account_holder') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="ccol-form-label">Modules </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="account_number" id="account_number" class="form-control" value="{!! old( 'account_number', isset($customers['customerdetails']['account_number']) ? $customers['customerdetails']['account_number'] :'' ) !!}">
                    @if ($errors->has('account_number'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('account_number') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Each Module Capacity </label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="number" step="1" name="bank_name" id="bank_name" class="form-control" value="{!! old( 'bank_name', isset($customers['customerdetails']['bank_name']) ? $customers['customerdetails']['bank_name'] :'' ) !!}">
                    @if ($errors->has('bank_name'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('bank_name') }}</p>
                    </div>
                    @endif

                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class=" col-form-label">No of Panels </label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="number" step="1" name="ifsc_code" id="ifsc_code" class="form-control" value="{!! old( 'ifsc_code', isset($customers['customerdetails']['ifsc_code']) ? $customers['customerdetails']['ifsc_code'] :'' ) !!}">
                    @if ($errors->has('ifsc_code'))
                    <div class="error col-lg-12">
                      <p class="text-danger">{{ $errors->first('ifsc_code') }}</p>
                    </div>
                    @endif

                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="input_section">
                  <label class=" col-form-label">PV Model No</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="otherid_no" id="otherid_no" class="form-control" value="{!! old( 'otherid_no', isset($customers['customerdetails']['otherid_no']) ? $customers['customerdetails']['otherid_no'] :'' ) !!}" maxlength="200">
                    @if ($errors->has('otherid_no'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('otherid_no') }}</p>
                    </div>
                    @endif
                  </div>


                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Type of Project</label>
                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control" name="visit_status" id="visit_status" style="width: 100%;">
                      <option value="" selected disabled>Select Type of Project</option>
                      <option value="Subsidy" @if(!empty($customers->customerdetails) && $customers->customerdetails->visit_status == "Subsidy") selected @endif>Subsidy</option>
                      <option value="Non Subsidy" @if(!empty($customers->customerdetails) && $customers->customerdetails->visit_status == "Non Subsidy") selected @endif>Non Subsidy</option>
                      <option value="Off Line" @if(!empty($customers->customerdetails) && $customers->customerdetails->visit_status == "Off Line") selected @endif>Off Line</option>
                      <option value="Off Grid" @if(!empty($customers->customerdetails) && $customers->customerdetails->visit_status == "Off Grid") selected @endif>Off Grid</option>
                    </select>
                    @if ($errors->has('visit_status'))
                    <div class="error col-lg-12">
                      <p class="text-danger">{{ $errors->first('visit_status') }}</p>
                    </div>
                    @endif
                  </div>


                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Mono/Poly</label>

                  <div class="form-group has-default bmd-form-group">
                    <select class="form-control" name="grade" id="grade" style="width: 100%;">
                      <option value="" selected disabled>Select Grade</option>
                      <option value="Bifacial" @if(!empty($customers->customerdetails) && $customers->customerdetails->grade == "Bifacial") selected @endif>Bifacial</option>
                      <option value="Mono" @if(!empty($customers->customerdetails) && $customers->customerdetails->grade == "Mono") selected @endif>Mono</option>
                      <option value="Mono Bifacial" @if(!empty($customers->customerdetails) && $customers->customerdetails->grade == "Mono Bifacial") selected @endif>Mono Bifacial</option>
                      <option value="Mono Dcr" @if(!empty($customers->customerdetails) && $customers->customerdetails->grade == "Mono Dcr") selected @endif>Mono Dcr</option>
                      <option value="Poly" @if(!empty($customers->customerdetails) && $customers->customerdetails->grade == "Poly") selected @endif>Poly</option>
                      <option value="Non-Dcr(mono)" @if(!empty($customers->customerdetails) && $customers->customerdetails->grade == "Non-Dcr(mono)") selected @endif>Non-Dcr(mono)</option>
                      <option value="NA" @if(!empty($customers->customerdetails) && $customers->customerdetails->grade == "NA") selected @endif>NA</option>
                    </select>
                  </div>


                </div>
              </div>

              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Application Date </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="application_date" id="application_date" class="form-control datepicker" value="{!! old( 'application_date', isset($customers['application_date']) ? $customers['application_date'] :'' ) !!}">
                    @if ($errors->has('application_date'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('application_date') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Commissioning Date </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="commissioning_date" id="commissioning_date" class="form-control datepicker" value="{!! old( 'commissioning_date', isset($customers['commissioning_date']) ? $customers['commissioning_date'] :'' ) !!}">
                    @if ($errors->has('commissioning_date'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('commissioning_date') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Invoice Date </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="invoice_date" id="invoice_date" class="form-control datepicker" value="{!! old( 'invoice_date', isset($customers['invoice_date']) ? $customers['invoice_date'] :'' ) !!}">
                    @if ($errors->has('invoice_date'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('invoice_date') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Inverter </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="inverter" id="inverter" class="form-control" value="{!! old( 'inverter', isset($customers['inverter']) ? $customers['inverter'] :'' ) !!}">
                    @if ($errors->has('inverter'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('inverter') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Inv Model No </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="inv_model_no" id="inv_model_no" class="form-control" value="{!! old( 'inv_model_no', isset($customers['inv_model_no']) ? $customers['inv_model_no'] :'' ) !!}">
                    @if ($errors->has('inv_model_no'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('inv_model_no') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Inverter Sr No </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="inverter_sr_no" id="inverter_sr_no" class="form-control" value="{!! old( 'inverter_sr_no', isset($customers['inverter_sr_no']) ? $customers['inverter_sr_no'] :'' ) !!}">
                    @if ($errors->has('inverter_sr_no'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('inverter_sr_no') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">ID </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="new_id" id="new_id" class="form-control" value="{!! old( 'new_id', isset($customers['new_id']) ? $customers['new_id'] :'' ) !!}">
                    @if ($errors->has('new_id'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('new_id') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>

              <div class="col-md-6" id="password_box">
                <div class="input_section">
                  <label class="col-form-label">Password</label>

                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="password" id="password" class="form-control" autocomplete="new-password" value="{{ old('password', $customers['password_string']) }}">
                  </div>
                  @if ($errors->has('password'))
                  <div class="error">
                    <p class="text-danger">{{ $errors->first('password') }}</p>
                  </div>
                  @endif
                </div>
              </div>
              
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">AMC Category </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="amc_category" id="amc_category" class="form-control" value="{!! old( 'amc_category', isset($customers['amc_category']) ? $customers['amc_category'] :'' ) !!}">
                    @if ($errors->has('amc_category'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('amc_category') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="input_section">
                  <label class="col-form-label">Warranty End Date </label>
                  <div class="form-group has-default bmd-form-group">
                    <input type="text" name="warranty_end_date" id="warranty_end_date" class="form-control datepicker" value="{!! old( 'warranty_end_date', isset($customers['warranty_end_date']) ? $customers['warranty_end_date'] :'' ) !!}">
                    @if ($errors->has('warranty_end_date'))
                    <div class="error">
                      <p class="text-danger">{{ $errors->first('warranty_end_date') }}</p>
                    </div>
                    @endif
                  </div>
                </div>
              </div>

            </div>
          </div>

        </div>

        <div class="row mt-5">
          <div class="col-md-2 col-sm-2">
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <div class="fileinput-new thumbnail">
                <img src="{!! !empty($customers['customerdocuments']->where('document_name','gstin')->pluck('file_path')->first()) ? $customers['customerdocuments']->where('document_name','gstin')->pluck('file_path')->first() : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview1">
                <div class="selectThumbnail">
                  <span class="btn btn-just-icon btn-round btn-file">
                    <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="imggstin" class="getimage1" accept="image/*,application/pdf">
                  </span>
                  <br>
                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                </div>
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
              <label class="bmd-label-floating">Attachment 1</label>
              @if ($errors->has('imggstin'))
              <div class="error col-lg-12">
                <p class="text-danger">{{ $errors->first('imggstin') }}</p>
              </div>
              @endif
            </div>
          </div>
          <div class="col-md-2 col-sm-2">
            <div class="fileinput fileinput-new" data-provides="fileinput">

              <div class="fileinput-new thumbnail">
                <img src="{!! !empty($customers['customerdocuments']->where('document_name','pan')->pluck('file_path')->first()) ? $customers['customerdocuments']->where('document_name','pan')->pluck('file_path')->first() : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview2">
                <div class="selectThumbnail">
                  <span class="btn btn-just-icon btn-round btn-file">
                    <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="imgpan" class="getimage2" accept="image/*,application/pdf">
                  </span>
                  <br>
                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                </div>
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
              <label class="bmd-label-floating">Attachment 2</label>
            </div>
          </div>
          <div class="col-md-2 col-sm-2">
            <div class="fileinput fileinput-new" data-provides="fileinput">

              <div class="fileinput-new thumbnail">
                <img src="{!! !empty($customers['customerdocuments']->where('document_name','aadhar')->pluck('file_path')->first()) ? $customers['customerdocuments']->where('document_name','aadhar')->pluck('file_path')->first() : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview3">
                <div class="selectThumbnail">
                  <span class="btn btn-just-icon btn-round btn-file">
                    <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="imgaadhar" class="getimage3" accept="image/*,application/pdf">
                  </span>
                  <br>
                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                </div>
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
              <label class="bmd-label-floating">Attachment 3</label>
            </div>
          </div>
          <div class="col-md-2 col-sm-2">
            <div class="fileinput fileinput-new" data-provides="fileinput">

              <div class="fileinput-new thumbnail">
                <img src="{!! !empty($customers['customerdocuments']->where('document_name','aadharback')->pluck('file_path')->first()) ? $customers['customerdocuments']->where('document_name','aadharback')->pluck('file_path')->first() : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview4">
                <div class="selectThumbnail">
                  <span class="btn btn-just-icon btn-round btn-file">
                    <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="imgaadharback" class="getimage4" accept="image/*,application/pdf">
                  </span>
                  <br>
                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                </div>
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
              <label class="bmd-label-floating">Attachment 4</label>
            </div>
          </div>
          <div class="col-md-2 col-sm-2">
            <div class="fileinput fileinput-new" data-provides="fileinput">

              <div class="fileinput-new thumbnail">
                <img src="{!! !empty($customers['customerdocuments']->where('document_name','bankpass')->pluck('file_path')->first()) ? $customers['customerdocuments']->where('document_name','bankpass')->pluck('file_path')->first() : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview5">
                <div class="selectThumbnail">
                  <span class="btn btn-just-icon btn-round btn-file">
                    <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="imgbankpass" class="getimage5" accept="image/*,application/pdf">
                  </span>
                  <br>
                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                </div>
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
              <label class="bmd-label-floating">Attachment 5</label>
            </div>
          </div>
          <div class="col-md-2 col-sm-2">
            <div class="fileinput fileinput-new" data-provides="fileinput">

              <div class="fileinput-new thumbnail">
                <img src="{!! !empty($customers['customerdocuments']->where('document_name','other')->pluck('file_path')->first()) ? $customers['customerdocuments']->where('document_name','other')->pluck('file_path')->first() : url('/').'/'.asset('assets/img/placeholder.jpg') !!}" class="imagepreview6">
                <div class="selectThumbnail">
                  <span class="btn btn-just-icon btn-round btn-file">
                    <span class="fileinput-new"><i class="fa fa-pencil"></i></span>
                    <span class="fileinput-exists">Change</span>
                    <input type="file" name="imgother" class="getimage6" accept="image/*,application/pdf">
                  </span>
                  <br>
                  <a href="#pablo" class="btn btn-danger btn-round fileinput-exists" data-dismiss="fileinput"><i class="fa fa-times"></i> Remove</a>
                </div>
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail img-circle"></div>
              <label class="bmd-label-floating">Attachment 6</label>
            </div>
          </div>
        </div>
        <hr class="my-3">
        <!-- <h4 class="section-heading mb-3  h4 mt-0 text-center text-info">Customer survey</h4>  -->
        {{--<div class="row last-inner-form">
            <div class="col-md-12">
              <div id="accordion" role="tablist">
                <div class="card-collapse">
                  <div class="card-header inner-form-heading" role="tab" id="headingOne">
                    <h4 class="section-heading mb-3  h4 mt-0 text-theme2"><a data-toggle="collapse" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne" class="collapsed">
                        Customer survey
                        <i class="material-icons">keyboard_arrow_down</i>
                      </a></h4>
                  </div>
                  <div id="collapseOne" class="collapse" role="tabpanel" aria-labelledby="headingOne" data-parent="#accordion" style="">
                    <div class="card-body">
                      @if(@isset($fields ))
                      @foreach($fields as $index => $field)
                      <input type="hidden" name="survey[{!! $index !!}][field_id]" value="{!! $field['id']!!}">

                      @if($field['field_type'] == 'Radio')
                      <div class="row">
                        <!-- <label class="col-sm-1 col-form-label label-checkbox"></label> -->
                        <div class="col-sm-12 checkbox-radios">
                          <h4 class="section-heading mb-3  h4 mt-0 text-center text-theme2"> {!! $field['label_name'] !!}</h4>
                          <div class="row">
                            @if(@isset($field['fieldsData'] ))
                            @foreach($field['fieldsData'] as $rows)
                            <div class="col-sm-4">
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" type="radio" name="survey[{!! $index !!}][value]" value="{!! $rows['value'] !!}" @if($customers['surveys']->where('field_id', $field['id'])->pluck('value')->first() == $rows['value']) checked @endif> {!! $rows['value'] !!}
                                  <span class="circle">
                                    <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                            </div>
                            @endforeach
                            @endif
                          </div>
                        </div>
                      </div>
                      @elseif($field['field_type'] == 'Checkbox')
                      <div class="row">
                        <!-- <label class="col-sm-1 col-form-label label-checkbox"></label> -->
                        <div class="col-sm-12 checkbox-radios">
                          <h4 class="section-heading mb-3  h4 mt-0 text-center text-theme2"> {!! $field['label_name'] !!}</h4>

                          <div class="row">
                            @if(@isset($field['fieldsData'] ))
                            @foreach($field['fieldsData'] as $i => $rows)
                            <div class="col-md-3">
                              <div class="form-check">
                                <label class="form-check-label">
                                  <input class="form-check-input" name="survey[{!! $index !!}][value][]" type="checkbox" value="{!! $rows['value'] !!}" @if(in_array($rows['value'], explode(', ', $customers[' surveys']->where('field_id', $field['id'])->pluck('value')->first()))) checked @endif> {!! $rows['value'] !!}
                                  <span class="form-check-sign">
                                    <span class="check"></span>
                                  </span>
                                </label>
                              </div>
                            </div>
                            @endforeach
                            @endif
                          </div>
                        </div>
                      </div>
                      @elseif($field['field_type'] == 'Select')
                      <div class="row">
                        <label class="col-md-3 col-form-label">{!! $field['label_name'] !!}</label>
                        <div class="col-md-9">
                          <div class="form-group has-default bmd-form-group">
                            <select class="form-control select2" name="survey[{!! $index !!}][value]" style="width: 100%;">
                              <option value="">Select {!! $field['label_name'] !!}</option>
                              @if(@isset($field['fieldsData'] ))
                              @foreach($field['fieldsData'] as $rows)
                              <option value="{!! $rows['value'] !!}">{!! $rows['value'] !!}</option>
                              @endforeach
                              @endif
                            </select>
                          </div>
                        </div>
                      </div>
                      @else
                      <div class="row">
                        <!-- <label class="col-sm-1 col-form-label label-checkbox"></label> -->
                        <div class="col-sm-12 checkbox-radios">
                          <h4 class="section-heading mb-3  h4 mt-0 text-center text-theme2"> {!! $field['label_name'] !!}</h4>
                          <div class="form-group has-default bmd-form-group">
                            <input type="text" name="survey[{!! $index !!}][value]" class="form-control" value="{!! $customers['surveys']->where('field_id', $field['id'])->pluck('value')->first() !!}" maxlength="200">
                          </div>
                        </div>
                      </div>
                      @endif

                      @endforeach
                      @endif
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div> --}}
        <div class="card-footer pull-right">
          {{ Form::submit('Submit', array('class' => 'btn btn-theme')) }}
        </div>
        {{ Form::close() }}
      </div>
    </div>
  </div>
  </div>
  </div>
  <script src="{{ url('/').'/'.asset('assets/js/jquery.custom.js?v='.time()) }}"></script>
  <script src="{{ asset('assets/js/validation_customers.js') . '?v=' . time() }}"></script>
  <script type="text/javascript">
    $(function() {
      //Initialize Select2 Elements
      $('.select2').select2()

      //Initialize Select2 Elements
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      })
    })
  </script>

  <script>
    $(document).ready(function() {

      // $(document).on('change','#type',function(e){

      $('#type').change(function() {

        var type = $('#type').val();
        if (type == '3') {
          $('#parentcustomer').hide()
          $('#parentcustomer').prop("disabled", true)
        } else if (type == '1') {
          $('#parentcustomer').hide()
          $('#parentcustomer').prop("disabled", true)
        } else if (type == '2') {
          $('#parentcustomer').show()
          $('#parentcustomer').prop("disabled", false)
        } else {
          $('#parentcustomer').hide()
          $('#parentcustomer').prop("disabled", true)
        }

      }).trigger('change');

      $(document).ready(function() {
        $('#change_password').on('click', function() {
          let isChecked = $(this).is(':checked');
          if (isChecked) {
            $('#password_box').show();
          } else {
            $('#password_box').hide();
          }
        });
      });

    });
    $(document).ready(function() {

      function toggleAddressView() {
        if ($('#same_address').is(':checked')) {
          $('#shipping_address').hide();
          $('#billing_address').removeClass('col-md-6').addClass('col-md-12');
        } else {
          $('#shipping_address').show();
          $('#billing_address').removeClass('col-md-12').addClass('col-md-6');
        }
      }

      // On change
      $(document).on('change', '#same_address', function() {
        toggleAddressView();
      });

      // On page load (for edit form)
      toggleAddressView();
    });
  </script>

</x-app-layout>