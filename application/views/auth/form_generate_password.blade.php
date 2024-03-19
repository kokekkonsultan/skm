@php 
	$ci = get_instance();
@endphp
<div id="load">
    
</div>
<input type="text" name="pass" id="pass" value="{{ $password }}" class="form-control" style="font-size: 14px;">
<button type="" onclick="generate(1)" class="btn btn-outline-primary btn-block font-weight-bold mt-3" id="button-generate">Generate Ulang</button>

<hr>
<div class="text-right">
	<button type="button" class="btn btn-light-primary font-weight-bold shadow-lg" data-dismiss="modal">Tutup</button>
	<button type="" onclick="copytextbox()" class="btn btn-primary font-weight-bold shadow-lg">Gunakan</button>
</div>

<script>
function copytextbox() {
    document.getElementById('password').value = document.getElementById('pass').value;
    document.getElementById('password_confirm').value = document.getElementById('pass').value;
    // alert("Berhasil menggunakan generate password");
    $('#exampleModal').modal('hide');
}

	function generate(id)
    {

        $.ajax({
            type: "post",
            url: "{{ base_url() }}auth/generate-password-form",
            data: "id="+id,
            dataType: "text",
            beforeSend:function(){
                document.getElementById("button-generate").disabled = true;
                $('#load').html("<div class='text-center'><img src='{{ base_url() }}assets/img/ajax/ajax-loader-big.gif'></div>");
            },
            success: function (response) {
            	document.getElementById('pass').value = response;
                document.getElementById("button-generate").disabled = false;
                $('#load').html("");
            }
        });
    }
</script>