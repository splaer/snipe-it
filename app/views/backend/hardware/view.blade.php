@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
@lang('admin/hardware/general.view') {{ $asset->asset_tag }} ::
@parent
@stop

{{-- Page content --}}
@section('content')

<div class="row header">
    <div class="col-md-12">
     <h3 class="name">
        @lang('admin/hardware/general.view')
        {{{ $asset->asset_tag }}}
        @if ($asset->name)
        ({{{ $asset->name }}})
        @endif
    </h3>

        <div class="btn-group pull-right">

		<div class="dropdown">
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">@lang('button.actions')
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu1">
                @if ($asset->status_id == 1)
                    @if ($asset->assigned_to != 0)
                        <li role="presentation"><a href="{{ route('checkin/hardware', $asset->id) }}">@lang('admin/hardware/general.checkin')</a></li>
                    @endif
                @elseif ($asset->status_id == 0)
                        <li role="presentation"><a href="{{ route('checkout/hardware', $asset->id) }}">@lang('admin/hardware/general.checkout')</a></li>
                @endif
                <li role="presentation"><a href="{{ route('update/hardware', $asset->id) }}">@lang('admin/hardware/general.edit')</a></li>
                <li role="presentation"><a href="{{ route('clone/hardware', $asset->id) }}">@lang('admin/hardware/general.clone')</a></li>
            </ul>
        </div>


    </div>
</div>
</div>

<div class="user-profile">
<div class="row profile">
<div class="col-md-9 bio">

		@if ($asset->deleted_at!='')
			<div class="alert alert-warning alert-block">
				<i class="icon-warning-sign"></i>
				@lang('admin/hardware/general.deleted', array('asset_id' => $asset->id))

			</div>

		@endif

        @if ($asset->serial)
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.serial'): </strong>
            <em>{{{ $asset->serial }}}</em></div>

        @endif

        @if ($asset->mac_address!='')
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.mac_address'):</strong>
            {{{ $asset->mac_address }}}
            </div>
        @endif

        @if ($asset->model->manufacturer)
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.manufacturer'): </strong>
            <a href="{{ route('update/manufacturer', $asset->model->manufacturer->id) }}">
            {{{ $asset->model->manufacturer->name }}}
            </a> </div>
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.model'):</strong>
            <a href="{{ route('view/model', $asset->model->id) }}">
            {{{ $asset->model->name }}}
            </a>
             / {{{ $asset->model->modelno }}}</div>
        @endif

        @if ($asset->purchase_date)
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.date'): </strong>
            {{{ $asset->purchase_date }}} </div>
        @endif

        @if ($asset->purchase_cost)
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.cost'):</strong>
            @lang('general.currency')
            {{{ number_format($asset->purchase_cost,2) }}} </div>
        @endif

        @if ($asset->order_number)
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.order'):</strong>
            {{{ $asset->order_number }}} </div>
        @endif

        @if ($asset->supplier_id)
            <div class="col-md-6" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.supplier'): </strong>
            <a href="{{ route('view/supplier', $asset->supplier_id) }}">
            {{{ $asset->supplier->name }}}
            </a> </div>
        @endif

        @if ($asset->warranty_months)
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.warranty'):</strong>
            {{{ $asset->warranty_months }}}
            @lang('admin/hardware/form.months')
            </div>
            <div class="col-md-12 {{{ $asset->warrantee_expires() < date("Y-m-d H:i:s") ? 'ui-state-highlight' : '' }}}"  style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.expires'):</strong>
            {{{ $asset->warrantee_expires() }}}</div>
        @endif

        @if ($asset->depreciation)
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.depreciation'): </strong>
            {{ $asset->depreciation->name }}
                ({{{ $asset->depreciation->months }}}
                @lang('admin/hardware/form.months')
                )</div>
            <div class="col-md-12" style="padding-bottom: 5px;"><strong>@lang('admin/hardware/form.fully_depreciated'): </strong>
            {{{ $asset->months_until_depreciated()->m }}}
            @lang('admin/hardware/form.months')
             @if ($asset->months_until_depreciated()->y > 0)
                , {{{ $asset->months_until_depreciated()->y }}}
                @lang('admin/hardware/form.years')
             @endif
              ({{{ $asset->depreciated_date() }}})
             </div>
        @endif


        @if ($asset->model->eol)
            <div class="col-md-12" style="padding-bottom: 5px;">
            <strong>@lang('admin/hardware/form.eol_rate'): </strong>
            {{{ $asset->model->eol }}}
            @lang('admin/hardware/form.months') </div>
            <div class="col-md-12" style="padding-bottom: 5px;">
            <strong>@lang('admin/hardware/form.eol_date'): </strong>
            {{{ $asset->eol_date() }}}
            @if ($asset->months_until_eol())
                 (
                 @if ($asset->months_until_eol()->y > 0) {{{ $asset->months_until_eol()->y }}}
                  @lang('general.years'),
                 @endif

                {{{ $asset->months_until_eol()->m }}}
                @lang('general.months')
                )
            @endif
            </div>
        @endif





