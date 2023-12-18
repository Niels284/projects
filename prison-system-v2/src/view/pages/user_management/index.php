<?php
session_start();

use Controller\Functions;

$_SESSION['current_page'] = 'gebruikers management';

include '../header.php';

if (
    isset($_POST['supervisor_change']) &&
    isset($_POST['service_number_change']) && !empty($_POST['service_number_change'])
) {
    $supervisor = $_POST['supervisor_change'];
    $service_number = $_POST['service_number_change'];
    $_POST = array();
    (new Functions)->update_supervisor_permissions($supervisor, $service_number);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['delete_user']) && !empty($_POST['delete_user'])) {
    (new Functions)->delete_account($_POST['delete_user']);
    $_POST = array();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['update_user_settings']) && !empty($_POST)) {
    $functions = ['Bewaker', 'Hoofdbewaker', 'Celbeheerder', 'Schoonmaker', 'Administratief medewerker', 'Manager'];
    $address = json_encode(
        [
            'city' => $_POST["city"],
            'street' => $_POST["street"],
            'house_number' => $_POST["house_number"],
            'house_number_extra' => empty($_POST["house_number_extra"]) ? "" : $_POST["house_number_extra"],
            'zipcode' => $_POST["zipcode"]
        ]
    );
    $function = $functions[$_POST["function"] - 1];
    $updated_user_settings = array(
        "service_number" => $_POST["service_number"],
        "firstname" => $_POST["firstname"],
        "lastname" => $_POST["lastname"],
        "emailaddress" => $_POST["emailaddress"],
        "phone_number" => $_POST["phone_number"],
        "username" => $_POST["username"],
        "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
        "address" => $address,
        "function" => $function,
        "supervisor" => $_POST["supervisor"] == 0 ? "0" : "1"
    );
    $_POST = array();
    $update = (new Functions)->update_user_settings($updated_user_settings);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

if (isset($_POST['add_new_user'])) {
    $functions = ['Bewaker', 'Hoofdbewaker', 'Celbeheerder', 'Schoonmaker', 'Administratief medewerker', 'Manager', "Inactief"];
    $address = json_encode(
        [
            'city' => $_POST["city"],
            'street' => $_POST["street"],
            'house_number' => $_POST["house_number"],
            'house_number_extra' => empty($_POST["house_number_extra"]) ? "" : $_POST["house_number_extra"],
            'zipcode' => $_POST["zipcode"]
        ]
    );
    $function = $functions[$_POST["function"] - 1];
    $add_new_user = array(
        "firstname" => $_POST["firstname"],
        "lastname" => $_POST["lastname"],
        "emailaddress" => $_POST["emailaddress"],
        "phone_number" => $_POST["phone_number"],
        "username" => $_POST["username"],
        "password" => password_hash($_POST["password"], PASSWORD_DEFAULT),
        "address" => $address,
        "function" => $function,
        "supervisor" => $_POST["supervisor"] == 0 ? "0" : "1"
    );
    $_POST = array();
    $add = (new Functions)->add_new_user($add_new_user);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

var_dump($_POST);
?>
<main>
    <h1>Je bevind je op de <?php echo $_SESSION['current_page'] ?> pagina</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-uppercase mb-0">Gebruikers beheer</h5>
                    </div>
                    <div class="table-responsive">
                        <table class="table no-wrap user-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col" class="border-0 text-uppercase font-medium pl-4">#</th>
                                    <th scope="col" class="border-0 text-uppercase font-medium">Naam</th>
                                    <th scope="col" class="border-0 text-uppercase font-medium">Functie</th>
                                    <th scope="col" class="border-0 text-uppercase font-medium">Email</th>
                                    <th scope="col" class="border-0 text-uppercase font-medium">Supervisor</th>
                                    <th scope="col" class="border-0 text-uppercase font-medium">Manage</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = (new Functions)->get_users();
                                foreach ($users as $user) {
                                ?>
                                    <tr>
                                        <td class="pl-4"><?php echo $user['id_user']; ?></td>
                                        <td>
                                            <h5 class="font-medium mb-0">
                                                <?php echo $user['firstname'] . ' ' . $user['lastname']; ?>
                                            </h5>
                                            <span class="text-muted">
                                                <?php
                                                $address = json_decode($user['address']);
                                                echo $address->street . ' ' . $address->house_number . $address->house_number_extra . ', ' . $address->city;
                                                ?></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo $user['function']; ?></span><br>
                                            <span class="text-muted">Dienst nr. <?php echo $user['id_service_number']; ?></span>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo $user['emailaddress']; ?></span><br>
                                            <span class="text-muted"><?php echo $user['phone_number']; ?></span>
                                        </td>
                                        <td>
                                            <form class="statusForm" method="POST">
                                                <select class="form-control category-select supervisor mx-auto" name="supervisor_change">
                                                    <option value="1" <?php echo $user['supervisor'] ? 'selected' : ''; ?>>Ja</option>
                                                    <option value="0" <?php echo !$user['supervisor'] ? 'selected' : ''; ?>>Nee</option>
                                                </select>
                                                <input type="hidden" name="service_number_change" value="<?php echo $user['id_service_number']; ?>">
                                            </form>
                                        </td>
                                        <td>
                                            <form method="post">
                                                <button type="button" class="btn btn-outline-info btn-circle btn-lg btn-circle ml-2 update" data-id_user="<?php echo $user['id_user']; ?>"><i class="fa fa-edit"></i> </button>
                                                <button type="submit" class="btn btn-outline-info btn-circle btn-lg ml-2 delete" name="delete_user" value="<?php echo $user['id_user']; ?>"><i class="fa fa-trash"></i> </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-primary mt-3 add_new_user">Nieuwe gebruiker toevoegen</button>
    </div>
    <section class="h-100 h-custom update_settings hidden">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12">
                    <div class="card card-registration card-registration-2" style="border-radius: 15px;">
                        <div class="card-body p-0 personal_settings_form">
                            <button class="close-button">Close</button>
                            <form method="post">
                                <div class="row g-0">
                                    <div class="col-lg-6">
                                        <div class="p-5">
                                            <h3 class="fw-normal mb-5">Persoonlijke gegevens</h3>
                                            <div class="row">
                                                <div class="col-md-6 mb-4 pb-2">
                                                    <div class="form-outline">
                                                        <label class="form-label" for="firstname">Voornaam</label>
                                                        <input type="text" id="firstname" name="firstname" class="form-control form-control-lg" placeholder="voornaam" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-4 pb-2">
                                                    <div class="form-outline">
                                                        <label class="form-label" for="lastname">Achternaam</label>
                                                        <input type="text" id="lastname" name="lastname" class="form-control form-control-lg" placeholder="achternaam" required />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <div class="form-outline">
                                                    <label class="form-label" for="emailaddress">E-mailadres</label>
                                                    <input type="email" id="emailaddress" name="emailaddress" class="form-control form-control-lg" placeholder="bijv. gebruiker@email.com" required />
                                                </div>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <div class="form-outline">
                                                    <label class="form-label" for="phone_number">Telefoonnummer</label>
                                                    <input type="tel" id="phone_number" name="phone_number" class="form-control form-control-lg" placeholder="bijv. 06 1234 5678" required />
                                                </div>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <div class="form-outline form-white">
                                                    <label class="form-label" for="zipcode">Postcode</label>
                                                    <input type="text" id="zipcode" name="zipcode" class="form-control form-control-lg" placeholder="bijv. 1234 AB" required />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 mb-4 pb-2">
                                                    <div class="form-outline">
                                                        <label class="form-label" for="Street">Straat</label>
                                                        <input type="text" id="street" name="street" class="form-control form-control-lg" placeholder="straatnaam" required />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-4 pb-2">
                                                    <div class="form-outline">
                                                        <label class="form-label" for="house_number">Huisnummer</label>
                                                        <input type="text" id="house_number" name="house_number" class="form-control form-control-lg" />
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-4 pb-2">
                                                    <div class="form-outline">
                                                        <label class="form-label" for="house_number_extra">Toevoeging</label>
                                                        <input type="text" id="house_number_extra" name="house_number_extra" class="form-control form-control-lg" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <div class="form-outline form-white">
                                                    <label class="form-label" for="city">Woonplaats</label>
                                                    <input type="text" id="city" name="city" class="form-control form-control-lg" placeholder="bijv. Amsterdam" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 bg-indigo">
                                        <div class="p-5">
                                            <h3 class="fw-normal mb-5">Gebruikersgegevens</h3>
                                            <div class="mb-4 pb-2">
                                                <div class="form-outline form-white">
                                                    <label class="form-label" for="username">Gebruikersnaam</label>
                                                    <input type="text" id="username" name="username" class="form-control form-control-lg" placeholder="gebruikersnaam" required />
                                                </div>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <div class="form-outline password_form form-white">
                                                    <label class="form-label" for="password">Wachtwoord</label>
                                                    <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="nieuwe wachtwoord" />
                                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                                </div>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <label class="form-label" for="function">Functie</label>
                                                <select class="select form-control" id="function" name="function" placeholder="Kies een functie" required>
                                                    <option value="1">Bewaker</option>
                                                    <option value="2">Hoofdbewaker</option>
                                                    <option value="3">Celbeheerder</option>
                                                    <option value="4">Schoonmaker</option>
                                                    <option value="5">Administratief medewerker</option>
                                                    <option value="6">Manager</option>
                                                    <option value="7">Inactief</option>
                                                </select>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <label class="form-label" for="supervisor">Supervisor</label>
                                                <select class="select form-control" id="supervisor" name="supervisor">
                                                    <option value="1">Ja</option>
                                                    <option value="0">Nee</option>
                                                </select>
                                            </div>
                                            <div class="mb-4 pb-2">
                                                <div class="form-outline password_form form-white">
                                                    <label class="form-label" for="service_number">Dienstnummer</label>
                                                    <input type="text" id="service_number_placeholder" class="form-control form-control-lg" disabled placeholder="(wordt automatisch gegenereerd)" />
                                                    <input type="hidden" id="service_number" name="service_number" class="form-control form-control-lg" />
                                                </div>
                                            </div>
                                            <?php
                                            if (!empty($update_user_settings["error"])) {
                                                echo '<div class="alert alert-danger" role="alert">' . $update_user_settings["error"] . '</div>';
                                            }
                                            if (!empty($update_user_settings["success"])) {
                                                echo '<div class="alert alert-success" role="alert">' . $update_user_settings["success"] . '</div>';
                                            }
                                            ?>
                                            <button type="submit" class="btn btn-primary save_user" name="" data-mdb-ripple-color="dark"></button>
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <div class="overlay"></div>
    </section>
</main>

<?php require_once '../footer.php'; ?>