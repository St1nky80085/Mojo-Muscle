<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/inup.css">
</head>
<body>
  <div id="signin-modal" class="modal-overlay">
    
    <span class="close-modal" id="close-modal">&times;</span>

    <div class="form-container">
      <input type="radio" name="form-nav" id="nav-login" checked class="hidden-radio" />
      <input type="radio" name="form-nav" id="nav-signup" class="hidden-radio" />
      <input type="radio" name="form-nav" id="nav-forgot" class="hidden-radio" />
      
      <div class="sign-up-container">
        <span class="form-title">Sign Up</span>
        <label for="nav-login" class="switch-label">Already have an account? Login</label>
        <form class="sign-up-form">
          <label for="username-sign-up">Username</label>
          <input class="input" placeholder="MojoMinion01" id="username-sign-up" name="Username" type="text" />
          
          <label for="email-sign-up">Email</label>
          <input class="input" placeholder="minion@mojojojo.com" id="email-sign-up" name="Email" type="email" />
          
          <label for="pwd-sign-up">Password</label>
          <input class="input" placeholder="••••••••" id="pwd-sign-up" name="Password" type="password" />
          
          <label for="confirm-pwd-sign-up">Confirm Password</label>
          <input class="input" placeholder="••••••••" id="confirm-pwd-sign-up" name="Password" type="password" />
          
          <button type="button">BECOME A MINION</button>

          <div class="social-message">
            <div class="line"></div>
            <p class="message">Or sign up with</p>
            <div class="line"></div>
          </div>
          <div class="social-icons">
            <button aria-label="Sign up with Google" class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5 fill-current"><path d="M16.318 13.714v5.484h9.078c-0.37 2.354-2.745 6.901-9.078 6.901-5.458 0-9.917-4.521-9.917-10.099s4.458-10.099 9.917-10.099c3.109 0 5.193 1.318 6.38 2.464l4.339-4.182c-2.786-2.599-6.396-4.182-10.719-4.182-8.844 0-16 7.151-16 16s7.156 16 16 16c9.234 0 15.365-6.49 15.365-15.635 0-1.052-0.115-1.854-0.255-2.651z"></path></svg>
            </button>
            <button aria-label="Sign up with Facebook" class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5 fill-current"><path d="M19.25 8.984h3.609v-6.328c-0.625-0.083-2.766-0.266-5.255-0.266-5.203 0-8.766 3.177-8.766 9.021v5.302h-5.88v7.031h5.88v17.844h7.271v-17.844h5.922l0.938-7.031h-6.859v-4.484c0-2.031 0.563-3.422 3.141-3.422z"></path></svg>
            </button>
          </div>
        </form>
      </div>
      
      <div class="log-in-container">
        <span class="form-title">Login</span>
        <label for="nav-signup" class="switch-label">Don't have an account? Sign Up</label>
        <form class="log-in-form">
          <label for="email-log-in">Email</label>
          <input class="input" placeholder="minion@mojojojo.com" id="email-log-in" name="Email" type="email" />
          
          <label for="pwd-log-in">Password</label>
          <input class="input" placeholder="••••••••" id="pwd-log-in" name="Password" type="password" />
          
          <label for="nav-forgot" class="forgot-pwd">Forgot password?</label>
          <button type="button">ENTER LAIR</button>
          
          <div class="social-message">
            <div class="line"></div>
            <p class="message">Or log in with</p>
            <div class="line"></div>
          </div>
          <div class="social-icons">
            <button aria-label="Log in with Google" class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5 fill-current"><path d="M16.318 13.714v5.484h9.078c-0.37 2.354-2.745 6.901-9.078 6.901-5.458 0-9.917-4.521-9.917-10.099s4.458-10.099 9.917-10.099c3.109 0 5.193 1.318 6.38 2.464l4.339-4.182c-2.786-2.599-6.396-4.182-10.719-4.182-8.844 0-16 7.151-16 16s7.156 16 16 16c9.234 0 15.365-6.49 15.365-15.635 0-1.052-0.115-1.854-0.255-2.651z"></path></svg>
            </button>
            <button aria-label="Log in with Facebook" class="icon">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" class="w-5 h-5 fill-current"><path d="M19.25 8.984h3.609v-6.328c-0.625-0.083-2.766-0.266-5.255-0.266-5.203 0-8.766 3.177-8.766 9.021v5.302h-5.88v7.031h5.88v17.844h7.271v-17.844h5.922l0.938-7.031h-6.859v-4.484c0-2.031 0.563-3.422 3.141-3.422z"></path></svg>
            </button>
          </div>
        </form>
      </div>

      <div class="forgot-pwd-container">
        <span class="form-title">Reset Code</span>
        <label for="nav-login" class="switch-label">Remembered it? Back to Login</label>
        <form class="forgot-pwd-form">
          <label for="email-forgot">Enter your registered email</label>
          <input class="input" placeholder="minion@mojojojo.com" id="email-forgot" name="Email" type="email" />
          
          <button type="button">SEND RECOVERY BEAM</button>
        </form>
      </div>

    </div>
</div>
    
</body>
</html>