@include('header')

    <style>
        span{
            color:red;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <form id="login_form">
            <input type="email" name="email" placeholder="Enter Email">
            <br>
            <span class="error email_err"></span>
            <br><br>
            <input type="password" name="password" placeholder="Enter Password">
            <br>
            <span class="error password_err"></span>
            <br><br>

            <input type="submit" value="Login">

            <a href="{{ url('login') }}" class="btn btn-info">Register</a>
    </form>
    <br>
    <p class="result"></p>
</body>

<script>

    $(document).ready(function(){
        $("#login_form").submit(function(event){
            event.preventDefault();

            var formData = $(this).serialize();

            $.ajax({
                url:"http://127.0.0.1:8000/api/login",
                type:"POST",
                data:formData,
                success:function(data){
                    $(".error").text("");
                    if(data.success == false){
                        $(".result").text(data.msg);
                    }
                    else if(data.success == true){
                        console.log(data);
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
                $("."+key+"_err").text(value);
            });
        }
    });
</script>



</html>
