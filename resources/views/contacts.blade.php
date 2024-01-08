<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Contacts</title>
    <script>
        if(!localStorage.getItem('token')){
           location.replace('/login')
        }
    </script>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    @foreach ($css as $cssitem)
        <link rel="stylesheet" href="{{asset($cssitem)}}"/>
    @endforeach
</head>
<body>
    <div>
        <div class="d-inline-block">
            <button class="btn btn-warning" id="logout">Logout</button>
        </div>
        <div class="d-inline-block">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#add-contact-modal">
                Add Contact
            </button>
        </div>
    </div>
    <div>
        
        <div>
            <input type="text" id="search-keyword" name="search-keyword" placeholder="Search"/>
        </div>
        <div>
            <table id="contacts-container" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Company</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div>
            <div id="pages"></div>
            <div>
                <select id="limits">
                    <?php
                        $limits = ['2','5','10','15','20'];
                    ?>
                    
                    @foreach ($limits as $limit)
                        @if ($loop->first)
                            <option value="{{$limit}}" selected>{{$limit}}</option>
                        @else
                            <option value="{{$limit}}" >{{$limit}}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="delete-confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Delete Conctact</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <span>Are you sure you want to Delete Conctact?</span>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-primary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-contact">Yes</button>
            </div>
            </div>
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>

    <div class="modal fade" id="update-confirm-modal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Update Conctact</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="update-contact-form">
                    <div class="modal-body">
                        <div>
                            <div>
                                Name
                                <input type="text" name="name" id="update-fname" placeholder="Name" required/>
                            </div>

                            <div>
                                Company
                                <input type="text" name="company" id="update-company" placeholder="company"/>
                            </div>

                            <div>
                                Phone
                                <input type="text" name="phone" id="update-phone"  placeholder="phone"/>
                            </div>
                            
                            <div>
                                Email
                                <input type="email" name="email" id="update-email" placeholder="email"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Update Contact</button>
                    </div>
                </form>
            </div>
            
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>


    <div class="modal fade" id="add-contact-modal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add Conctact</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
                <form id="add-contact-form">
                    <div class="modal-body">
                        <div>
                            <div>
                                Name
                                <input type="text" name="name" placeholder="Name" required/>
                            </div>

                            <div>
                                Company
                                <input type="text" name="company" placeholder="company"/>
                            </div>

                            <div>
                                Phone
                                <input type="text" name="phone"  placeholder="phone"/>
                            </div>
                            
                            <div>
                                Email
                                <input type="email" name="email" placeholder="email"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Create Contact</button>
                    </div>
                </form>
            </div>
            
            <!-- /.modal-content -->
        </div>
    <!-- /.modal-dialog -->
    </div>


</body>
    @foreach ($js as $item)
        <script src="{{asset($item)}}"></script>
    @endforeach
</html> 