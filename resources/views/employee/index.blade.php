<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.css" rel="stylesheet" />
    <script src="https://code.iconify.design/3/3.1.0/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link type="text/css" rel="stylesheet" href="/js/simplePagination.css" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Employee Data</title>
</head>

<body class="flex h-screen flex-col bg-sky-200 p-5">
    <div class="p-5 rounded-md">
        <h1 class="font-extrabold uppercase text-2xl pt-2 mb-5">Employee Data</h1>
        <div class="flex justify-between mb-3">
            <select id="sortBy"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block p-2.5">
                <option selected>Sort By</option>
                <option value="1">Ascending</option>
                <option value="2">Descending</option>
            </select>
            <button id="addBtn" class="px-3 py-2 bg-white text-sky-600 text-2xl text-extrabold rounded-md">
                <span class="iconify" data-icon="mdi:plus-thick"></span>
            </button>
        </div>
        <div class="main relative overflow-x-auto shadow-md sm:rounded-md">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Sl.No
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Username
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Phone
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Gender
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <div id="pagination" class="bg-white py-5 flex justify-center">

            </div>
        </div>

    </div>
    @include('employee.components.add_form')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.4/flowbite.min.js"></script>
    <script src="/js/jquery.simplePagination.js"></script>
    <script>
        const ele = document.getElementById('add-employee')
        const modal = new Modal(ele)
        $(function() {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('[data-modal-hide="add-employee"]').click(function(e){
                e.preventDefault();
                modal.hide()
            })

            $("#addBtn").click(function(){
                $('#add-employee h3').html('Add Employee Details')
                $("#add-employee [type='submit']").html('Submit')
                $("#employeeForm").attr('action', '/add')
                $("#employeeForm").attr('method', 'post')
                $("#employeeForm option").removeAttr('selected')
                $("#employeeForm")[0].reset()
                modal.show()
            })

            getEmployees();
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            })


            $("#employeeForm").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: $(this).attr('method'),
                    url: $(this).attr('action'),
                    data: $(this).serialize()
                }).done(({status, message}) => {
                    Toast.fire({
                        icon: 'success',
                        title: message
                    })
                    getEmployees();
                    modal.hide()

                })
            })
            var page = 1;
            var sortby = '';
            function getEmployees(pageNumber = page) {
                page = pageNumber;
                $.ajax({
                    type: "get",
                    url: "/get-employees-details",
                    data: {
                        page,
                        sortby
                    }
                }).done(({
                    status,
                    data
                }) => {
                    if (status) {
                        $('tbody').html('')
                        if(data.data.length > 0)
                        {
                            data.data.forEach((item) => {
                            $('tbody').append(`
                            <tr class="bg-white border-b dark:bg-gray-900 dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                ${data.from++}
                            </th>
                            <td class="px-6 py-4">
                                ${item.username}
                            </td>
                            <td class="px-6 py-4">
                                ${item.email}
                            </td>
                            <td class="px-6 py-4">
                                ${item.phone}

                            </td>
                            <td class="px-6 py-4">
                                ${item.gender == 1 ? 'Male' : 'Female'}

                            </td>
                            <td class="px-6 py-4 flex gap-2 items-center">
                                <a href="javascript:void(0)" data-id="${item.id}"
                                    class="font-medium text-xl text-sky-600 hover:underline edit"><span class="iconify"
                                        data-icon="material-symbols:edit-rounded"></span></a>
                                <a href="javascript:void(0)" data-id="${item.id}"
                                    class="font-bold text-xl text-red-600 hover:underline delete"><span class="iconify"
                                        data-icon="material-symbols:delete-rounded"></span></a>
                            </td>
                        </tr>
                        `)})

                        $("#pagination").pagination({
                            items: parseInt(data.total),
                            itemsOnPage: parseInt(data.per_page),
                            currentPage: data.current_page,
                            displayedPages: 3,
                            navStyle: "pagination justify-content-center justify-content-md-start",
                            listStyle: "page-item",
                            linkStyle: "page-link",
                            onPageClick: function(pageNumber, event) {
                                event ? event.preventDefault() : '';
                                getEmployees(pageNumber);

                            }
                        })
                        }
                        else
                        {
                            if(page > 1)
                            {
                                getEmployees(page-1)
                            }
                            else
                            {
                                $("#pagination").html('')
                                $(".main").html('<p class="bg-white text-center py-5 text-xl font-bold">No Data Available.</p>')
                            }
                        }
                        $('.delete').click(function() {
                            Swal.fire({
                                title: 'Are you sure?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                cancelButtonText: 'No',
                                confirmButtonText: 'Yes'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        type: "delete",
                                        url: '/delete',
                                        data: {
                                            id: $(this).data('id')
                                        }
                                    }).done(({status, message})=>{
                                        if(status)
                                        {
                                            getEmployees();
                                            Swal.fire({
                                                text: message,
                                                icon: 'success',
                                            })
                                        }
                                        else
                                        {
                                            Swal.fire({
                                                text: message,
                                                icon: 'error',
                                            })
                                        }
                                    }).fail(()=>{
                                        Swal.fire({
                                            text: "Error Occurred!",
                                            icon: 'error',
                                        })
                                    })
                                }
                            })
                        })
                        $('.edit').click(function(){
                            const id = $(this).data("id")
                            $.ajax({
                                type: 'get',
                                url: `/edit/${id}`
                            }).done(({status, data, message})=>{
                                if(status)
                                {
                                    $('#add-employee h3').html('Edit Employee Details')
                                    $("#add-employee [type='submit']").html('Update')
                                    $("#employeeForm").attr('action', '/update')
                                    $("#employeeForm").attr('method', 'patch')
                                    $('[type="hidden"]').remove();
                                    $("#employeeForm").append(`<input type="hidden" value="${data.id}" name="id">`)


                                    $("#username").val(data.username)
                                    $("#phone").val(data.phone)
                                    $("#email").val(data.email)
                                    $("#gender").html(`
                                    <option selected>Select Gender</option>
                                    <option ${data.gender == 1 ? 'selected' : ''} value="1">Male</option>
                                    <option ${data.gender == 2 ? 'selected' : ''} value="2">Female</option>
                                    `)
                                    modal.show()
                                    
                                }
                                else
                                {
                                    Toast.fire({
                                        icon: 'error',
                                        title: message
                                    })
                                }
                            }).fail(()=>{
                                Toast.fire({
                                        icon: 'error',
                                        title: "Error Occurred!"
                                    })
                            })
                        })
                    }


                })
            }

            $("#sortBy").change(function(){
                sortby = $(this).val()
                getEmployees();
            })


        })
    </script>
</body>

</html>
