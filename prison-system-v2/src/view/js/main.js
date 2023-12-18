function togglePasswordVisibility(toggleButtonId, passwordInputId) {
  const toggleButton = document.querySelector(toggleButtonId);
  const passwordInput = document.querySelector(passwordInputId);

  if (toggleButton && passwordInput) {
    toggleButton.addEventListener("click", () => {
      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);
      toggleButton.classList.toggle("bi-eye-slash");
      toggleButton.classList.toggle("bi-eye");
    });
  }
}

// Gebruik de functie met controles voor verschillende sets van knoppen en wachtwoorden:
togglePasswordVisibility("#togglePassword", "#password");
togglePasswordVisibility("#togglePassword1", "#password1");
togglePasswordVisibility("#togglePassword2", "#password2");
togglePasswordVisibility("#togglePassword3", "#password3");

document.querySelectorAll("button.update").forEach(function (element) {
  element.addEventListener("click", function () {
    // user data ophalen
    const id_user = this.dataset.id_user;
    if (id_user && id_user.length >= 1) {
      // Gebruik van AJAX met de juiste URL en gegevens
      $.ajax({
        method: "POST",
        url: "./ajax.php",
        data: {
          id_user: id_user,
        },
        success: function (response) {
          const user = response;
          const userAddress = JSON.parse(user.address);

          // Elementen die moeten worden gevuld
          const formElements = {
            service_number: user.id_service_number,
            service_number_placeholder: user.id_service_number,
            username: user.username,
            password: "",
            firstname: user.firstname,
            lastname: user.lastname,
            emailaddress: user.emailaddress,
            phone_number: user.phone_number,
            function: {
              Bewaker: "1",
              Hoofdbewaker: "2",
              Celbeheerder: "3",
              Schoonmaker: "4",
              "Administratief medewerker": "5",
              Manager: "6",
              Inactief: "7",
            }[user.function],
            supervisor: user.supervisor === 0 ? "0" : "1",
            zipcode: userAddress.zipcode,
            street: userAddress.street,
            house_number: userAddress.house_number,
            house_number_extra: userAddress.house_number_extra,
            city: userAddress.city,
          };

          // Loop door de elementen en vul ze in
          for (const [elementId, value] of Object.entries(formElements)) {
            document.getElementById(elementId).value = value;
          }

          // Tonen van formulier
          document.querySelector("button.save_user").textContent =
            "Wijzigingen opslaan";
          document.querySelector("button.save_user").name =
            "update_user_settings";
          document.querySelector(".update_settings").classList.toggle("hidden");
        },
      });
    }
  });
});

document
  .querySelector("button.add_new_user")
  .addEventListener("click", function () {
    // Elementen die moeten worden gevuld
    const formElements = {
      service_number: "",
      service_number_placeholder: "",
      username: "",
      password: "",
      firstname: "",
      lastname: "",
      emailaddress: "",
      phone_number: "",
      function: 7,
      supervisor: 0,
      zipcode: "",
      street: "",
      house_number: "",
      house_number_extra: "",
      city: "",
    };

    // Loop door de elementen en vul ze in
    for (const [elementId, value] of Object.entries(formElements)) {
      document.getElementById(elementId).value = value;
    }

    // Tonen van formulier
    document.querySelector("button.save_user").textContent =
      "Nieuwe gebruiker toevoegen";
    document.querySelector("button.save_user").name = "add_new_user";
    document.querySelector(".update_settings").classList.toggle("hidden");
  });

// Zoek alle select-elementen met de klasse "select_supervisor" in de DOM
document.querySelectorAll(".category-select.supervisor").forEach((select) => {
  // Voeg een eventlistener toe aan elk select-element
  select.addEventListener("change", () => {
    // Vind het bijbehorende formulier van het select-element
    const form = select.closest(".statusForm");
    if (form) {
      form.submit();
    }
  });
});

document
  .querySelector(".personal_settings_form > button.close-button")
  .addEventListener("click", function () {
    document.querySelector(".update_settings").classList.toggle("hidden");
    document.querySelector("button.save_user").name = "";
    document.querySelector("button.save_user").textContent = "";
  });

document.querySelectorAll("button.delete").forEach(function (select) {});
