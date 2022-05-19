<div class="container">
    <div class="row">
        <div class="col-md-6 mx-auto">
            <h2>Validation Form</h2>

            <form action="" method="post" id="valid-form">
                <?php wp_nonce_field('vform', 'nonce'); ?>
                <div class="alert d-none"></div>
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" placeholder="name" class="form-control">
                    <div class="invalid-feedback">Name must between 2-25 characters</div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="email" class="form-control">
                    <div class="invalid-feedback">Enter a valid Email</div>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="phone" name="phone" id="phone" placeholder="phone" class="form-control">
                    <div class="invalid-feedback">Enter a valid Phone Number</div>
                </div>

                <div class="form-group">
                    <label for="zipcode">Zipcode</label>
                    <input type="zipcode" name="zipcode" id="zip" placeholder="zipcode" class="form-control">
                    <div class="invalid-feedback">Enter a valid Zipcode</div>
                </div>
                
                <input type="submit" value="submit" name="submit" id='validation-submit' class="btn btn-primary btn-block">
            </form>
        </div>
    </div>
</div>