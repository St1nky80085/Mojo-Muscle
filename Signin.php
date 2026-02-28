<div id="signin-modal" class="modal-overlay">
    <span class="close-modal" id="close-modal">&times;</span>
    <div class="form-container">
      <input type="radio" name="form-nav" id="nav-login" checked class="hidden-radio" />
      <input type="radio" name="form-nav" id="nav-signup" class="hidden-radio" />
      <input type="radio" name="form-nav" id="nav-forgot" class="hidden-radio" />

      <!-- SIGN UP -->
      <div class="sign-up-container">
        <span class="form-title">Sign Up</span>
        <!-- Selected plan indicator -->
        <div id="selected-plan-display" style="display:none; text-align:center; margin-bottom:10px;">
            <span id="selected-plan-label" style="font-size:0.8rem; font-family:'Courier New',monospace; color:#92ff77; background:rgba(146,255,119,0.1); border:1px solid #92ff77; padding:4px 14px; border-radius:30px;"></span>
        </div>
        <label for="nav-login" class="switch-label">Already have an account? Login</label>
        <form class="sign-up-form" id="register-form">
          <input type="hidden" id="selected-plan" name="plan" value="Free" />
          <label for="username-sign-up">Username</label>
          <input class="input" placeholder="MojoMinion01" id="username-sign-up" name="username" type="text" required />
          <label for="email-sign-up">Email</label>
          <input class="input" placeholder="minion@mojojojo.com" id="email-sign-up" name="email" type="email" required />
          <label for="pwd-sign-up">Password</label>
          <input class="input" placeholder="Min 8 chars, 1 upper, 1 number" id="pwd-sign-up" name="password" type="password" required />
          <label for="confirm-pwd-sign-up">Confirm Password</label>
          <input class="input" placeholder="••••••••" id="confirm-pwd-sign-up" name="confirm_password" type="password" required />
          <button type="submit" id="register-btn">BECOME A MINION</button>
          <div class="social-message"><div class="line"></div><p class="message">Or sign up with</p><div class="line"></div></div>
          <div class="social-icons">
            <button type="button" aria-label="Google" class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16.318 13.714v5.484h9.078c-0.37 2.354-2.745 6.901-9.078 6.901-5.458 0-9.917-4.521-9.917-10.099s4.458-10.099 9.917-10.099c3.109 0 5.193 1.318 6.38 2.464l4.339-4.182c-2.786-2.599-6.396-4.182-10.719-4.182-8.844 0-16 7.151-16 16s7.156 16 16 16c9.234 0 15.365-6.49 15.365-15.635 0-1.052-0.115-1.854-0.255-2.651z"/></svg></button>
            <button type="button" aria-label="Facebook" class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M19.25 8.984h3.609v-6.328c-0.625-0.083-2.766-0.266-5.255-0.266-5.203 0-8.766 3.177-8.766 9.021v5.302h-5.88v7.031h5.88v17.844h7.271v-17.844h5.922l0.938-7.031h-6.859v-4.484c0-2.031 0.563-3.422 3.141-3.422z"/></svg></button>
          </div>
        </form>
      </div>

      <!-- LOGIN -->
      <div class="log-in-container">
        <span class="form-title">Login</span>
        <label for="nav-signup" class="switch-label">Don't have an account? Sign Up</label>
        <form class="log-in-form" id="login-form">
          <label for="email-login">Email</label>
          <input class="input" placeholder="minion@mojojojo.com" id="email-login" name="email" type="email" required />
          <label for="pwd-login">Password</label>
          <input class="input" placeholder="••••••••" id="pwd-login" name="password" type="password" required />
          <button type="submit" id="login-btn">ENTER LAIR</button>
          <label for="nav-forgot" class="switch-label">Forgot password?</label>
          <div class="social-message"><div class="line"></div><p class="message">Or login with</p><div class="line"></div></div>
          <div class="social-icons">
            <button type="button" aria-label="Google" class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M16.318 13.714v5.484h9.078c-0.37 2.354-2.745 6.901-9.078 6.901-5.458 0-9.917-4.521-9.917-10.099s4.458-10.099 9.917-10.099c3.109 0 5.193 1.318 6.38 2.464l4.339-4.182c-2.786-2.599-6.396-4.182-10.719-4.182-8.844 0-16 7.151-16 16s7.156 16 16 16c9.234 0 15.365-6.49 15.365-15.635 0-1.052-0.115-1.854-0.255-2.651z"/></svg></button>
            <button type="button" aria-label="Facebook" class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><path d="M19.25 8.984h3.609v-6.328c-0.625-0.083-2.766-0.266-5.255-0.266-5.203 0-8.766 3.177-8.766 9.021v5.302h-5.88v7.031h5.88v17.844h7.271v-17.844h5.922l0.938-7.031h-6.859v-4.484c0-2.031 0.563-3.422 3.141-3.422z"/></svg></button>
          </div>
        </form>
      </div>

      <!-- FORGOT PASSWORD - must use forgot-pwd-container and forgot-pwd-form to match inup.css -->
      <div class="forgot-pwd-container">
        <span class="form-title">Forgot Password</span>
        <label for="nav-login" class="switch-label">Remember it? Back to Login</label>
        <form class="forgot-pwd-form" id="forgot-form">
          <label for="email-forgot">Email</label>
          <input class="input" placeholder="minion@mojojojo.com" id="email-forgot" name="email" type="email" required />
          <button type="submit" id="forgot-btn">SEND RECOVERY BEAM</button>
        </form>
      </div>

    </div>
</div>

<div id="mojo-toast" class="mojo-toast"></div>
