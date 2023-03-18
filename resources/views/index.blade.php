<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>

</head>

<body>
    <div class="h-screen flex flex-col justify-center items-center">
        <div>
            <h1 class='text-xl font-bold'>Registration</h1>
        </div>
        <form class="flex flex-col gap-3" action="/sign-up" method="post">
            @csrf
            <input class="p-2 border" type="text" name="username" placeholder="Enter Username">
            
            <input class="p-2 border" type="password" name="password" placeholder="Enter Password">
            <input class="p-2 border" type="password" name="cpassword" placeholder="Enter confirm Password">
            <div class="flex">
                <input class="p-2 border" type="tel" id="phone" name="phone" placeholder="Enter Phone Number">
                <button type="button" id="get-otp" class="p-1 bg-black text-white">Get Otp</button>
            </div>
            <input class="p-2 border" type="text" id="otp" name="otp" placeholder="Enter Otp">
            <button id="submit" class="px-3 py-2 bg-blue-300">Sign Up</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    <script>
        let otpp = '';
        $(function () {
            $("#otp").change(function(){
                if($(this).val() == otpp)
                {
                    $(this).removeClass('border-red-600').addClass('border-green-600')
                    $("#submit").show()

                }
                else
                {
                    $(this).removeClass('border-green-600').addClass('border-red-600')
                    $("#submit").hide()

                }
            })

            $("#get-otp").click(function () {
               $.ajax({
                type: "GET",
                url: '/get-otp',
                async: false,
                data: {
                    phone: $("#phone").val(),
                }
               }).done(({otp})=>{
                otpp = otp
                alert("Otp as been sent.")
               })
            })
        })
    </script>
</body>

</html>