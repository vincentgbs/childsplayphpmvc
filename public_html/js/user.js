function regformhash(form, uid, email, password, conf) {
    if (uid.value == ''         || 
          email.value == ''     || 
          password.value == ''  || 
          conf.value == '') {
 
        alert('You must provide all the requested details. Please try again');
        return false;
    }
 
    // Check the email format
    re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/; 
    if(!re.test(form.email.value)) { 
        alert("Please enter a valid email address."); 
        form.email.focus();
        return false; 
    }
    if (email.value.length > 128) {
        alert("Your email is too long, please use a different email."); 
        form.username.focus();
        return false; 
    }

    // Check the username
    re = /^\w+$/; 
    if(!re.test(form.username.value)) { 
        alert("Username must contain only letters, numbers and underscores."); 
        form.username.focus();
        return false; 
    }
    if (username.value.length > 32) {
        alert("This username is too long."); 
        form.username.focus();
        return false; 
    }
 
    // Check that the password is sufficiently long (min 6 chars)
    // The check is duplicated below, but this is included to give more
    // specific guidance to the user
    if (password.value.length < 8) {
        alert('Passwords must be at least 8 characters long.');
        form.password.focus();
        return false;
    }
 
    // Check password and confirmation are the same
    if (password.value != conf.value) {
        alert('Your password and confirmation do not match.');
        form.password.focus();
        return false;
    }
 
    // Create a new element input, this will be our hashed password field. 
    var p = document.createElement("input");
 
    // Add the new element to our form. 
    form.appendChild(p);
    p.name = "p";
    p.type = "hidden";
    p.value = CryptoJS.SHA512(password.value);
 
    // Make sure the plaintext password doesn't get sent. 
    password.value = "";
    conf.value = "";
 
    // Finally submit the form. 
    form.submit();
    return true;
}

function formhash (form, password) {
    var email = form.email.value;
    var pass = form.password.value;

    pass = CryptoJS.SHA512(pass);
    var secret = document.createElement("input");
    secret.name = "p";
    secret.type = "hidden";
    secret.value = pass;
    form.appendChild(secret);
    
    form.password.value = "";
    
    form.submit();
}