<div class="col-md-12">
  		<!-- Licenses assets table -->
        <h6>Software Assigned </h6>
		<br>
		<!-- checked out assets table -->
		@if (count($asset->licenses) > 0)
		<table class="table table-hover">
			<thead>
				<tr>
					<th class="col-md-4"><span class="line"></span>@lang('general.name')</th>
					<th class="col-md-1"><span class="line"></span>@lang('table.actions')</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($asset->licenseseats as $seat)
				<tr>
					<td><a href="{{ route('view/license', $seat->license->id) }}">{{{ $seat->license->name }}}</a></td>
					<td><a href="{{ route('checkin/license', $seat->id) }}" class="btn-flat info">@lang('general.checkin')</a>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>
		@else

		<div class="col-md-12">
			<div class="alert alert-info alert-block">
				<i class="icon-info-sign"></i>
				@lang('general.no_results')
			</div>
		</div>
		@endif

        <!-- checked out assets table -->

        <table class="table table-hover table-fixed break-word">
            <thead>
                <tr>
                    <th class="col-md-3"><span class="line"></span>@lang('general.date')</th>
                    <th class="col-md-2"><span class="line"></span>@lang('general.admin')</th>
                    <th class="col-md-2"><span class="line"></span>@lang('table.actions')</th>
                    <th class="col-md-2"><span class="line"></span>@lang('general.user')</th>
                    <th class="col-md-3"><span class="line"></span>@lang('general.notes')</th>
                </tr>
            </thead>
            <tbody>
            @if (count($asset->assetlog) > 0)
                @foreach ($asset->assetlog as $log)
                 @if ((isset($log->checkedout_to)) && ($log->checkedout_to!=0))
                <tr>
                    <td>{{{ $log->created_at }}}</td>
                    <td>
                        @if (isset($log->user_id))
                        {{{ $log->adminlog->fullName() }}}
                        @endif
                    </td>
                    <td>{{ $log->action_type }}</td>
                    <td>
                        @if ((isset($log->checkedout_to)) && ($log->checkedout_to!=0) && ($log->checkedout_to!=''))
                        <a href="{{ route('view/user', $log->checkedout_to) }}">
                        {{{ $log->userlog->fullName() }}}
                        </a>
                        @endif
                    </td>
                    <td>
                        @if ($log->note) {{{ $log->note }}}
                        @endif
                    </td>
                </tr>
                 @endif
                @endforeach
                @endif
                <tr>
                    <td>{{ $asset->created_at }}</td>
                    <td>
                    @if (isset($asset->adminuser->id)) {{{ $asset->adminuser->fullName() }}}
                    @else
                    @lang('general.unknown_admin')
                    @endif
                    </td>
                    <td>@lang('general.created_asset')</td>
                    <td></td>
                    <td>
