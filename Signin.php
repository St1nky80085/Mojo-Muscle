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
