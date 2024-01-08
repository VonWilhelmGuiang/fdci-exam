$(function(){
    $(document).on('submit','#login-form',function(e){
        e.preventDefault();
        const formdata = new FormData($(this)[0])
        $.ajax({
            url: "/api/auth/login",
            type: "POST",
            data:  formdata,
            contentType: false,
            processData:false,
            cache: false,
            success: (response)=>{
                alert(response.message)
                localStorage.setItem('token',response.token);
                location.replace('/contacts');
            },
            error: (err)=>{
                if(err.responseJSON.errors){
                    const errors = (ExtractErrors(err.responseJSON.errors))
                    alert(errors.join('\n'))
                }else{
                    alert(err.responseJSON.message+'\n')
                }
            }
        })
    })
})