@php
$ci = get_instance();
@endphp

<div class="row">
@foreach ($manage_survey->result() as $value)
	<div class="col-md-6">
		<div class="card shadow mt-5">
			<div class="card-header font-weight-bold">
				{{ $value->survey_name }}
			</div>
			<div class="card-body">
            
				<div class="text-center font-weight-bold mt-5">
					Bagikan link dibawah ini kepada responden untuk dilakukan pengisian.
				</div>
				<br>
				<div class='input-group'>
					<input class='form-control' id='kt_clipboard_1'
						value="{{ base_url() }}survei/{{ $value->slug }}/{{ $value->uuid }}" readonly />
					<div class='input-group-append'>
						<a href='javascript:void(0)' class='btn btn-light-primary' data-clipboard='true'
							data-clipboard-target='#kt_clipboard_1'><i class='la la-copy'></i> <strong>Copy
								Link</strong></a>
					</div>
				</div>

				<br>
				<div class="text-center font-weight-bold mt-5">
					Atau gunakan tombol dibawah ini.
				</div>

				<br>

				<div class="text-center">
					<a class="btn btn-primary font-weight-bold shadow btn-block"
						href="{{ base_url() }}survei/{{ $value->slug }}/{{ $value->uuid }}"
						target="_blank"><i class="fas fa-link"></i>Link Survei</a>
				</div>
			</div>
		</div>
	</div>
@endforeach
</div>