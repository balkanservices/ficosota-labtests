@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
<!--                    @lang('samples_list.title')-->

                    <!--Конструкция - Performance Limits - (diapers Pufies Baby Art & Dry Maxi)-->
                    Creep Resistance, 4h - Performance Limits - (diapers Pufies Baby Art & Dry Maxi)


                    <!--<a style="position: absolute; right: 25px; top: 6px;" href="{{route('samples_lists.audit', ['id' => $samplesList->id])}}" class="btn btn-sm btn-link">@lang('samples_list.btn.audit')</a>-->
                </div>

                <div class="panel-body">

<!--                    <div class="row">
                        <div class="col-md-6 field-div-text">
                            <label class="field-label" for="manifacturer">Изберете поле за добавяне</label>
                            <select class="form-control js-select2" id="field" name="field">
                                <option value="1">предни уши / Дължина на уши, mm (left) / Ширина и дължина / Уши </option>
                                <option value="1">предни уши / Дължина на уши, mm (right) / Ширина и дължина / Уши </option>
                                <option value="1">предни уши / Ширина на уши, mm (left) / Ширина и дължина / Уши </option>
                                <option value="1">предни уши / Ширина на уши, mm (right) / Ширина и дължина / Уши </option>
                            </select>
                        </div>
                        <div class="col-md-6 field-div-text">
                            <br/><br/>
                            <button class="btn btn-primary">Добавяне</button>
                        </div>
                    </div>

                    <hr />
-->

<!--                    @foreach ($fields as $field)
                    <div class="row">
                        <div class="col-md-3 field-div-text" style="padding-top:20px;">
                            {{ $field }}
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">По-малко от (blocked)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">По-малко от (alert)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">Повече от (alert)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">Повече от (blocked)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>

                    </div>
                    <hr>
                    @endforeach-->

                    @foreach ($fields2 as $field)
                    <div class="row">
                        <div class="col-md-3 field-div-text" style="padding-top:20px;">
                            {{ $field }}
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">По-малко от (blocked)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">По-малко от (alert)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">Повече от (alert)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>
                        <div class="col-md-2 field-div-text">
                            <label class="field-label" for="ltb">Повече от (blocked)</label>
                            <input type="text" class="form-control" id="ltb"/>
                        </div>

                    </div>
                    <hr>
                    @endforeach

                    <div class="text-right">
                        <button class="btn btn-success">Запазване</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css_files')
	<link href="{{ asset('css/datatables.min.css') }}" rel="stylesheet">
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/datatables/Editor-1.6.5/css/editor.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ asset('js/datatables/Editor-1.6.5/css/editor.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('javascript')
    <script src="{{ asset('js/datatables.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/dataTables.editor.min.js') }}"></script>
    <script src="{{ asset('js/datatables/Editor-1.6.5/js/editor.bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/moment.min.js') }}"></script>
	<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('js/select2.min.js') }}"></script>

   	<script>

    var editor;
    var packages_editor;

	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	$(document).ready(function() {
        var packages_editor = new $.fn.dataTable.Editor( {
			ajax: "{{ route('samples_list.packages.json_post', ['samplesListId' => $samplesList->id]) }}",
			idSrc:  'id',
			table: "#packages",
			fields: [ {
                    label: "@lang('samples_list.manifacturing_time'):",
					name: "manifacturing_time"
				}, {
					label: "@lang('samples_list.samples_count'):",
					name: "samples_count",
					attr: {
						type: 'number'
					}
				}, {
					label: "@lang('samples_list.comment'):",
					name: "comment",
					attr: {
						type: 'textarea'
					}
				}
			]
		} );

		// Activate an inline edit on click of a table cell
		$('#packages').on( 'click', 'tbody td', function (e) {
			packages_editor.inline( this );
		} );

		$('#packages').DataTable( {
			dom: "Bfrtip",
			ajax: "{{ route('samples_list.packages.json', ['samplesListId' => $samplesList->id]) }}",
			order: [],
			searching: false,
			paging: false,
			bInfo: false,
			columns: [
				{ data: "manifacturing_time", sClass: "text-center" },
                { data: "samples_count", sClass: "text-center" },
                { data: "comment", sClass: "text-center" },
                { data: null, sClass: "text-center", "defaultContent": "" }
			],
            columnDefs: [{"targets":3, "data":"id", "render": function(data,type,full,meta)
            {
                var packageUrl = "{{ route('samples_list.package.index', ['packageId' => '__ID__']) }}";
                packageUrl = packageUrl.replace('__ID__', data.id);
                return '<a class="btn btn-info btn-xs" href="' + packageUrl + '">@lang("samples_list.package_analyses")</a>';
            }}],
			select: {
				style:    'multi',
				selector: 'td:first-child'
			},
            keys: {
                keys: [ 9, 13 ],
                editor: packages_editor,
                editOnFocus: true,
                focus: ':eq(0)'

            },
            buttons: [
//				{ extend: "create", editor: editor },
//				{ extend: "edit",   editor: editor },
//				{ extend: "remove", editor: editor }
			]
		} );

        packages_editor.on('initEdit', function() {
			packages_editor.show();
            packages_editor.disable('manifacturing_time');
            packages_editor.disable('samples_count');
		});

        packages_editor.on('submitComplete', function() {
		});

//        $('#add_package').click(function(){
//            $.ajax({
//				type: "POST",
//				url: "{{ route('samples_list.packages.create_rows', ['samplesListId' => $samplesList->id]) }}",
//				data: {
//					new_rows: 1
//				},
//				success: function( msg ) {
//					$('#packages').DataTable().ajax.reload();
//				}
//			});
//			return;
//		});

        $('#qa_journal_id').change(function() {
            if($('#qa_journal_id').val() !== '') {
                $('#product_id').val('');
                $('#product_id').prop("disabled", true);
            } else {
                $('#product_id').prop("disabled", false);
            }
        });

        $('#product_id').change(function() {
            if($('#product_id').val() !== '') {
                $('#qa_journal_id').val('');
                $('#qa_journal_id').prop("disabled", true);
            } else {
                $('#qa_journal_id').prop("disabled", false);
            }
        });
	} );


	$(function () {
		$('.date').datetimepicker({
			allowInputToggle: true,
			format: "YYYY-MM-DD"
		});

		$('.time').datetimepicker({
			allowInputToggle: true,
			format: "HH:mm"
		});

        $('#delete').click(function() {
            if (confirm("{{ __('samples_list.confirm_delete') }}")) {
                window.location.href = "{{ route('samples_list.delete', ['id' => $samplesList->id]) }}";
            }
            return false;
        });

        $("form input").keypress(function (e) {
            if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
                return false;
            } else {
                return true;
            }
        });

        $('.js-select2').select2({
//            tags: true,
            width: '100%'
        });

        $('.js-select2-search').select2({
            tags: false,
            dropdownAutoWidth : true
        });
	});
    </script>

@endsection
