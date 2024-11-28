const password = document.getElementById("Password");
const confirmPassword = document.getElementById("passwordConfirmation");
const password_confirmation_help = document.getElementById(
  "passwordConfirmationHelp"
);
const password_confirmation_help_span =
  password_confirmation_help.getElementsByTagName("span")[0];

function repeat_pw_check() {
  if (confirmPassword.value == "") {
    password_confirmation_help_span.textContent = "*Required.";
  } else if (password.value === confirmPassword.value) {
    password_confirmation_help_span.textContent = "Password ok.";
  } else {
    password_confirmation_help_span.textContent =
      "*Confirmed password is different.";
  }
}

password.addEventListener("input", repeat_pw_check);
confirmPassword.addEventListener("input", repeat_pw_check);
