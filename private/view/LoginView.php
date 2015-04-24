<script type="text/javascript" src='js/sha.js'></script>
<script type="text/javascript" src='js/user.js'></script>

<?php echo isset($data['e'])?$data['e']:null; ?>

<form action="?app=User/processLogin" method="post" name="login_form">                      
    Email: <input type="text" name="email" />
    Password: <input type="password" 
                     name="password" 
                     id="password"/>
    <input type="button" 
           value="Login" 
           onclick="formhash(this.form, this.form.password);" /> 
</form>
<p>If you don't have a login, please <a href="?app=User/Register">register</a></p>
<p>If you are done, please <a href="?app=User/Logout">log out</a>.</p>