<?php include_once("header.php") ?>

<div class="container">
    <h2 class="my-3">Register new account</h2>

    <!-- Create auction form -->
    <form method="POST" action="process_registration.php">
        <div class="form-group row">
            <label for="accountType" class="col-sm-2 col-form-label text-right">Registering as a:</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="accountType" id="accountBuyer" value="buyer"
                        checked>
                    <label class="form-check-label" for="accountBuyer">Buyer</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="accountType" id="accountSeller" value="seller">
                    <label class="form-check-label" for="accountSeller">Seller</label>
                </div>
                <small id="accountTypeHelp" class="form-text-inline text-muted"><span class="text-danger">*
                        Required.</span></small>
            </div>
        </div>
        <div class="form-group row">
            <label for="email" class="col-sm-2 col-form-label text-right">Email</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
                <small id="emailHelp" class="form-text text-muted"><span class="text-danger">* Required.</span></small>
            </div>
        </div>
        <div class="form-group row">
            <label for="Password" class="col-sm-2 col-form-label text-right">Password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="password" id="Password" placeholder="Password" required>
                <small id="passwordHelp" class="form-text text-muted"><span class="text-danger">*
                        Required.</span></small>
            </div>
        </div>
        <div class="form-group row">
            <label for="passwordConfirmation" class="col-sm-2 col-form-label text-right">Repeat password</label>
            <div class="col-sm-10">
                <input type="password" class="form-control" name="passwordConfirmation" id="passwordConfirmation"
                    placeholder="Enter password again" required>
                <small id="passwordConfirmationHelp" class="form-text text-muted"><span class="text-danger">*
                        Required.</span></small>
            </div>
        </div>
        <div class="form-group row">
            <button type="submit" class="btn btn-primary form-control">Register</button>
        </div>
    </form>

    <div class="text-center">Already have an account? <a href="" data-toggle="modal" data-target="#loginModal">Login</a>

    </div>
</div>

<script type="text/javascript" src="js/pw_check.js"></script>
<?php include_once("footer.php") ?>