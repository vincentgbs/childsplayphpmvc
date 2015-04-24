<script type="text/javascript" src='js/sha.js'></script>
<script type="text/javascript" src='js/user.js'></script>

<?php echo isset($data['e'])?$data['e']:null; ?>

<h1>Register with us</h1>
<ul>
    <li>Usernames may contain only digits, upper and lower case letters and underscores</li>
    <li>Passwords must be at least 8 characters long</li>
</ul>
<form action="?app=User/processRegistration" 
        method="post" 
        name="registration_form">
    Username: <input type='text' 
        name='username' 
        id='username' /><br>
    Email: <input type="text" name="email" id="email" /><br>
    Password: <input type="password"
                     name="password" 
                     id="password"/><br>
    Confirm password: <input type="password" 
                             name="confirmpwd" 
                             id="confirmpwd" /><br>
    <input type="button" 
           value="Register" 
           onclick="return regformhash(this.form,
                           this.form.username,
                           this.form.email,
                           this.form.password,
                           this.form.confirmpwd);" /> 
</form>
<p>Return to the <a href="?app=User/Login">login page</a>.</p>