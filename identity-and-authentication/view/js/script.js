$(document).ready(() => {
  const isValidInput = (input, pattern) => input.match(pattern);

  const handleInputValidation = (input, pattern) => {
    const isValid = isValidInput(input.val(), pattern);
    input
      .removeClass("is-valid is-invalid")
      .toggleClass("is-invalid", !isValid)
      .toggleClass("is-valid", isValid);
  };

  const $username = $("#username");
  const $password = $("#password");
  const $password1 = $("#password1");
  const $password2 = $("#password2");
  const $code = $("#code");

  if ($username.length) {
    $username.on("keyup", () =>
      handleInputValidation($username, /^[a-zA-Z0-9]+$/)
    );
  }

  if ($password.length) {
    $password.on("keyup", () =>
      handleInputValidation($password, /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/)
    );
  }

  if ($password1.length) {
    $password1.on("keyup", () =>
      handleInputValidation($password1, /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/)
    );
  }

  if ($password2.length) {
    $password2.on("keyup", () =>
      handleInputValidation($password2, /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/)
    );
  }

  if ($code.length) {
    $code.on("keyup", () => handleInputValidation($code, /^[0-9]{6}$/));
  }
});
