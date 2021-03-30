@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        
        <section class="content-header">
            <h1> {{__('site.users')}} </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active">@lang('site.users')</li>
            </ol>
        </section><!-- end content-header -->

        <section class="content">

            <form action="{{ route('dashboard.users.index') }}" method="get">

                <div class="row" style="margin-bottom: 15px">

                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->search}}">
                    </div>

                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search')</button>
                         @if (auth()->user()->hasPermission('users_create'))
                            <a href="{{ route('dashboard.users.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                        @else
                            <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>
                        @endif <!-- end IF Create -->
                    </div>

                </div>
            </form> <!-- end of form -->
            @include('partials._session')
            
                
           
                <div class="box box-primary">
                    <div class="box-header">
                      {{-- <h3 class="box-title">Quick Example</h3> --}}
                      <div class="box-body">
                      @if ($users->count()> 0)
                        <table class="table table-bordered table-hover">
                          <thead>                  
                            <tr>
                              <th style="width: 10px">#</th>
                              <th>{{__('site.name')}}</th>
                              <th>{{__('site.email')}}</th>
                              <th >{{__('site.image')}}</th>
                              <th >{{__('site.action')}}</th>
                              
                            </tr>
                          </thead>
                          <tbody>
                            
                            @foreach ($users as $index=>$user)
                                <tr>
                                    <td> {{$index + 1}}</td>
                                    <td>{{$user -> name}}</td>
                                    <td>{{$user -> email}}</td>
                                    <td><img src="{{$user-> image_path }}" class="img-thumbnail" style="width: 100px"></td>
                                    <td> 
                                      @if (auth()->user()->hasPermission('users_update'))
                                        <a  href="{{route('dashboard.users.edit', $user -> id)}}" class=" btn btn-info">{{__('site.edit')}} </a>

                                      @else
                                      <a  href="#" class=" btn btn-info disabled">{{__('site.edit')}} </a>
                                      @endif <!-- end IF Edite -->
                                      @if (auth()->user()->hasPermission('users_delete'))
                                        <form  action="{{route('dashboard.users.destroy', $user -> id)}}" method="post" style="display: inline-block" > 
                                          {{ csrf_field() }}
                                          {{ method_field('delete') }}
                                          <button type="submit" class="btn btn-danger delete"><i class="fa fa-trash"></i> {{__('site.delete')}}</button>
                                        </form>
                                      @else
                                        <a  href="#" class=" btn btn-danger disabled">{{__('site.delete')}} </a>
                                      @endif <!-- end IF Delete -->
                                    </td>
                                </tr>
                            @endforeach
            
                          </tbody>
                          
                        </table>

                        <!-- the part of paginations -->

                        {{$users->appends(request()->query())->links() }}
                        
                        <!-- End of paginations -->
                      @else
                        <h2>@lang('site.no_data_found')</h2>
                      @endif

                  </div> <!-- End box-body -->
                </div> <!-- End box-header -->
              </div> <!-- box box-primary -->
    
        </section><!-- end content -->

    </div><!-- end content-weapper -->
@endsection