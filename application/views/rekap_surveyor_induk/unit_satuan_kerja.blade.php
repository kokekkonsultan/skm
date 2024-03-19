@php
$ci = get_instance();
@endphp

<label for="" class="font-weight-bold">Pilih Unit atau Satuan Kerja</label>
<select name="akun_anak" id="akun_anak" class="form-control kt_select2_2">
	<option value="">Please Select</option>
	@foreach ($user_anak->result() as $value)
		<option value="{{ $value->id }}">{{ $value->first_name }} {{ $value->last_name }}</option>
	@endforeach
</select>