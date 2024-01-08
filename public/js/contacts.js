$(function(){
    const TOKEN = localStorage.getItem('token');
    var current_page = 1;
    var selected_contact_id = null;

    const formatPages = (num_of_page,clss_name='') => {
        let pages = [];
        for (let count = 1; count <= num_of_page ; count++) {
            pages.push(`
                <button data-page="${count}" 
                    class="${clss_name}${current_page === count? ' current-page': ''}"
                    ${current_page === count? 'disabled': ''}
                    >
                        ${count}
                </button>
            `);
        }
        return pages.join('');
    }    
    const table_data = (offset = current_page, limit = $('#limits').val(), keyword = $('#search-keyword').val()) =>{
        $.ajax({
            url:`/api/contact/view?offset=${offset}&limit=${limit}&keyword=${keyword}`,
            type:'GET',
            headers : {
                'Authorization' : `Bearer ${TOKEN}`
            },
            beforeSend:()=>{
                $('#contacts-container tbody').html('<tr colspan="4"><td>Loading</td></tr>');
            },
            success:(response)=>{
                const contact_list = response.contact_list;
                const total_count = response.count; 
                const tbl_body_container = $('#contacts-container tbody');
                const tbl_notif_container = $('#contact-notif-container');
                const page_container = $('#pages');
    
                if(total_count<0){
                    tbl_notif_container.html('<span>No Available Contacts</span>')
                }else{
                    const contacts = contact_list?.map((contact)=>`
                        <tr class="contact-data">
                            <td data-contact-name="${contact.name}">${contact.name??''}</td>
                            <td data-contact-company="${contact.company}">${contact.company??''}</td>
                            <td data-contact-phone="${contact.phone}">${contact.phone??''}</td>
                            <td data-contact-email="${contact.email}">${contact.email??''}</td>
                            <td>
                                <button class="delete-contact" data-contact-id="${contact.contact_id}">Delete</button>
                                <button class="update-contact" data-contact-id="${contact.contact_id}">Update</button>
                            </td>
                        </tr>
                    `)
                    tbl_body_container.html(contacts);
                    const page_count = Math.ceil(total_count/limit);
                    page_container.html(formatPages(page_count,'page_number'));
                }
            },
            error:(err)=>{
                alert(err.responseJSON.message);
                localStorage.clear();
                location.replace('/login')
            }
        });
    }

    $(document).on('submit','#add-contact-form',function(e){
        e.preventDefault();
        const formdata = new FormData($(this)[0]);
        $.ajax({
            url:'/api/contact/create',
            type:'POST',
            headers:{
                'Authorization': `Bearer ${TOKEN}`
            },
            data:formdata,
            processData:false,
            contentType: false,
            success:(response)=>{
                if(response.success === true){
                    alert('Contact Created');
                    table_data();
                    $('#add-contact-modal').modal('hide')
                }else{
                    alert('An error has occured creating contact');
                }
            },
            error:(err)=>{
                const errors = (ExtractErrors(err.responseJSON.message))
                if(errors)
                    alert(errors.join(''));
                else
                    alert('An error has occured');
            }
        });
    })
    
    // initialize table
    table_data();

    // table updates
    $(document).on('click','.page_number',function(){
        const select_page = $(this).data('page'); 
        current_page = select_page;
        table_data();
    });
    $(document).on('change','#limits',function(){
        current_page = 1;
        table_data();
    });
    $(document).on('input','#search-keyword',function(){
        current_page = 1;
        table_data();
    });

    // table row data actions
    $(document).on('click','.update-contact',function(){
        selected_contact_id = $(this).data('contact-id');
        const contact_raw_data = $(this).parents('.contact-data')?.children('td');
        const contact_data = {
            'name' : $(contact_raw_data[0]).data('contact-name'),
            'company' : $(contact_raw_data[1]).data('contact-company'),
            'phone' : $(contact_raw_data[2]).data('contact-phone'),
            'email' : $(contact_raw_data[3]).data('contact-email'),
        }
        $('#update-fname').val(contact_data.name);
        $('#update-company').val(contact_data.company);
        $('#update-phone').val(contact_data.phone);
        $('#update-email').val(contact_data.email);
        $('#update-confirm-modal').modal('show');
    });

    //update contact
    $(document).on('submit','#update-contact-form',function(e){
        e.preventDefault();
        const formdata = {
            'name': $('#update-fname').val(),
            'company': $('#update-company').val(),
            'phone': $('#update-phone').val(),
            'email': $('#update-email').val()
        };
        $.ajax({
            url:`/api/contact/update/${selected_contact_id}`,
            type:'PUT',
            headers:{
                'Authorization': `Bearer ${TOKEN}`
            },
            data:formdata,
            success:(response)=>{
                console.log(response)
                if(response.success === true){
                    alert('Contact Updated');
                    table_data();
                    $('#update-confirm-modal').modal('hide')
                }else{
                    alert('An error has occured updating contact');
                }
            },
            error:(err)=>{
                console.log(err)
                const errors = (ExtractErrors(err.responseJSON.message))
                if(errors)
                    alert(errors.join('\n'));
                else
                    alert('An error has occured');
            }
        });
    })

    //delete contact
    $(document).on('click','.delete-contact',function(){
        selected_contact_id = $(this).data('contact-id');
        $('#delete-confirm-modal').modal('show');
    })

    $(document).on('click','#confirm-delete-contact',function(){
        $.ajax({
            url:`/api/contact/delete/${selected_contact_id}`,
            type:'DELETE',
            headers:{
                'Authorization': `Bearer ${TOKEN}`
            },
            success:(response)=>{
                console.log(response)
                if(response.success === true){
                    alert('Contact Deleted');
                    table_data();
                    $('#delete-confirm-modal').modal('hide');
                }else{
                    alert('An error has occured deleting contact');
                }
            },
            error:(err)=>{
                console.log(err)
                const errors = (ExtractErrors(err.responseJSON.message))
                if(errors)
                    alert(errors.join('\n'));
                else
                    alert('An error has occured');
            }
        });
    })

    $(document).on('click','#logout',function(){
        $.ajax({
            url:`/api/auth/logout`,
            type:'DELETE',
            headers:{
                'Authorization': `Bearer ${TOKEN}`
            },
            success:(response)=>{
                console.log(response)
                if(response.success === true){
                    location.replace('/login');
                    localStorage.clear();
                }else{
                    alert('An error has occured ');
                }
            },
            error:(err)=>{
                console.log(err)
                const errors = (ExtractErrors(err.responseJSON.message))
                if(errors)
                    alert(errors.join('\n'));
                else
                    alert('An error has occured');
            }
        });
    })
    
})