<!--                    @if ($asset->notes)
                    {{{ $asset->notes }}}
                    @endif -->
                    </td>
                </tr>
            </tbody>
        </table>


        </div>
</div>
        <!-- side address column -->
        <div class="col-md-3 col-xs-12 address pull-right">

        	<!-- Asset notes -->
@if ($asset->notes)

		<h6>@lang('admin/hardware/form.notes'):</h6>
		 <div class="break-word">{{ nl2br(e($asset->notes)) }}</div>

@endif

            @if ($qr_code->display)
            <h6>@lang('admin/hardware/form.qr')</h6>
            <p>
                <img src="{{{ $qr_code->url }}}" />
            </p>
            @endif

            @if ((isset($asset->assigneduser) && ($asset->assigned_to > 0)) && ($asset->deleted_at==''))
                <h6><br>@lang('admin/hardware/form.checkedout_to')</h6>
                <ul>

                    <li><img src="{{{ $asset->assigneduser->gravatar() }}}" class="img-circle" style="width: 100px; margin-right: 20px;" /><br /><br /></li>
                    <li><a href="{{ route('view/user', $asset->assigned_to) }}">{{ $asset->assigneduser->fullName() }}</a></li>


                    @if (isset($asset->assetloc->address))
                        <li>{{{ $asset->assetloc->address }}}
                        @if (isset($asset->assetloc->address2)) {{{ $asset->assetloc->address2 }}}
                        @endif
                        </li>
                        @if (isset($asset->assetloc->city))
                            <li>{{{ $asset->assetloc->city }}}, {{{ $asset->assetloc->state }}} {{{ $asset->assetloc->zip }}}</li>
                        @endif

                    @endif

                    @if (isset($asset->assigneduser->email))
                        <li><br /><i class="icon-envelope-alt"></i> <a href="mailto:{{{ $asset->assigneduser->email }}}">{{{ $asset->assigneduser->email }}}</a></li>
                    @endif

                    @if ((isset($asset->assigneduser->phone)) && ($asset->assigneduser->phone!=''))
                        <li><i class="icon-phone"></i> {{{ $asset->assigneduser->phone }}}</li>
                    @endif


                    <li><br /><a href="{{ route('checkin/hardware', $asset->id) }}" class="btn-flat large info ">@lang('admin/hardware/general.checkin')</a></li>
                    </ul>

			 @endif

            @if (($asset->status_id ) && ($asset->status_id > 0))
			<!-- Status Info -->

                @if ($asset->assetstatus)
                    <h6><br>{{{ $asset->assetstatus->name }}}
                    @lang('admin/hardware/general.asset')</h6>

                    <ul>
                    	 @if (($asset->assetstatus->deployable=='1') && ($asset->assigned_to > 0) && ($asset->deleted_at==''))
                    	<li><br /><a href="{{ route('checkin/hardware', $asset->id) }}" class="btn-flat large info ">@lang('admin/hardware/general.checkin')</a></li>
                    	@elseif ((($asset->assetstatus->deployable=='1') &&  (($asset->assigned_to=='') || ($asset->assigned_to==0))) && ($asset->deleted_at==''))
                    	<li><br /><a href="{{ route('checkout/hardware', $asset->id) }}" class="btn-flat large info ">@lang('admin/hardware/general.checkout')</a></li>
						@elseif  ($asset->deleted_at!='')

						<li><br /><a href="{{ route('restore/hardware', $asset->id) }}" class="btn-flat large info ">@lang('admin/hardware/general.restore')</a></li>

                    	@endif
                    </ul>

					@if ($asset->assetstatus->notes)
                    <div class="col-md-12">
						<div class="alert alert-info alert-block">
							<i class="icon-info-sign"></i>
							{{{ $asset->assetstatus->notes }}}

						</div>
                    </div>
                    @endif

                 @endif
            @endif

        </div>
    </div>
</div>
@stop
