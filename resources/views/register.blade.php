@include('header')

    <style>
        span{
            color:red;
        }
    </style>
</head>
<body>
    <h1>User Registration</h1>
    <form id="register_form">
            <input type="text" name="name" placeholder="Enter Name">
            <br>
            <span class="error name_err"></span>
            <br><br>
            <input type="email" name="email" placeholder="Enter Email">
            <br>
            <span class="error email_err"></span>
            <br><br>
            <input type="password" name="password" placeholder="Enter Password">
            <br>
            <span class="error password_err"></span>
            <br><br>
            <input type="password" name="password_confirmation" placeholder="Enter Confirm Password"><br><br>
            <span class="error password_confirmation_err"></span>
            <input type="submit" value="Register">
            <a href="{{ url('login') }}" class="btn btn-info">Login</a>
    </form>
    <br>
    <p class="result"></p>
</body>

<script>

    $(document).ready(function(){
        $("#register_form").submit(function(event){
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url:"http://127.0.0.1:8000/api/register",
                type:"POST",
                data:formData,
                success:function(data){
                   if(data.msg){
                        $("#register_form")[0].reset();
                        $(".error").text("");
                        $(".result").text(data.msg);
                   }
                   else{
                        printErrorMsg(data);
                   }
                }
            });
        });

        function printErrorMsg(msg){
            $(".error").text("");
            $.each(msg,function(key, value){

                if(key == 'password'){
                    if(value.length > 1) {
                        $(".password_err").text(value[0]);
                        $(".password_confirmation_err").text(value[1]);
                    }
                    else{
                        if(value[0].includes('password confirmation')){
                            $(".password_confirmation_err").text(value);
                        }
                        else{
                            $(".password_err").text(value);
                        }
                    }
                }else{

                    $("."+key+"_err").text(value);
                    }

            });
        }
    });
</script>



</html>
